<?php
/**
 * RecurSoft - Sesión y control de acceso.
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/validacion.php';
require_once __DIR__ . '/procesos.php';

function rs_usuario_sesion(): ?array
{
    return $_SESSION['rs_user'] ?? null;
}

function rs_login_usuario(array $usuario): void
{
    $empleado = rs_one('SELECT id_empleado FROM empleado WHERE id_usuario = ? LIMIT 1', [(int) $usuario['id_usuario']]);
    $_SESSION['rs_user'] = [
        'id_usuario' => (int) $usuario['id_usuario'],
        'id_rol' => (int) $usuario['id_rol'],
        'nombre' => $usuario['nombre'] ?? '',
        'apellido' => $usuario['apellido'] ?? '',
        'correo' => $usuario['correo'] ?? '',
        'nombre_rol' => $usuario['nombre_rol'] ?? '',
        'id_empleado' => $empleado['id_empleado'] ?? null,
    ];
}

function rs_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function rs_es_admin(?array $user = null): bool
{
    $user = $user ?: rs_usuario_sesion();
    if (!$user) {
        return false;
    }
    $rol = strtolower((string) ($user['nombre_rol'] ?? ''));
    return (int) ($user['id_rol'] ?? 0) === 1 || $rol === 'administrador';
}

function rs_requiere_login(): array
{
    $user = rs_usuario_sesion();
    if (!$user) {
        rs_flash('error', 'Inicie sesión para continuar.');
        rs_redirect('login.php');
    }
    return $user;
}

function rs_requiere_admin(): array
{
    $user = rs_requiere_login();
    if (!rs_es_admin($user)) {
        rs_flash('error', 'No tiene permisos para el panel de administrador.');
        rs_redirect('dashboardEM/index.php');
    }
    return $user;
}

function rs_requiere_empleado(): array
{
    $user = rs_requiere_login();
    if (rs_es_admin($user)) {
        rs_flash('error', 'Esta sección corresponde al panel del empleado.');
        rs_redirect('dashboardAD/index.php');
    }
    return $user;
}

function rs_aplicar_navbar_usuario(): void
{
    $user = rs_usuario_sesion();
    if (!$user) {
        return;
    }
    $GLOBALS['navbarUserName'] = trim(($user['nombre'] ?? '') . ' ' . ($user['apellido'] ?? ''));
    $GLOBALS['navbarUserRole'] = $user['nombre_rol'] ?? '';
}

function rs_password_hash_safe(string $plain): string
{
    return password_hash($plain, PASSWORD_BCRYPT);
}

function rs_password_verify_safe(string $plain, string $hash): bool
{
    if ($hash === '') {
        return false;
    }
    if (password_get_info($hash)['algo'] !== 0) {
        return password_verify($plain, $hash);
    }
    return hash_equals($hash, $plain);
}
