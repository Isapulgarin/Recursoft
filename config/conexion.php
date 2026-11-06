<?php
/**
 * RecurSoft - Rutas, sesión y conexión PDO a MySQL.
 */

if (!defined('RS_ROOT')) {
    define('RS_ROOT', dirname(__DIR__));
}

if (!defined('RS_BASE')) {
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT'])
        ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))
        : '';
    $projectRoot = str_replace('\\', '/', realpath(RS_ROOT));
    $basePath = '/';

    if ($documentRoot && $projectRoot && strpos($projectRoot, $documentRoot) === 0) {
        $relative = substr($projectRoot, strlen($documentRoot));
        $basePath = rtrim(str_replace('\\', '/', $relative), '/') . '/';
        if ($basePath === '/') {
            $basePath = '/';
        }
    }

    define('RS_BASE', $basePath === '//' ? '/' : $basePath);
}

if (!defined('RS_DB_HOST')) {
    define('RS_DB_HOST', 'localhost');
    define('RS_DB_NAME', 'recursoft');
    define('RS_DB_USER', 'root');
    define('RS_DB_PASS', '');
    define('RS_DB_CHARSET', 'utf8mb4');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * Devuelve la conexión PDO única de la aplicación.
 */
function rs_pdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . RS_DB_HOST . ';dbname=' . RS_DB_NAME . ';charset=' . RS_DB_CHARSET;

    try {
        $pdo = new PDO($dsn, RS_DB_USER, RS_DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        exit('No fue posible conectar con la base de datos recursoft. Verifique que MySQL esté activo.');
    }

    return $pdo;
}
