<?php
$pageTitle = 'Categorías';
$dashboardType = 'AD';
$activePage = 'categorias';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Categorías</h1>
            <p>Clasificación visual de recursos institucionales.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalCategoria">
            <i class="bi bi-plus-lg me-1"></i>Nueva categoría
        </button>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual">
            <label class="form-label" for="buscarCat">Buscar categoría</label>
            <input class="form-control" id="buscarCat" type="search" placeholder="Nombre o código">
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Recursos asociados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(5); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Registrar categoría</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="codigoCat">Código</label>
                        <input class="form-control" id="codigoCat" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="nombreCat">Nombre</label>
                        <input class="form-control" id="nombreCat" type="text">
                    </div>
                    <div>
                        <label class="form-label" for="descCat">Descripción</label>
                        <textarea class="form-control" id="descCat" rows="3"></textarea>
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
