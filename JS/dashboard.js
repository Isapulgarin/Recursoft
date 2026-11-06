/**
 * RecurSoft - Comportamiento visual de dashboards
 * Sin datos ni gráficos reales: solo contenedores y estados vacíos.
 */
(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var searchInputs = document.querySelectorAll("[data-table-search]");
        searchInputs.forEach(function (input) {
            input.addEventListener("input", function () {
                /* Reservado para filtrado cuando exista backend */
            });
        });
    });
})();
