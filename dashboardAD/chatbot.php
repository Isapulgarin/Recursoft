<?php
require_once __DIR__ . '/../includes/auth.php';
rs_requiere_admin();

$pageTitle = 'Chatbot y Asistencia';
$dashboardType = 'AD';
$activePage = 'chatbot';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/slider.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Chatbot y Asistencia</h1>
        <p>Interfaz de asistencia institucional. No hay motor conversacional en esta etapa.</p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card-rs h-100">
                <div class="card-header py-3">Conversaciones</div>
                <?php rs_empty_state('bi-chat-left-text', 'Sin conversaciones', 'No hay registros disponibles.'); ?>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card-rs chat-shell d-flex flex-column">
                <div class="card-header py-3">Asistente RecurSoft</div>
                <div class="p-3 flex-grow-1 d-flex flex-column">
                    <div class="chat-thread flex-grow-1 d-flex align-items-center justify-content-center mb-3">
                        <?php rs_empty_state('bi-robot', 'Sin mensajes', 'El historial de asistencia aparecerá aquí.'); ?>
                    </div>
                    <form class="form-visual d-flex flex-column flex-sm-row gap-2">
                        <label class="visually-hidden" for="mensajeAdmin">Mensaje</label>
                        <input class="form-control" id="mensajeAdmin" type="text" placeholder="Escriba una consulta">
                        <button class="btn btn-rs" type="submit">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
