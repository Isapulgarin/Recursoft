<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();
rs_marcar_prestamos_vencidos();

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_prestamo');

    if ($accion === 'guardar') {
        $old = $_POST;
        $actual = $id > 0 ? rs_registro('prestamo', $id) : null;
        if ($id > 0 && (!$actual || !in_array($actual['estado'], RS_ESTADOS_PRESTAMO_VIGENTE, true))) {
            $errores['general'] = 'Solo se pueden editar préstamos pendientes, activos o vencidos.';
        }
        $permitidos = $actual ? ['Pendiente', 'Activo', 'Vencido'] : ['Pendiente', 'Activo'];
        rs_opcion($errores, rs_post('estado'), $permitidos, 'estado', 'estado');
        $d = rs_validar_prestamo($_POST, $errores, $actual);
        if ($d['estado'] === 'Vencido' && $actual && $actual['estado'] !== 'Vencido') {
            $errores['estado'] = 'El estado vencido se asigna automáticamente.';
        }

        if (!$errores) {
            try {
                if ($actual) {
                    $pdo = rs_pdo();
                    $pdo->beginTransaction();
                    rs_exec(
                        'UPDATE prestamo SET id_empleado = ?, id_recurso = ?, fecha_solicitud = ?, fecha_entrega = ?, fecha_devolucion_programada = ?, estado = ?, observaciones = ?
                         WHERE id_prestamo = ?',
                        [$d['id_empleado'], $d['id_recurso'], $d['fecha_solicitud'], $d['fecha_entrega'], $d['fecha_devolucion_programada'], $d['estado'], $d['observaciones'], $id]
                    );
                    $detalle = $actual['estado'] !== $d['estado']
                        ? "Estado: {$actual['estado']} → {$d['estado']}."
                        : 'Datos del préstamo actualizados.';
                    rs_registrar_historial($id, 'Préstamo actualizado', $detalle);
                    rs_recalcular_disponibilidad($actual['id_recurso']);
                    rs_recalcular_disponibilidad($d['id_recurso']);
                    $pdo->commit();
                    rs_flash('ok', 'Préstamo actualizado correctamente.');
                } else {
                    rs_crear_prestamo($d, 'Administrador');
                    rs_flash('ok', 'Préstamo registrado correctamente.');
                }
                rs_redirect('dashboardAD/prestamos.php');
            } catch (Throwable $e) {
                if (rs_pdo()->inTransaction()) {
                    rs_pdo()->rollBack();
                }
                $errores['general'] = 'No fue posible guardar el préstamo.';
            }
        }
        $abrirModal = true;
    }

    if (in_array($accion, ['entregar', 'cancelar'], true) && $id > 0) {
        $p = rs_registro('prestamo', $id);
        if (!$p) {
            rs_flash('error', 'El préstamo no existe.');
        } elseif ($accion === 'entregar' && $p['estado'] !== 'Pendiente') {
            rs_flash('error', 'Solo se pueden entregar préstamos pendientes.');
        } elseif ($accion === 'cancelar' && !in_array($p['estado'], ['Pendiente', 'Activo'], true)) {
            rs_flash('error', 'Solo se pueden cancelar préstamos pendientes o activos.');
        } else {
            $pdo = rs_pdo();
            $pdo->beginTransaction();
            try {
                if ($accion === 'entregar') {
                    $entrega = $p['fecha_entrega'] ?: date('Y-m-d');
                    rs_exec("UPDATE prestamo SET estado = 'Activo', fecha_entrega = ? WHERE id_prestamo = ?", [$entrega, $id]);
                    rs_registrar_historial($id, 'Recurso entregado', 'Entrega el ' . rs_fecha_txt($entrega) . '.');
                    $msg = 'Préstamo marcado como entregado.';
                } else {
                    rs_exec("UPDATE prestamo SET estado = 'Cancelado' WHERE id_prestamo = ?", [$id]);
                    rs_registrar_historial($id, 'Préstamo cancelado', 'Cancelado por el administrador.');
                    $msg = 'Préstamo cancelado.';
                }
                rs_recalcular_disponibilidad($p['id_recurso']);
                $pdo->commit();
                rs_flash('ok', $msg);
            } catch (Throwable $e) {
                $pdo->rollBack();
                rs_flash('error', 'No fue posible actualizar el préstamo.');
            }
        }
        rs_redirect('dashboardAD/prestamos.php');
    }
}

$q = rs_trim(rs_get('q'));
$filtroEstado = rs_trim(rs_get('estado'));
$desde = rs_trim(rs_get('desde'));
$hasta = rs_trim(rs_get('hasta'));
$errFiltro = [];

$sql = "SELECT p.*, r.codigo_interno, r.nombre_recurso, CONCAT(e.nombre, ' ', e.apellido) AS empleado, e.documento
        FROM prestamo p
        INNER JOIN recurso r ON r.id_recurso = p.id_recurso
        INNER JOIN empleado e ON e.id_empleado = p.id_empleado
        WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (CONCAT(e.nombre, ' ', e.apellido) LIKE ? OR e.documento LIKE ? OR r.nombre_recurso LIKE ? OR r.codigo_interno LIKE ?)";
    array_push($params, "%{$q}%", "%{$q}%", "%{$q}%", "%{$q}%");
}
if ($filtroEstado !== '') {
    $sql .= ' AND p.estado = ?';
    $params[] = $filtroEstado;
}
if ($desde !== '' && rs_fecha_ok($errFiltro, $desde, 'desde', 'desde')) {
    $sql .= ' AND p.fecha_solicitud >= ?';
    $params[] = $desde;
}
if ($hasta !== '' && rs_fecha_ok($errFiltro, $hasta, 'hasta', 'hasta')) {
    $sql .= ' AND p.fecha_solicitud <= ?';
    $params[] = $hasta;
}
$sql .= ' ORDER BY p.id_prestamo DESC';
$prestamos = rs_all($sql, $params);

$empleadosActivos = rs_all("SELECT id_empleado, documento, nombre, apellido, estado FROM empleado ORDER BY nombre, apellido");
$recursosLista = rs_all('SELECT id_recurso, codigo_interno, nombre_recurso, disponibilidad, estado FROM recurso ORDER BY nombre_recurso');
