<?php
$pageTitle = 'Empleados';
$dashboardType = 'AD';
$activePage = 'empleados';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Empleados</h1>
            <p>Directorio institucional. Los registros aparecerán cuando exista conexión a MySQL.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalEmpleado">
            <i class="bi bi-plus-lg me-1"></i>Nuevo empleado
        </button>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-8">
                <label class="form-label" for="buscarEmpleado">Buscar</label>
                <input class="form-control" id="buscarEmpleado" type="search" placeholder="Nombre, área o cargo">
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
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Área</th>
                        <th>Cargo</th>
                        <th>Contacto</th>
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

<div class="modal fade" id="modalEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Registrar empleado</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="nombreEmp">Nombre</label>
                            <input class="form-control" id="nombreEmp" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="areaEmp">Área</label>
                            <input class="form-control" id="areaEmp" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="cargoEmp">Cargo</label>
                            <input class="form-control" id="cargoEmp" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="correoEmp">Correo</label>
                            <input class="form-control" id="correoEmp" type="email">
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
