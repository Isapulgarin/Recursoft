<?php
require_once __DIR__ . '/../controllers/historial.php';
rs_requiere_admin();

$pageTitle = 'Historial de Préstamos';
$dashboardType = 'AD';
$activePage = 'historial';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Historial de Préstamos</h1>
        <p>Acciones registradas automáticamente por el sistema en cada préstamo.</p>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-3">
                <label class="form-label" for="histPrestamo">N.º de préstamo</label>
                <input class="form-control" id="histPrestamo" name="prestamo" type="number" min="1" step="1" value="<?php echo rs_h($filtroPrestamo); ?>">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label" for="histDesde">Desde</label>
                <input class="form-control" id="histDesde" name="desde" type="date" value="<?php echo rs_h($desde); ?>">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label" for="histHasta">Hasta</label>
                <input class="form-control" id="histHasta" name="hasta" type="date" value="<?php echo rs_h($hasta); ?>">
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs w-100" type="submit">Consultar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="historial.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Préstamo</th>
                        <th>Empleado</th>
                        <th>Recurso</th>
                        <th>Acción</th>
                        <th>Detalle</th>
                        <th>Estado actual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$historial): rs_empty_table_row(7); endif; ?>
                    <?php foreach ($historial as $h): ?>
                        <tr>
                            <td><?php echo rs_fecha_txt($h['fecha_accion']); ?></td>
                            <td><a href="historial.php?prestamo=<?php echo (int) $h['id_prestamo']; ?>">#<?php echo (int) $h['id_prestamo']; ?></a></td>
                            <td><?php echo rs_h($h['empleado']); ?></td>
                            <td><?php echo rs_h($h['codigo_interno'] . ' - ' . $h['nombre_recurso']); ?></td>
                            <td><?php echo rs_h($h['accion']); ?></td>
                            <td class="small"><?php echo rs_h($h['detalle']); ?></td>
                            <td><?php echo rs_badge_proceso($h['estado_prestamo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
