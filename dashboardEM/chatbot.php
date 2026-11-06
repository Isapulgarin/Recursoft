<?php
require_once __DIR__ . '/../includes/auth.php';
rs_requiere_empleado();

$pageTitle = 'Chatbot y Asistencia';
$dashboardType = 'EM';
$activePage = 'chatbot';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/sliderEM.php';
require_once __DIR__ . '/../includes/navbarEM.php';
?>

<main class="main-content">
    <div class="page-heading section-gap">
        <h1>Chatbot y Asistencia</h1>
        <p>Canal de ayuda para el empleado. Sin respuestas automáticas todavía.</p>
    </div>

    <div class="card-rs chat-shell d-flex flex-column">
        <div class="card-header py-3">Asistente RecurSoft</div>
        <div class="p-3 flex-grow-1 d-flex flex-column">
            <div class="chat-thread flex-grow-1 d-flex align-items-center justify-content-center mb-3">
                <?php rs_empty_state('bi-robot', 'Sin mensajes', 'Cuando el asistente esté conectado, la conversación aparecerá aquí.'); ?>
            </div>
            <form class="form-visual d-flex flex-column flex-sm-row gap-2">
                <label class="visually-hidden" for="mensajeEmp">Mensaje</label>
                <input class="form-control" id="mensajeEmp" type="text" placeholder="Escriba su consulta">
                <button class="btn btn-rs" type="submit">Enviar</button>
            </form>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';
require_once __DIR__ . '/../includes/scripts.php';
