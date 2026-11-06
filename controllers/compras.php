<?php
require_once __DIR__ . '/_post.php';

rs_requiere_admin();

const RS_ESTADOS_COMPRA = ['Solicitada', 'Autorizada', 'Recibida', 'Anulada'];

$errores = [];
$old = [];
$abrirModal = false;
$detallesOld = [['id_recurso' => '', 'cantidad' => '', 'costo_unitario' => '']];

if (rs_post_valido('admin')) {
    $accion = rs_post('accion');
    $id = (int) rs_post('id_compra');

    if ($accion === 'guardar') {
        $old = $_POST;
        $actual = $id > 0 ? rs_registro('compra', $id) : null;
        if ($id > 0 && !$actual) {
            $errores['general'] = 'La compra que intenta editar no existe.';
        }
        $idProveedor = rs_entero_id($errores, rs_post('id_proveedor'), 'id_proveedor', 'un proveedor');
        $fecha = rs_fecha_ok($errores, rs_post('fecha_compra'), 'fecha_compra', 'compra');
        $estado = rs_opcion($errores, rs_post('estado'), RS_ESTADOS_COMPRA, 'estado', 'estado');
        $observaciones = rs_max($errores, rs_trim(rs_post('observaciones')), 'observaciones', 150, 'Las observaciones');
        $proveedor = rs_fk($errores, 'proveedor', $idProveedor, 'id_proveedor', 'proveedor');
        if ($proveedor && !rs_es_activo($proveedor['estado']) && (!$actual || (int) $actual['id_proveedor'] !== $idProveedor)) {
            $errores['id_proveedor'] = 'El proveedor seleccionado está inactivo.';
        }
        if ($fecha && $fecha > date('Y-m-d')) {
            $errores['fecha_compra'] = 'La fecha de compra no puede ser futura.';
        }

        $recursosPost = (array) rs_post('det_recurso', []);
        $cantidadesPost = (array) rs_post('det_cantidad', []);
        $costosPost = (array) rs_post('det_costo', []);
        $detalles = [];
        $detallesOld = [];
        $total = 0.0;
        foreach ($recursosPost as $i => $idRecursoTxt) {
            $cantTxt = rs_trim($cantidadesPost[$i] ?? '');
            $costoTxt = rs_trim($costosPost[$i] ?? '');
            if (rs_trim($idRecursoTxt) === '' && $cantTxt === '' && $costoTxt === '') {
                continue;
            }
            $detallesOld[] = ['id_recurso' => $idRecursoTxt, 'cantidad' => $cantTxt, 'costo_unitario' => $costoTxt];
            $fila = count($detallesOld);
            if (!ctype_digit((string) $idRecursoTxt) || !rs_registro('recurso', (int) $idRecursoTxt)) {
                $errores['detalles'] = "Fila {$fila}: debe seleccionar un recurso existente.";
                continue;
            }
            if (!preg_match('/^\d+$/', $cantTxt) || (int) $cantTxt < 1) {
                $errores['detalles'] = "Fila {$fila}: la cantidad debe ser un número entero positivo.";
                continue;
            }
            if (!is_numeric($costoTxt) || (float) $costoTxt < 0) {
                $errores['detalles'] = "Fila {$fila}: el costo unitario no puede ser negativo.";
                continue;
            }
            $cantidad = (int) $cantTxt;
            $costo = round((float) $costoTxt, 2);
            $detalles[] = [(int) $idRecursoTxt, $cantidad, $costo];
            $total += $cantidad * $costo;
        }
        if (!$detallesOld) {
            $detallesOld = [['id_recurso' => '', 'cantidad' => '', 'costo_unitario' => '']];
        }
        if (!$detalles && !isset($errores['detalles'])) {
            $errores['detalles'] = 'La compra debe tener al menos un recurso en el detalle.';
        }

        if (!$errores) {
            $pdo = rs_pdo();
            try {
                $pdo->beginTransaction();
                $totalTxt = number_format($total, 2, '.', '');
                if ($actual) {
                    rs_exec('UPDATE compra SET id_proveedor = ?, fecha_compra = ?, total = ?, estado = ?, observaciones = ? WHERE id_compra = ?',
                        [$idProveedor, $fecha, $totalTxt, $estado, $observaciones, $id]);
                    rs_exec('DELETE FROM detalle_compra WHERE id_compra = ?', [$id]);
                    $idCompra = $id;
                } else {
                    rs_exec('INSERT INTO compra (id_proveedor, fecha_compra, total, estado, observaciones) VALUES (?, ?, ?, ?, ?)',
                        [$idProveedor, $fecha, $totalTxt, $estado, $observaciones]);
                    $idCompra = (int) rs_last_id();
                }
                foreach ($detalles as [$idRecurso, $cantidad, $costo]) {
                    rs_exec('INSERT INTO detalle_compra (id_compra, id_recurso, cantidad, costo_unitario) VALUES (?, ?, ?, ?)',
                        [$idCompra, $idRecurso, $cantidad, number_format($costo, 2, '.', '')]);
                }
                $pdo->commit();
                rs_flash('ok', $actual ? 'Compra actualizada correctamente.' : 'Compra registrada correctamente.');
                rs_redirect('dashboardAD/compras.php');
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $errores['general'] = 'No fue posible guardar la compra.';
            }
        }
        $abrirModal = true;
    }

    if ($accion === 'anular' && $id > 0) {
        $row = rs_registro('compra', $id);
        if ($row && $row['estado'] !== 'Anulada') {
            rs_exec("UPDATE compra SET estado = 'Anulada' WHERE id_compra = ?", [$id]);
            rs_flash('ok', 'La compra fue anulada.');
        }
        rs_redirect('dashboardAD/compras.php');
    }
}

$q = rs_trim(rs_get('q'));
$periodo = rs_trim(rs_get('periodo'));
$filtroEstado = rs_trim(rs_get('estado'));
$sql = 'SELECT c.*, p.nombre_empresa, p.NIT,
               (SELECT COUNT(*) FROM detalle_compra d WHERE d.id_compra = c.id_compra) AS items
        FROM compra c
        INNER JOIN proveedor p ON p.id_proveedor = c.id_proveedor
        WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (p.nombre_empresa LIKE ? OR p.NIT LIKE ? OR c.id_compra = ?)';
    array_push($params, "%{$q}%", "%{$q}%", ctype_digit($q) ? (int) $q : 0);
}
if (preg_match('/^\d{4}-\d{2}$/', $periodo)) {
    $sql .= " AND DATE_FORMAT(c.fecha_compra, '%Y-%m') = ?";
    $params[] = $periodo;
}
if ($filtroEstado !== '') {
    $sql .= ' AND c.estado = ?';
    $params[] = $filtroEstado;
}
$compras = rs_all($sql . ' ORDER BY c.id_compra DESC', $params);

$detallesPorCompra = [];
if ($compras) {
    $ids = array_map(fn ($c) => (int) $c['id_compra'], $compras);
    $marcas = implode(',', array_fill(0, count($ids), '?'));
    $filas = rs_all(
        "SELECT d.*, r.codigo_interno, r.nombre_recurso, (d.cantidad * d.costo_unitario) AS subtotal
         FROM detalle_compra d INNER JOIN recurso r ON r.id_recurso = d.id_recurso
         WHERE d.id_compra IN ({$marcas}) ORDER BY d.id_detalle_compra",
        $ids
    );
    foreach ($filas as $f) {
        $detallesPorCompra[(int) $f['id_compra']][] = $f;
    }
}

$proveedoresLista = rs_all('SELECT id_proveedor, nombre_empresa, NIT, estado FROM proveedor ORDER BY nombre_empresa');
$recursosLista = rs_all('SELECT id_recurso, codigo_interno, nombre_recurso FROM recurso ORDER BY nombre_recurso');
