<?php
$emPagina = 'prestamos';
require_once __DIR__ . '/../controllers/empleado_panel.php';

$recursosDisponibles = rs_all(
    "SELECT r.id_recurso, r.codigo_interno, r.nombre_recurso, c.nombre_categoria FROM recurso r
     LEFT JOIN categoria_recurso c ON c.id_categoria = r.id_categoria
     WHERE r.disponibilidad = 'Disponible' AND r.estado NOT IN ('Dañado','Fuera de servicio','Inactivo')
     ORDER BY r.nombre_recurso"
);
$preseleccion = rs_old($old, 'id_recurso', rs_get('recurso'));
$abrirModal = $abrirModal || ($preseleccion !== '' && !$old && !$sinEmpleado);
$hoy = date('Y-m-d');

$pageTitle = 'Mis Préstamos';
$dashboardType = 'EM';
$activePage = 'prestamos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Mis Préstamos</h1>
            <p>Solicite préstamos de recursos y consulte el estado de los suyos.</p>
        </div>
        <?php if (!$sinEmpleado): ?>
            <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalSolicitud" data-rs-new>
                <i class="bi bi-plus-lg me-1"></i>Solicitar préstamo
            </button>
        <?php endif; ?>
    </div>

    <?php rs_flash_html(); ?>
    <?php if ($sinEmpleado): ?>
        <div class="alert alert-rs alert-rs-error">Su cuenta no está asociada a un empleado. Contacte al administrador para poder solicitar préstamos.</div>
    <?php endif; ?>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Recurso</th>
                        <th>Solicitud</th>
                        <th>Entrega</th>
                        <th>Devolución programada</th>
                        <th>Devolución real</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$misPrestamos): rs_empty_table_row(7); endif; ?>
                    <?php foreach ($misPrestamos as $p): ?>
                        <tr>
                            <td><?php echo (int) $p['id_prestamo']; ?></td>
                            <td><?php echo rs_h($p['nombre_recurso']); ?><div class="small text-secondary"><?php echo rs_h($p['codigo_interno']); ?></div></td>
                            <td><?php echo rs_fecha_txt($p['fecha_solicitud']); ?></td>
                            <td><?php echo rs_fecha_txt($p['fecha_entrega']); ?></td>
                            <td><?php echo rs_fecha_txt($p['fecha_devolucion_programada']); ?></td>
                            <td><?php echo rs_fecha_txt($p['fecha_devolucion_real']); ?></td>
                            <td><?php echo rs_badge_proceso($p['estado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalSolicitud'); ?>
<div class="modal fade" id="modalSolicitud" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Solicitud de préstamo</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="solicitar_prestamo">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">La solicitud queda en estado <strong>Pendiente</strong> hasta la entrega del recurso. Los campos con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="recursoSol">Recurso solicitado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_recurso'); ?>" id="recursoSol" name="id_recurso" required data-label="un recurso">
                                <?php echo rs_opciones_html($recursosDisponibles, 'id_recurso', fn ($r) => $r['codigo_interno'] . ' - ' . $r['nombre_recurso'] . ' (' . ($r['nombre_categoria'] ?? 'Sin categoría') . ')', $preseleccion); ?>
                            </select>
                            <?php if (!$recursosDisponibles): ?><div class="form-text">No hay recursos disponibles en este momento.</div><?php endif; ?>
                            <?php echo rs_error_campo($errores, 'id_recurso'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaUso">Fecha de entrega deseada</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_entrega'); ?>" id="fechaUso" name="fecha_entrega" type="date" min="<?php echo $hoy; ?>" value="<?php echo rs_h(rs_old($old, 'fecha_entrega')); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_entrega'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaDevEst">Devolución programada<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_devolucion_programada'); ?>" id="fechaDevEst" name="fecha_devolucion_programada" type="date" min="<?php echo $hoy; ?>" required data-label="Fecha de devolución programada" data-after="fecha_entrega" data-after-strict data-after-msg="La fecha de devolución programada debe ser posterior a la fecha de entrega." value="<?php echo rs_h(rs_old($old, 'fecha_devolucion_programada')); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_devolucion_programada'); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="motivoSol">Observaciones</label>
                            <textarea class="form-control<?php echo rs_invalid($errores, 'observaciones'); ?>" id="motivoSol" name="observaciones" rows="3" maxlength="150" placeholder="Motivo o detalles de uso"><?php echo rs_h(rs_old($old, 'observaciones')); ?></textarea>
                            <?php echo rs_error_campo($errores, 'observaciones'); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-rs-outline" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-rs-outline" type="reset">Limpiar</button>
                    <button class="btn btn-rs" type="submit"><i class="bi bi-send me-1"></i>Enviar solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
