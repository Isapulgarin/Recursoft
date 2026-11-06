<?php
$pageTitle = 'Dashboard';
$dashboardType = 'AD';
$activePage = 'dashboard';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <section class="admin-hero section-gap">
        <h2>Bienvenido al panel de administración</h2>
        <p>Supervise recursos, préstamos y catálogos institucionales desde un único entorno visual.</p>
    </section>

    <div class="row g-3 section-gap">
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-1"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="stat-label">Total de recursos</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin datos cargados</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-2"><i class="bi bi-clipboard-check"></i></div>
                <div>
                    <div class="stat-label">Préstamos</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin datos cargados</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-3"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <div class="stat-label">Recursos disponibles</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin datos cargados</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-4"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-label">Usuarios</div>
                    <div class="stat-value">—</div>
                    <div class="stat-hint">Sin datos cargados</div>
                </div>
            </article>
        </div>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-xl-8">
            <div class="card-rs h-100">
                <div class="card-header py-3">Actividad de préstamos</div>
                <div class="p-3">
                    <div class="chart-placeholder">
                        <i class="bi bi-bar-chart-line"></i>
                        <strong>Sin datos para graficar</strong>
                        <span>El contenedor quedará listo para un motor de gráficos.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card-rs h-100">
                <div class="card-header py-3">Estado operativo</div>
                <div class="p-3">
                    <div class="chart-placeholder">
                        <i class="bi bi-pie-chart"></i>
                        <strong>Sin indicadores</strong>
                        <span>No hay registros disponibles.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-heading d-flex flex-column flex-md-row justify-content-between gap-2 section-gap">
        <div>
            <h1>Accesos rápidos</h1>
            <p>Ingrese a los módulos de gestión sin datos de demostración.</p>
        </div>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="usuarios.php"><i class="bi bi-people"></i><strong>Usuarios</strong><span>Administrar cuentas</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="recursos.php"><i class="bi bi-box-seam"></i><strong>Recursos</strong><span>Inventario institucional</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="prestamos.php"><i class="bi bi-clipboard-check"></i><strong>Préstamos</strong><span>Solicitudes y seguimiento</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="reportes.php"><i class="bi bi-graph-up"></i><strong>Reportes</strong><span>Consultas y exportación visual</span></a></div>
    </div>

    <div class="card-rs">
        <div class="card-header py-3">Actividad reciente</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(4); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
