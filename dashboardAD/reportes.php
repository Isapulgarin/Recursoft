<?php
require_once __DIR__ . '/../controllers/reportes.php';

$pageTitle = 'Reportes';
$dashboardType = 'AD';
$activePage = 'reportes';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
$idSesion = (int) (rs_usuario_sesion()['id_usuario'] ?? 0);
?>

<main class="main-content">
    <div class="page-heading d-flex flex-column flex-lg-row justify-content-between gap-3 section-gap">
        <div>
            <h1>Reportes</h1>
            <p>Genere reportes en PDF o CSV a partir de la información registrada.</p>
        </div>
        <div class="action-btns no-print">
            <button class="btn btn-rs" type="button" data-bs-toggle="modal" data-bs-target="#modalReporte" data-rs-new>
                <i class="bi bi-file-earmark-plus me-1"></i>Generar reporte
            </button>
        </div>
    </div>

    <?php rs_flash_html(); ?>
    <?php if ($descargaPendiente): ?>
        <div class="alert alert-rs alert-rs-ok d-flex align-items-center gap-2">
            <i class="bi bi-download"></i>
            <div>Su reporte está listo. <a class="fw-bold" href="../controllers/reporte_descargar.php?id=<?php echo (int) $descargaPendiente; ?>" target="_blank" rel="noopener">Descargar / abrir reporte</a></div>
        </div>
    <?php endif; ?>

    <div class="row g-3 section-gap">
        <div class="col-6 col-xl-3"><article class="stat-card"><div class="stat-icon bg-1"><i class="bi bi-box-seam"></i></div><div><div class="stat-label">Recursos</div><div class="stat-value"><?php echo $resumen['recursos']; ?></div></div></article></div>
        <div class="col-6 col-xl-3"><article class="stat-card"><div class="stat-icon bg-2"><i class="bi bi-clipboard-check"></i></div><div><div class="stat-label">Préstamos</div><div class="stat-value"><?php echo $resumen['prestamos']; ?></div></div></article></div>
        <div class="col-6 col-xl-3"><article class="stat-card"><div class="stat-icon bg-3"><i class="bi bi-tools"></i></div><div><div class="stat-label">Mantenimientos</div><div class="stat-value"><?php echo $resumen['mantenimientos']; ?></div></div></article></div>
        <div class="col-6 col-xl-3"><article class="stat-card"><div class="stat-icon bg-4"><i class="bi bi-bag-check"></i></div><div><div class="stat-label">Total en compras</div><div class="stat-value fs-6"><?php echo rs_dinero($resumen['compras']); ?></div></div></article></div>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-5">
                <label class="form-label" for="filtroTipoRep">Tipo de reporte</label>
                <select class="form-select" id="filtroTipoRep" name="tipo">
                    <?php echo rs_opciones_lista(RS_TIPOS_REPORTE, $filtroTipo, 'Todos'); ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="filtroFormatoRep">Formato</label>
                <select class="form-select" id="filtroFormatoRep" name="formato">
                    <?php echo rs_opciones_lista(RS_FORMATOS_REPORTE, $filtroFormato, 'Todos'); ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <button class="btn btn-rs-outline w-100" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-rs-outline w-100" href="reportes.php" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="card-header py-3">Reportes generados</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>N.º</th>
                        <th>Tipo</th>
                        <th>Fecha de generación</th>
                        <th>Usuario</th>
                        <th>Descripción</th>
                        <th>Formato</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$reportes): rs_empty_table_row(7); endif; ?>
                    <?php foreach ($reportes as $r): ?>
                        <tr>
                            <td><?php echo (int) $r['id_reporte']; ?></td>
                            <td><?php echo rs_h($r['tipo_reporte']); ?></td>
                            <td><?php echo rs_fecha_txt($r['fecha_generacion']); ?></td>
                            <td><?php echo rs_h($r['usuario']); ?></td>
                            <td class="small"><?php echo rs_h($r['descripcion']); ?></td>
                            <td><span class="badge-rs badge-mantenimiento"><?php echo rs_h($r['formato']); ?></span></td>
                            <td class="table-actions">
                                <a class="btn btn-sm btn-rs-outline" href="../controllers/reporte_descargar.php?id=<?php echo (int) $r['id_reporte']; ?>" target="_blank" rel="noopener" title="Descargar"><i class="bi bi-download"></i></a>
                                <?php echo rs_btn_eliminar('id_reporte', $r['id_reporte'], 'este reporte'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php echo rs_marcador_modal($abrirModal, 'modalReporte'); ?>
<div class="modal fade" id="modalReporte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5">Generar reporte</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form class="form-visual" method="post" data-validate>
                <?php echo rs_csrf_field(); ?>
                <input type="hidden" name="accion" value="generar">
                <div class="modal-body">
                    <?php echo rs_error_general($errores); ?>
                    <p class="small text-secondary">Los campos marcados con <span class="req">*</span> son obligatorios.</p>
                    <div class="mb-3">
                        <label class="form-label" for="usuarioRep">Usuario relacionado<span class="req">*</span></label>
                        <select class="form-select<?php echo rs_invalid($errores, 'id_usuario'); ?>" id="usuarioRep" name="id_usuario" required data-label="un usuario" data-default="<?php echo $idSesion; ?>">
                            <?php echo rs_opciones_html($usuariosLista, 'id_usuario', fn ($u) => $u['nombre'] . ' ' . $u['apellido'] . ' (' . $u['correo'] . ')', rs_old($old, 'id_usuario', $idSesion)); ?>
                        </select>
                        <?php echo rs_error_campo($errores, 'id_usuario'); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tipoReporte">Tipo de reporte<span class="req">*</span></label>
                        <select class="form-select<?php echo rs_invalid($errores, 'tipo_reporte'); ?>" id="tipoReporte" name="tipo_reporte" required data-label="el tipo de reporte">
                            <?php echo rs_opciones_lista(RS_TIPOS_REPORTE, rs_old($old, 'tipo_reporte')); ?>
                        </select>
                        <?php echo rs_error_campo($errores, 'tipo_reporte'); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="fechaRep">Fecha de generación</label>
                        <input class="form-control" id="fechaRep" type="date" value="<?php echo date('Y-m-d'); ?>" disabled>
                        <div class="form-text">Se asigna automáticamente.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="descRep">Descripción</label>
                        <textarea class="form-control<?php echo rs_invalid($errores, 'descripcion'); ?>" id="descRep" name="descripcion" rows="2" maxlength="150"><?php echo rs_h(rs_old($old, 'descripcion')); ?></textarea>
                        <div class="form-text">Máximo 150 caracteres.</div>
                        <?php echo rs_error_campo($errores, 'descripcion'); ?>
                    </div>
                    <div>
                        <label class="form-label" for="formatoRep">Formato<span class="req">*</span></label>
                        <select class="form-select<?php echo rs_invalid($errores, 'formato'); ?>" id="formatoRep" name="formato" required data-label="el formato">
                            <?php echo rs_opciones_lista(RS_FORMATOS_REPORTE, rs_old($old, 'formato')); ?>
                        </select>
                        <?php echo rs_error_campo($errores, 'formato'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-rs-outline" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-rs-outline" type="reset">Limpiar</button>
                    <button class="btn btn-rs" type="submit"><i class="bi bi-file-earmark-arrow-down me-1"></i>Generar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
