<?php
require_once __DIR__ . '/../controllers/categorias.php';

$pageTitle = 'Categorías';
$dashboardType = 'AD';
$activePage = 'categorias';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Categorías</h1>
            <p>Clasificación de los recursos institucionales.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalCategoria" data-rs-new>
            <i class="bi bi-plus-lg me-1"></i>Nueva categoría
        </button>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-6">
                <label class="form-label" for="buscarCat">Buscar categoría</label>
                <input class="form-control" id="buscarCat" name="q" type="search" maxlength="50" placeholder="Nombre o descripción" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="filtroEstadoCat">Estado</label>
                <select class="form-select" id="filtroEstadoCat" name="estado">
                    <?php echo rs_opciones_lista(['Activo', 'Inactivo'], $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="categorias.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Recursos asociados</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$categorias): rs_empty_table_row(6); endif; ?>
                    <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td><?php echo (int) $c['id_categoria']; ?></td>
                            <td><?php echo rs_h($c['nombre_categoria']); ?></td>
                            <td><?php echo rs_h($c['descripcion']); ?></td>
                            <td><?php echo (int) $c['total_recursos']; ?></td>
                            <td><?php echo rs_badge_estado($c['estado']); ?></td>
                            <td class="table-actions">
                                <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalCategoria"
                                    data-rs-edit="<?php echo rs_json_attr([
                                        'id_categoria' => $c['id_categoria'], 'nombre_categoria' => $c['nombre_categoria'],
                                        'descripcion' => $c['descripcion'], 'estado' => rs_estado_texto($c['estado']),
                                    ]); ?>"><i class="bi bi-pencil"></i></button>
                                <?php echo rs_btn_estado('id_categoria', $c['id_categoria'], $c['estado'], 'esta categoría'); ?>
                                <?php if ((int) $c['total_recursos'] === 0): ?>
                                    <?php echo rs_btn_eliminar('id_categoria', $c['id_categoria'], 'esta categoría'); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalCategoria'); ?>
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" data-title-new="Registrar categoría" data-title-edit="Editar categoría"><?php echo !empty($old['id_categoria']) ? 'Editar categoría' : 'Registrar categoría'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_categoria" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_categoria')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="mb-3">
                        <label class="form-label" for="nombreCat">Nombre de la categoría<span class="req">*</span></label>
                        <input class="form-control<?php echo rs_invalid($errores, 'nombre_categoria'); ?>" id="nombreCat" name="nombre_categoria" type="text" maxlength="100" required data-label="Nombre" value="<?php echo rs_h(rs_old($old, 'nombre_categoria')); ?>">
                        <?php echo rs_error_campo($errores, 'nombre_categoria'); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="descCat">Descripción</label>
                        <textarea class="form-control<?php echo rs_invalid($errores, 'descripcion'); ?>" id="descCat" name="descripcion" rows="3" maxlength="150"><?php echo rs_h(rs_old($old, 'descripcion')); ?></textarea>
                        <?php echo rs_error_campo($errores, 'descripcion'); ?>
                    </div>
                    <div>
                        <label class="form-label" for="estadoCat">Estado<span class="req">*</span></label>
                        <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoCat" name="estado" required data-default="Activo" data-label="un estado">
                            <?php echo rs_opciones_lista(['Activo', 'Inactivo'], rs_old($old, 'estado', 'Activo'), null); ?>
                        </select>
                        <?php echo rs_error_campo($errores, 'estado'); ?>
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
