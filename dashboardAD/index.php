<?php
require_once __DIR__ . '/../includes/auth.php';
rs_requiere_admin();
rs_marcar_prestamos_vencidos();

$stats = rs_one(
    "SELECT (SELECT COUNT(*) FROM recurso) AS recursos,
            (SELECT COUNT(*) FROM recurso WHERE disponibilidad = 'Disponible') AS disponibles,
            (SELECT COUNT(*) FROM prestamo WHERE estado IN ('Pendiente','Activo','Vencido')) AS prestamos,
            (SELECT COUNT(*) FROM prestamo WHERE estado = 'Vencido') AS vencidos,
            (SELECT COUNT(*) FROM usuario) AS usuarios,
            (SELECT COUNT(*) FROM usuario WHERE estado IN ('Activo', '1')) AS usuarios_activos"
);
$porDisponibilidad = rs_all('SELECT disponibilidad, COUNT(*) AS total FROM recurso GROUP BY disponibilidad ORDER BY total DESC');
$porEstadoPrestamo = rs_all('SELECT estado, COUNT(*) AS total FROM prestamo GROUP BY estado ORDER BY total DESC');
$actividad = rs_all(
    "SELECT h.fecha_accion, h.accion, h.detalle, h.id_prestamo, p.estado, r.nombre_recurso
     FROM historial_prestamo h
     INNER JOIN prestamo p ON p.id_prestamo = h.id_prestamo
     INNER JOIN recurso r ON r.id_recurso = p.id_recurso
     ORDER BY h.id_historial DESC LIMIT 8"
);

$pageTitle = 'Dashboard';
$dashboardType = 'AD';
$activePage = 'dashboard';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <?php rs_flash_html(); ?>
    <section class="admin-hero section-gap">
        <h2>Bienvenido al panel de administración</h2>
        <p>Supervise recursos, préstamos y catálogos institucionales desde un único entorno.</p>
    </section>

    <div class="row g-3 section-gap">
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-1"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="stat-label">Total de recursos</div>
                    <div class="stat-value"><?php echo (int) $stats['recursos']; ?></div>
                    <div class="stat-hint">Registrados en inventario</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-2"><i class="bi bi-clipboard-check"></i></div>
                <div>
                    <div class="stat-label">Préstamos</div>
                    <div class="stat-value"><?php echo (int) $stats['prestamos']; ?></div>
                    <div class="stat-hint">Vigentes · <?php echo (int) $stats['vencidos']; ?> vencidos</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-3"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <div class="stat-label">Recursos disponibles</div>
                    <div class="stat-value"><?php echo (int) $stats['disponibles']; ?></div>
                    <div class="stat-hint">Listos para préstamo</div>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xxl-3">
            <article class="stat-card">
                <div class="stat-icon bg-4"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-label">Usuarios</div>
                    <div class="stat-value"><?php echo (int) $stats['usuarios']; ?></div>
                    <div class="stat-hint"><?php echo (int) $stats['usuarios_activos']; ?> activos</div>
                </div>
            </article>
        </div>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-xl-8">
            <div class="card-rs h-100">
                <div class="card-header py-3">Préstamos por estado</div>
                <div class="p-3">
                    <?php if (!$porEstadoPrestamo): ?>
                        <div class="chart-placeholder"><i class="bi bi-bar-chart-line"></i><strong>Sin préstamos registrados</strong></div>
                    <?php endif; ?>
                    <?php $maxP = max(array_column($porEstadoPrestamo, 'total') ?: [1]); ?>
                    <?php foreach ($porEstadoPrestamo as $fila): ?>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small"><span><?php echo rs_h($fila['estado']); ?></span><strong><?php echo (int) $fila['total']; ?></strong></div>
                            <div class="progress" role="progressbar" aria-label="<?php echo rs_h($fila['estado']); ?>"><div class="progress-bar" style="width: <?php echo round($fila['total'] * 100 / $maxP); ?>%; background: var(--color2);"></div></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card-rs h-100">
                <div class="card-header py-3">Disponibilidad de recursos</div>
                <div class="p-3">
                    <?php if (!$porDisponibilidad): ?>
                        <div class="chart-placeholder"><i class="bi bi-pie-chart"></i><strong>Sin recursos registrados</strong></div>
                    <?php endif; ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($porDisponibilidad as $fila): ?>
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <?php echo rs_badge_disponibilidad($fila['disponibilidad']); ?><strong><?php echo (int) $fila['total']; ?></strong>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="page-heading d-flex flex-column flex-md-row justify-content-between gap-2 section-gap">
        <div>
            <h1>Accesos rápidos</h1>
            <p>Ingrese a los módulos de gestión.</p>
        </div>
    </div>

    <div class="row g-3 section-gap">
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="usuarios.php"><i class="bi bi-people"></i><strong>Usuarios</strong><span>Administrar cuentas</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="recursos.php"><i class="bi bi-box-seam"></i><strong>Recursos</strong><span>Inventario institucional</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="prestamos.php"><i class="bi bi-clipboard-check"></i><strong>Préstamos</strong><span>Solicitudes y seguimiento</span></a></div>
        <div class="col-12 col-sm-6 col-xxl-3"><a class="quick-access" href="reportes.php"><i class="bi bi-graph-up"></i><strong>Reportes</strong><span>Consultas y exportación</span></a></div>
    </div>

    <div class="card-rs">
        <div class="card-header py-3">Actividad reciente</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Préstamo</th>
                        <th>Acción</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$actividad): rs_empty_table_row(4); endif; ?>
                    <?php foreach ($actividad as $a): ?>
                        <tr>
                            <td><?php echo rs_fecha_txt($a['fecha_accion']); ?></td>
                            <td>#<?php echo (int) $a['id_prestamo']; ?> · <?php echo rs_h($a['nombre_recurso']); ?></td>
                            <td><?php echo rs_h($a['accion']); ?><div class="small text-secondary"><?php echo rs_h($a['detalle']); ?></div></td>
                            <td><?php echo rs_badge_proceso($a['estado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
