<?php
$emPagina = 'historial';
require_once __DIR__ . '/../controllers/empleado_panel.php';
require_once __DIR__ . '/../controllers/historial.php';

$pageTitle = 'Mi Historial';
$dashboardType = 'EM';
$activePage = 'historial';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Mi Historial</h1>
        <p>Actividad registrada sobre sus propios préstamos.</p>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="histEmpPrestamo">N.º préstamo</label>
                <input class="form-control" id="histEmpPrestamo" name="prestamo" type="number" min="1" value="<?php echo rs_h($filtroPrestamo); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="histEmpDesde">Desde</label>
                <input class="form-control" id="histEmpDesde" name="desde" type="date" value="<?php echo rs_h($desde); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="histEmpHasta">Hasta</label>
                <input class="form-control" id="histEmpHasta" name="hasta" type="date" value="<?php echo rs_h($hasta); ?>">
            </div>
            <div class="col-6 col-md-2"><button class="btn btn-rs w-100" type="submit">Consultar</button></div>
            <div class="col-6 col-md-1"><a class="btn btn-rs-outline w-100" href="historial.php" title="Limpiar"><i class="bi bi-x-lg"></i></a></div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Préstamo</th>
                        <th>Recurso</th>
                        <th>Acción</th>
                        <th>Detalle</th>
                        <th>Estado actual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$historial): rs_empty_table_row(6); endif; ?>
                    <?php foreach ($historial as $h): ?>
                        <tr>
                            <td><?php echo rs_fecha_txt($h['fecha_accion']); ?></td>
                            <td>#<?php echo (int) $h['id_prestamo']; ?></td>
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
