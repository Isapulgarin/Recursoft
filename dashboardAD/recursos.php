<?php
require_once __DIR__ . '/../controllers/recursos.php';

$pageTitle = 'Recursos';
$dashboardType = 'AD';
$activePage = 'recursos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Recursos</h1>
            <p>Inventario de bienes institucionales y control de disponibilidad.</p>
        </div>
        <div class="action-btns">
            <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalRecurso" data-rs-new>
                <i class="bi bi-plus-lg me-1"></i>Nuevo recurso
            </button>
        </div>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-3">
                <label class="form-label" for="buscarRecurso">Buscar recurso</label>
                <input class="form-control" id="buscarRecurso" name="q" type="search" maxlength="100" placeholder="Código, nombre, serial o marca" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label" for="filtroCategoria">Categoría</label>
                <select class="form-select" id="filtroCategoria" name="categoria">
                    <?php echo rs_opciones_html($categorias, 'id_categoria', 'nombre_categoria', $filtroCategoria, 'Todas'); ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label" for="filtroEstadoRecurso">Estado</label>
                <select class="form-select" id="filtroEstadoRecurso" name="estado">
                    <?php echo rs_opciones_lista(RS_ESTADOS_RECURSO, $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label" for="filtroDisp">Disponibilidad</label>
                <select class="form-select" id="filtroDisp" name="disponibilidad">
                    <?php echo rs_opciones_lista(['Disponible', 'Prestado', 'En mantenimiento', 'No disponible'], $filtroDisp, 'Todas'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="recursos.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Recurso</th>
                        <th>Categoría</th>
                        <th>Serial / Marca / Modelo</th>
                        <th>Estado</th>
                        <th>Disponibilidad</th>
                        <th>Adquisición</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$recursos): rs_empty_table_row(8); endif; ?>
                    <?php foreach ($recursos as $r): ?>
                        <tr>
                            <td><?php echo rs_h($r['codigo_interno']); ?></td>
                            <td><?php echo rs_h($r['nombre_recurso']); ?><div class="small text-secondary"><?php echo rs_h($r['descripcion']); ?></div></td>
                            <td><?php echo rs_h($r['nombre_categoria'] ?? 'Sin categoría'); ?></td>
                            <td class="small"><?php echo rs_h($r['serial'] ?: '—'); ?><br><?php echo rs_h(trim($r['marca'] . ' ' . $r['modelo']) ?: '—'); ?></td>
                            <td><?php echo rs_badge_proceso($r['estado']); ?></td>
                            <td><?php echo rs_badge_disponibilidad($r['disponibilidad']); ?></td>
                            <td><?php echo rs_fecha_txt($r['fecha_adquisicion']); ?></td>
                            <td class="table-actions">
                                <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalRecurso"
                                    data-rs-edit="<?php echo rs_json_attr([
                                        'id_recurso' => $r['id_recurso'], 'id_categoria' => $r['id_categoria'], 'codigo_interno' => $r['codigo_interno'],
                                        'nombre_recurso' => $r['nombre_recurso'], 'descripcion' => $r['descripcion'], 'serial' => $r['serial'],
                                        'marca' => $r['marca'], 'modelo' => $r['modelo'], 'estado' => $r['estado'],
                                        'disponibilidad' => $r['disponibilidad'] === 'No disponible' ? 'No disponible' : 'Disponible',
                                        'fecha_registro' => $r['fecha_registro'], 'fecha_adquisicion' => $r['fecha_adquisicion'],
                                    ]); ?>"><i class="bi bi-pencil"></i></button>
                                <?php echo rs_btn_eliminar('id_recurso', $r['id_recurso'], 'este recurso'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalRecurso'); ?>
<div class="modal fade" id="modalRecurso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" data-title-new="Registrar recurso" data-title-edit="Editar recurso"><?php echo !empty($old['id_recurso']) ? 'Editar recurso' : 'Registrar recurso'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_recurso" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_recurso')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="categoriaRecurso">Categoría<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_categoria'); ?>" id="categoriaRecurso" name="id_categoria" required data-label="una categoría">
                                <option value="">Seleccione</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?php echo (int) $c['id_categoria']; ?>"<?php echo (string) rs_old($old, 'id_categoria') === (string) $c['id_categoria'] ? ' selected' : ''; ?>>
                                        <?php echo rs_h($c['nombre_categoria'] . (rs_es_activo($c['estado']) ? '' : ' (inactiva)')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'id_categoria'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="codigoRecurso">Código interno<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'codigo_interno'); ?>" id="codigoRecurso" name="codigo_interno" type="text" maxlength="30" required value="<?php echo rs_h(rs_old($old, 'codigo_interno')); ?>">
                            <?php echo rs_error_campo($errores, 'codigo_interno'); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="nombreRecurso">Nombre del recurso<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'nombre_recurso'); ?>" id="nombreRecurso" name="nombre_recurso" type="text" maxlength="100" required data-label="Nombre" value="<?php echo rs_h(rs_old($old, 'nombre_recurso')); ?>">
                            <?php echo rs_error_campo($errores, 'nombre_recurso'); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="descRecurso">Descripción</label>
                            <textarea class="form-control<?php echo rs_invalid($errores, 'descripcion'); ?>" id="descRecurso" name="descripcion" rows="2" maxlength="150"><?php echo rs_h(rs_old($old, 'descripcion')); ?></textarea>
                            <?php echo rs_error_campo($errores, 'descripcion'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="serialRecurso">Serial</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'serial'); ?>" id="serialRecurso" name="serial" type="text" maxlength="50" value="<?php echo rs_h(rs_old($old, 'serial')); ?>">
                            <?php echo rs_error_campo($errores, 'serial'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="marcaRecurso">Marca</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'marca'); ?>" id="marcaRecurso" name="marca" type="text" maxlength="50" value="<?php echo rs_h(rs_old($old, 'marca')); ?>">
                            <?php echo rs_error_campo($errores, 'marca'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="modeloRecurso">Modelo</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'modelo'); ?>" id="modeloRecurso" name="modelo" type="text" maxlength="50" value="<?php echo rs_h(rs_old($old, 'modelo')); ?>">
                            <?php echo rs_error_campo($errores, 'modelo'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="estadoRecurso">Estado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoRecurso" name="estado" required data-default="Nuevo" data-label="un estado">
                                <?php echo rs_opciones_lista(RS_ESTADOS_RECURSO, rs_old($old, 'estado', 'Nuevo'), null); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'estado'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="dispRecurso">Disponibilidad<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'disponibilidad'); ?>" id="dispRecurso" name="disponibilidad" required data-default="Disponible" data-label="la disponibilidad">
                                <?php echo rs_opciones_lista(['Disponible', 'No disponible'], rs_old($old, 'disponibilidad', 'Disponible'), null); ?>
                            </select>
                            <div class="form-text">Los estados "Prestado" y "En mantenimiento" se asignan automáticamente.</div>
                            <?php echo rs_error_campo($errores, 'disponibilidad'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaRegRecurso">Fecha de registro<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_registro'); ?>" id="fechaRegRecurso" name="fecha_registro" type="date" max="<?php echo date('Y-m-d'); ?>" data-no-future required data-default="<?php echo date('Y-m-d'); ?>" value="<?php echo rs_h(rs_old($old, 'fecha_registro', date('Y-m-d'))); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_registro'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fechaAdqRecurso">Fecha de adquisición</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'fecha_adquisicion'); ?>" id="fechaAdqRecurso" name="fecha_adquisicion" type="date" max="<?php echo date('Y-m-d'); ?>" data-no-future value="<?php echo rs_h(rs_old($old, 'fecha_adquisicion')); ?>">
                            <?php echo rs_error_campo($errores, 'fecha_adquisicion'); ?>
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
