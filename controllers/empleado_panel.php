<?php
/**
 * RecurSoft - Controlador del panel del empleado (rol Auxiliar).
 * Cada vista define $emPagina antes de incluir este archivo.
 * El empleado solo consulta y opera sobre su propia información.
 */
require_once __DIR__ . '/_post.php';

$usuarioEm = rs_requiere_empleado();
rs_marcar_prestamos_vencidos();

$idEmpleado = (int) ($usuarioEm['id_empleado'] ?? 0);
if (!$idEmpleado) {
    $fila = rs_one('SELECT id_empleado FROM empleado WHERE id_usuario = ? LIMIT 1', [(int) $usuarioEm['id_usuario']]);
    $idEmpleado = (int) ($fila['id_empleado'] ?? 0);
    $_SESSION['rs_user']['id_empleado'] = $idEmpleado ?: null;
}
$empleadoEm = $idEmpleado ? rs_registro('empleado', $idEmpleado) : null;
$sinEmpleado = !$empleadoEm;
$emPagina = $emPagina ?? '';
$errores = [];
$old = [];
$abrirModal = false;
$destino = 'dashboardEM/' . $emPagina . '.php';

if (rs_post_valido('empleado')) {
    $accion = rs_post('accion');

    if ($accion === 'solicitar_prestamo' && $emPagina === 'prestamos') {
        $old = $_POST;
        if ($sinEmpleado || !rs_es_activo($empleadoEm['estado'])) {
            $errores['general'] = 'Su cuenta no está asociada a un empleado activo. Contacte al administrador.';
        } else {
            $datos = $_POST;
            $datos['id_empleado'] = $idEmpleado;
            $datos['estado'] = 'Pendiente';
            $datos['fecha_solicitud'] = date('Y-m-d');
            $d = rs_validar_prestamo($datos, $errores);
            if (!$errores) {
                try {
                    rs_crear_prestamo($d, 'el empleado');
                    rs_flash('ok', 'Solicitud de préstamo registrada correctamente.');
                    rs_redirect($destino);
                } catch (Throwable $e) {
                    $errores['general'] = 'No fue posible registrar la solicitud.';
                }
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'devolver' && $emPagina === 'devoluciones') {
        $old = $_POST;
        if ($sinEmpleado) {
            $errores['general'] = 'Su cuenta no está asociada a un empleado.';
        } elseif (rs_procesar_devolucion($_POST, $errores, $idEmpleado)) {
            rs_flash('ok', 'Devolución registrada correctamente.');
            rs_redirect($destino);
        }
    }

    if ($accion === 'reportar_dano' && $emPagina === 'reporte_danos') {
        $old = $_POST;
        $idRecurso = rs_entero_id($errores, rs_post('id_recurso'), 'id_recurso', 'un recurso');
        $fecha = rs_fecha_ok($errores, rs_post('fecha_inicio'), 'fecha_inicio', 'incidente');
        $prioridad = rs_opcion($errores, rs_post('prioridad'), ['Baja', 'Media', 'Alta'], 'prioridad', 'la prioridad');
        $tipoDano = rs_opcion($errores, rs_post('tipo_dano'), ['Físico', 'Funcional', 'Pérdida de accesorio'], 'tipo_dano', 'el tipo de daño');
        $descripcion = rs_max($errores, rs_req($errores, rs_post('descripcion'), 'descripcion', 'descripción'), 'descripcion', 150, 'La descripción');
        if ($fecha && $fecha > date('Y-m-d')) {
            $errores['fecha_inicio'] = 'La fecha del incidente no puede ser futura.';
        }
        $permitido = $idRecurso ? rs_one(
            "SELECT 1 FROM prestamo WHERE id_recurso = ? AND id_empleado = ? AND estado IN ('Activo','Vencido','Devuelto') LIMIT 1",
            [$idRecurso, $idEmpleado]
        ) : null;
        if ($idRecurso && !isset($errores['id_recurso']) && !$permitido) {
            $errores['id_recurso'] = 'Solo puede reportar daños de recursos que le fueron prestados.';
        }
        if (!$errores) {
            $pdo = rs_pdo();
            try {
                $pdo->beginTransaction();
                rs_exec(
                    "INSERT INTO mantenimiento (id_recurso, tipo_mantenimiento, descripcion, fecha_inicio, costo, estado, observaciones)
                     VALUES (?, 'Correctivo', ?, ?, 0, 'Reportado', ?)",
                    [$idRecurso, $descripcion, $fecha, mb_substr("Reportado por {$empleadoEm['nombre']} {$empleadoEm['apellido']}. Tipo: {$tipoDano}. Prioridad: {$prioridad}.", 0, 150)]
                );
                rs_recalcular_disponibilidad($idRecurso);
                $pdo->commit();
                rs_flash('ok', 'El reporte de daño fue enviado al administrador.');
                rs_redirect($destino);
            } catch (PDOException $e) {
                $pdo->rollBack();
                $errores['general'] = 'No fue posible enviar el reporte.';
            }
        }
    }

    if ($accion === 'perfil' && $emPagina === 'perfil') {
        $old = $_POST;
        $idUsuario = (int) $usuarioEm['id_usuario'];
        $telefono = rs_telefono_ok($errores, rs_max($errores, rs_req($errores, rs_post('telefono'), 'telefono', 'teléfono'), 'telefono', 20, 'El teléfono'), 'telefono');
        $actualPass = (string) rs_post('contrasena_actual');
        $nueva = rs_password_ok($errores, rs_post('contrasena'), 'contrasena', false);
        if ($nueva !== '') {
            if ((string) rs_post('contrasena_confirmar') !== $nueva) {
                $errores['contrasena_confirmar'] = 'La confirmación no coincide con la nueva contraseña.';
            }
            $u = rs_one('SELECT `contraseña` AS hash FROM usuario WHERE id_usuario = ?', [$idUsuario]);
            if (!$u || !rs_password_verify_safe($actualPass, (string) $u['hash'])) {
                $errores['contrasena_actual'] = 'La contraseña actual no es correcta.';
            }
        }
        if (!$errores) {
            $pdo = rs_pdo();
            $pdo->beginTransaction();
            try {
                rs_exec('UPDATE usuario SET telefono = ? WHERE id_usuario = ?', [$telefono, $idUsuario]);
                if ($nueva !== '') {
                    rs_exec('UPDATE usuario SET `contraseña` = ? WHERE id_usuario = ?', [rs_password_hash_safe($nueva), $idUsuario]);
                }
                if ($empleadoEm) {
                    rs_exec('UPDATE empleado SET telefono = ? WHERE id_empleado = ?', [$telefono, $idEmpleado]);
                }
                $pdo->commit();
                rs_flash('ok', 'Su perfil se actualizó correctamente.');
                rs_redirect($destino);
            } catch (PDOException $e) {
                $pdo->rollBack();
                $errores['general'] = 'No fue posible actualizar el perfil.';
            }
        }
    }
}

$misPrestamos = $idEmpleado ? rs_all(
    "SELECT p.*, r.codigo_interno, r.nombre_recurso FROM prestamo p
     INNER JOIN recurso r ON r.id_recurso = p.id_recurso
     WHERE p.id_empleado = ? ORDER BY p.id_prestamo DESC",
    [$idEmpleado]
) : [];
