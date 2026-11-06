<?php
/**
 * RecurSoft - Pantalla de inicio de sesión (solo interfaz).
 * Sin validación ni credenciales de prueba.
 */
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
                    <li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Interfaz lista para conectar con MySQL</li>
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
                <p class="text-secondary mb-4">Ingrese sus credenciales institucionales cuando el backend esté disponible.</p>

                <form class="form-visual" method="post" action="#" autocomplete="off" novalidate>
                    <div class="mb-3">
                        <label class="form-label" for="usuario">Usuario o correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input class="form-control" type="text" id="usuario" name="usuario" placeholder="usuario@institucion.edu" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Contraseña</label>
                        <div class="password-wrap">
                            <input class="form-control" type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                            <button class="btn-eye" type="button" id="togglePassword" aria-label="Mostrar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-rs w-100" type="submit">
                        Iniciar sesión
                    </button>
                </form>

                <p class="small text-secondary mt-4 mb-0">Accesos de referencia visual (sin autenticación):</p>
                <div class="d-flex flex-column flex-sm-row gap-2 mt-2">
                    <a class="btn btn-rs-outline flex-fill" href="dashboardAD/index.php">Ver panel administrador</a>
                    <a class="btn btn-rs-outline flex-fill" href="dashboardEM/index.php">Ver panel empleado</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>
