<?php
$emPagina = 'devoluciones';
require_once __DIR__ . '/../controllers/empleado_panel.php';

$pendientes = $idEmpleado ? rs_prestamos_sin_devolucion($idEmpleado) : [];
$misDevoluciones = $idEmpleado ? rs_all(
    'SELECT d.*, r.codigo_interno, r.nombre_recurso FROM devolucion d
     INNER JOIN prestamo p ON p.id_prestamo = d.id_prestamo
     INNER JOIN recurso r ON r.id_recurso = p.id_recurso
     WHERE p.id_empleado = ? ORDER BY d.id_devolucion DESC',
    [$idEmpleado]
) : [];
$hoy = date('Y-m-d');

$pageTitle = 'Devoluciones';
$dashboardType = 'EM';
$activePage = 'devoluciones';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Devoluciones</h1>
        <p>Registre el retorno de los recursos que tiene en préstamo.</p>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs p-3 p-md-4 section-gap">
        <h2 class="h5 text-primary mb-3">Registrar devolución</h2>
        <?php echo rs_error_general($errores); ?>
        <form class="form-visual row g-3" method="post" data-validate>
            <?php echo rs_csrf_field(); ?>
            <input type="hidden" name="accion" value="devolver">
            <div class="col-12 col-md-6">
                <label class="form-label" for="prestamoDevEmp">Préstamo<span class="req">*</span></label>
                <select class="form-select<?php echo rs_invalid($errores, 'id_prestamo'); ?>" id="prestamoDevEmp" name="id_prestamo" required data-label="un préstamo">
                    <?php echo rs_opciones_html($pendientes, 'id_prestamo', fn ($p) => '#' . $p['id_prestamo'] . ' - ' . $p['codigo_interno'] . ' ' . $p['nombre_recurso'] . ' (entregado ' . rs_fecha_txt($p['fecha_entrega']) . ')', rs_old($old, 'id_prestamo')); ?>
                </select>
                <?php if (!$pendientes): ?><div class="form-text">No tiene préstamos activos pendientes de devolución.</div><?php endif; ?>
                <?php echo rs_error_campo($errores, 'id_prestamo'); ?>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="fechaDevEmp">Fecha de devolución<span class="req">*</span></label>
                <input class="form-control<?php echo rs_invalid($errores, 'fecha_devolucion'); ?>" id="fechaDevEmp" name="fecha_devolucion" type="date" max="<?php echo $hoy; ?>" data-no-future required value="<?php echo rs_h(rs_old($old, 'fecha_devolucion', $hoy)); ?>">
                <?php echo rs_error_campo($errores, 'fecha_devolucion'); ?>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="condEmp">Estado del recurso<span class="req">*</span></label>
                <select class="form-select<?php echo rs_invalid($errores, 'estado_recurso'); ?>" id="condEmp" name="estado_recurso" required data-label="el estado del recurso">
                    <?php echo rs_opciones_lista(RS_ESTADOS_DEVOLUCION, rs_old($old, 'estado_recurso')); ?>
                </select>
                <?php echo rs_error_campo($errores, 'estado_recurso'); ?>
            </div>
            <div class="col-12">
                <label class="form-label" for="notaDevEmp">Observaciones</label>
                <textarea class="form-control<?php echo rs_invalid($errores, 'observaciones'); ?>" id="notaDevEmp" name="observaciones" rows="3" maxlength="150"><?php echo rs_h(rs_old($old, 'observaciones')); ?></textarea>
                <div class="form-text">Obligatorias si el recurso se devuelve dañado.</div>
                <?php echo rs_error_campo($errores, 'observaciones'); ?>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-rs" type="submit"<?php echo $pendientes ? '' : ' disabled'; ?>>Registrar devolución</button>
                <button class="btn btn-rs-outline" type="reset">Limpiar</button>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="card-header py-3">Devoluciones propias</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Préstamo</th>
                        <th>Recurso</th>
                        <th>Fecha</th>
                        <th>Estado del recurso</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$misDevoluciones): rs_empty_table_row(5); endif; ?>
                    <?php foreach ($misDevoluciones as $d): ?>
                        <tr>
                            <td>#<?php echo (int) $d['id_prestamo']; ?></td>
                            <td><?php echo rs_h($d['codigo_interno'] . ' - ' . $d['nombre_recurso']); ?></td>
                            <td><?php echo rs_fecha_txt($d['fecha_devolucion']); ?></td>
                            <td><?php echo rs_badge_proceso($d['estado_recurso']); ?></td>
                            <td class="small"><?php echo rs_h($d['observaciones']); ?></td>
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
