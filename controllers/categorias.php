<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_categoria');

    if ($accion === 'guardar') {
        $old = $_POST;
        $nombre = rs_max($errores, rs_req($errores, rs_post('nombre_categoria'), 'nombre_categoria', 'nombre'), 'nombre_categoria', 50, 'El nombre');
        $descripcion = rs_max($errores, rs_trim(rs_post('descripcion')), 'descripcion', 150, 'La descripción');
        $estado = rs_opcion($errores, rs_post('estado'), ['Activo', 'Inactivo'], 'estado', 'estado');
        if ($id > 0 && !rs_registro('categoria_recurso', $id)) {
            $errores['general'] = 'La categoría que intenta editar no existe.';
        }
        rs_unico($errores, 'categoria_recurso', 'nombre_categoria', $nombre, 'nombre_categoria', 'Ya existe una categoría con ese nombre.', $id ?: null);

        if (!$errores) {
            try {
                if ($id > 0) {
                    rs_exec('UPDATE categoria_recurso SET nombre_categoria = ?, descripcion = ?, estado = ? WHERE id_categoria = ?', [$nombre, $descripcion, $estado, $id]);
                    rs_flash('ok', 'Categoría actualizada correctamente.');
                } else {
                    rs_exec('INSERT INTO categoria_recurso (nombre_categoria, descripcion, estado) VALUES (?, ?, ?)', [$nombre, $descripcion, $estado]);
                    rs_flash('ok', 'Categoría registrada correctamente.');
                }
                rs_redirect('dashboardAD/categorias.php');
            } catch (PDOException $e) {
                $errores['general'] = 'No fue posible guardar la categoría.';
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'estado' && $id > 0) {
        $row = rs_registro('categoria_recurso', $id);
        if ($row) {
            rs_exec('UPDATE categoria_recurso SET estado = ? WHERE id_categoria = ?', [rs_estado_opuesto($row['estado']), $id]);
            rs_flash('ok', 'El estado de la categoría se actualizó.');
        }
        rs_redirect('dashboardAD/categorias.php');
    }

    if ($accion === 'eliminar' && $id > 0) {
        if (rs_existe('recurso', 'id_categoria', $id)) {
            rs_flash('error', 'No se puede eliminar porque esta categoría tiene recursos asociados. Puede inactivarla.');
        } else {
            try {
                rs_exec('DELETE FROM categoria_recurso WHERE id_categoria = ?', [$id]);
                rs_flash('ok', 'Categoría eliminada.');
            } catch (PDOException $e) {
                rs_flash('error', 'No se puede eliminar porque este registro tiene información relacionada.');
            }
        }
        rs_redirect('dashboardAD/categorias.php');
    }
}

$q = rs_trim(rs_get('q'));
$filtroEstado = rs_trim(rs_get('estado'));
$sql = 'SELECT c.*, (SELECT COUNT(*) FROM recurso r WHERE r.id_categoria = c.id_categoria) AS total_recursos
        FROM categoria_recurso c WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (c.nombre_categoria LIKE ? OR c.descripcion LIKE ?)';
    array_push($params, "%{$q}%", "%{$q}%");
}
if ($filtroEstado !== '') {
    $sql .= ' AND c.estado = ?';
    $params[] = $filtroEstado;
}
$sql .= ' ORDER BY c.nombre_categoria';
$categorias = rs_all($sql, $params);
