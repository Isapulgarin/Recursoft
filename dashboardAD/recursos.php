<?php
$pageTitle = 'Recursos';
$dashboardType = 'AD';
$activePage = 'recursos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Recursos</h1>
            <p>Inventario visual de bienes institucionales, listo para conectar con la base de datos.</p>
        </div>
        <div class="action-btns">
            <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalRecurso">
                <i class="bi bi-plus-lg me-1"></i>Nuevo recurso
            </button>
        </div>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-4">
                <label class="form-label" for="buscarRecurso">Buscar recurso</label>
                <input class="form-control" id="buscarRecurso" type="search" placeholder="Código o nombre">
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="filtroCategoria">Categoría</label>
                <select class="form-select" id="filtroCategoria">
                    <option value="">Todas</option>
                    <option>Equipos</option>
                    <option>Mobiliario</option>
                    <option>Material didáctico</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label" for="filtroEstadoRecurso">Estado</label>
                <select class="form-select" id="filtroEstadoRecurso">
                    <option value="">Todos</option>
                    <option>Disponible</option>
                    <option>Prestado</option>
                    <option>Mantenimiento</option>
                </select>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Recurso</th>
                        <th>Categoría</th>
                        <th>Ubicación</th>
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

<div class="modal fade" id="modalRecurso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Registrar recurso</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="codigoRecurso">Código</label>
                            <input class="form-control" id="codigoRecurso" type="text">
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label" for="nombreRecurso">Nombre</label>
                            <input class="form-control" id="nombreRecurso" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="categoriaRecurso">Categoría</label>
                            <select class="form-select" id="categoriaRecurso">
                                <option value="">Seleccione</option>
                                <option>Equipos</option>
                                <option>Mobiliario</option>
                                <option>Material didáctico</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="ubicacionRecurso">Ubicación</label>
                            <input class="form-control" id="ubicacionRecurso" type="text">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="descRecurso">Descripción</label>
                            <textarea class="form-control" id="descRecurso" rows="3"></textarea>
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
