<?php
$pageTitle = 'Catálogo de Recursos';
$dashboardType = 'EM';
$activePage = 'recursos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Catálogo de Recursos</h1>
        <p>Consulta de disponibilidad. El empleado no administra el inventario.</p>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2">
            <div class="col-12 col-md-6">
                <label class="form-label" for="buscarCat">Buscar</label>
                <input class="form-control" id="buscarCat" type="search" placeholder="Nombre o categoría">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="catEmp">Categoría</label>
                <select class="form-select" id="catEmp">
                    <option value="">Todas</option>
                    <option>Equipos</option>
                    <option>Mobiliario</option>
                    <option>Material didáctico</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3 d-flex align-items-end">
                <button class="btn btn-rs w-100" type="submit">Filtrar</button>
            </div>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card-rs h-100">
                <div class="catalog-placeholder" style="min-height:140px;border:0;border-bottom:1px dashed var(--color4);border-radius:var(--radius) var(--radius) 0 0;">
                    <i class="bi bi-image fs-2 text-secondary"></i>
                </div>
                <div class="p-3">
                    <h2 class="h6 text-primary">Recurso no disponible</h2>
                    <p class="small text-secondary mb-2">No hay información para mostrar.</p>
                    <span class="badge-rs badge-inactivo">Sin datos</span>
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card-rs h-100">
                <div class="catalog-placeholder" style="min-height:140px;border:0;border-bottom:1px dashed var(--color4);border-radius:var(--radius) var(--radius) 0 0;">
                    <i class="bi bi-image fs-2 text-secondary"></i>
                </div>
                <div class="p-3">
                    <h2 class="h6 text-primary">Recurso no disponible</h2>
                    <p class="small text-secondary mb-2">No hay información para mostrar.</p>
                    <span class="badge-rs badge-inactivo">Sin datos</span>
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card-rs h-100">
                <div class="catalog-placeholder" style="min-height:140px;border:0;border-bottom:1px dashed var(--color4);border-radius:var(--radius) var(--radius) 0 0;">
                    <i class="bi bi-image fs-2 text-secondary"></i>
                </div>
                <div class="p-3">
                    <h2 class="h6 text-primary">Recurso no disponible</h2>
                    <p class="small text-secondary mb-2">No hay información para mostrar.</p>
                    <span class="badge-rs badge-inactivo">Sin datos</span>
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <article class="card-rs h-100">
                <div class="catalog-placeholder" style="min-height:140px;border:0;border-bottom:1px dashed var(--color4);border-radius:var(--radius) var(--radius) 0 0;">
                    <i class="bi bi-image fs-2 text-secondary"></i>
                </div>
                <div class="p-3">
                    <h2 class="h6 text-primary">Recurso no disponible</h2>
                    <p class="small text-secondary mb-2">No hay información para mostrar.</p>
                    <span class="badge-rs badge-inactivo">Sin datos</span>
                </div>
            </article>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
