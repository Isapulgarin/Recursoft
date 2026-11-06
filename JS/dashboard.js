/**
 * RecurSoft - Comportamiento de módulos específicos.
 */
(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        initDetalleCompra();
    });

    function dinero(valor) {
        return "$ " + valor.toLocaleString("es-CO", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    /* Detalle de compras: agregar/quitar filas y calcular subtotales y total. */
    function initDetalleCompra() {
        var form = document.getElementById("formCompra");
        var body = document.getElementById("detalleCompraBody");
        var tpl = document.getElementById("tplFilaDetalle");
        if (!form || !body || !tpl) {
            return;
        }

        function agregarFila(datos) {
            var fila = tpl.content.firstElementChild.cloneNode(true);
            if (datos) {
                fila.querySelector('[name="det_recurso[]"]').value = datos.id_recurso || "";
                fila.querySelector('[name="det_cantidad[]"]').value = datos.cantidad || "";
                fila.querySelector('[name="det_costo[]"]').value = datos.costo_unitario || "";
            }
            body.appendChild(fila);
            return fila;
        }

        function recalcular() {
            var total = 0;
            body.querySelectorAll(".fila-detalle").forEach(function (fila) {
                var cant = parseInt(fila.querySelector('[name="det_cantidad[]"]').value, 10) || 0;
                var costo = parseFloat(fila.querySelector('[name="det_costo[]"]').value) || 0;
                var sub = cant > 0 && costo >= 0 ? cant * costo : 0;
                fila.querySelector(".subtotal").textContent = dinero(sub);
                total += sub;
            });
            document.getElementById("totalCompra").textContent = dinero(total);
        }

        function reiniciar(detalles) {
            body.innerHTML = "";
            (detalles && detalles.length ? detalles : [null]).forEach(agregarFila);
            recalcular();
        }

        document.getElementById("btnAgregarDetalle").addEventListener("click", function () {
            agregarFila(null).querySelector("select").focus();
        });

        body.addEventListener("click", function (e) {
            var btn = e.target.closest(".btn-quitar-detalle");
            if (!btn) {
                return;
            }
            if (body.querySelectorAll(".fila-detalle").length > 1) {
                btn.closest(".fila-detalle").remove();
            } else {
                btn.closest(".fila-detalle").querySelectorAll("input, select").forEach(function (f) {
                    f.value = "";
                });
            }
            recalcular();
        });

        body.addEventListener("input", recalcular);
        body.addEventListener("change", recalcular);
        form.addEventListener("rs:new", function () { reiniciar(null); });
        form.addEventListener("rs:reset", function () { reiniciar(null); });
        form.addEventListener("rs:edit", function (e) { reiniciar(e.detail.detalles || []); });

        form.rsExtraValidation = function () {
            var error = document.getElementById("errorDetalles");
            var filas = body.querySelectorAll(".fila-detalle").length;
            error.hidden = filas > 0;
            error.textContent = filas > 0 ? "" : "La compra debe tener al menos un recurso en el detalle.";
            return filas > 0;
        };

        recalcular();
    }
})();
