<?php
/**
 * RecurSoft - Validaciones de servidor.
 */

function rs_errores_nuevo(): array
{
    return [];
}

function rs_req(array &$errores, $valor, string $campo, string $etiqueta): string
{
    $texto = rs_trim($valor);
    if ($texto === '') {
        $errores[$campo] = "El campo {$etiqueta} es obligatorio.";
    }
    return $texto;
}

function rs_max(array &$errores, string $valor, string $campo, int $max, string $etiqueta): string
{
    if ($valor !== '' && mb_strlen($valor) > $max) {
        $errores[$campo] = "{$etiqueta} no puede superar {$max} caracteres.";
    }
    return $valor;
}

function rs_email_ok(array &$errores, string $valor, string $campo, string $etiqueta = 'El correo'): string
{
    if ($valor === '') {
        return $valor;
    }
    if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
        $errores[$campo] = "{$etiqueta} no tiene un formato válido.";
    }
    return mb_strtolower($valor);
}

function rs_telefono_ok(array &$errores, string $valor, string $campo): string
{
    if ($valor === '') {
        return $valor;
    }
    $digitos = preg_replace('/\D+/', '', $valor);
    if (strlen($digitos) < 7 || strlen($digitos) > 15) {
        $errores[$campo] = 'El teléfono debe tener entre 7 y 15 dígitos.';
        return $valor;
    }
    return $valor;
}

function rs_documento_ok(array &$errores, string $valor, string $campo, string $etiqueta = 'El documento'): string
{
    if ($valor === '') {
        return $valor;
    }
    if (!preg_match('/^[A-Za-z0-9.\-]{5,20}$/', $valor)) {
        $errores[$campo] = "{$etiqueta} debe tener entre 5 y 20 caracteres alfanuméricos.";
    }
    return $valor;
}

function rs_nit_ok(array &$errores, string $valor, string $campo): string
{
    if ($valor === '') {
        return $valor;
    }
    if (!preg_match('/^[0-9.\-]{6,30}$/', $valor)) {
        $errores[$campo] = 'El NIT debe tener entre 6 y 30 dígitos (puede incluir puntos o guion).';
    }
    return $valor;
}

function rs_entero_id(array &$errores, $valor, string $campo, string $etiqueta): int
{
    if ($valor === '' || $valor === null) {
        $errores[$campo] = "Debe seleccionar {$etiqueta}.";
        return 0;
    }
    if (!ctype_digit((string) $valor) && !is_int($valor)) {
        $errores[$campo] = "{$etiqueta} no es válido.";
        return 0;
    }
    return (int) $valor;
}

function rs_entero_min(array &$errores, $valor, string $campo, string $etiqueta, int $min = 1): int
{
    if ($valor === '' || $valor === null || !is_numeric($valor)) {
        $errores[$campo] = "{$etiqueta} debe ser un número válido.";
        return 0;
    }
    $num = (int) $valor;
    if ($num < $min) {
        $errores[$campo] = "{$etiqueta} no puede ser menor que {$min}.";
    }
    return $num;
}

function rs_decimal_min(array &$errores, $valor, string $campo, string $etiqueta, float $min = 0): ?string
{
    if ($valor === '' || $valor === null) {
        $errores[$campo] = "{$etiqueta} es obligatorio.";
        return null;
    }
    if (!is_numeric($valor)) {
        $errores[$campo] = "{$etiqueta} debe ser numérico.";
        return null;
    }
    $num = round((float) $valor, 2);
    if ($num < $min) {
        $errores[$campo] = "{$etiqueta} no puede ser negativo.";
    }
    return number_format($num, 2, '.', '');
}

function rs_fecha_ok(array &$errores, $valor, string $campo, string $etiqueta, bool $obligatoria = true): ?string
{
    $valor = rs_trim($valor);
    if ($valor === '') {
        if ($obligatoria) {
            $errores[$campo] = "La fecha de {$etiqueta} es obligatoria.";
        }
        return null;
    }
    $dt = DateTime::createFromFormat('Y-m-d', $valor);
    $ok = $dt && $dt->format('Y-m-d') === $valor;
    if (!$ok) {
        $errores[$campo] = "La fecha de {$etiqueta} no es válida.";
        return null;
    }
    return $valor;
}

function rs_fechas_orden(array &$errores, ?string $inicio, ?string $fin, string $campoFin, string $mensaje): void
{
    if ($inicio && $fin && $fin < $inicio) {
        $errores[$campoFin] = $mensaje;
    }
}

function rs_password_ok(array &$errores, $valor, string $campo, bool $obligatoria): string
{
    $valor = (string) $valor;
    if ($valor === '') {
        if ($obligatoria) {
            $errores[$campo] = 'La contraseña es obligatoria.';
        }
        return $valor;
    }
    if (mb_strlen($valor) < 8) {
        $errores[$campo] = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif (!preg_match('/[A-Za-z]/', $valor) || !preg_match('/\d/', $valor)) {
        $errores[$campo] = 'La contraseña debe incluir letras y números.';
    }
    return $valor;
}

function rs_opcion(array &$errores, $valor, array $permitidas, string $campo, string $etiqueta): string
{
    $valor = rs_trim($valor);
    if ($valor === '' || !in_array($valor, $permitidas, true)) {
        $errores[$campo] = "Seleccione un valor válido para {$etiqueta}.";
        return '';
    }
    return $valor;
}

function rs_unico(array &$errores, string $tabla, string $columna, $valor, string $campo, string $mensaje, $excluirId = null, string $columnaId = ''): void
{
    if ($valor === '' || isset($errores[$campo])) {
        return;
    }
    if (rs_existe($tabla, $columna, $valor, $excluirId, $columnaId)) {
        $errores[$campo] = $mensaje;
    }
}

function rs_fk(array &$errores, string $tabla, $id, string $campo, string $etiqueta): ?array
{
    if (isset($errores[$campo]) || !$id) {
        return null;
    }
    $row = rs_registro($tabla, $id);
    if (!$row) {
        $errores[$campo] = "El {$etiqueta} seleccionado no existe.";
        return null;
    }
    return $row;
}

function rs_error_campo(array $errores, string $campo): string
{
    if (empty($errores[$campo])) {
        return '';
    }
    return '<div class="campo-error">' . rs_h($errores[$campo]) . '</div>';
}

function rs_invalid(array $errores, string $campo): string
{
    return empty($errores[$campo]) ? '' : ' is-invalid';
}
