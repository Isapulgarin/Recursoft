<?php
/**
 * Pie de página de la página pública.
 */
?>
<footer class="site-footer">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-6">
                <a class="footer-brand" href="<?php echo rs_h(RS_BASE); ?>index.php#inicio">
                    <i class="bi bi-box-seam-fill me-2"></i>RecurSoft
                </a>
                <p class="footer-description">
                    Plataforma para apoyar la gestión, el control y el seguimiento
                    de los recursos institucionales.
                </p>
            </div>
            <div class="col-6 col-lg-3">
                <h2 class="footer-heading">Navegación</h2>
                <ul class="footer-links">
                    <li><a href="<?php echo rs_h(RS_BASE); ?>index.php#inicio">Inicio</a></li>
                    <li><a href="<?php echo rs_h(RS_BASE); ?>index.php#quienes">Nosotros</a></li>
                    <li><a href="<?php echo rs_h(RS_BASE); ?>index.php#funciones">Funcionalidades</a></li>
                    <li><a href="<?php echo rs_h(RS_BASE); ?>index.php#contacto">Contacto</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h2 class="footer-heading">Módulos</h2>
                <ul class="footer-links">
                    <li>Inventario</li>
                    <li>Préstamos</li>
                    <li>Mantenimientos</li>
                    <li>Reportes</li>
                </ul>
            </div>
        </div>
        <hr class="footer-divider">
        <p class="footer-copy mb-0">© <?php echo date('Y'); ?> RecurSoft. Sistema de Gestión de Recursos Institucionales.</p>
    </div>
</footer>
