<?php
$emPagina = 'recursos';
require_once __DIR__ . '/../controllers/empleado_panel.php';

$buscar = rs_trim(rs_get('q'));
$filtroCat = rs_trim(rs_get('categoria'));
$soloDisp = rs_get('disponibles') === '1';
$sql = "SELECT r.id_recurso, r.codigo_interno, r.nombre_recurso, r.descripcion, r.marca, r.modelo, r.estado, r.disponibilidad, c.nombre_categoria
        FROM recurso r LEFT JOIN categoria_recurso c ON c.id_categoria = r.id_categoria
        WHERE r.estado <> 'Inactivo'";
$params = [];
if ($buscar !== '') {
    $sql .= ' AND (r.nombre_recurso LIKE ? OR r.codigo_interno LIKE ? OR c.nombre_categoria LIKE ?)';
    $like = '%' . $buscar . '%';
    array_push($params, $like, $like, $like);
}
if ($filtroCat !== '' && ctype_digit($filtroCat)) {
    $sql .= ' AND r.id_categoria = ?';
    $params[] = (int) $filtroCat;
}
if ($soloDisp) {
    $sql .= " AND r.disponibilidad = 'Disponible'";
}
$catalogo = rs_all($sql . ' ORDER BY r.nombre_recurso', $params);
$categoriasLista = rs_all("SELECT id_categoria, nombre_categoria FROM categoria_recurso WHERE estado IN ('Activo', '1') ORDER BY nombre_categoria");

$pageTitle = 'Recursos';
$dashboardType = 'EM';
$activePage = 'recursos';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Catálogo de recursos</h1>
        <p>Consulte los recursos institucionales y solicite los que estén disponibles.</p>
    </div>

    <div class="card-rs toolbar-card section-gap">
        <form class="form-visual row g-2 align-items-end" method="get">
            <div class="col-12 col-md-5">
                <label class="form-label" for="buscarCat">Buscar</label>
                <input class="form-control" id="buscarCat" name="q" type="search" placeholder="Nombre, código o categoría" value="<?php echo rs_h($buscar); ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label" for="catEmp">Categoría</label>
                <select class="form-select" id="catEmp" name="categoria">
                    <?php echo rs_opciones_html($categoriasLista, 'id_categoria', 'nombre_categoria', $filtroCat, 'Todas'); ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="soloDisp" name="disponibles" value="1"<?php echo $soloDisp ? ' checked' : ''; ?>>
                    <label class="form-check-label" for="soloDisp">Solo disponibles</label>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-rs w-100" type="submit">Filtrar</button>
            </div>
        </form>
    </div>

    <div class="row g-3">
        <?php if (!$catalogo): ?>
            <div class="col-12"><div class="card-rs catalog-placeholder"><?php rs_empty_state('bi-box-seam', 'Sin recursos', 'No hay recursos que coincidan con la búsqueda.'); ?></div></div>
        <?php endif; ?>
        <?php foreach ($catalogo as $r): ?>
            <div class="col-12 col-md-6 col-xl-3">
                <article class="card-rs h-100 d-flex flex-column">
                    <div class="catalog-placeholder" style="min-height:140px;border:0;border-bottom:1px dashed var(--color4);border-radius:var(--radius) var(--radius) 0 0;">
                        <i class="bi bi-box-seam fs-2 text-secondary"></i>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h2 class="h6 text-primary mb-1"><?php echo rs_h($r['nombre_recurso']); ?></h2>
                        <div class="small text-secondary mb-2"><?php echo rs_h($r['codigo_interno'] . ' · ' . ($r['nombre_categoria'] ?? 'Sin categoría')); ?></div>
                        <p class="small mb-2"><?php echo rs_h($r['descripcion'] ?: trim(($r['marca'] ?? '') . ' ' . ($r['modelo'] ?? ''))); ?></p>
                        <div class="mt-auto d-flex justify-content-between align-items-center gap-2">
                            <?php echo rs_badge_disponibilidad($r['disponibilidad']); ?>
                            <?php if ($r['disponibilidad'] === 'Disponible' && !$sinEmpleado): ?>
                                <a class="btn btn-sm btn-rs" href="prestamos.php?recurso=<?php echo (int) $r['id_recurso']; ?>">Solicitar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
