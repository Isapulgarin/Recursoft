<?php
require_once __DIR__ . '/../includes/auth.php';

function rs_post_valido(string $rol = 'admin'): bool
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }
    if ($rol === 'admin') {
        rs_requiere_admin();
    } elseif ($rol === 'empleado') {
        rs_requiere_empleado();
    } else {
        rs_requiere_login();
    }
    if (!rs_csrf_ok()) {
        rs_flash('error', 'La sesión del formulario expiró. Vuelva a intentar.');
        return false;
    }
    return true;
}
