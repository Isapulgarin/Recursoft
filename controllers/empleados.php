<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_empleado');

    if ($accion === 'guardar') {
        $old = $_POST;
        $idUsuario = rs_entero_id($errores, rs_post('id_usuario'), 'id_usuario', 'un usuario');
        $documento = rs_documento_ok($errores, rs_max($errores, rs_req($errores, rs_post('documento'), 'documento', 'documento'), 'documento', 20, 'El documento'), 'documento');
        $nombre = rs_max($errores, rs_req($errores, rs_post('nombre'), 'nombre', 'nombre'), 'nombre', 50, 'El nombre');
        $apellido = rs_max($errores, rs_req($errores, rs_post('apellido'), 'apellido', 'apellido'), 'apellido', 50, 'El apellido');
        $cargo = rs_max($errores, rs_req($errores, rs_post('cargo'), 'cargo', 'cargo'), 'cargo', 50, 'El cargo');
        $telefono = rs_telefono_ok($errores, rs_max($errores, rs_trim(rs_post('telefono')), 'telefono', 20, 'El teléfono'), 'telefono');
        $correo = rs_email_ok($errores, rs_max($errores, rs_trim(rs_post('correo')), 'correo', 100, 'El correo'), 'correo');
        $estado = rs_opcion($errores, rs_post('estado'), ['Activo', 'Inactivo'], 'estado', 'estado');

        if ($id > 0 && !rs_registro('empleado', $id)) {
            $errores['general'] = 'El empleado que intenta editar no existe.';
        }
        rs_fk($errores, 'usuario', $idUsuario, 'id_usuario', 'usuario');
        rs_unico($errores, 'empleado', 'id_usuario', $idUsuario, 'id_usuario', 'Esta cuenta de usuario ya está asociada a otro empleado.', $id ?: null);
        rs_unico($errores, 'empleado', 'documento', $documento, 'documento', 'Este documento ya se encuentra registrado.', $id ?: null);

        if (!$errores) {
            try {
                $datos = [$idUsuario, $documento, $nombre, $apellido, $cargo, $telefono, $correo, $estado];
                if ($id > 0) {
                    $datos[] = $id;
                    rs_exec('UPDATE empleado SET id_usuario = ?, documento = ?, nombre = ?, apellido = ?, cargo = ?, telefono = ?, correo = ?, estado = ? WHERE id_empleado = ?', $datos);
                    rs_flash('ok', 'Empleado actualizado correctamente.');
                } else {
                    rs_exec('INSERT INTO empleado (id_usuario, documento, nombre, apellido, cargo, telefono, correo, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', $datos);
                    rs_flash('ok', 'Empleado registrado correctamente.');
                }
                rs_redirect('dashboardAD/empleados.php');
            } catch (PDOException $e) {
                $errores['general'] = 'No fue posible guardar el empleado.';
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'estado' && $id > 0) {
        $row = rs_registro('empleado', $id);
        if ($row) {
            rs_exec('UPDATE empleado SET estado = ? WHERE id_empleado = ?', [rs_estado_opuesto($row['estado']), $id]);
            rs_flash('ok', 'El estado del empleado se actualizó.');
        }
        rs_redirect('dashboardAD/empleados.php');
    }

    if ($accion === 'eliminar' && $id > 0) {
        if (rs_existe('prestamo', 'id_empleado', $id)) {
            rs_flash('error', 'No se puede eliminar porque este empleado tiene préstamos registrados. Puede inactivarlo.');
        } else {
            try {
                rs_exec('DELETE FROM empleado WHERE id_empleado = ?', [$id]);
                rs_flash('ok', 'Empleado eliminado.');
            } catch (PDOException $e) {
                rs_flash('error', 'No se puede eliminar porque este registro tiene información relacionada.');
            }
        }
        rs_redirect('dashboardAD/empleados.php');
    }
}

$q = rs_trim(rs_get('q'));
$filtroEstado = rs_trim(rs_get('estado'));
$sql = 'SELECT e.*, u.correo AS correo_usuario, CONCAT(u.nombre, " ", u.apellido) AS nombre_usuario
        FROM empleado e
        LEFT JOIN usuario u ON u.id_usuario = e.id_usuario
        WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (e.documento LIKE ? OR e.nombre LIKE ? OR e.apellido LIKE ? OR CONCAT(e.nombre, " ", e.apellido) LIKE ?)';
    array_push($params, "%{$q}%", "%{$q}%", "%{$q}%", "%{$q}%");
}
if ($filtroEstado !== '') {
    $sql .= ' AND ' . rs_sql_estado('e.estado', $filtroEstado);
}
$sql .= ' ORDER BY e.apellido, e.nombre';
$empleados = rs_all($sql, $params);

$usuariosDisponibles = rs_all(
    'SELECT u.id_usuario, u.nombre, u.apellido, u.correo, e.id_empleado
     FROM usuario u
     LEFT JOIN empleado e ON e.id_usuario = u.id_usuario
     ORDER BY u.nombre, u.apellido'
);
