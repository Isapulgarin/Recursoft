<?php
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
        <p>Consulta histórica institucional. La tabla permanece vacía hasta integrar el backend.</p>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-4">
                <label class="form-label" for="histDesde">Desde</label>
                <input class="form-control" id="histDesde" type="date">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="histHasta">Hasta</label>
                <input class="form-control" id="histHasta" type="date">
            </div>
            <div class="col-12 col-md-4 d-flex align-items-end">
                <button class="btn btn-rs w-100" type="submit">Consultar</button>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Usuario</th>
                        <th>Recurso</th>
                        <th>Salida</th>
                        <th>Retorno</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(6); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
