<?php
/**
 * Barra superior del Administrador.
 */
$navbarUserName = $navbarUserName ?? 'Administrador';
$navbarUserRole = $navbarUserRole ?? 'Rol administrativo';
?>
<div class="main-wrapper flex-grow-1">
<header class="navbar-rs d-flex align-items-center justify-content-between gap-2">
    <div class="d-flex align-items-center gap-2 min-w-0">
        <button class="btn-sidebar-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarRecursoft" aria-controls="sidebarRecursoft" aria-label="Abrir menú">
            <i class="bi bi-list fs-4"></i>
        </button>
        <button class="btn-sidebar-toggle d-none d-lg-inline-flex" type="button" id="btnCollapseSidebar" aria-label="Contraer menú lateral">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
        <span class="brand-name">RecurSoft</span>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="dropdown">
            <button class="btn-icon-round" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notificaciones">
                <i class="bi bi-bell"></i>
                <span class="notif-dot" aria-hidden="true"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: min(320px, 90vw);">
                <strong class="text-primary">Notificaciones</strong>
                <p class="small text-secondary mb-0 mt-2">No hay notificaciones disponibles.</p>
            </div>
        </div>

        <div class="navbar-user">
            <div class="navbar-user-meta text-end">
                <div class="name"><?php echo rs_h($navbarUserName); ?></div>
                <div class="role"><?php echo rs_h($navbarUserRole); ?></div>
            </div>
            <img src="<?php echo rs_h(RS_BASE); ?>IMG/avatar.png" alt="Avatar del usuario">
        </div>
    </div>
</header>
