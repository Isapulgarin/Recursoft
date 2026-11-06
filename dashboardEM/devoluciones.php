<?php
$pageTitle = 'Devoluciones';
$dashboardType = 'EM';
$activePage = 'devoluciones';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Devoluciones</h1>
        <p>Registro visual del retorno de recursos asignados al empleado.</p>
    </div>

    <div class="card-rs p-3 p-md-4 section-gap">
        <h2 class="h5 text-primary mb-3">Registrar devolución</h2>
        <form class="form-visual row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label" for="folioDevEmp">Folio de préstamo</label>
                <input class="form-control" id="folioDevEmp" type="text">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="fechaDevEmp">Fecha de retorno</label>
                <input class="form-control" id="fechaDevEmp" type="date">
            </div>
            <div class="col-12">
                <label class="form-label" for="condEmp">Condición</label>
                <select class="form-select" id="condEmp">
                    <option>Óptima</option>
                    <option>Con observaciones</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label" for="notaDevEmp">Observaciones</label>
                <textarea class="form-control" id="notaDevEmp" rows="3"></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-rs" type="submit">Registrar devolución</button>
            </div>
        </form>
    </div>

    <div class="card-rs">
        <div class="card-header py-3">Devoluciones propias</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Recurso</th>
                        <th>Fecha</th>
                        <th>Condición</th>
                    </tr>
                </thead>
                <tbody>
                    <?php rs_empty_table_row(4); ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
