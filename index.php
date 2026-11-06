<?php
/**
 * RecurSoft - Página pública de presentación.
 * Integra la interfaz adaptada con el resto del sistema (login y paneles).
 */
$pageTitle = 'Inicio';
$dashboardType = 'login';
$bodyClass = 'public-page';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar-publica.php';
?>

<main>
    <section id="inicio" class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-6">
                    <span class="eyebrow mb-3">
                        <i class="bi bi-building me-2"></i>Gestión institucional
                    </span>
                    <h1 class="display-5 fw-bold hero-title">
                        Controla y administra los recursos de tu institución fácilmente.
                    </h1>
                    <p class="lead text-secondary mt-4">
                        RecurSoft es una plataforma diseñada para organizar la gestión
                        de recursos institucionales, controlar préstamos y devoluciones,
                        y facilitar el seguimiento de su disponibilidad y estado.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                        <a class="btn btn-brand btn-lg" href="<?php echo rs_h(RS_BASE); ?>login.php">
                            Iniciar sesión <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#quienes" class="btn btn-outline-brand btn-lg">
                            Conocer más
                        </a>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="preview-panel">
                        <div class="preview-panel-header">
                            <div class="d-flex align-items-center gap-2">
                                <span class="brand-mark brand-mark-small">
                                    <i class="bi bi-box-seam-fill"></i>
                                </span>
                                <div>
                                    <strong>RecurSoft</strong>
                                    <small class="d-block text-white-50">Gestión institucional</small>
                                </div>
                            </div>
                            <span class="preview-status"><i class="bi bi-circle-fill me-1"></i> Plataforma</span>
                        </div>
                        <div class="preview-panel-body">
                            <h2 class="h5 fw-bold mb-1">Administración centralizada</h2>
                            <p class="text-secondary small mb-4">
                                Una vista organizada de los procesos institucionales.
                            </p>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="preview-tile">
                                        <i class="bi bi-box-seam"></i>
                                        <strong>Inventario</strong>
                                        <span>Control de recursos</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="preview-tile">
                                        <i class="bi bi-arrow-left-right"></i>
                                        <strong>Préstamos</strong>
                                        <span>Seguimiento de movimientos</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="preview-tile">
                                        <i class="bi bi-tools"></i>
                                        <strong>Mantenimiento</strong>
                                        <span>Estado de los recursos</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="preview-tile">
                                        <i class="bi bi-file-earmark-bar-graph"></i>
                                        <strong>Reportes</strong>
                                        <span>Información organizada</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-note mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Interfaz de presentación del sistema.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="quienes" class="section-space bg-soft">
        <div class="container">
            <div class="section-heading text-center">
                <span class="section-kicker">Conócenos</span>
                <h2>¿Quiénes somos?</h2>
                <p>Una herramienta pensada para mejorar la organización de los recursos institucionales.</p>
            </div>

            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-5">
                    <div class="about-illustration">
                        <div class="about-icon"><i class="bi bi-building-gear"></i></div>
                        <h3>RecurSoft</h3>
                        <p>Gestión de recursos institucionales</p>
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <h3 class="h3 fw-bold text-brand mb-3">Organización y control en un solo lugar</h3>
                    <p class="text-secondary">
                        RecurSoft es una plataforma web creada para facilitar la administración
                        de recursos institucionales, como equipos tecnológicos, mobiliario,
                        materiales académicos y otros elementos utilizados en las actividades
                        de una institución.
                    </p>
                    <p class="text-secondary">
                        Su propósito es centralizar la información y apoyar los procesos de
                        registro, préstamo, devolución, mantenimiento y adquisición, permitiendo
                        consultar el estado y la disponibilidad de los recursos.
                    </p>
                    <div class="row g-3 mt-3">
                        <div class="col-12 col-sm-6">
                            <div class="mini-feature">
                                <i class="bi bi-box-seam"></i>
                                <div><strong>Recursos</strong><span>Inventario organizado</span></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mini-feature">
                                <i class="bi bi-arrow-left-right"></i>
                                <div><strong>Préstamos</strong><span>Control de movimientos</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <article class="info-card h-100">
                        <div class="info-icon"><i class="bi bi-bullseye"></i></div>
                        <h2 class="h3">Misión</h2>
                        <p class="text-secondary mb-0">
                            Facilitar la administración de los recursos institucionales mediante
                            una plataforma organizada que apoye el control de inventarios,
                            préstamos, devoluciones y mantenimientos.
                        </p>
                    </article>
                </div>
                <div class="col-12 col-md-6">
                    <article class="info-card h-100">
                        <div class="info-icon"><i class="bi bi-eye"></i></div>
                        <h2 class="h3">Visión</h2>
                        <p class="text-secondary mb-0">
                            Ser una herramienta de apoyo para las instituciones que buscan
                            mejorar la organización, el seguimiento y el aprovechamiento de
                            sus recursos.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space bg-soft">
        <div class="container">
            <div class="section-heading text-center">
                <span class="section-kicker">Nuestros principios</span>
                <h2>Valores</h2>
                <p>Principios que orientan el desarrollo y uso de la plataforma.</p>
            </div>
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="value-card">
                        <i class="bi bi-shield-check"></i>
                        <h3>Seguridad</h3>
                        <p>Organización y cuidado de la información institucional.</p>
                    </article>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="value-card">
                        <i class="bi bi-lightbulb"></i>
                        <h3>Innovación</h3>
                        <p>Uso de herramientas tecnológicas para apoyar los procesos.</p>
                    </article>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="value-card">
                        <i class="bi bi-people"></i>
                        <h3>Colaboración</h3>
                        <p>Apoyo a las actividades de administradores y empleados.</p>
                    </article>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="value-card">
                        <i class="bi bi-award"></i>
                        <h3>Calidad</h3>
                        <p>Una experiencia clara, consistente y fácil de utilizar.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section id="funciones" class="section-space">
        <div class="container">
            <div class="section-heading text-center">
                <span class="section-kicker">Qué puedes gestionar</span>
                <h2>Funcionalidades principales</h2>
                <p>Procesos que se organizan desde RecurSoft.</p>
            </div>
            <div class="row g-4">
                <?php
                $features = [
                    ['bi-box-seam', 'Inventario', 'Organización y consulta de los recursos institucionales.'],
                    ['bi-arrow-left-right', 'Préstamos y devoluciones', 'Seguimiento de la entrega y devolución de recursos.'],
                    ['bi-people', 'Usuarios y empleados', 'Administración de los perfiles y roles del sistema.'],
                    ['bi-file-earmark-bar-graph', 'Reportes', 'Consulta de información para apoyar decisiones.'],
                    ['bi-tools', 'Mantenimiento', 'Seguimiento del estado y mantenimiento de los recursos.'],
                    ['bi-clock-history', 'Historial', 'Consulta de los movimientos y préstamos registrados.'],
                ];
                foreach ($features as $feature):
                ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <article class="feature-card h-100">
                            <div class="feature-icon"><i class="bi <?php echo rs_h($feature[0]); ?>"></i></div>
                            <h3><?php echo rs_h($feature[1]); ?></h3>
                            <p><?php echo rs_h($feature[2]); ?></p>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section-space bg-soft">
        <div class="container">
            <div class="section-heading text-center">
                <span class="section-kicker">Una plataforma organizada</span>
                <h2>¿Por qué utilizar RecurSoft?</h2>
                <p>Una experiencia enfocada en la gestión de recursos institucionales.</p>
            </div>
            <div class="row g-4 text-center">
                <div class="col-6 col-lg-3">
                    <div class="benefit-item"><i class="bi bi-diagram-3"></i><h3>Centralización</h3><p>Información organizada en un mismo sistema.</p></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="benefit-item"><i class="bi bi-shield-lock"></i><h3>Roles</h3><p>Vistas diferenciadas para cada tipo de usuario.</p></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="benefit-item"><i class="bi bi-phone"></i><h3>Responsive</h3><p>Interfaz adaptable a distintos dispositivos.</p></div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="benefit-item"><i class="bi bi-clipboard-data"></i><h3>Seguimiento</h3><p>Apoyo al control de los procesos institucionales.</p></div>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto" class="section-space">
        <div class="container">
            <div class="section-heading text-center">
                <span class="section-kicker">Estamos para ayudarte</span>
                <h2>Contacto</h2>
                <p>Información institucional de RecurSoft.</p>
            </div>
            <div class="contact-card text-center">
                <div class="contact-icon"><i class="bi bi-chat-square-text"></i></div>
                <h3>¿Tienes preguntas sobre RecurSoft?</h3>
                <p class="text-secondary mb-0">
                    Comunícate con el equipo encargado de la plataforma a través de los
                    canales oficiales de tu institución.
                </p>
            </div>
        </div>
    </section>
</main>

<?php
require_once __DIR__ . '/includes/footer-publico.php';
require_once __DIR__ . '/includes/scripts.php';
