<?php
$pageTitle = 'Dashboard';
$dashboardType = 'EM';
$activePage = 'dashboard';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <section class="employee-hero section-gap">
        <h2>Bienvenido al panel del empleado</h2>
        <p>Consulte recursos, gestione sus préstamos y reporte incidencias desde este espacio.</p>
    </section>

    <div class="row g-3 section-gap">
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-2"><i class="bi bi-journal-check"></i></div>
                <div>
                    <div class="stat-label">Préstamos activos</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin datos cargados</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-3"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="stat-label">Historial</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin datos cargados</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-1"><i class="bi bi-grid"></i></div>
                <div>
                    <div class="stat-label">Catálogo visible</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin datos cargados</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-4"><i class="bi bi-bell"></i></div>
                <div>
                    <div class="stat-label">Avisos</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin notificaciones</div>
                </div>
            </article>
        </div>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="recursos.php"><i class="bi bi-grid"></i><strong>Catálogo</strong><span>Explorar recursos</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="prestamos.php"><i class="bi bi-journal-check"></i><strong>Solicitar préstamo</strong><span>Formulario visual</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="devoluciones.php"><i class="bi bi-arrow-return-left"></i><strong>Devolver</strong><span>Registrar retorno</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="reporte_danos.php"><i class="bi bi-exclamation-triangle"></i><strong>Reportar daño</strong><span>Incidencia visual</span></a></div>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-xl-7">
            <div class="card-rs h-100">
                <div class="card-header py-3">Préstamos activos</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Recurso</th>
                                <th>Salida</th>
                                <th>Retorno</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php rs_empty_table_row(4); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card-rs h-100">
                <div class="card-header py-3">Notificaciones</div>
                <div class="p-3">
                    <div class="notif-item">
                        <strong>Avisos del sistema</strong>
                        <p class="small text-secondary mb-0">No hay notificaciones disponibles.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-heading section-gap">
        <h1>Recursos destacados</h1>
        <p>El catálogo se mostrará en tarjetas cuando existan registros.</p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card-rs catalog-placeholder resource-card-skeleton">
                <?php rs_empty_state('bi-image', 'Sin recurso', 'No hay información disponible.'); ?>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card-rs catalog-placeholder resource-card-skeleton">
                <?php rs_empty_state('bi-image', 'Sin recurso', 'No hay información disponible.'); ?>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card-rs catalog-placeholder resource-card-skeleton">
                <?php rs_empty_state('bi-image', 'Sin recurso', 'No hay información disponible.'); ?>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
