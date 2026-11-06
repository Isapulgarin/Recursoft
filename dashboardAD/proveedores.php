<?php
$pageTitle = 'Proveedores';
$dashboardType = 'AD';
$activePage = 'proveedores';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Proveedores</h1>
            <p>Directorio comercial para adquisiciones institucionales.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalProveedor">
            <i class="bi bi-plus-lg me-1"></i>Nuevo proveedor
        </button>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-8">
                <label class="form-label" for="buscarProv">Buscar</label>
                <input class="form-control" id="buscarProv" type="search" placeholder="Razón social o RFC">
            </div>
            <div class="col-12 col-md-4 d-flex align-items-end">
                <button class="btn btn-rs-outline w-100" type="submit">Buscar</button>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(6); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalProveedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Registrar proveedor</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="razonProv">Razón social</label>
                            <input class="form-control" id="razonProv" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="rfcProv">Identificador fiscal</label>
                            <input class="form-control" id="rfcProv" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="telProv">Teléfono</label>
                            <input class="form-control" id="telProv" type="tel">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="mailProv">Correo</label>
                            <input class="form-control" id="mailProv" type="email">
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
