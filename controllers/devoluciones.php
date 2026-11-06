<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();
rs_marcar_prestamos_vencidos();

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin') && rs_post('accion') === 'guardar') {
    $old = $_POST;
    if (rs_procesar_devolucion($_POST, $errores)) {
        rs_flash('ok', 'Devolución registrada correctamente.');
        rs_redirect('dashboardAD/devoluciones.php');
    }
    $abrirModal = true;
}

$q = rs_trim(rs_get('q'));
$filtroEstado = rs_trim(rs_get('estado_recurso'));
$sql = "SELECT d.*, p.fecha_entrega, r.codigo_interno, r.nombre_recurso, CONCAT(e.nombre, ' ', e.apellido) AS empleado
        FROM devolucion d
        INNER JOIN prestamo p ON p.id_prestamo = d.id_prestamo
        INNER JOIN recurso r ON r.id_recurso = p.id_recurso
        INNER JOIN empleado e ON e.id_empleado = p.id_empleado
        WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (r.nombre_recurso LIKE ? OR r.codigo_interno LIKE ? OR CONCAT(e.nombre, ' ', e.apellido) LIKE ? OR d.id_prestamo = ?)";
    array_push($params, "%{$q}%", "%{$q}%", "%{$q}%", ctype_digit($q) ? (int) $q : 0);
}
if ($filtroEstado !== '') {
    $sql .= ' AND d.estado_recurso = ?';
    $params[] = $filtroEstado;
}
$devoluciones = rs_all($sql . ' ORDER BY d.id_devolucion DESC', $params);
$prestamosPendientes = rs_prestamos_sin_devolucion();
