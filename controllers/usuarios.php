<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();
rs_marcar_prestamos_vencidos();

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_usuario');

    if ($accion === 'guardar') {
        $old = $_POST;
        $nombre = rs_max($errores, rs_req($errores, rs_post('nombre'), 'nombre', 'nombre'), 'nombre', 50, 'Nombre');
        $apellido = rs_max($errores, rs_req($errores, rs_post('apellido'), 'apellido', 'apellido'), 'apellido', 50, 'Apellido');
        $correo = rs_email_ok($errores, rs_req($errores, rs_post('correo'), 'correo', 'correo'), 'correo');
        $telefono = rs_telefono_ok($errores, rs_max($errores, rs_req($errores, rs_post('telefono'), 'telefono', 'teléfono'), 'telefono', 20, 'Teléfono'), 'telefono');
        $idRol = rs_entero_id($errores, rs_post('id_rol'), 'id_rol', 'un rol');
        $estado = rs_opcion($errores, rs_post('estado'), ['Activo', 'Inactivo'], 'estado', 'estado');
        if ($id > 0 && !rs_registro('usuario', $id)) {
            $errores['general'] = 'El usuario que intenta editar no existe.';
        }
        if ($id > 0 && $id === (int) (rs_usuario_sesion()['id_usuario'] ?? 0) && $estado === 'Inactivo') {
            $errores['estado'] = 'No puede inactivar su propia cuenta.';
        }
        $password = rs_password_ok($errores, rs_post('contrasena'), 'contrasena', $id === 0);
        rs_fk($errores, 'rol', $idRol, 'id_rol', 'rol');
        rs_unico($errores, 'usuario', 'correo', $correo, 'correo', 'Este correo ya se encuentra registrado.', $id ?: null, 'id_usuario');

        if (!$errores) {
            try {
                if ($id > 0) {
                    if ($password !== '') {
                        rs_exec(
                            'UPDATE usuario SET id_rol = ?, nombre = ?, apellido = ?, correo = ?, telefono = ?, `contraseña` = ?, estado = ? WHERE id_usuario = ?',
                            [$idRol, $nombre, $apellido, $correo, $telefono, rs_password_hash_safe($password), $estado, $id]
                        );
                    } else {
                        rs_exec(
                            'UPDATE usuario SET id_rol = ?, nombre = ?, apellido = ?, correo = ?, telefono = ?, estado = ? WHERE id_usuario = ?',
                            [$idRol, $nombre, $apellido, $correo, $telefono, $estado, $id]
                        );
                    }
                    rs_flash('ok', 'Usuario actualizado correctamente.');
                } else {
                    rs_exec(
                        'INSERT INTO usuario (id_rol, nombre, apellido, correo, telefono, `contraseña`, estado) VALUES (?, ?, ?, ?, ?, ?, ?)',
                        [$idRol, $nombre, $apellido, $correo, $telefono, rs_password_hash_safe($password), $estado]
                    );
                    rs_flash('ok', 'Usuario registrado correctamente.');
                }
                rs_redirect('dashboardAD/usuarios.php');
            } catch (PDOException $e) {
                $errores['general'] = 'No fue posible guardar el usuario.';
            }
        }
        $abrirModal = true;
    }

    $propio = $id > 0 && $id === (int) (rs_usuario_sesion()['id_usuario'] ?? 0);

    if ($accion === 'estado' && $id > 0) {
        $row = rs_registro('usuario', $id);
        if ($propio) {
            rs_flash('error', 'No puede inactivar su propia cuenta.');
        } elseif ($row) {
            rs_exec('UPDATE usuario SET estado = ? WHERE id_usuario = ?', [rs_estado_opuesto($row['estado']), $id]);
            rs_flash('ok', 'El estado del usuario se actualizó.');
        }
        rs_redirect('dashboardAD/usuarios.php');
    }

    if ($accion === 'eliminar' && $id > 0) {
        $vinculo = rs_one('SELECT id_empleado FROM empleado WHERE id_usuario = ? LIMIT 1', [$id]);
        $reportes = rs_one('SELECT id_reporte FROM reporte WHERE id_usuario = ? LIMIT 1', [$id]);
        if ($propio) {
            rs_flash('error', 'No puede eliminar su propia cuenta.');
        } elseif ($vinculo || $reportes) {
            rs_flash('error', 'No se puede eliminar porque este usuario tiene información relacionada (empleado o reportes). Puede inactivarlo.');
        } else {
            try {
                rs_exec('DELETE FROM usuario WHERE id_usuario = ?', [$id]);
                rs_flash('ok', 'Usuario eliminado.');
            } catch (PDOException $e) {
                rs_flash('error', 'No se puede eliminar el usuario porque tiene registros relacionados.');
            }
        }
        rs_redirect('dashboardAD/usuarios.php');
    }
}

$q = rs_trim(rs_get('q'));
$filtroRol = rs_trim(rs_get('rol'));
$filtroEstado = rs_trim(rs_get('estado'));

$sql = 'SELECT u.id_usuario, u.id_rol, u.nombre, u.apellido, u.correo, u.telefono, u.estado, r.nombre_rol
        FROM usuario u
        LEFT JOIN rol r ON r.id_rol = u.id_rol
        WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (u.nombre LIKE ? OR u.apellido LIKE ? OR u.correo LIKE ? OR r.nombre_rol LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like, $like);
}
if ($filtroRol !== '') {
    $sql .= ' AND u.id_rol = ?';
    $params[] = (int) $filtroRol;
}
if ($filtroEstado !== '') {
    if ($filtroEstado === 'Activo') {
        $sql .= " AND (u.estado = 'Activo' OR u.estado = '1')";
    } else {
        $sql .= " AND (u.estado = 'Inactivo' OR u.estado = '0' OR u.estado IS NULL)";
    }
}
$sql .= ' ORDER BY u.id_usuario DESC';
$usuarios = rs_all($sql, $params);
$roles = rs_roles();
