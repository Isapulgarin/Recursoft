<?php
require_once __DIR__ . '/../controllers/prestamos.php';

$pageTitle = 'Préstamos';
$dashboardType = 'AD';
$activePage = 'prestamos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
$hoy = date('Y-m-d');
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Préstamos</h1>
            <p>Solicitudes, entregas y seguimiento de recursos prestados.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalPrestamo" data-rs-new>
            <i class="bi bi-plus-lg me-1"></i>Registrar préstamo
        </button>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-3">
                <label class="form-label" for="buscarPrestamo">Buscar</label>
                <input class="form-control" id="buscarPrestamo" name="q" type="search" maxlength="100" placeholder="Empleado, documento o recurso" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label" for="desdePrestamo">Desde</label>
                <input class="form-control" id="desdePrestamo" name="desde" type="date" value="<?php echo rs_h($desde); ?>">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label" for="hastaPrestamo">Hasta</label>
                <input class="form-control" id="hastaPrestamo" name="hasta" type="date" value="<?php echo rs_h($hasta); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label" for="estadoPrestamo">Estado</label>
                <select class="form-select" id="estadoPrestamo" name="estado">
                    <?php echo rs_opciones_lista(['Pendiente', 'Activo', 'Vencido', 'Devuelto', 'Cancelado'], $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="prestamos.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="d-flex flex-wrap gap-2 section-gap">
        <span class="filter-chip"><span class="legend-dot ok"></span> Activo</span>
        <span class="filter-chip"><span class="legend-dot warn"></span> Pendiente</span>
        <span class="filter-chip"><span class="legend-dot danger"></span> Vencido</span>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Empleado</th>
                        <th>Recurso</th>
                        <th>Solicitud</th>
                        <th>Entrega</th>
                        <th>Devolución programada</th>
                        <th>Devolución real</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$prestamos): rs_empty_table_row(9); endif; ?>
                    <?php foreach ($prestamos as $p): ?>
                        <?php $vigente = in_array($p['estado'], RS_ESTADOS_PRESTAMO_VIGENTE, true); ?>
                        <tr>
                            <td><?php echo (int) $p['id_prestamo']; ?></td>
                            <td><?php echo rs_h($p['empleado']); ?><div class="small text-secondary"><?php echo rs_h($p['documento']); ?></div></td>
                            <td><?php echo rs_h($p['nombre_recurso']); ?><div class="small text-secondary"><?php echo rs_h($p['codigo_interno']); ?></div></td>
                            <td><?php echo rs_fecha_txt($p['fecha_solicitud']); ?></td>
                            <td><?php echo rs_fecha_txt($p['fecha_entrega']); ?></td>
                            <td><?php echo rs_fecha_txt($p['fecha_devolucion_programada']); ?></td>
                            <td><?php echo rs_fecha_txt($p['fecha_devolucion_real']); ?></td>
                            <td><?php echo rs_badge_proceso($p['estado']); ?></td>
                            <td class="table-actions">
                                <?php if ($vigente): ?>
                                    <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalPrestamo"
                                        data-rs-edit="<?php echo rs_json_attr([
                                            'id_prestamo' => $p['id_prestamo'], 'id_empleado' => $p['id_empleado'], 'id_recurso' => $p['id_recurso'],
                                            'fecha_solicitud' => $p['fecha_solicitud'], 'fecha_entrega' => $p['fecha_entrega'],
                                            'fecha_devolucion_programada' => $p['fecha_devolucion_programada'], 'estado' => $p['estado'],
                                            'observaciones' => $p['observaciones'],
                                        ]); ?>"><i class="bi bi-pencil"></i></button>
                                <?php endif; ?>
                                <?php if ($p['estado'] === 'Pendiente'): ?>
                                    <?php echo rs_btn_post('entregar', 'id_prestamo', $p['id_prestamo'], 'bi-box-arrow-up-right', 'Marcar como entregado', '¿Confirmar la entrega del recurso?'); ?>
                                <?php endif; ?>
                                <?php if (in_array($p['estado'], ['Activo', 'Vencido'], true)): ?>
                                    <a class="btn btn-sm btn-rs-outline" href="devoluciones.php?prestamo=<?php echo (int) $p['id_prestamo']; ?>" title="Registrar devolución"><i class="bi bi-arrow-return-left"></i></a>
                                <?php endif; ?>
                                <?php if (in_array($p['estado'], ['Pendiente', 'Activo'], true)): ?>
                                    <?php echo rs_btn_post('cancelar', 'id_prestamo', $p['id_prestamo'], 'bi-x-circle', 'Cancelar préstamo', '¿Cancelar este préstamo?', 'text-danger'); ?>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-rs-outline" href="historial.php?prestamo=<?php echo (int) $p['id_prestamo']; ?>" title="Ver historial"><i class="bi bi-clock-history"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalPrestamo'); ?>
<div class="modal fade" id="modalPrestamo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" data-title-new="Registrar préstamo" data-title-edit="Editar préstamo"><?php echo !empty($old['id_prestamo']) ? 'Editar préstamo' : 'Registrar préstamo'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_prestamo" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_prestamo')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="empleadoPrestamo">Empleado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_empleado'); ?>" id="empleadoPrestamo" name="id_empleado" required data-label="un empleado">
                                <option value="">Seleccione</option>
                                <?php foreach ($empleadosActivos as $e): ?>
                                    <option value="<?php echo (int) $e['id_empleado']; ?>"<?php echo (string) rs_old($old, 'id_empleado') === (string) $e['id_empleado'] ? ' selected' : ''; ?><?php echo rs_es_activo($e['estado']) ? '' : ' disabled'; ?>>
                                        <?php echo rs_h($e['nombre'] . ' ' . $e['apellido'] . ' - ' . $e['documento'] . (rs_es_activo($e['estado']) ? '' : ' (inactivo)')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'id_empleado'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="recursoPrestamo">Recurso<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_recurso'); ?>" id="recursoPrestamo" name="id_recurso" required data-label="un recurso">
                                <option value="">Seleccione</option>
                                <?php foreach ($recursosLista as $r): ?>
                                    <option value="<?php echo (int) $r['id_recurso']; ?>"<?php echo (string) rs_old($old, 'id_recurso') === (string) $r['id_recurso'] ? ' selected' : ''; ?>>
                                        <?php echo rs_h($r['codigo_interno'] . ' - ' . $r['nombre_recurso'] . ' (' . $r['disponibilidad'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Solo se pueden prestar recursos disponibles.</div>
                            <?php echo rs_error_campo($errores, 'id_recurso'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="fechaSolicitud">Fecha de solicitud<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_solicitud'); ?>" id="fechaSolicitud" name="fecha_solicitud" type="date" max="<?php echo $hoy; ?>" data-no-future required data-default="<?php echo $hoy; ?>" value="<?php echo rs_h(rs_old($old, 'fecha_solicitud', $hoy)); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_solicitud'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="fechaEntrega">Fecha de entrega</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_entrega'); ?>" id="fechaEntrega" name="fecha_entrega" type="date" data-after="fecha_solicitud" data-after-msg="La fecha de entrega no puede ser anterior a la fecha de solicitud." value="<?php echo rs_h(rs_old($old, 'fecha_entrega')); ?>">
                            <div class="form-text">Obligatoria si el préstamo queda activo.</div>
                            <?php echo rs_error_campo($errores, 'fecha_entrega'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="fechaProgramada">Devolución programada<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_devolucion_programada'); ?>" id="fechaProgramada" name="fecha_devolucion_programada" type="date" required data-label="Fecha de devolución programada" data-after="fecha_entrega" data-after-strict data-after-msg="La fecha de devolución programada debe ser posterior a la fecha de entrega." value="<?php echo rs_h(rs_old($old, 'fecha_devolucion_programada')); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_devolucion_programada'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="estadoNuevoPrestamo">Estado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoNuevoPrestamo" name="estado" required data-default="Pendiente" data-label="un estado">
                                <?php echo rs_opciones_lista(['Pendiente', 'Activo', 'Vencido'], rs_old($old, 'estado', 'Pendiente'), null); ?>
                            </select>
                            <div class="form-text">Para cancelar o devolver use las acciones de la tabla.</div>
                            <?php echo rs_error_campo($errores, 'estado'); ?>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label" for="obsPrestamo">Observaciones</label>
                            <textarea class="form-control<?php echo rs_invalid($errores, 'observaciones'); ?>" id="obsPrestamo" name="observaciones" rows="2" maxlength="150"><?php echo rs_h(rs_old($old, 'observaciones')); ?></textarea>
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
