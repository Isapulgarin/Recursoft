<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();

const RS_TIPOS_REPORTE = ['Inventario', 'Préstamos', 'Devoluciones', 'Mantenimientos', 'Compras'];
const RS_FORMATOS_REPORTE = ['PDF', 'CSV'];

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_reporte');

    if ($accion === 'generar') {
        $old = $_POST;
        $idUsuario = rs_entero_id($errores, rs_post('id_usuario'), 'id_usuario', 'un usuario');
        $tipo = rs_opcion($errores, rs_post('tipo_reporte'), RS_TIPOS_REPORTE, 'tipo_reporte', 'el tipo de reporte');
        $descripcion = rs_max($errores, rs_trim(rs_post('descripcion')), 'descripcion', 150, 'La descripción');
        $formato = rs_opcion($errores, rs_post('formato'), RS_FORMATOS_REPORTE, 'formato', 'el formato');
        rs_fk($errores, 'usuario', $idUsuario, 'id_usuario', 'usuario');

        if (!$errores) {
            try {
                rs_exec('INSERT INTO reporte (id_usuario, tipo_reporte, fecha_generacion, descripcion, formato) VALUES (?, ?, CURDATE(), ?, ?)',
                    [$idUsuario, $tipo, $descripcion, $formato]);
                $nuevo = (int) rs_last_id();
                rs_flash('ok', 'Reporte generado correctamente.');
                $_SESSION['rs_reporte_descarga'] = $nuevo;
                rs_redirect('dashboardAD/reportes.php');
            } catch (PDOException $e) {
                $errores['general'] = 'No fue posible generar el reporte.';
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'eliminar' && $id > 0) {
        rs_exec('DELETE FROM reporte WHERE id_reporte = ?', [$id]);
        rs_flash('ok', 'Reporte eliminado del registro.');
        rs_redirect('dashboardAD/reportes.php');
    }
}

$descargaPendiente = $_SESSION['rs_reporte_descarga'] ?? null;
unset($_SESSION['rs_reporte_descarga']);

$filtroTipo = rs_trim(rs_get('tipo'));
$filtroFormato = rs_trim(rs_get('formato'));
$sql = "SELECT rp.*, CONCAT(u.nombre, ' ', u.apellido) AS usuario FROM reporte rp
        INNER JOIN usuario u ON u.id_usuario = rp.id_usuario WHERE 1=1";
$params = [];
if ($filtroTipo !== '') {
    $sql .= ' AND rp.tipo_reporte = ?';
    $params[] = $filtroTipo;
}
if ($filtroFormato !== '') {
    $sql .= ' AND rp.formato = ?';
    $params[] = $filtroFormato;
}
$reportes = rs_all($sql . ' ORDER BY rp.id_reporte DESC', $params);
$usuariosLista = rs_all('SELECT id_usuario, nombre, apellido, correo FROM usuario ORDER BY nombre, apellido');

$resumen = [
    'recursos' => (int) (rs_one('SELECT COUNT(*) AS n FROM recurso')['n'] ?? 0),
    'prestamos' => (int) (rs_one('SELECT COUNT(*) AS n FROM prestamo')['n'] ?? 0),
    'mantenimientos' => (int) (rs_one('SELECT COUNT(*) AS n FROM mantenimiento')['n'] ?? 0),
    'compras' => (float) (rs_one("SELECT COALESCE(SUM(total), 0) AS n FROM compra WHERE estado <> 'Anulada'")['n'] ?? 0),
];
