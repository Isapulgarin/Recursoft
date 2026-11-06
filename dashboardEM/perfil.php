<?php
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
        <p>Formulario visual de datos personales. No se almacenan cambios en esta etapa.</p>
    </div>

    <div class="card-rs overflow-hidden">
        <div class="profile-cover"></div>
        <div class="px-3 px-md-4 pb-4">
            <img class="profile-avatar" src="<?php echo rs_h(RS_BASE); ?>IMG/avatar.png" alt="Avatar del empleado">
            <p class="employee-note mt-3">El empleado solo puede consultar y actualizar su propia información cuando el backend esté conectado.</p>

            <form class="form-visual row g-3 mt-1">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="nombrePerfil">Nombre</label>
                    <input class="form-control" id="nombrePerfil" type="text">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="correoPerfil">Correo</label>
                    <input class="form-control" id="correoPerfil" type="email">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="areaPerfil">Área</label>
                    <input class="form-control" id="areaPerfil" type="text">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="cargoPerfil">Cargo</label>
                    <input class="form-control" id="cargoPerfil" type="text">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="telPerfil">Teléfono</label>
                    <input class="form-control" id="telPerfil" type="tel">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="passPerfil">Nueva contraseña</label>
                    <input class="form-control" id="passPerfil" type="password" autocomplete="new-password">
                </div>
                <div class="col-12">
                    <button class="btn btn-rs" type="submit">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
