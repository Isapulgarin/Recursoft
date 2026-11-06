<?php
require_once __DIR__ . '/../controllers/compras.php';

$pageTitle = 'Compras y Adquisiciones';
$dashboardType = 'AD';
$activePage = 'compras';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
$hoy = date('Y-m-d');
$opcionesRecurso = fn ($sel) => rs_opciones_html($recursosLista, 'id_recurso', fn ($r) => $r['codigo_interno'] . ' - ' . $r['nombre_recurso'], $sel);
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Compras y Adquisiciones</h1>
            <p>Registro de compras a proveedores con su detalle de recursos.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalCompra" data-rs-new>
            <i class="bi bi-plus-lg me-1"></i>Nueva adquisición
        </button>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-4">
                <label class="form-label" for="buscarCompra">Buscar</label>
                <input class="form-control" id="buscarCompra" name="q" type="search" maxlength="100" placeholder="N.º, proveedor o NIT" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="periodoCompra">Periodo</label>
                <input class="form-control" id="periodoCompra" name="periodo" type="month" value="<?php echo rs_h($periodo); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label" for="estadoCompra">Estado</label>
                <select class="form-select" id="estadoCompra" name="estado">
                    <?php echo rs_opciones_lista(RS_ESTADOS_COMPRA, $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="compras.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
                        <th>Ítems</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$compras): rs_empty_table_row(7); endif; ?>
                    <?php foreach ($compras as $c): ?>
                        <?php $det = $detallesPorCompra[(int) $c['id_compra']] ?? []; ?>
                        <tr>
                            <td><?php echo (int) $c['id_compra']; ?></td>
                            <td><?php echo rs_h($c['nombre_empresa']); ?><div class="small text-secondary">NIT <?php echo rs_h($c['NIT']); ?></div></td>
                            <td><?php echo rs_fecha_txt($c['fecha_compra']); ?></td>
                            <td><?php echo (int) $c['items']; ?></td>
                            <td><?php echo rs_dinero($c['total']); ?></td>
                            <td><?php echo rs_badge_proceso($c['estado']); ?></td>
                            <td class="table-actions">
                                <button class="btn btn-sm btn-rs-outline" type="button" title="Ver detalle" data-bs-toggle="collapse" data-bs-target="#detalle<?php echo (int) $c['id_compra']; ?>"><i class="bi bi-list-ul"></i></button>
                                <?php if ($c['estado'] !== 'Anulada'): ?>
                                    <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalCompra"
                                        data-rs-edit="<?php echo rs_json_attr([
                                            'id_compra' => $c['id_compra'], 'id_proveedor' => $c['id_proveedor'], 'fecha_compra' => $c['fecha_compra'],
                                            'estado' => $c['estado'], 'observaciones' => $c['observaciones'],
                                            'detalles' => array_map(fn ($d) => ['id_recurso' => $d['id_recurso'], 'cantidad' => $d['cantidad'], 'costo_unitario' => $d['costo_unitario']], $det),
                                        ]); ?>"><i class="bi bi-pencil"></i></button>
                                    <?php echo rs_btn_post('anular', 'id_compra', $c['id_compra'], 'bi-x-circle', 'Anular compra', '¿Anular esta compra?', 'text-danger'); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="collapse" id="detalle<?php echo (int) $c['id_compra']; ?>">
                            <td colspan="7" class="bg-light">
                                <?php if ($c['observaciones']): ?><p class="small mb-2"><strong>Observaciones:</strong> <?php echo rs_h($c['observaciones']); ?></p><?php endif; ?>
                                <table class="table table-sm mb-0">
                                    <thead><tr><th>Recurso</th><th>Cantidad</th><th>Costo unitario</th><th>Subtotal</th></tr></thead>
                                    <tbody>
                                        <?php if (!$det): rs_empty_table_row(4, 'Sin detalle'); endif; ?>
                                        <?php foreach ($det as $d): ?>
                                            <tr>
                                                <td><?php echo rs_h($d['codigo_interno'] . ' - ' . $d['nombre_recurso']); ?></td>
                                                <td><?php echo (int) $d['cantidad']; ?></td>
                                                <td><?php echo rs_dinero($d['costo_unitario']); ?></td>
                                                <td><?php echo rs_dinero($d['subtotal']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalCompra'); ?>
<div class="modal fade" id="modalCompra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" data-title-new="Registrar adquisición" data-title-edit="Editar adquisición"><?php echo !empty($old['id_compra']) ? 'Editar adquisición' : 'Registrar adquisición'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate id="formCompra">
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_compra" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_compra')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-5">
                            <label class="form-label" for="provCompra">Proveedor<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_proveedor'); ?>" id="provCompra" name="id_proveedor" required data-label="un proveedor">
                                <option value="">Seleccione</option>
                                <?php foreach ($proveedoresLista as $p): ?>
                                    <option value="<?php echo (int) $p['id_proveedor']; ?>"<?php echo (string) rs_old($old, 'id_proveedor') === (string) $p['id_proveedor'] ? ' selected' : ''; ?>>
                                        <?php echo rs_h($p['nombre_empresa'] . ' - NIT ' . $p['NIT'] . (rs_es_activo($p['estado']) ? '' : ' (inactivo)')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'id_proveedor'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="fechaCompra">Fecha de compra<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_compra'); ?>" id="fechaCompra" name="fecha_compra" type="date" max="<?php echo $hoy; ?>" data-no-future required data-default="<?php echo $hoy; ?>" value="<?php echo rs_h(rs_old($old, 'fecha_compra', $hoy)); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_compra'); ?>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="estadoNuevaCompra">Estado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoNuevaCompra" name="estado" required data-default="Solicitada" data-label="un estado">
                                <?php echo rs_opciones_lista(array_slice(RS_ESTADOS_COMPRA, 0, 3), rs_old($old, 'estado', 'Solicitada'), null); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'estado'); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="obsCompra">Observaciones</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'observaciones'); ?>" id="obsCompra" name="observaciones" type="text" maxlength="150" value="<?php echo rs_h(rs_old($old, 'observaciones')); ?>">
                            <?php echo rs_error_campo($errores, 'observaciones'); ?>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-primary">Detalle de la compra<span class="req">*</span></strong>
                                <button class="btn btn-sm btn-rs-outline" type="button" id="btnAgregarDetalle"><i class="bi bi-plus-lg me-1"></i>Agregar recurso</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm detalle-compra-table mb-1">
                                    <thead>
                                        <tr><th>Recurso</th><th>Cantidad</th><th>Costo unitario</th><th>Subtotal</th><th></th></tr>
                                    </thead>
                                    <tbody id="detalleCompraBody">
                                        <?php foreach ($detallesOld as $d): ?>
                                            <tr class="fila-detalle">
                                                <td><select class="form-select form-select-sm" name="det_recurso[]" required data-label="un recurso"><?php echo $opcionesRecurso($d['id_recurso']); ?></select></td>
                                                <td><input class="form-control form-control-sm" name="det_cantidad[]" type="number" min="1" step="1" required data-label="Cantidad" value="<?php echo rs_h($d['cantidad']); ?>"></td>
                                                <td><input class="form-control form-control-sm" name="det_costo[]" type="number" min="0" step="0.01" required data-label="Costo unitario" value="<?php echo rs_h($d['costo_unitario']); ?>"></td>
                                                <td class="subtotal align-middle">$ 0,00</td>
                                                <td><button class="btn btn-sm btn-rs-outline text-danger btn-quitar-detalle" type="button" title="Quitar"><i class="bi bi-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr><th colspan="3" class="text-end">Total</th><th id="totalCompra">$ 0,00</th><th></th></tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="form-text">El subtotal (cantidad × costo unitario) y el total se calculan automáticamente; el servidor los recalcula al guardar.</div>
                            <?php echo rs_error_campo($errores, 'detalles'); ?>
                            <div class="campo-error" id="errorDetalles" hidden></div>
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

<template id="tplFilaDetalle">
    <tr class="fila-detalle">
        <td><select class="form-select form-select-sm" name="det_recurso[]" required data-label="un recurso"><?php echo $opcionesRecurso(''); ?></select></td>
        <td><input class="form-control form-control-sm" name="det_cantidad[]" type="number" min="1" step="1" required data-label="Cantidad"></td>
        <td><input class="form-control form-control-sm" name="det_costo[]" type="number" min="0" step="0.01" required data-label="Costo unitario"></td>
        <td class="subtotal align-middle">$ 0,00</td>
        <td><button class="btn btn-sm btn-rs-outline text-danger btn-quitar-detalle" type="button" title="Quitar"><i class="bi bi-trash"></i></button></td>
    </tr>
</template>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
