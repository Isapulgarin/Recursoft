<?php
/**
 * Navegación pública de la página de presentación.
 */
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top public-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?php echo rs_h(RS_BASE); ?>index.php#inicio">
            <span class="brand-mark"><i class="bi bi-box-seam-fill"></i></span>
            <span>RecurSoft</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#menuPrincipal" aria-controls="menuPrincipal"
                aria-expanded="false" aria-label="Abrir menú de navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav mx-auto my-3 my-lg-0">
                <li class="nav-item"><a href="<?php echo rs_h(RS_BASE); ?>index.php#inicio" class="nav-link">Inicio</a></li>
                <li class="nav-item"><a href="<?php echo rs_h(RS_BASE); ?>index.php#quienes" class="nav-link">¿Quiénes somos?</a></li>
                <li class="nav-item"><a href="<?php echo rs_h(RS_BASE); ?>index.php#funciones" class="nav-link">Funcionalidades</a></li>
                <li class="nav-item"><a href="<?php echo rs_h(RS_BASE); ?>index.php#contacto" class="nav-link">Contacto</a></li>
            </ul>
            <a class="btn btn-brand" href="<?php echo rs_h(RS_BASE); ?>login.php">
                Iniciar sesión
            </a>
        </div>
    </div>
</nav>
