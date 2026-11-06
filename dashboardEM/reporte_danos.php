<?php
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
        <p>Formulario de incidencia. No se envía información al servidor en esta etapa.</p>
    </div>

    <div class="card-rs p-3 p-md-4 section-gap">
        <form class="form-visual row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label" for="recursoDano">Recurso</label>
                <input class="form-control" id="recursoDano" type="text">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="fechaDano">Fecha del incidente</label>
                <input class="form-control" id="fechaDano" type="date">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="tipoDano">Tipo</label>
                <select class="form-select" id="tipoDano">
                    <option>Físico</option>
                    <option>Funcional</option>
                    <option>Pérdida de accesorio</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="prioridadDano">Prioridad</label>
                <select class="form-select" id="prioridadDano">
                    <option>Baja</option>
                    <option>Media</option>
                    <option>Alta</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label" for="descDano">Descripción</label>
                <textarea class="form-control" id="descDano" rows="4" placeholder="Describa el daño observado"></textarea>
            </div>
            <div class="col-12">
                <label class="form-label" for="evidencia">Evidencia (archivo)</label>
                <input class="form-control" id="evidencia" type="file" disabled>
                <div class="form-text">La carga de archivos se habilitará con el backend.</div>
            </div>
            <div class="col-12">
                <button class="btn btn-rs" type="submit">Enviar reporte</button>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="card-header py-3">Reportes enviados</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Recurso</th>
                        <th>Fecha</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(5); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
