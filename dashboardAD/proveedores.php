<?php
require_once __DIR__ . '/../controllers/proveedores.php';

$pageTitle = 'Proveedores';
$dashboardType = 'AD';
$activePage = 'proveedores';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Proveedores</h1>
            <p>Directorio de empresas proveedoras de recursos.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalProveedor" data-rs-new>
            <i class="bi bi-plus-lg me-1"></i>Nuevo proveedor
        </button>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-6">
                <label class="form-label" for="buscarProv">Buscar</label>
                <input class="form-control" id="buscarProv" name="q" type="search" maxlength="100" placeholder="Empresa, NIT o contacto" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="filtroEstadoProv">Estado</label>
                <select class="form-select" id="filtroEstadoProv" name="estado">
                    <?php echo rs_opciones_lista(['Activo', 'Inactivo'], $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="proveedores.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>NIT</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$proveedores): rs_empty_table_row(7); endif; ?>
                    <?php foreach ($proveedores as $p): ?>
                        <tr>
                            <td><?php echo rs_h($p['nombre_empresa']); ?><div class="small text-secondary"><?php echo rs_h($p['direccion']); ?></div></td>
                            <td><?php echo rs_h($p['NIT']); ?></td>
                            <td><?php echo rs_h($p['contacto']); ?></td>
                            <td><?php echo rs_h($p['telefono']); ?></td>
                            <td><?php echo rs_h($p['correo']); ?></td>
                            <td><?php echo rs_badge_estado($p['estado']); ?></td>
                            <td class="table-actions">
                                <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalProveedor"
                                    data-rs-edit="<?php echo rs_json_attr([
                                        'id_proveedor' => $p['id_proveedor'], 'nombre_empresa' => $p['nombre_empresa'], 'NIT' => $p['NIT'],
                                        'contacto' => $p['contacto'], 'telefono' => $p['telefono'], 'correo' => $p['correo'],
                                        'direccion' => $p['direccion'], 'estado' => rs_estado_texto($p['estado']),
                                    ]); ?>"><i class="bi bi-pencil"></i></button>
                                <?php echo rs_btn_estado('id_proveedor', $p['id_proveedor'], $p['estado'], 'este proveedor'); ?>
                                <?php echo rs_btn_eliminar('id_proveedor', $p['id_proveedor'], 'este proveedor'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalProveedor'); ?>
<div class="modal fade" id="modalProveedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" data-title-new="Registrar proveedor" data-title-edit="Editar proveedor"><?php echo !empty($old['id_proveedor']) ? 'Editar proveedor' : 'Registrar proveedor'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_proveedor" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_proveedor')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="empresaProv">Nombre de la empresa<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'nombre_empresa'); ?>" id="empresaProv" name="nombre_empresa" type="text" maxlength="100" required value="<?php echo rs_h(rs_old($old, 'nombre_empresa')); ?>">
                            <?php echo rs_error_campo($errores, 'nombre_empresa'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="nitProv">NIT<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'NIT'); ?>" id="nitProv" name="NIT" type="text" maxlength="30" pattern="[0-9.\-]{6,30}" title="El NIT debe tener entre 6 y 30 dígitos (puede incluir puntos o guion)." required value="<?php echo rs_h(rs_old($old, 'NIT')); ?>">
                            <?php echo rs_error_campo($errores, 'NIT'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="contactoProv">Contacto</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'contacto'); ?>" id="contactoProv" name="contacto" type="text" maxlength="100" value="<?php echo rs_h(rs_old($old, 'contacto')); ?>">
                            <?php echo rs_error_campo($errores, 'contacto'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="telProv">Teléfono</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'telefono'); ?>" id="telProv" name="telefono" type="tel" maxlength="20" pattern="[0-9+()\s\-]{7,20}" title="El teléfono debe tener entre 7 y 15 dígitos." value="<?php echo rs_h(rs_old($old, 'telefono')); ?>">
                            <?php echo rs_error_campo($errores, 'telefono'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="correoProv">Correo</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'correo'); ?>" id="correoProv" name="correo" type="email" maxlength="100" value="<?php echo rs_h(rs_old($old, 'correo')); ?>">
                            <?php echo rs_error_campo($errores, 'correo'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="estadoProv">Estado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoProv" name="estado" required data-default="Activo" data-label="un estado">
                                <?php echo rs_opciones_lista(['Activo', 'Inactivo'], rs_old($old, 'estado', 'Activo'), null); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'estado'); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="dirProv">Dirección</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'direccion'); ?>" id="dirProv" name="direccion" type="text" maxlength="150" value="<?php echo rs_h(rs_old($old, 'direccion')); ?>">
                            <?php echo rs_error_campo($errores, 'direccion'); ?>
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
