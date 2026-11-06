<?php
require_once __DIR__ . '/../includes/auth.php';

$accion = rs_post('accion');

if ($accion === 'login') {
    if (!rs_csrf_ok()) {
        rs_flash('error', 'La sesión del formulario expiró. Intente de nuevo.');
        rs_redirect('login.php');
    }

    $errores = [];
    $correo = rs_email_ok($errores, rs_req($errores, rs_post('usuario'), 'usuario', 'correo'), 'usuario');
    $password = rs_trim(rs_post('password'));
    if ($password === '') {
        $errores['password'] = 'La contraseña es obligatoria.';
    }

    if (!$errores) {
        $usuario = rs_one(
            'SELECT u.id_usuario, u.id_rol, u.nombre, u.apellido, u.correo, u.telefono, u.estado,
                    u.`contraseña` AS contrasena, r.nombre_rol
             FROM usuario u
             LEFT JOIN rol r ON r.id_rol = u.id_rol
             WHERE u.correo = ?
             LIMIT 1',
            [$correo]
        );

        if (!$usuario || !rs_password_verify_safe($password, (string) $usuario['contrasena'])) {
            $errores['usuario'] = 'Correo o contraseña incorrectos.';
        } elseif (!rs_es_activo($usuario['estado'])) {
            $errores['usuario'] = 'La cuenta está inactiva. Contacte al administrador.';
        } else {
            rs_login_usuario($usuario);
            rs_flash('ok', 'Sesión iniciada correctamente.');
            if (rs_es_admin()) {
                rs_redirect('dashboardAD/index.php');
            }
            rs_redirect('dashboardEM/index.php');
        }
    }

    $_SESSION['rs_login_errores'] = $errores;
    $_SESSION['rs_login_old'] = ['usuario' => rs_post('usuario')];
    rs_redirect('login.php');
}

if ($accion === 'logout' || ($_GET['accion'] ?? '') === 'logout') {
    rs_logout();
    session_start();
    rs_flash('ok', 'Sesión cerrada.');
    rs_redirect('login.php');
}

rs_redirect('login.php');
