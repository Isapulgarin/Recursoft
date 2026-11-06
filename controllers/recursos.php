<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_recurso');

    if ($accion === 'guardar') {
        $old = $_POST;
        $actual = $id > 0 ? rs_registro('recurso', $id) : null;
        if ($id > 0 && !$actual) {
            $errores['general'] = 'El recurso que intenta editar no existe.';
        }
        $idCategoria = rs_entero_id($errores, rs_post('id_categoria'), 'id_categoria', 'una categoría');
        $codigo = rs_max($errores, rs_req($errores, rs_post('codigo_interno'), 'codigo_interno', 'código interno'), 'codigo_interno', 50, 'El código interno');
        $nombre = rs_max($errores, rs_req($errores, rs_post('nombre_recurso'), 'nombre_recurso', 'nombre'), 'nombre_recurso', 100, 'El nombre');
        $descripcion = rs_max($errores, rs_trim(rs_post('descripcion')), 'descripcion', 150, 'La descripción');
        $serial = rs_max($errores, rs_trim(rs_post('serial')), 'serial', 100, 'El serial');
        $marca = rs_max($errores, rs_trim(rs_post('marca')), 'marca', 50, 'La marca');
        $modelo = rs_max($errores, rs_trim(rs_post('modelo')), 'modelo', 50, 'El modelo');
        $estado = rs_opcion($errores, rs_post('estado'), RS_ESTADOS_RECURSO, 'estado', 'estado');
        $disponibilidad = rs_opcion($errores, rs_post('disponibilidad'), ['Disponible', 'No disponible'], 'disponibilidad', 'disponibilidad');
        $fechaRegistro = rs_fecha_ok($errores, rs_post('fecha_registro'), 'fecha_registro', 'registro');
        $fechaAdquisicion = rs_fecha_ok($errores, rs_post('fecha_adquisicion'), 'fecha_adquisicion', 'adquisición', false);

        $categoria = rs_fk($errores, 'categoria_recurso', $idCategoria, 'id_categoria', 'categoría');
        if ($categoria && !rs_es_activo($categoria['estado']) && (!$actual || (int) $actual['id_categoria'] !== $idCategoria)) {
            $errores['id_categoria'] = 'La categoría seleccionada está inactiva.';
        }
        if ($fechaRegistro && $fechaRegistro > date('Y-m-d')) {
            $errores['fecha_registro'] = 'La fecha de registro no puede ser futura.';
        }
        if ($fechaAdquisicion && $fechaAdquisicion > date('Y-m-d')) {
            $errores['fecha_adquisicion'] = 'La fecha de adquisición no puede ser futura.';
        }
        rs_unico($errores, 'recurso', 'codigo_interno', $codigo, 'codigo_interno', 'El código interno ya existe.', $id ?: null);
        rs_unico($errores, 'recurso', 'serial', $serial, 'serial', 'El serial ya está registrado en otro recurso.', $id ?: null);

        if (!$errores) {
            $serialDb = $serial !== '' ? $serial : null;
            try {
                $pdo = rs_pdo();
                $pdo->beginTransaction();
                if ($id > 0) {
                    rs_exec(
                        'UPDATE recurso SET id_categoria = ?, codigo_interno = ?, nombre_recurso = ?, descripcion = ?, serial = ?, marca = ?, modelo = ?,
                                estado = ?, disponibilidad = ?, fecha_registro = ?, fecha_adquisicion = ?
                         WHERE id_recurso = ?',
                        [$idCategoria, $codigo, $nombre, $descripcion, $serialDb, $marca, $modelo, $estado, $disponibilidad, $fechaRegistro, $fechaAdquisicion, $id]
                    );
                    rs_recalcular_disponibilidad($id);
                    $msg = 'Recurso actualizado correctamente.';
                } else {
                    rs_exec(
                        'INSERT INTO recurso (id_categoria, codigo_interno, nombre_recurso, descripcion, serial, marca, modelo, estado, disponibilidad, fecha_registro, fecha_adquisicion)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [$idCategoria, $codigo, $nombre, $descripcion, $serialDb, $marca, $modelo, $estado, $disponibilidad, $fechaRegistro, $fechaAdquisicion]
                    );
                    rs_recalcular_disponibilidad(rs_last_id());
                    $msg = 'Recurso registrado correctamente.';
                }
                $pdo->commit();
                rs_flash('ok', $msg);
                rs_redirect('dashboardAD/recursos.php');
            } catch (PDOException $e) {
                if (rs_pdo()->inTransaction()) {
                    rs_pdo()->rollBack();
                }
                $errores['general'] = 'No fue posible guardar el recurso.';
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'eliminar' && $id > 0) {
        if (rs_existe('prestamo', 'id_recurso', $id) || rs_one('SELECT 1 FROM mantenimiento WHERE id_recurso = ? LIMIT 1', [$id])
            || rs_one('SELECT 1 FROM detalle_compra WHERE id_recurso = ? LIMIT 1', [$id])) {
            rs_flash('error', 'No se puede eliminar porque este recurso tiene información relacionada (préstamos, mantenimientos o compras). Puede marcarlo como "No disponible".');
        } else {
            try {
                rs_exec('DELETE FROM recurso WHERE id_recurso = ?', [$id]);
                rs_flash('ok', 'Recurso eliminado.');
            } catch (PDOException $e) {
                rs_flash('error', 'No se puede eliminar porque este registro tiene información relacionada.');
            }
        }
        rs_redirect('dashboardAD/recursos.php');
    }
}

$q = rs_trim(rs_get('q'));
$filtroCategoria = rs_trim(rs_get('categoria'));
$filtroEstado = rs_trim(rs_get('estado'));
$filtroDisp = rs_trim(rs_get('disponibilidad'));

$sql = 'SELECT r.*, c.nombre_categoria FROM recurso r
        LEFT JOIN categoria_recurso c ON c.id_categoria = r.id_categoria
        WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (r.codigo_interno LIKE ? OR r.nombre_recurso LIKE ? OR r.serial LIKE ? OR r.marca LIKE ? OR r.modelo LIKE ?)';
    array_push($params, "%{$q}%", "%{$q}%", "%{$q}%", "%{$q}%", "%{$q}%");
}
if ($filtroCategoria !== '') {
    $sql .= ' AND r.id_categoria = ?';
    $params[] = (int) $filtroCategoria;
}
if ($filtroEstado !== '') {
    $sql .= ' AND r.estado = ?';
    $params[] = $filtroEstado;
}
if ($filtroDisp !== '') {
    $sql .= ' AND r.disponibilidad = ?';
    $params[] = $filtroDisp;
}
$sql .= ' ORDER BY r.id_recurso DESC';
$recursos = rs_all($sql, $params);
$categorias = rs_all('SELECT id_categoria, nombre_categoria, estado FROM categoria_recurso ORDER BY nombre_categoria');
$categoriasActivas = array_values(array_filter($categorias, fn ($c) => rs_es_activo($c['estado'])));
