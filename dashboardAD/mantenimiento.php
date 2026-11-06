<?php
require_once __DIR__ . '/../controllers/mantenimiento.php';

$pageTitle = 'Mantenimientos';
$dashboardType = 'AD';
$activePage = 'mantenimiento';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
$hoy = date('Y-m-d');
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Mantenimientos</h1>
            <p>Órdenes de servicio técnico preventivo y correctivo de los recursos.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalMantenimiento" data-rs-new>
            <i class="bi bi-plus-lg me-1"></i>Nuevo mantenimiento
        </button>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-5">
                <label class="form-label" for="filtroRecursoMant">Recurso</label>
                <select class="form-select" id="filtroRecursoMant" name="recurso">
                    <?php echo rs_opciones_html($recursosLista, 'id_recurso', fn ($r) => $r['codigo_interno'] . ' - ' . $r['nombre_recurso'], $filtroRecurso, 'Todos'); ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="filtroEstadoMant">Estado</label>
                <select class="form-select" id="filtroEstadoMant" name="estado">
                    <?php echo rs_opciones_lista(RS_ESTADOS_MANT, $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="mantenimiento.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Recurso</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Inicio</th>
                        <th>Finalización</th>
                        <th>Costo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$mantenimientos): rs_empty_table_row(9); endif; ?>
                    <?php foreach ($mantenimientos as $m): ?>
                        <tr>
                            <td><?php echo (int) $m['id_mantenimiento']; ?></td>
                            <td><?php echo rs_h($m['nombre_recurso']); ?><div class="small text-secondary"><?php echo rs_h($m['codigo_interno']); ?></div></td>
                            <td><?php echo rs_h($m['tipo_mantenimiento']); ?></td>
                            <td class="small"><?php echo rs_h($m['descripcion']); ?></td>
                            <td><?php echo rs_fecha_txt($m['fecha_inicio']); ?></td>
                            <td><?php echo rs_fecha_txt($m['fecha_fin']); ?></td>
                            <td><?php echo rs_dinero($m['costo']); ?></td>
                            <td><?php echo rs_badge_proceso($m['estado']); ?></td>
                            <td class="table-actions">
                                <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalMantenimiento"
                                    data-rs-edit="<?php echo rs_json_attr([
                                        'id_mantenimiento' => $m['id_mantenimiento'], 'id_recurso' => $m['id_recurso'],
                                        'tipo_mantenimiento' => $m['tipo_mantenimiento'], 'descripcion' => $m['descripcion'],
                                        'fecha_inicio' => $m['fecha_inicio'], 'fecha_fin' => $m['fecha_fin'], 'costo' => $m['costo'],
                                        'estado' => $m['estado'], 'observaciones' => $m['observaciones'],
                                    ]); ?>"><i class="bi bi-pencil"></i></button>
                                <?php echo rs_btn_eliminar('id_mantenimiento', $m['id_mantenimiento'], 'este mantenimiento'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalMantenimiento'); ?>
<div class="modal fade" id="modalMantenimiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" data-title-new="Registrar mantenimiento" data-title-edit="Editar mantenimiento"><?php echo !empty($old['id_mantenimiento']) ? 'Editar mantenimiento' : 'Registrar mantenimiento'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_mantenimiento" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_mantenimiento')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label" for="recursoMant">Recurso<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_recurso'); ?>" id="recursoMant" name="id_recurso" required data-label="un recurso">
                                <?php echo rs_opciones_html($recursosLista, 'id_recurso', fn ($r) => $r['codigo_interno'] . ' - ' . $r['nombre_recurso'] . ' (' . $r['disponibilidad'] . ')', rs_old($old, 'id_recurso')); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'id_recurso'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="tipoMant">Tipo de mantenimiento<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'tipo_mantenimiento'); ?>" id="tipoMant" name="tipo_mantenimiento" required data-label="el tipo de mantenimiento">
                                <?php echo rs_opciones_lista(RS_TIPOS_MANT, rs_old($old, 'tipo_mantenimiento')); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'tipo_mantenimiento'); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="descMant">Descripción</label>
                            <textarea class="form-control<?php echo rs_invalid($errores, 'descripcion'); ?>" id="descMant" name="descripcion" rows="2" maxlength="150"><?php echo rs_h(rs_old($old, 'descripcion')); ?></textarea>
                            <?php echo rs_error_campo($errores, 'descripcion'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="inicioMant">Fecha de inicio<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_inicio'); ?>" id="inicioMant" name="fecha_inicio" type="date" required data-default="<?php echo $hoy; ?>" value="<?php echo rs_h(rs_old($old, 'fecha_inicio', $hoy)); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_inicio'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="finMant">Fecha de finalización</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_fin'); ?>" id="finMant" name="fecha_fin" type="date" data-after="fecha_inicio" data-after-msg="La fecha de finalización no puede ser anterior a la fecha de inicio." value="<?php echo rs_h(rs_old($old, 'fecha_fin')); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_fin'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="costoMant">Costo</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'costo'); ?>" id="costoMant" name="costo" type="number" min="0" step="0.01" value="<?php echo rs_h(rs_old($old, 'costo')); ?>">
                            <?php echo rs_error_campo($errores, 'costo'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="estadoMant">Estado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoMant" name="estado" required data-default="Programado" data-label="un estado">
                                <?php echo rs_opciones_lista(RS_ESTADOS_MANT, rs_old($old, 'estado', 'Programado'), null); ?>
                            </select>
                            <div class="form-text">Mientras esté reportado, programado o en proceso, el recurso no se puede prestar.</div>
                            <?php echo rs_error_campo($errores, 'estado'); ?>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label" for="obsMant">Observaciones</label>
                            <textarea class="form-control<?php echo rs_invalid($errores, 'observaciones'); ?>" id="obsMant" name="observaciones" rows="2" maxlength="150"><?php echo rs_h(rs_old($old, 'observaciones')); ?></textarea>
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
