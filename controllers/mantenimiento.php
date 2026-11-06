<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();

const RS_ESTADOS_MANT = ['Reportado', 'Programado', 'En proceso', 'Finalizado', 'Cancelado'];
const RS_TIPOS_MANT = ['Preventivo', 'Correctivo'];

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_mantenimiento');

    if ($accion === 'guardar') {
        $old = $_POST;
        $actual = $id > 0 ? rs_registro('mantenimiento', $id) : null;
        if ($id > 0 && !$actual) {
            $errores['general'] = 'El mantenimiento que intenta editar no existe.';
        }
        $idRecurso = rs_entero_id($errores, rs_post('id_recurso'), 'id_recurso', 'un recurso');
        $tipo = rs_opcion($errores, rs_post('tipo_mantenimiento'), RS_TIPOS_MANT, 'tipo_mantenimiento', 'el tipo de mantenimiento');
        $descripcion = rs_max($errores, rs_trim(rs_post('descripcion')), 'descripcion', 150, 'La descripción');
        $fechaInicio = rs_fecha_ok($errores, rs_post('fecha_inicio'), 'fecha_inicio', 'inicio');
        $fechaFin = rs_fecha_ok($errores, rs_post('fecha_fin'), 'fecha_fin', 'finalización', false);
        $costoTxt = rs_trim(rs_post('costo'));
        $costo = $costoTxt === '' ? '0.00' : rs_decimal_min($errores, $costoTxt, 'costo', 'El costo');
        $estado = rs_opcion($errores, rs_post('estado'), RS_ESTADOS_MANT, 'estado', 'estado');
        $observaciones = rs_max($errores, rs_trim(rs_post('observaciones')), 'observaciones', 150, 'Las observaciones');

        rs_fk($errores, 'recurso', $idRecurso, 'id_recurso', 'recurso');
        rs_fechas_orden($errores, $fechaInicio, $fechaFin, 'fecha_fin', 'La fecha de finalización no puede ser anterior a la fecha de inicio.');
        if ($estado === 'Finalizado' && !$fechaFin && !isset($errores['fecha_fin'])) {
            $errores['fecha_fin'] = 'Para finalizar el mantenimiento indique la fecha de finalización.';
        }
        $activo = in_array($estado, ['Reportado', 'Programado', 'En proceso'], true);
        if ($activo && $idRecurso && !isset($errores['id_recurso'])) {
            $prestado = rs_one(
                "SELECT 1 FROM prestamo WHERE id_recurso = ? AND estado IN ('Pendiente','Activo','Vencido') LIMIT 1",
                [$idRecurso]
            );
            if ($prestado) {
                $errores['id_recurso'] = 'El recurso tiene un préstamo vigente. Registre primero la devolución.';
            }
        }

        if (!$errores) {
            $pdo = rs_pdo();
            try {
                $pdo->beginTransaction();
                $datos = [$idRecurso, $tipo, $descripcion, $fechaInicio, $fechaFin, $costo, $estado, $observaciones];
                if ($actual) {
                    $datos[] = $id;
                    rs_exec(
                        'UPDATE mantenimiento SET id_recurso = ?, tipo_mantenimiento = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, costo = ?, estado = ?, observaciones = ?
                         WHERE id_mantenimiento = ?',
                        $datos
                    );
                } else {
                    rs_exec(
                        'INSERT INTO mantenimiento (id_recurso, tipo_mantenimiento, descripcion, fecha_inicio, fecha_fin, costo, estado, observaciones)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                        $datos
                    );
                }
                if ($estado === 'Finalizado') {
                    rs_exec("UPDATE recurso SET estado = 'Bueno' WHERE id_recurso = ? AND estado = 'Dañado'", [$idRecurso]);
                }
                if ($actual && (int) $actual['id_recurso'] !== $idRecurso) {
                    rs_recalcular_disponibilidad($actual['id_recurso']);
                }
                rs_recalcular_disponibilidad($idRecurso);
                $pdo->commit();
                rs_flash('ok', $actual ? 'Mantenimiento actualizado correctamente.' : 'Mantenimiento registrado correctamente.');
                rs_redirect('dashboardAD/mantenimiento.php');
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $errores['general'] = 'No fue posible guardar el mantenimiento.';
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'eliminar' && $id > 0) {
        $row = rs_registro('mantenimiento', $id);
        if ($row) {
            rs_exec('DELETE FROM mantenimiento WHERE id_mantenimiento = ?', [$id]);
            rs_recalcular_disponibilidad($row['id_recurso']);
            rs_flash('ok', 'Mantenimiento eliminado.');
        }
        rs_redirect('dashboardAD/mantenimiento.php');
    }
}

$filtroRecurso = rs_trim(rs_get('recurso'));
$filtroEstado = rs_trim(rs_get('estado'));
$sql = 'SELECT m.*, r.codigo_interno, r.nombre_recurso FROM mantenimiento m
        INNER JOIN recurso r ON r.id_recurso = m.id_recurso WHERE 1=1';
$params = [];
if ($filtroRecurso !== '') {
    $sql .= ' AND m.id_recurso = ?';
    $params[] = (int) $filtroRecurso;
}
if ($filtroEstado !== '') {
    $sql .= ' AND m.estado = ?';
    $params[] = $filtroEstado;
}
$mantenimientos = rs_all($sql . ' ORDER BY m.id_mantenimiento DESC', $params);
$recursosLista = rs_all('SELECT id_recurso, codigo_interno, nombre_recurso, disponibilidad FROM recurso ORDER BY nombre_recurso');
