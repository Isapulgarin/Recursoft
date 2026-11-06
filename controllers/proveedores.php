<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();

$errores = [];
$old = [];
$abrirModal = false;

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_proveedor');

    if ($accion === 'guardar') {
        $old = $_POST;
        $nombre = rs_max($errores, rs_req($errores, rs_post('nombre_empresa'), 'nombre_empresa', 'nombre de la empresa'), 'nombre_empresa', 100, 'El nombre de la empresa');
        $nit = rs_nit_ok($errores, rs_max($errores, rs_req($errores, rs_post('NIT'), 'NIT', 'NIT'), 'NIT', 30, 'El NIT'), 'NIT');
        $contacto = rs_max($errores, rs_trim(rs_post('contacto')), 'contacto', 100, 'El contacto');
        $telefono = rs_telefono_ok($errores, rs_max($errores, rs_trim(rs_post('telefono')), 'telefono', 20, 'El teléfono'), 'telefono');
        $correo = rs_email_ok($errores, rs_max($errores, rs_trim(rs_post('correo')), 'correo', 100, 'El correo'), 'correo');
        $direccion = rs_max($errores, rs_trim(rs_post('direccion')), 'direccion', 150, 'La dirección');
        $estado = rs_opcion($errores, rs_post('estado'), ['Activo', 'Inactivo'], 'estado', 'estado');
        if ($id > 0 && !rs_registro('proveedor', $id)) {
            $errores['general'] = 'El proveedor que intenta editar no existe.';
        }
        rs_unico($errores, 'proveedor', 'NIT', $nit, 'NIT', 'Este NIT ya se encuentra registrado.', $id ?: null);

        if (!$errores) {
            try {
                $datos = [$nombre, $nit, $contacto, $telefono, $correo, $direccion, $estado];
                if ($id > 0) {
                    $datos[] = $id;
                    rs_exec('UPDATE proveedor SET nombre_empresa = ?, NIT = ?, contacto = ?, telefono = ?, correo = ?, direccion = ?, estado = ? WHERE id_proveedor = ?', $datos);
                    rs_flash('ok', 'Proveedor actualizado correctamente.');
                } else {
                    rs_exec('INSERT INTO proveedor (nombre_empresa, NIT, contacto, telefono, correo, direccion, estado) VALUES (?, ?, ?, ?, ?, ?, ?)', $datos);
                    rs_flash('ok', 'Proveedor registrado correctamente.');
                }
                rs_redirect('dashboardAD/proveedores.php');
            } catch (PDOException $e) {
                $errores['general'] = 'No fue posible guardar el proveedor.';
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'estado' && $id > 0) {
        $row = rs_registro('proveedor', $id);
        if ($row) {
            rs_exec('UPDATE proveedor SET estado = ? WHERE id_proveedor = ?', [rs_estado_opuesto($row['estado']), $id]);
            rs_flash('ok', 'El estado del proveedor se actualizó.');
        }
        rs_redirect('dashboardAD/proveedores.php');
    }

    if ($accion === 'eliminar' && $id > 0) {
        if (rs_existe('compra', 'id_proveedor', $id)) {
            rs_flash('error', 'No se puede eliminar porque este proveedor tiene compras registradas. Puede inactivarlo.');
        } else {
            try {
                rs_exec('DELETE FROM proveedor WHERE id_proveedor = ?', [$id]);
                rs_flash('ok', 'Proveedor eliminado.');
            } catch (PDOException $e) {
                rs_flash('error', 'No se puede eliminar porque este registro tiene información relacionada.');
            }
        }
        rs_redirect('dashboardAD/proveedores.php');
    }
}

$q = rs_trim(rs_get('q'));
$filtroEstado = rs_trim(rs_get('estado'));
$sql = 'SELECT * FROM proveedor WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (nombre_empresa LIKE ? OR NIT LIKE ? OR contacto LIKE ?)';
    array_push($params, "%{$q}%", "%{$q}%", "%{$q}%");
}
if ($filtroEstado !== '') {
    $sql .= ' AND estado = ?';
    $params[] = $filtroEstado;
}
$sql .= ' ORDER BY nombre_empresa';
$proveedores = rs_all($sql, $params);
