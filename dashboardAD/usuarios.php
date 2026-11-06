<?php
require_once __DIR__ . '/../controllers/usuarios.php';

$pageTitle = 'Usuarios';
$dashboardType = 'AD';
$activePage = 'usuarios';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
$idSesion = (int) (rs_usuario_sesion()['id_usuario'] ?? 0);
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Usuarios</h1>
            <p>Gestión de cuentas institucionales, roles y estado de acceso.</p>
        </div>
        <div class="action-btns">
            <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalUsuario" data-rs-new>
                <i class="bi bi-plus-lg me-1"></i>Nuevo usuario
            </button>
        </div>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-4">
                <label class="form-label" for="buscarUsuario">Buscar</label>
                <input class="form-control" id="buscarUsuario" name="q" type="search" maxlength="100" placeholder="Nombre, apellido o correo" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="filtroRol">Rol</label>
                <select class="form-select" id="filtroRol" name="rol">
                    <?php echo rs_opciones_html($roles, 'id_rol', 'nombre_rol', $filtroRol, 'Todos'); ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label" for="filtroEstado">Estado</label>
                <select class="form-select" id="filtroEstado" name="estado">
                    <?php echo rs_opciones_lista(['Activo', 'Inactivo'], $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="usuarios.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
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
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$usuarios): rs_empty_table_row(7); endif; ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?php echo (int) $u['id_usuario']; ?></td>
                            <td><?php echo rs_h($u['nombre'] . ' ' . $u['apellido']); ?></td>
                            <td><?php echo rs_h($u['correo']); ?></td>
                            <td><?php echo rs_h($u['telefono']); ?></td>
                            <td><?php echo rs_h($u['nombre_rol'] ?? 'Sin rol'); ?></td>
                            <td><?php echo rs_badge_estado($u['estado']); ?></td>
                            <td class="table-actions">
                                <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalUsuario"
                                    data-rs-edit="<?php echo rs_json_attr([
                                        'id_usuario' => $u['id_usuario'], 'nombre' => $u['nombre'], 'apellido' => $u['apellido'],
                                        'correo' => $u['correo'], 'telefono' => $u['telefono'], 'id_rol' => $u['id_rol'],
                                        'estado' => rs_estado_texto($u['estado']),
                                    ]); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <?php if ((int) $u['id_usuario'] !== $idSesion): ?>
                                    <form method="post" data-confirm="¿Desea <?php echo rs_es_activo($u['estado']) ? 'inactivar' : 'activar'; ?> este usuario?">
                                        <?php echo rs_csrf_field(); ?>
                                        <input type="hidden" name="accion" value="estado">
                                        <input type="hidden" name="id_usuario" value="<?php echo (int) $u['id_usuario']; ?>">
                                        <button class="btn btn-sm btn-rs-outline" type="submit" title="<?php echo rs_es_activo($u['estado']) ? 'Inactivar' : 'Activar'; ?>">
                                            <i class="bi <?php echo rs_es_activo($u['estado']) ? 'bi-toggle-on' : 'bi-toggle-off'; ?>"></i>
                                        </button>
                                    </form>
                                    <form method="post" data-confirm="¿Eliminar este usuario? Esta acción no se puede deshacer.">
                                        <?php echo rs_csrf_field(); ?>
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id_usuario" value="<?php echo (int) $u['id_usuario']; ?>">
                                        <button class="btn btn-sm btn-rs-outline text-danger" type="submit" title="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalUsuario'); ?>
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="modalUsuarioLabel" data-title-new="Registrar usuario" data-title-edit="Editar usuario"><?php echo !empty($old['id_usuario']) ? 'Editar usuario' : 'Registrar usuario'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_usuario" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_usuario')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="nombreUsuario">Nombre<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'nombre'); ?>" id="nombreUsuario" name="nombre" type="text" maxlength="50" required value="<?php echo rs_h(rs_old($old, 'nombre')); ?>">
                            <?php echo rs_error_campo($errores, 'nombre'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="apellidoUsuario">Apellido<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'apellido'); ?>" id="apellidoUsuario" name="apellido" type="text" maxlength="50" required value="<?php echo rs_h(rs_old($old, 'apellido')); ?>">
                            <?php echo rs_error_campo($errores, 'apellido'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="correoUsuario">Correo<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'correo'); ?>" id="correoUsuario" name="correo" type="email" maxlength="100" required value="<?php echo rs_h(rs_old($old, 'correo')); ?>">
                            <?php echo rs_error_campo($errores, 'correo'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="telefonoUsuario">Teléfono<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'telefono'); ?>" id="telefonoUsuario" name="telefono" type="tel" maxlength="20" pattern="[0-9+()\s\-]{7,20}" title="El teléfono debe tener entre 7 y 15 dígitos." required value="<?php echo rs_h(rs_old($old, 'telefono')); ?>">
                            <?php echo rs_error_campo($errores, 'telefono'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="contrasenaUsuario">Contraseña<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'contrasena'); ?>" id="contrasenaUsuario" name="contrasena" type="password" maxlength="72" autocomplete="new-password" data-password-rule data-required-new <?php echo empty($old['id_usuario']) ? 'required' : ''; ?>>
                            <div class="form-text">Mínimo 8 caracteres con letras y números. Al editar, déjela vacía para conservar la actual.</div>
                            <?php echo rs_error_campo($errores, 'contrasena'); ?>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="rolUsuario">Rol<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_rol'); ?>" id="rolUsuario" name="id_rol" required data-label="un rol">
                                <?php echo rs_opciones_html($roles, 'id_rol', 'nombre_rol', rs_old($old, 'id_rol')); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'id_rol'); ?>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="estadoUsuario">Estado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoUsuario" name="estado" required data-default="Activo" data-label="un estado">
                                <?php echo rs_opciones_lista(['Activo', 'Inactivo'], rs_old($old, 'estado', 'Activo'), null); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'estado'); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-rs-outline" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-rs-outline" type="reset">Limpiar</button>
                    <button class="btn btn-rs" type="submit"><i class="bi bi-save me-1"></i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
