<?php
$pageTitle = 'Reportes';
$dashboardType = 'AD';
$activePage = 'reportes';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Reportes</h1>
            <p>Filtros y contenedores listos para exportación cuando exista información real.</p>
        </div>
        <div class="action-btns no-print">
            <button class="btn btn-rs-outline" type="button" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Imprimir vista
            </button>
        </div>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-4">
                <label class="form-label" for="tipoReporte">Tipo de reporte</label>
                <select class="form-select" id="tipoReporte">
                    <option>Inventario</option>
                    <option>Préstamos</option>
                    <option>Mantenimientos</option>
                    <option>Adquisiciones</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="repDesde">Desde</label>
                <input class="form-control" id="repDesde" type="date">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="repHasta">Hasta</label>
                <input class="form-control" id="repHasta" type="date">
            </div>
            <div class="col-12">
                <button class="btn btn-rs" type="submit">Generar vista</button>
            </div>
        </form>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-lg-6">
            <div class="card-rs h-100">
                <div class="card-header py-3">Tendencia</div>
                <div class="p-3">
                    <div class="chart-placeholder">
                        <i class="bi bi-graph-up"></i>
                        <strong>Sin datos para graficar</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card-rs h-100">
                <div class="card-header py-3">Distribución</div>
                <div class="p-3">
                    <div class="chart-placeholder">
                        <i class="bi bi-pie-chart"></i>
                        <strong>Sin indicadores</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-rs">
        <div class="card-header py-3">Detalle del reporte</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Cantidad</th>
                        <th>Periodo</th>
                        <th>Observación</th>
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
