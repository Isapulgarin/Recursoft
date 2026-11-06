<?php
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
        <p>Consulta exclusiva de la actividad del empleado autenticado (cuando exista sesión).</p>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-5">
                <label class="form-label" for="histEmpDesde">Desde</label>
                <input class="form-control" id="histEmpDesde" type="date">
            </div>
            <div class="col-12 col-md-5">
                <label class="form-label" for="histEmpHasta">Hasta</label>
                <input class="form-control" id="histEmpHasta" type="date">
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end">
                <button class="btn btn-rs w-100" type="submit">Ver</button>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Recurso</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(4); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
