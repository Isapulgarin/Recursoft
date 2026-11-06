# RecurSoft

Sistema de Gestión de Recursos Institucionales. Incluye la **página pública adaptada** y los **paneles de administrador y empleado**. Esta entrega es de interfaz (PHP para vistas, HTML5, CSS3, Bootstrap 5). No hay autenticación real ni conexión a MySQL.

## Cómo ejecutarlo en XAMPP

1. Instale [XAMPP](https://www.apachefriends.org/) e inicie **Apache**.
2. Coloque la carpeta `RECURSOFT` en `C:\xampp\htdocs\`.
3. Abra:
   - Inicio público: `http://localhost/RECURSOFT/index.php`
   - Inicio de sesión: `http://localhost/RECURSOFT/login.php`
   - Panel administrador: `http://localhost/RECURSOFT/dashboardAD/index.php`
   - Panel empleado: `http://localhost/RECURSOFT/dashboardEM/index.php`

Los botones y formularios son visuales: no validan credenciales ni guardan registros.

## Estructura

- `index.php` página pública (frontend adaptado)
- `login.php` acceso al sistema
- `config/` preparación de rutas (sin conexión a BD)
- `includes/` cabecera, navegación pública, sidebars, pie y scripts
- `CSS/` paleta institucional, landing y componentes
- `JS/` interacción visual
- `IMG/` logotipo y avatar
- `dashboardAD/` módulos del administrador
- `dashboardEM/` módulos del empleado
