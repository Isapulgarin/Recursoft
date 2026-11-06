<?php
require_once __DIR__ . '/../includes/auth.php';

/**
 * Consulta del historial de préstamos. El administrador ve todo;
 * el empleado solo el historial de sus propios préstamos.
 */
$usuarioHist = rs_requiere_login();
$esAdminHist = rs_es_admin($usuarioHist);

$filtroPrestamo = rs_trim(rs_get('prestamo'));
$desde = rs_trim(rs_get('desde'));
$hasta = rs_trim(rs_get('hasta'));
$errFiltro = [];

$sql = "SELECT h.*, p.estado AS estado_prestamo, r.codigo_interno, r.nombre_recurso, CONCAT(e.nombre, ' ', e.apellido) AS empleado
        FROM historial_prestamo h
        INNER JOIN prestamo p ON p.id_prestamo = h.id_prestamo
        INNER JOIN recurso r ON r.id_recurso = p.id_recurso
        INNER JOIN empleado e ON e.id_empleado = p.id_empleado
        WHERE 1=1";
$params = [];
if (!$esAdminHist) {
    $sql .= ' AND p.id_empleado = ?';
    $params[] = (int) ($usuarioHist['id_empleado'] ?? 0);
}
if ($filtroPrestamo !== '' && ctype_digit($filtroPrestamo)) {
    $sql .= ' AND h.id_prestamo = ?';
    $params[] = (int) $filtroPrestamo;
}
if ($desde !== '' && rs_fecha_ok($errFiltro, $desde, 'desde', 'inicio')) {
    $sql .= ' AND h.fecha_accion >= ?';
    $params[] = $desde;
}
if ($hasta !== '' && rs_fecha_ok($errFiltro, $hasta, 'hasta', 'fin')) {
    $sql .= ' AND h.fecha_accion <= ?';
    $params[] = $hasta;
}
$historial = rs_all($sql . ' ORDER BY h.fecha_accion DESC, h.id_historial DESC', $params);
