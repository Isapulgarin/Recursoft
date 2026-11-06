<?php
$pageTitle = 'Mis Préstamos';
$dashboardType = 'EM';
$activePage = 'prestamos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Mis Préstamos</h1>
            <p>El empleado puede solicitar un préstamo y consultar únicamente los propios.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalSolicitud">
            <i class="bi bi-plus-lg me-1"></i>Solicitar préstamo
        </button>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Recurso</th>
                        <th>Fecha de solicitud</th>
                        <th>Retorno estimado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(5); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalSolicitud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Solicitud de préstamo</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="recursoSol">Recurso solicitado</label>
                            <input class="form-control" id="recursoSol" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaUso">Fecha de uso</label>
                            <input class="form-control" id="fechaUso" type="date">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaDevEst">Retorno estimado</label>
                            <input class="form-control" id="fechaDevEst" type="date">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="motivoSol">Motivo</label>
                            <textarea class="form-control" id="motivoSol" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-rs-outline" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-rs" type="submit">Enviar solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
