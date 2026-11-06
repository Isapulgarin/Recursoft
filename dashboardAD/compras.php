<?php
$pageTitle = 'Compras y Adquisiciones';
$dashboardType = 'AD';
$activePage = 'compras';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Compras y Adquisiciones</h1>
            <p>Formularios y listados preparados para el módulo de compras.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalCompra">
            <i class="bi bi-plus-lg me-1"></i>Nueva adquisición
        </button>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-4">
                <label class="form-label" for="buscarCompra">Buscar</label>
                <input class="form-control" id="buscarCompra" type="search" placeholder="Folio o proveedor">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="periodoCompra">Periodo</label>
                <input class="form-control" id="periodoCompra" type="month">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="estadoCompra">Estado</label>
                <select class="form-select" id="estadoCompra">
                    <option value="">Todos</option>
                    <option>Solicitada</option>
                    <option>Autorizada</option>
                    <option>Recibida</option>
                </select>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Proveedor</th>
                        <th>Concepto</th>
                        <th>Fecha</th>
                        <th>Monto</th>
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

<div class="modal fade" id="modalCompra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Registrar adquisición</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="provCompra">Proveedor</label>
                            <input class="form-control" id="provCompra" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaCompra">Fecha</label>
                            <input class="form-control" id="fechaCompra" type="date">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="conceptoCompra">Concepto</label>
                            <input class="form-control" id="conceptoCompra" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="montoCompra">Monto</label>
                            <input class="form-control" id="montoCompra" type="number" min="0" step="0.01">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="estadoNuevaCompra">Estado</label>
                            <select class="form-select" id="estadoNuevaCompra">
                                <option>Solicitada</option>
                                <option>Autorizada</option>
                                <option>Recibida</option>
                            </select>
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
