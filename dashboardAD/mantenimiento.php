<?php
$pageTitle = 'Mantenimientos';
$dashboardType = 'AD';
$activePage = 'mantenimiento';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Mantenimientos</h1>
            <p>Tablero visual de órdenes de servicio técnico. Sin programación de flujos.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalMantto">
            <i class="bi bi-plus-lg me-1"></i>Nueva orden
        </button>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-6">
                <label class="form-label" for="buscarMantto">Buscar</label>
                <input class="form-control" id="buscarMantto" type="search" placeholder="Recurso o técnico">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="tipoMantto">Tipo</label>
                <select class="form-select" id="tipoMantto">
                    <option value="">Todos</option>
                    <option>Preventivo</option>
                    <option>Correctivo</option>
                </select>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Recurso</th>
                        <th>Tipo</th>
                        <th>Programado</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(7); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalMantto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Orden de mantenimiento</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="recursoMantto">Recurso</label>
                            <input class="form-control" id="recursoMantto" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="tipoOrden">Tipo</label>
                            <select class="form-select" id="tipoOrden">
                                <option>Preventivo</option>
                                <option>Correctivo</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaMantto">Fecha programada</label>
                            <input class="form-control" id="fechaMantto" type="date">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="tecnico">Responsable</label>
                            <input class="form-control" id="tecnico" type="text">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="detalleMantto">Detalle del servicio</label>
                            <textarea class="form-control" id="detalleMantto" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-rs-outline" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-rs" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
