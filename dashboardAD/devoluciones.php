<?php
$pageTitle = 'Devoluciones';
$dashboardType = 'AD';
$activePage = 'devoluciones';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Devoluciones</h1>
            <p>Registro visual de retornos de recursos. Sin operaciones de almacenamiento.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalDevolucion">
            <i class="bi bi-plus-lg me-1"></i>Registrar devolución
        </button>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-6">
                <label class="form-label" for="buscarDevolucion">Buscar</label>
                <input class="form-control" id="buscarDevolucion" type="search" placeholder="Folio o recurso">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="condicion">Condición</label>
                <select class="form-select" id="condicion">
                    <option value="">Todas</option>
                    <option>Óptima</option>
                    <option>Con observaciones</option>
                    <option>Daño reportado</option>
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
                        <th>Recurso</th>
                        <th>Responsable</th>
                        <th>Fecha de retorno</th>
                        <th>Condición</th>
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

<div class="modal fade" id="modalDevolucion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Formulario de devolución</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="folioDev">Folio de préstamo</label>
                            <input class="form-control" id="folioDev" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaDev">Fecha de retorno</label>
                            <input class="form-control" id="fechaDev" type="date">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="condDev">Condición del recurso</label>
                            <select class="form-select" id="condDev">
                                <option>Óptima</option>
                                <option>Con observaciones</option>
                                <option>Daño reportado</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="notasDev">Notas</label>
                            <textarea class="form-control" id="notasDev" rows="3"></textarea>
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
