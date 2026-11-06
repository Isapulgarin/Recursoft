<?php
/**
 * RecurSoft - Procesos compartidos entre módulos (préstamos y devoluciones).
 * Se usan desde el panel del administrador y desde el panel del empleado.
 */

const RS_ESTADOS_PRESTAMO_VIGENTE = ['Pendiente', 'Activo', 'Vencido'];
const RS_ESTADOS_RECURSO = ['Nuevo', 'Bueno', 'Regular', 'Dañado', 'Fuera de servicio'];
const RS_ESTADOS_DEVOLUCION = ['Bueno', 'Regular', 'Dañado'];

/**
 * Valida los datos de un préstamo. $actual es el préstamo existente al editar.
 */
function rs_validar_prestamo(array $in, array &$errores, ?array $actual = null): array
{
    $idEmpleado = rs_entero_id($errores, $in['id_empleado'] ?? '', 'id_empleado', 'un empleado');
    $idRecurso = rs_entero_id($errores, $in['id_recurso'] ?? '', 'id_recurso', 'un recurso');
    $fechaSolicitud = rs_fecha_ok($errores, $in['fecha_solicitud'] ?? '', 'fecha_solicitud', 'solicitud');
    $fechaEntrega = rs_fecha_ok($errores, $in['fecha_entrega'] ?? '', 'fecha_entrega', 'entrega', false);
    $fechaProgramada = rs_fecha_ok($errores, $in['fecha_devolucion_programada'] ?? '', 'fecha_devolucion_programada', 'devolución programada');
    $observaciones = rs_max($errores, rs_trim($in['observaciones'] ?? ''), 'observaciones', 150, 'Las observaciones');
    $estado = rs_trim($in['estado'] ?? 'Pendiente');

    $empleado = rs_fk($errores, 'empleado', $idEmpleado, 'id_empleado', 'empleado');
    if ($empleado && !rs_es_activo($empleado['estado'])) {
        $errores['id_empleado'] = 'El empleado seleccionado está inactivo.';
    }
    $recurso = rs_fk($errores, 'recurso', $idRecurso, 'id_recurso', 'recurso');

    if ($fechaSolicitud && $fechaSolicitud > date('Y-m-d')) {
        $errores['fecha_solicitud'] = 'La fecha de solicitud no puede ser futura.';
    }
    rs_fechas_orden($errores, $fechaSolicitud, $fechaEntrega, 'fecha_entrega', 'La fecha de entrega no puede ser anterior a la fecha de solicitud.');
    $base = $fechaEntrega ?: $fechaSolicitud;
    if ($base && $fechaProgramada && $fechaProgramada <= $base) {
        $errores['fecha_devolucion_programada'] = $fechaEntrega
            ? 'La fecha de devolución programada debe ser posterior a la fecha de entrega.'
            : 'La fecha de devolución programada debe ser posterior a la fecha de solicitud.';
    }
    if ($estado === 'Activo' && !$fechaEntrega && !isset($errores['fecha_entrega'])) {
        $errores['fecha_entrega'] = 'Para un préstamo activo debe indicar la fecha de entrega.';
    }

    $cambiaRecurso = !$actual || (int) $actual['id_recurso'] !== $idRecurso;
    if ($recurso && $cambiaRecurso && !isset($errores['id_recurso'])) {
        if (!rs_recurso_libre($idRecurso, $actual['id_prestamo'] ?? null)) {
            $errores['id_recurso'] = 'El recurso seleccionado no está disponible.';
        }
    }

    return [
        'id_empleado' => $idEmpleado,
        'id_recurso' => $idRecurso,
        'fecha_solicitud' => $fechaSolicitud,
        'fecha_entrega' => $fechaEntrega,
        'fecha_devolucion_programada' => $fechaProgramada,
        'observaciones' => $observaciones,
        'estado' => $estado,
    ];
}

/**
 * Registra un préstamo nuevo con su historial y actualiza la disponibilidad del recurso.
 */
function rs_crear_prestamo(array $d, string $origen = 'Administrador'): int
{
    $pdo = rs_pdo();
    $pdo->beginTransaction();
    try {
        rs_exec(
            'INSERT INTO prestamo (id_empleado, id_recurso, fecha_solicitud, fecha_entrega, fecha_devolucion_programada, estado, observaciones)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [$d['id_empleado'], $d['id_recurso'], $d['fecha_solicitud'], $d['fecha_entrega'], $d['fecha_devolucion_programada'], $d['estado'], $d['observaciones']]
        );
        $id = (int) rs_last_id();
        rs_registrar_historial($id, 'Solicitud registrada', "Registrado por {$origen}. Estado: {$d['estado']}.");
        if ($d['estado'] === 'Activo') {
            rs_registrar_historial($id, 'Recurso entregado', 'Entrega el ' . rs_fecha_txt($d['fecha_entrega']) . '.');
        }
        rs_recalcular_disponibilidad($d['id_recurso']);
        $pdo->commit();
        return $id;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Préstamos vigentes que aún no tienen devolución. Si se indica empleado, solo los suyos.
 */
function rs_prestamos_sin_devolucion(?int $idEmpleado = null): array
{
    $sql = "SELECT p.id_prestamo, p.fecha_entrega, p.fecha_solicitud, p.fecha_devolucion_programada, p.estado,
                   r.codigo_interno, r.nombre_recurso, CONCAT(e.nombre, ' ', e.apellido) AS empleado
            FROM prestamo p
            INNER JOIN recurso r ON r.id_recurso = p.id_recurso
            INNER JOIN empleado e ON e.id_empleado = p.id_empleado
            LEFT JOIN devolucion d ON d.id_prestamo = p.id_prestamo
            WHERE d.id_devolucion IS NULL AND p.estado IN ('Activo','Vencido')";
    $params = [];
    if ($idEmpleado !== null) {
        $sql .= ' AND p.id_empleado = ?';
        $params[] = $idEmpleado;
    }
    return rs_all($sql . ' ORDER BY p.id_prestamo DESC', $params);
}

/**
 * Valida y registra una devolución. Devuelve true si se guardó.
 */
function rs_procesar_devolucion(array $in, array &$errores, ?int $soloEmpleado = null): bool
{
    $idPrestamo = rs_entero_id($errores, $in['id_prestamo'] ?? '', 'id_prestamo', 'un préstamo');
    $fecha = rs_fecha_ok($errores, $in['fecha_devolucion'] ?? '', 'fecha_devolucion', 'devolución');
    $estadoRecurso = rs_opcion($errores, $in['estado_recurso'] ?? '', RS_ESTADOS_DEVOLUCION, 'estado_recurso', 'el estado del recurso');
    $observaciones = rs_max($errores, rs_trim($in['observaciones'] ?? ''), 'observaciones', 150, 'Las observaciones');

    $prestamo = rs_fk($errores, 'prestamo', $idPrestamo, 'id_prestamo', 'préstamo');
    if ($prestamo) {
        if ($soloEmpleado !== null && (int) $prestamo['id_empleado'] !== $soloEmpleado) {
            $errores['id_prestamo'] = 'El préstamo seleccionado no le pertenece.';
        } elseif (rs_existe('devolucion', 'id_prestamo', $idPrestamo)) {
            $errores['id_prestamo'] = 'Este préstamo ya tiene una devolución registrada.';
        } elseif (!in_array($prestamo['estado'], ['Activo', 'Vencido'], true)) {
            $errores['id_prestamo'] = 'Solo se pueden devolver préstamos activos o vencidos.';
        }
    }
    if ($fecha && $fecha > date('Y-m-d')) {
        $errores['fecha_devolucion'] = 'La fecha de devolución no puede ser futura.';
    }
    if ($prestamo && $fecha && !isset($errores['id_prestamo'])) {
        $entrega = $prestamo['fecha_entrega'] ?: $prestamo['fecha_solicitud'];
        if ($fecha < $entrega) {
            $errores['fecha_devolucion'] = 'La fecha de devolución no puede ser anterior a la fecha de entrega.';
        }
    }
    if ($estadoRecurso === 'Dañado' && $observaciones === '' && !isset($errores['observaciones'])) {
        $errores['observaciones'] = 'Describa el daño en las observaciones.';
    }

    if ($errores) {
        return false;
    }

    $pdo = rs_pdo();
    $pdo->beginTransaction();
    try {
        rs_exec(
            'INSERT INTO devolucion (id_prestamo, fecha_devolucion, estado_recurso, observaciones) VALUES (?, ?, ?, ?)',
            [$idPrestamo, $fecha, $estadoRecurso, $observaciones]
        );
        rs_exec(
            "UPDATE prestamo SET estado = 'Devuelto', fecha_devolucion_real = ? WHERE id_prestamo = ?",
            [$fecha, $idPrestamo]
        );
        rs_exec('UPDATE recurso SET estado = ? WHERE id_recurso = ?', [$estadoRecurso, (int) $prestamo['id_recurso']]);
        rs_registrar_historial($idPrestamo, 'Devolución registrada', 'Recurso devuelto en estado ' . $estadoRecurso . '.');
        rs_recalcular_disponibilidad($prestamo['id_recurso']);
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        $pdo->rollBack();
        $errores['general'] = 'No fue posible registrar la devolución.';
        return false;
    }
}
