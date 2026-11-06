<?php
$emPagina = 'perfil';
require_once __DIR__ . '/../controllers/empleado_panel.php';

$datosUsuario = rs_one('SELECT u.nombre, u.apellido, u.correo, u.telefono, u.estado, r.nombre_rol FROM usuario u LEFT JOIN rol r ON r.id_rol = u.id_rol WHERE u.id_usuario = ?', [(int) $usuarioEm['id_usuario']]) ?? [];

$pageTitle = 'Mi Perfil';
$dashboardType = 'EM';
$activePage = 'perfil';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Mi Perfil</h1>
        <p>Consulte sus datos y actualice su teléfono o contraseña.</p>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs overflow-hidden">
        <div class="profile-cover"></div>
        <div class="px-3 px-md-4 pb-4">
            <img class="profile-avatar" src="<?php echo rs_h(RS_BASE); ?>IMG/avatar.png" alt="Avatar del empleado">
            <p class="employee-note mt-3">Los datos institucionales (nombre, correo, documento y cargo) solo pueden ser modificados por el administrador.</p>
            <?php echo rs_error_general($errores); ?>

            <form class="form-visual row g-3 mt-1" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="perfil">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="nombrePerfil">Nombre</label>
                    <input class="form-control" id="nombrePerfil" type="text" value="<?php echo rs_h(($datosUsuario['nombre'] ?? '') . ' ' . ($datosUsuario['apellido'] ?? '')); ?>" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="correoPerfil">Correo</label>
                    <input class="form-control" id="correoPerfil" type="email" value="<?php echo rs_h($datosUsuario['correo'] ?? ''); ?>" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="docPerfil">Documento</label>
                    <input class="form-control" id="docPerfil" type="text" value="<?php echo rs_h($empleadoEm['documento'] ?? 'Sin empleado asociado'); ?>" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="cargoPerfil">Cargo</label>
                    <input class="form-control" id="cargoPerfil" type="text" value="<?php echo rs_h($empleadoEm['cargo'] ?? ''); ?>" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="telPerfil">Teléfono<span class="req">*</span></label>
                    <input class="form-control<?php echo rs_invalid($errores, 'telefono'); ?>" id="telPerfil" name="telefono" type="tel" maxlength="20" required pattern="[0-9+()\s\-]{7,20}" title="Ingrese un teléfono válido (7 a 20 dígitos)." value="<?php echo rs_h(rs_old($old, 'telefono', $datosUsuario['telefono'] ?? '')); ?>">
                    <?php echo rs_error_campo($errores, 'telefono'); ?>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="rolPerfil">Rol</label>
                    <input class="form-control" id="rolPerfil" type="text" value="<?php echo rs_h($datosUsuario['nombre_rol'] ?? ''); ?>" disabled>
                </div>
                <div class="col-12"><h2 class="h6 text-primary mt-2 mb-0">Cambiar contraseña <span class="small text-secondary fw-normal">(opcional)</span></h2></div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="passActual">Contraseña actual</label>
                    <input class="form-control<?php echo rs_invalid($errores, 'contrasena_actual'); ?>" id="passActual" name="contrasena_actual" type="password" autocomplete="current-password">
                    <?php echo rs_error_campo($errores, 'contrasena_actual'); ?>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="passPerfil">Nueva contraseña</label>
                    <input class="form-control<?php echo rs_invalid($errores, 'contrasena'); ?>" id="passPerfil" name="contrasena" type="password" minlength="8" data-password-rule autocomplete="new-password">
                    <div class="form-text">Mínimo 8 caracteres, con letras y números.</div>
                    <?php echo rs_error_campo($errores, 'contrasena'); ?>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="passConfirmar">Confirmar contraseña</label>
                    <input class="form-control<?php echo rs_invalid($errores, 'contrasena_confirmar'); ?>" id="passConfirmar" name="contrasena_confirmar" type="password" autocomplete="new-password">
                    <?php echo rs_error_campo($errores, 'contrasena_confirmar'); ?>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-rs" type="submit">Guardar cambios</button>
                    <button class="btn btn-rs-outline" type="reset">Restablecer</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
