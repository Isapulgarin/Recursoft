<?php
$emPagina = 'reporte_danos';
require_once __DIR__ . '/../controllers/empleado_panel.php';

$misRecursos = $idEmpleado ? rs_all(
    "SELECT DISTINCT r.id_recurso, r.codigo_interno, r.nombre_recurso FROM prestamo p
     INNER JOIN recurso r ON r.id_recurso = p.id_recurso
     WHERE p.id_empleado = ? AND p.estado IN ('Activo','Vencido','Devuelto') ORDER BY r.nombre_recurso",
    [$idEmpleado]
) : [];
$misReportes = $idEmpleado && $empleadoEm ? rs_all(
    "SELECT m.*, r.codigo_interno, r.nombre_recurso FROM mantenimiento m
     INNER JOIN recurso r ON r.id_recurso = m.id_recurso
     WHERE m.observaciones LIKE ? ORDER BY m.id_mantenimiento DESC",
    ['Reportado por ' . $empleadoEm['nombre'] . ' ' . $empleadoEm['apellido'] . '.%']
) : [];
$hoy = date('Y-m-d');

$pageTitle = 'Reportar Daños';
$dashboardType = 'EM';
$activePage = 'reporte_danos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>
<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Reportar Daños</h1>
        <p>Informe al administrador sobre daños en recursos que tiene o tuvo en préstamo. Se registra como mantenimiento correctivo "Reportado".</p>
    </div>

    <?php rs_flash_html(); ?>

    <div class="card-rs p-3 p-md-4 section-gap">
        <?php echo rs_error_general($errores); ?>
        <form class="form-visual row g-3" method="post" data-validate>
            <?php echo rs_csrf_field(); ?>
            <input type="hidden" name="accion" value="reportar_dano">
            <div class="col-12 col-md-6">
                <label class="form-label" for="recursoDano">Recurso<span class="req">*</span></label>
                <select class="form-select<?php echo rs_invalid($errores, 'id_recurso'); ?>" id="recursoDano" name="id_recurso" required data-label="un recurso">
                    <?php echo rs_opciones_html($misRecursos, 'id_recurso', fn ($r) => $r['codigo_interno'] . ' - ' . $r['nombre_recurso'], rs_old($old, 'id_recurso')); ?>
                </select>
                <?php echo rs_error_campo($errores, 'id_recurso'); ?>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="fechaDano">Fecha del incidente<span class="req">*</span></label>
                <input class="form-control<?php echo rs_invalid($errores, 'fecha_inicio'); ?>" id="fechaDano" name="fecha_inicio" type="date" max="<?php echo $hoy; ?>" data-no-future required value="<?php echo rs_h(rs_old($old, 'fecha_inicio', $hoy)); ?>">
                <?php echo rs_error_campo($errores, 'fecha_inicio'); ?>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="tipoDano">Tipo<span class="req">*</span></label>
                <select class="form-select<?php echo rs_invalid($errores, 'tipo_dano'); ?>" id="tipoDano" name="tipo_dano" required data-label="el tipo de daño">
                    <?php echo rs_opciones_lista(['Físico', 'Funcional', 'Pérdida de accesorio'], rs_old($old, 'tipo_dano')); ?>
                </select>
                <?php echo rs_error_campo($errores, 'tipo_dano'); ?>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="prioridadDano">Prioridad<span class="req">*</span></label>
                <select class="form-select<?php echo rs_invalid($errores, 'prioridad'); ?>" id="prioridadDano" name="prioridad" required data-label="la prioridad">
                    <?php echo rs_opciones_lista(['Baja', 'Media', 'Alta'], rs_old($old, 'prioridad')); ?>
                </select>
                <?php echo rs_error_campo($errores, 'prioridad'); ?>
            </div>
            <div class="col-12">
                <label class="form-label" for="descDano">Descripción<span class="req">*</span></label>
                <textarea class="form-control<?php echo rs_invalid($errores, 'descripcion'); ?>" id="descDano" name="descripcion" rows="4" maxlength="150" required placeholder="Describa el daño observado"><?php echo rs_h(rs_old($old, 'descripcion')); ?></textarea>
                <?php echo rs_error_campo($errores, 'descripcion'); ?>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-rs" type="submit">Enviar reporte</button>
                <button class="btn btn-rs-outline" type="reset">Limpiar</button>
            </div>
        </form>
    </div>
    <div class="card-rs">
        <div class="card-header py-3">Reportes enviados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Recurso</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$misReportes): rs_empty_table_row(5); endif; ?>
                    <?php foreach ($misReportes as $m): ?>
                        <tr>
                            <td><?php echo (int) $m['id_mantenimiento']; ?></td>
                            <td><?php echo rs_h($m['codigo_interno'] . ' - ' . $m['nombre_recurso']); ?></td>
                            <td><?php echo rs_fecha_txt($m['fecha_inicio']); ?></td>
                            <td class="small"><?php echo rs_h($m['descripcion']); ?></td>
                            <td><?php echo rs_badge_proceso($m['estado']); ?></td>
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
