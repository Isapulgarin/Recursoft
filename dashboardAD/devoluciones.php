<?php
require_once __DIR__ . '/../controllers/devoluciones.php';

$pageTitle = 'Devoluciones';
$dashboardType = 'AD';
$activePage = 'devoluciones';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
$hoy = date('Y-m-d');
$preseleccion = rs_old($old, 'id_prestamo', rs_get('prestamo'));
$abrirModal = $abrirModal || ($preseleccion !== '' && !$old);
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Devoluciones</h1>
            <p>Registro del retorno de recursos prestados y su condición.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalDevolucion" data-rs-new>
            <i class="bi bi-plus-lg me-1"></i>Registrar devolución
        </button>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-6">
                <label class="form-label" for="buscarDevolucion">Buscar</label>
                <input class="form-control" id="buscarDevolucion" name="q" type="search" maxlength="100" placeholder="N.º de préstamo, recurso o empleado" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="condicion">Estado del recurso</label>
                <select class="form-select" id="condicion" name="estado_recurso">
                    <?php echo rs_opciones_lista(RS_ESTADOS_DEVOLUCION, $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="devoluciones.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Préstamo</th>
                        <th>Recurso</th>
                        <th>Empleado</th>
                        <th>Entrega</th>
                        <th>Devolución</th>
                        <th>Estado del recurso</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$devoluciones): rs_empty_table_row(8); endif; ?>
                    <?php foreach ($devoluciones as $d): ?>
                        <tr>
                            <td><?php echo (int) $d['id_devolucion']; ?></td>
                            <td>#<?php echo (int) $d['id_prestamo']; ?></td>
                            <td><?php echo rs_h($d['nombre_recurso']); ?><div class="small text-secondary"><?php echo rs_h($d['codigo_interno']); ?></div></td>
                            <td><?php echo rs_h($d['empleado']); ?></td>
                            <td><?php echo rs_fecha_txt($d['fecha_entrega']); ?></td>
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

<?php echo rs_marcador_modal($abrirModal, 'modalDevolucion'); ?>
<div class="modal fade" id="modalDevolucion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Registrar devolución</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="prestamoDev">Préstamo relacionado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_prestamo'); ?>" id="prestamoDev" name="id_prestamo" required data-label="un préstamo">
                                <option value="">Seleccione</option>
                                <?php foreach ($prestamosPendientes as $p): ?>
                                    <option value="<?php echo (int) $p['id_prestamo']; ?>" data-entrega="<?php echo rs_h($p['fecha_entrega'] ?: $p['fecha_solicitud']); ?>"<?php echo (string) $preseleccion === (string) $p['id_prestamo'] ? ' selected' : ''; ?>>
                                        <?php echo rs_h('#' . $p['id_prestamo'] . ' - ' . $p['codigo_interno'] . ' ' . $p['nombre_recurso'] . ' - ' . $p['empleado'] . ' (entregado ' . rs_fecha_txt($p['fecha_entrega']) . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!$prestamosPendientes): ?><div class="form-text">No hay préstamos activos pendientes de devolución.</div><?php endif; ?>
                            <?php echo rs_error_campo($errores, 'id_prestamo'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaDev">Fecha de devolución<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_devolucion'); ?>" id="fechaDev" name="fecha_devolucion" type="date" max="<?php echo $hoy; ?>" data-no-future required data-default="<?php echo $hoy; ?>" value="<?php echo rs_h(rs_old($old, 'fecha_devolucion', $hoy)); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_devolucion'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="condDev">Estado del recurso<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado_recurso'); ?>" id="condDev" name="estado_recurso" required data-label="el estado del recurso">
                                <?php echo rs_opciones_lista(RS_ESTADOS_DEVOLUCION, rs_old($old, 'estado_recurso')); ?>
                            </select>
                            <div class="form-text">Si es "Dañado", el recurso queda no disponible hasta revisar su condición.</div>
                            <?php echo rs_error_campo($errores, 'estado_recurso'); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="notasDev">Observaciones</label>
                            <textarea class="form-control<?php echo rs_invalid($errores, 'observaciones'); ?>" id="notasDev" name="observaciones" rows="3" maxlength="150"><?php echo rs_h(rs_old($old, 'observaciones')); ?></textarea>
                            <?php echo rs_error_campo($errores, 'observaciones'); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-rs-outline" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-rs-outline" type="reset">Limpiar</button>
                    <button class="btn btn-rs" type="submit"><i class="bi bi-save me-1"></i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
