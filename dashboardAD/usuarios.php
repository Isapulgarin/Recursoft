<?php
$pageTitle = 'Usuarios';
$dashboardType = 'AD';
$activePage = 'usuarios';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Usuarios</h1>
            <p>Estructura visual para la gestión de cuentas institucionales.</p>
        </div>
        <div class="action-btns">
            <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalUsuario">
                <i class="bi bi-plus-lg me-1"></i>Nuevo usuario
            </button>
        </div>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label" for="buscarUsuario">Buscar</label>
                <input class="form-control" id="buscarUsuario" data-table-search type="search" placeholder="Nombre, correo o rol">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="filtroRol">Rol</label>
                <select class="form-select" id="filtroRol">
                    <option value="">Todos</option>
                    <option>Administrador</option>
                    <option>Empleado</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <button class="btn btn-rs-outline w-100" type="submit">Filtrar</button>
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
                        <th>Correo</th>
                        <th>Rol</th>
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

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="modalUsuarioLabel">Registrar usuario</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="nombreUsuario">Nombre completo</label>
                            <input class="form-control" id="nombreUsuario" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="correoUsuario">Correo</label>
                            <input class="form-control" id="correoUsuario" type="email">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="rolUsuario">Rol</label>
                            <select class="form-select" id="rolUsuario">
                                <option value="">Seleccione</option>
                                <option>Administrador</option>
                                <option>Empleado</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="estadoUsuario">Estado</label>
                            <select class="form-select" id="estadoUsuario">
                                <option>Activo</option>
                                <option>Inactivo</option>
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
