<?php
/**
 * RecurSoft - Pantalla de inicio de sesión.
 */
require_once __DIR__ . '/includes/auth.php';

if (rs_usuario_sesion()) {
    rs_redirect(rs_es_admin() ? 'dashboardAD/index.php' : 'dashboardEM/index.php');
}

$loginErrores = $_SESSION['rs_login_errores'] ?? [];
$loginOld = $_SESSION['rs_login_old'] ?? [];
unset($_SESSION['rs_login_errores'], $_SESSION['rs_login_old']);

$pageTitle = 'Iniciar sesión';
$dashboardType = 'login';
$bodyClass = 'login-page';
require_once __DIR__ . '/includes/head.php';
?>

<div class="container-fluid px-0">
    <div class="row g-0 login-grid">
        <div class="col-lg-6 d-none d-lg-flex login-brand-panel">
            <div class="px-lg-5">
                <img src="<?php echo rs_h(RS_BASE); ?>IMG/logo.png" alt="Logotipo RecurSoft">
                <h1 class="display-6 fw-bold mb-3">RecurSoft</h1>
                <p class="fs-5 mb-4">Sistema de Gestión de Recursos Institucionales</p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Control de inventario y préstamos</li>
                    <li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Paneles para administrador y empleado</li>
                    <li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Información conectada con MySQL</li>
                </ul>
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-center justify-content-center p-3 p-md-4">
            <div class="login-card">
                <div class="d-flex d-lg-none align-items-center gap-3 mb-3">
                    <img src="<?php echo rs_h(RS_BASE); ?>IMG/logo.png" alt="Logotipo RecurSoft" width="56" height="56" class="rounded-3">
                    <div>
                        <strong>RecurSoft</strong>
                        <div class="small text-secondary">Gestión institucional</div>
                    </div>
                </div>

                <p class="mb-3">
                    <a class="small text-decoration-none" href="<?php echo rs_h(RS_BASE); ?>index.php">
                        <i class="bi bi-arrow-left me-1"></i>Volver al inicio
                    </a>
                </p>
                <h2 class="h4 fw-bold text-primary mb-1">Iniciar sesión</h2>
                <p class="text-secondary mb-4">Ingrese sus credenciales institucionales.</p>

                <?php rs_flash_html(); ?>

                <form class="form-visual" method="post" action="<?php echo rs_h(RS_BASE); ?>controllers/auth.php" autocomplete="off" data-validate>
                    <?php echo rs_csrf_field(); ?>
                    <input type="hidden" name="accion" value="login">
                    <div class="mb-3">
                        <label class="form-label" for="usuario">Correo electrónico<span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input class="form-control<?php echo rs_invalid($loginErrores, 'usuario'); ?>" type="email" id="usuario" name="usuario" maxlength="100" placeholder="usuario@institucion.edu" value="<?php echo rs_h($loginOld['usuario'] ?? ''); ?>" required data-label="Correo">
                        </div>
                        <?php echo rs_error_campo($loginErrores, 'usuario'); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Contraseña<span class="req">*</span></label>
                        <div class="password-wrap">
                            <input class="form-control<?php echo rs_invalid($loginErrores, 'password'); ?>" type="password" id="password" name="password" placeholder="Ingrese su contraseña" required data-label="Contraseña">
                            <button class="btn-eye" type="button" id="togglePassword" aria-label="Mostrar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <?php echo rs_error_campo($loginErrores, 'password'); ?>
                    </div>

                    <button class="btn btn-rs w-100" type="submit">
                        Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>
