<?php
require_once __DIR__ . '/../controllers/empleados.php';

$pageTitle = 'Empleados';
$dashboardType = 'AD';
$activePage = 'empleados';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Empleados</h1>
            <p>Personal institucional asociado a cuentas de usuario.</p>
        </div>
        <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalEmpleado" data-rs-new>
            <i class="bi bi-plus-lg me-1"></i>Nuevo empleado
        </button>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-6">
                <label class="form-label" for="buscarEmp">Buscar</label>
                <input class="form-control" id="buscarEmp" name="q" type="search" maxlength="100" placeholder="Documento o nombre" value="<?php echo rs_h($q); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="filtroEstadoEmp">Estado</label>
                <select class="form-select" id="filtroEstadoEmp" name="estado">
                    <?php echo rs_opciones_lista(['Activo', 'Inactivo'], $filtroEstado, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-search me-1"></i>Buscar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="empleados.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Contacto</th>
                        <th>Cuenta de usuario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$empleados): rs_empty_table_row(7); endif; ?>
                    <?php foreach ($empleados as $e): ?>
                        <tr>
                            <td><?php echo rs_h($e['documento']); ?></td>
                            <td><?php echo rs_h($e['nombre'] . ' ' . $e['apellido']); ?></td>
                            <td><?php echo rs_h($e['cargo']); ?></td>
                            <td><?php echo rs_h($e['telefono']); ?><div class="small text-secondary"><?php echo rs_h($e['correo']); ?></div></td>
                            <td><?php echo rs_h($e['correo_usuario'] ?? 'Sin cuenta'); ?></td>
                            <td><?php echo rs_badge_estado($e['estado']); ?></td>
                            <td class="table-actions">
                                <button class="btn btn-sm btn-rs-outline" type="button" title="Editar" data-bs-toggle="modal" data-bs-target="#modalEmpleado"
                                    data-rs-edit="<?php echo rs_json_attr([
                                        'id_empleado' => $e['id_empleado'], 'id_usuario' => $e['id_usuario'], 'documento' => $e['documento'],
                                        'nombre' => $e['nombre'], 'apellido' => $e['apellido'], 'cargo' => $e['cargo'],
                                        'telefono' => $e['telefono'], 'correo' => $e['correo'], 'estado' => rs_estado_texto($e['estado']),
                                    ]); ?>"><i class="bi bi-pencil"></i></button>
                                <?php echo rs_btn_estado('id_empleado', $e['id_empleado'], $e['estado'], 'este empleado'); ?>
                                <?php echo rs_btn_eliminar('id_empleado', $e['id_empleado'], 'este empleado'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalEmpleado'); ?>
<div class="modal fade" id="modalEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" data-title-new="Registrar empleado" data-title-edit="Editar empleado"><?php echo !empty($old['id_empleado']) ? 'Editar empleado' : 'Registrar empleado'; ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="id_empleado" data-rs-id value="<?php echo rs_h(rs_old($old, 'id_empleado')); ?>">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="usuarioEmp">Usuario relacionado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'id_usuario'); ?>" id="usuarioEmp" name="id_usuario" required data-label="un usuario">
                                <option value="">Seleccione</option>
                                <?php foreach ($usuariosDisponibles as $u): ?>
                                    <option value="<?php echo (int) $u['id_usuario']; ?>"<?php echo (string) rs_old($old, 'id_usuario') === (string) $u['id_usuario'] ? ' selected' : ''; ?>>
                                        <?php echo rs_h($u['nombre'] . ' ' . $u['apellido'] . ' (' . $u['correo'] . ')' . ($u['id_empleado'] ? ' - ya asociado' : '')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Cada cuenta de usuario solo puede asociarse a un empleado.</div>
                            <?php echo rs_error_campo($errores, 'id_usuario'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="docEmp">Documento<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'documento'); ?>" id="docEmp" name="documento" type="text" maxlength="20" pattern="[A-Za-z0-9.\-]{5,20}" title="El documento debe tener entre 5 y 20 caracteres alfanuméricos." required value="<?php echo rs_h(rs_old($old, 'documento')); ?>">
                            <?php echo rs_error_campo($errores, 'documento'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="cargoEmp">Cargo<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'cargo'); ?>" id="cargoEmp" name="cargo" type="text" maxlength="50" required value="<?php echo rs_h(rs_old($old, 'cargo')); ?>">
                            <?php echo rs_error_campo($errores, 'cargo'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="nombreEmp">Nombre<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'nombre'); ?>" id="nombreEmp" name="nombre" type="text" maxlength="50" required value="<?php echo rs_h(rs_old($old, 'nombre')); ?>">
                            <?php echo rs_error_campo($errores, 'nombre'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="apellidoEmp">Apellido<span class="req">*</span></label>
                            <input class="form-control<?php echo rs_invalid($errores, 'apellido'); ?>" id="apellidoEmp" name="apellido" type="text" maxlength="50" required value="<?php echo rs_h(rs_old($old, 'apellido')); ?>">
                            <?php echo rs_error_campo($errores, 'apellido'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="telEmp">Teléfono</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'telefono'); ?>" id="telEmp" name="telefono" type="tel" maxlength="20" pattern="[0-9+()\s\-]{7,20}" title="El teléfono debe tener entre 7 y 15 dígitos." value="<?php echo rs_h(rs_old($old, 'telefono')); ?>">
                            <?php echo rs_error_campo($errores, 'telefono'); ?>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label" for="correoEmp">Correo</label>
                            <input class="form-control<?php echo rs_invalid($errores, 'correo'); ?>" id="correoEmp" name="correo" type="email" maxlength="100" value="<?php echo rs_h(rs_old($old, 'correo')); ?>">
                            <?php echo rs_error_campo($errores, 'correo'); ?>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="estadoEmp">Estado<span class="req">*</span></label>
                            <select class="form-select<?php echo rs_invalid($errores, 'estado'); ?>" id="estadoEmp" name="estado" required data-default="Activo" data-label="un estado">
                                <?php echo rs_opciones_lista(['Activo', 'Inactivo'], rs_old($old, 'estado', 'Activo'), null); ?>
                            </select>
                            <?php echo rs_error_campo($errores, 'estado'); ?>
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
