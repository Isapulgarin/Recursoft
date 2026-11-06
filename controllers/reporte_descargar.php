<?php
require_once __DIR__ . '/../includes/auth.php';

rs_requiere_admin();

/**
 * Descarga un reporte registrado: CSV como archivo y PDF como vista imprimible
 * (el navegador permite guardarla como PDF).
 */
$reporte = rs_registro('reporte', (int) rs_get('id'));
if (!$reporte) {
    rs_flash('error', 'El reporte solicitado no existe.');
    rs_redirect('dashboardAD/reportes.php');
}

$consultas = [
    'Inventario' => [
        ['Código', 'Recurso', 'Categoría', 'Serial', 'Marca', 'Modelo', 'Estado', 'Disponibilidad', 'Fecha registro', 'Fecha adquisición'],
        'SELECT r.codigo_interno, r.nombre_recurso, c.nombre_categoria, r.serial, r.marca, r.modelo, r.estado, r.disponibilidad, r.fecha_registro, r.fecha_adquisicion
         FROM recurso r LEFT JOIN categoria_recurso c ON c.id_categoria = r.id_categoria ORDER BY r.codigo_interno',
    ],
    'Préstamos' => [
        ['N.º', 'Empleado', 'Documento', 'Recurso', 'Solicitud', 'Entrega', 'Devolución programada', 'Devolución real', 'Estado'],
        "SELECT p.id_prestamo, CONCAT(e.nombre, ' ', e.apellido), e.documento, CONCAT(r.codigo_interno, ' - ', r.nombre_recurso),
                p.fecha_solicitud, p.fecha_entrega, p.fecha_devolucion_programada, p.fecha_devolucion_real, p.estado
         FROM prestamo p INNER JOIN empleado e ON e.id_empleado = p.id_empleado INNER JOIN recurso r ON r.id_recurso = p.id_recurso
         ORDER BY p.id_prestamo DESC",
    ],
    'Devoluciones' => [
        ['N.º', 'Préstamo', 'Recurso', 'Empleado', 'Fecha devolución', 'Estado del recurso', 'Observaciones'],
        "SELECT d.id_devolucion, d.id_prestamo, CONCAT(r.codigo_interno, ' - ', r.nombre_recurso), CONCAT(e.nombre, ' ', e.apellido),
                d.fecha_devolucion, d.estado_recurso, d.observaciones
         FROM devolucion d INNER JOIN prestamo p ON p.id_prestamo = d.id_prestamo
         INNER JOIN recurso r ON r.id_recurso = p.id_recurso INNER JOIN empleado e ON e.id_empleado = p.id_empleado
         ORDER BY d.id_devolucion DESC",
    ],
    'Mantenimientos' => [
        ['N.º', 'Recurso', 'Tipo', 'Descripción', 'Inicio', 'Finalización', 'Costo', 'Estado'],
        "SELECT m.id_mantenimiento, CONCAT(r.codigo_interno, ' - ', r.nombre_recurso), m.tipo_mantenimiento, m.descripcion,
                m.fecha_inicio, m.fecha_fin, m.costo, m.estado
         FROM mantenimiento m INNER JOIN recurso r ON r.id_recurso = m.id_recurso ORDER BY m.id_mantenimiento DESC",
    ],
    'Compras' => [
        ['N.º', 'Proveedor', 'NIT', 'Fecha', 'Ítems', 'Total', 'Estado'],
        'SELECT c.id_compra, p.nombre_empresa, p.NIT, c.fecha_compra,
                (SELECT COUNT(*) FROM detalle_compra d WHERE d.id_compra = c.id_compra), c.total, c.estado
         FROM compra c INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor ORDER BY c.id_compra DESC',
    ],
];

if (!isset($consultas[$reporte['tipo_reporte']])) {
    rs_flash('error', 'El tipo de reporte no es válido.');
    rs_redirect('dashboardAD/reportes.php');
}

[$encabezados, $sql] = $consultas[$reporte['tipo_reporte']];
$st = rs_pdo()->query($sql);
$filas = $st->fetchAll(PDO::FETCH_NUM);
$nombreArchivo = 'reporte_' . (int) $reporte['id_reporte'] . '_' . preg_replace('/[^a-z]/', '', strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $reporte['tipo_reporte']))) . '_' . date('Ymd');

if (strtoupper((string) $reporte['formato']) === 'CSV') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, $encabezados, ';');
    foreach ($filas as $fila) {
        fputcsv($out, $fila, ';');
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo rs_h($nombreArchivo); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 12px; padding: 24px; }
        h1 { color: #32578b; font-size: 20px; }
        thead th { background: #32578b !important; color: #fff !important; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1>RecurSoft · Reporte de <?php echo rs_h($reporte['tipo_reporte']); ?></h1>
            <div>Generado el <?php echo rs_fecha_txt($reporte['fecha_generacion']); ?> · Reporte N.º <?php echo (int) $reporte['id_reporte']; ?></div>
            <?php if ($reporte['descripcion']): ?><div class="text-secondary"><?php echo rs_h($reporte['descripcion']); ?></div><?php endif; ?>
        </div>
        <button class="btn btn-sm btn-primary no-print" onclick="window.print()">Guardar como PDF / Imprimir</button>
    </div>
    <table class="table table-sm table-bordered">
        <thead><tr><?php foreach ($encabezados as $e): ?><th><?php echo rs_h($e); ?></th><?php endforeach; ?></tr></thead>
        <tbody>
            <?php if (!$filas): ?><tr><td colspan="<?php echo count($encabezados); ?>" class="text-center">No hay registros.</td></tr><?php endif; ?>
            <?php foreach ($filas as $fila): ?>
                <tr><?php foreach ($fila as $v): ?><td><?php echo rs_h($v); ?></td><?php endforeach; ?></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-secondary">Total de registros: <?php echo count($filas); ?></div>
</body>
</html>
