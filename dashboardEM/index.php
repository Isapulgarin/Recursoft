<?php
$emPagina = 'index';
require_once __DIR__ . '/../controllers/empleado_panel.php';

$activos = array_values(array_filter($misPrestamos, fn ($p) => in_array($p['estado'], RS_ESTADOS_PRESTAMO_VIGENTE, true)));
$vencidos = array_values(array_filter($misPrestamos, fn ($p) => $p['estado'] === 'Vencido'));
$proximos = array_values(array_filter($activos, fn ($p) => $p['estado'] === 'Activo' && $p['fecha_devolucion_programada'] && $p['fecha_devolucion_programada'] <= date('Y-m-d', strtotime('+2 days'))));
$disponibles = (int) (rs_one("SELECT COUNT(*) AS n FROM recurso WHERE disponibilidad = 'Disponible'")['n'] ?? 0);
$destacados = rs_all(
    "SELECT r.id_recurso, r.codigo_interno, r.nombre_recurso, r.disponibilidad, c.nombre_categoria FROM recurso r
     LEFT JOIN categoria_recurso c ON c.id_categoria = r.id_categoria
     WHERE r.disponibilidad = 'Disponible' ORDER BY r.id_recurso DESC LIMIT 3"
);

$pageTitle = 'Dashboard';
$dashboardType = 'EM';
$activePage = 'dashboard';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <?php rs_flash_html(); ?>
    <?php if ($sinEmpleado): ?>
        <div class="alert alert-rs alert-rs-error">Su cuenta no está asociada a un empleado. Contacte al administrador.</div>
    <?php endif; ?>
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
                    <div class="stat-value"><?php echo (int) (count($activos)); ?></div>
                    <div class="stat-hint">Préstamos vigentes</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-3"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="stat-label">Historial</div>
                    <div class="stat-value"><?php echo (int) (count($misPrestamos)); ?></div>
                    <div class="stat-hint">Préstamos registrados</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-1"><i class="bi bi-grid"></i></div>
                <div>
                    <div class="stat-label">Catálogo visible</div>
                    <div class="stat-value"><?php echo (int) ($disponibles); ?></div>
                    <div class="stat-hint">Recursos disponibles</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-4"><i class="bi bi-bell"></i></div>
                <div>
                    <div class="stat-label">Avisos</div>
                    <div class="stat-value"><?php echo (int) (count($vencidos) + count($proximos)); ?></div>
                    <div class="stat-hint">Vencidos o próximos a vencer</div>
                </div>
            </article>
        </div>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="recursos.php"><i class="bi bi-grid"></i><strong>Catálogo</strong><span>Explorar recursos</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="prestamos.php"><i class="bi bi-journal-check"></i><strong>Solicitar préstamo</strong><span>Nueva solicitud</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="devoluciones.php"><i class="bi bi-arrow-return-left"></i><strong>Devolver</strong><span>Registrar retorno</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="reporte_danos.php"><i class="bi bi-exclamation-triangle"></i><strong>Reportar daño</strong><span>Informar incidencia</span></a></div>
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
                            <?php if (!$activos): rs_empty_table_row(4); endif; ?>
                            <?php foreach ($activos as $p): ?>
                                <tr>
                                    <td><?php echo rs_h($p['nombre_recurso']); ?></td>
                                    <td><?php echo rs_fecha_txt($p['fecha_entrega']); ?></td>
                                    <td><?php echo rs_fecha_txt($p['fecha_devolucion_programada']); ?></td>
                                    <td><?php echo rs_badge_proceso($p['estado']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card-rs h-100">
                <div class="card-header py-3">Notificaciones</div>
                <div class="p-3">
                    <?php if (!$vencidos && !$proximos): ?>
                        <div class="notif-item">
                            <strong>Avisos del sistema</strong>
                            <p class="small text-secondary mb-0">No hay notificaciones disponibles.</p>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($vencidos as $p): ?>
                        <div class="notif-item">
                            <strong class="text-danger">Préstamo #<?php echo (int) $p['id_prestamo']; ?> vencido</strong>
                            <p class="small text-secondary mb-0"><?php echo rs_h($p['nombre_recurso']); ?> debía devolverse el <?php echo rs_fecha_txt($p['fecha_devolucion_programada']); ?>.</p>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($proximos as $p): ?>
                        <div class="notif-item">
                            <strong>Devolución próxima</strong>
                            <p class="small text-secondary mb-0"><?php echo rs_h($p['nombre_recurso']); ?> vence el <?php echo rs_fecha_txt($p['fecha_devolucion_programada']); ?>.</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="page-heading section-gap">
        <h1>Recursos destacados</h1>
        <p>Últimos recursos disponibles para préstamo.</p>
    </div>

    <div class="row g-3">
        <?php if (!$destacados): ?>
            <div class="col-12"><div class="card-rs catalog-placeholder resource-card-skeleton"><?php rs_empty_state('bi-image', 'Sin recursos', 'No hay recursos disponibles.'); ?></div></div>
        <?php endif; ?>
        <?php foreach ($destacados as $r): ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card-rs p-3 h-100">
                    <h2 class="h6 text-primary mb-1"><?php echo rs_h($r['nombre_recurso']); ?></h2>
                    <div class="small text-secondary mb-3"><?php echo rs_h($r['codigo_interno'] . ' · ' . ($r['nombre_categoria'] ?? 'Sin categoría')); ?></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <?php echo rs_badge_disponibilidad($r['disponibilidad']); ?>
                        <?php if (!$sinEmpleado): ?><a class="btn btn-sm btn-rs" href="prestamos.php?recurso=<?php echo (int) $r['id_recurso']; ?>">Solicitar</a><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
