<?php
$pageTitle = 'Préstamos';
$dashboardType = 'AD';
$activePage = 'prestamos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Préstamos</h1>
            <p>Vista administrativa de solicitudes. Los procesos de negocio no están activos en esta etapa.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalPrestamo">
            <i class="bi bi-plus-lg me-1"></i>Registrar préstamo
        </button>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-4">
                <label class="form-label" for="buscarPrestamo">Buscar</label>
                <input class="form-control" id="buscarPrestamo" type="search" placeholder="Solicitante o recurso">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="desdePrestamo">Desde</label>
                <input class="form-control" id="desdePrestamo" type="date">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="estadoPrestamo">Estado</label>
                <select class="form-select" id="estadoPrestamo">
                    <option value="">Todos</option>
                    <option>Pendiente</option>
                    <option>Activo</option>
                    <option>Vencido</option>
                </select>
            </div>
        </form>
    </div>

    <div class="d-flex flex-wrap gap-2 section-gap">
        <span class="filter-chip"><span class="legend-dot ok"></span> Activo</span>
        <span class="filter-chip"><span class="legend-dot warn"></span> Pendiente</span>
        <span class="filter-chip"><span class="legend-dot danger"></span> Vencido</span>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Solicitante</th>
                        <th>Recurso</th>
                        <th>Fecha salida</th>
                        <th>Fecha retorno</th>
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

<div class="modal fade" id="modalPrestamo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Formulario de préstamo</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="solicitante">Solicitante</label>
                            <input class="form-control" id="solicitante" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="recursoPrestamo">Recurso</label>
                            <input class="form-control" id="recursoPrestamo" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaSalida">Fecha de salida</label>
                            <input class="form-control" id="fechaSalida" type="date">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaRetorno">Fecha estimada de retorno</label>
                            <input class="form-control" id="fechaRetorno" type="date">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="obsPrestamo">Observaciones</label>
                            <textarea class="form-control" id="obsPrestamo" rows="3"></textarea>
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
