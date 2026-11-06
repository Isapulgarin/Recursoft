/**
 * RecurSoft - Interacciones de interfaz
 * (menús, contraseña, offcanvas, validación de formularios y modales de registro/edición)
 */
(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        initPasswordToggle();
        initVisualForms();
        initFormValidation();
        initModalForms();
        initConfirmForms();
        initAutoOpenModal();
        initMobileNavClose();
        initCollapsedSidebar();
    });

    function initPasswordToggle() {
        var button = document.getElementById("togglePassword");
        var input = document.getElementById("password");
        if (!button || !input) {
            return;
        }

        button.addEventListener("click", function () {
            var hidden = input.getAttribute("type") === "password";
            input.setAttribute("type", hidden ? "text" : "password");
            var icon = button.querySelector("i");
            if (icon) {
                icon.className = hidden ? "bi bi-eye-slash" : "bi bi-eye";
            }
            button.setAttribute(
                "aria-label",
                hidden ? "Ocultar contraseña" : "Mostrar contraseña"
            );
        });
    }

    /* Formularios sin atributo method son solo demostrativos (p. ej. chatbot). */
    function initVisualForms() {
        document.querySelectorAll(".form-visual:not([method])").forEach(function (form) {
            form.addEventListener("submit", function (event) {
                event.preventDefault();
            });
        });
    }

    /* ---------------------------------------------------------------
       Validación en el navegador
       --------------------------------------------------------------- */
    function fieldLabel(field) {
        if (field.dataset.label) {
            return field.dataset.label;
        }
        var label = field.id ? document.querySelector('label[for="' + field.id + '"]') : null;
        return label ? label.textContent.replace("*", "").trim() : "Este campo";
    }

    function errorBox(field) {
        var container = field.closest(".input-group, .password-wrap") || field;
        var box = container.nextElementSibling;
        if (!box || !box.classList.contains("campo-error")) {
            box = null;
        }
        if (!box) {
            box = document.createElement("div");
            box.className = "campo-error";
            box.dataset.js = "1";
            container.insertAdjacentElement("afterend", box);
        }
        return box;
    }

    function setError(field, message) {
        var box = errorBox(field);
        box.textContent = message;
        box.hidden = !message;
        field.classList.toggle("is-invalid", !!message);
        field.setCustomValidity(message || "");
    }

    function today() {
        var d = new Date();
        var m = String(d.getMonth() + 1).padStart(2, "0");
        var day = String(d.getDate()).padStart(2, "0");
        return d.getFullYear() + "-" + m + "-" + day;
    }

    function validateField(field) {
        if (field.disabled || field.type === "hidden" || field.type === "submit" || field.type === "button") {
            return true;
        }
        field.setCustomValidity("");
        var value = (field.value || "").trim();
        var label = fieldLabel(field);
        var message = "";
        var form = field.form;

        if (field.required && value === "") {
            message = field.tagName === "SELECT"
                ? "Debe seleccionar " + label.toLowerCase() + "."
                : "El campo " + label.toLowerCase() + " es obligatorio.";
        } else if (value !== "") {
            if (field.type === "email" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                message = "El correo no tiene un formato válido.";
            } else if (field.maxLength > 0 && value.length > field.maxLength) {
                message = label + " no puede superar " + field.maxLength + " caracteres.";
            } else if (field.minLength > 0 && value.length < field.minLength) {
                message = label + " debe tener al menos " + field.minLength + " caracteres.";
            } else if (field.type === "number") {
                var num = Number(value);
                if (isNaN(num)) {
                    message = label + " debe ser numérico.";
                } else if (field.min !== "" && num < Number(field.min)) {
                    message = label + " no puede ser menor que " + field.min + ".";
                } else if (field.step === "1" && !Number.isInteger(num)) {
                    message = label + " debe ser un número entero.";
                }
            } else if (field.pattern && !new RegExp("^(?:" + field.pattern + ")$").test(value)) {
                message = field.title || label + " no tiene un formato válido.";
            } else if (field.type === "date") {
                if (field.dataset.noFuture !== undefined && value > today()) {
                    message = "La fecha no puede ser futura.";
                }
                var afterId = field.dataset.after;
                if (!message && afterId && form) {
                    var other = form.querySelector('[name="' + afterId + '"]');
                    var strict = field.dataset.afterStrict !== undefined;
                    if (other && other.value && (strict ? value <= other.value : value < other.value)) {
                        message = field.dataset.afterMsg || "La fecha no puede ser anterior a " + fieldLabel(other).toLowerCase() + ".";
                    }
                }
            }
            if (!message && field.dataset.passwordRule !== undefined) {
                if (value.length < 8) {
                    message = "La contraseña debe tener al menos 8 caracteres.";
                } else if (!/[A-Za-z]/.test(value) || !/\d/.test(value)) {
                    message = "La contraseña debe incluir letras y números.";
                }
            }
        }

        setError(field, message);
        return message === "";
    }

    function clearErrors(form) {
        form.querySelectorAll(".is-invalid").forEach(function (f) {
            f.classList.remove("is-invalid");
            if (f.setCustomValidity) {
                f.setCustomValidity("");
            }
        });
        form.querySelectorAll(".campo-error").forEach(function (b) {
            b.hidden = true;
        });
        form.querySelectorAll(".alert-rs-error[data-form-error]").forEach(function (a) {
            a.remove();
        });
    }

    function initFormValidation() {
        document.querySelectorAll("form[data-validate]").forEach(function (form) {
            form.setAttribute("novalidate", "novalidate");
            var fields = form.querySelectorAll("input, select, textarea");

            fields.forEach(function (field) {
                var evt = field.tagName === "SELECT" || field.type === "date" ? "change" : "blur";
                field.addEventListener(evt, function () {
                    validateField(field);
                });
                field.addEventListener("input", function () {
                    if (field.classList.contains("is-invalid")) {
                        validateField(field);
                    }
                });
            });

            form.addEventListener("submit", function (event) {
                var ok = true;
                form.querySelectorAll("input, select, textarea").forEach(function (field) {
                    if (!validateField(field)) {
                        ok = false;
                    }
                });
                if (typeof form.rsExtraValidation === "function" && !form.rsExtraValidation()) {
                    ok = false;
                }
                if (!ok) {
                    event.preventDefault();
                    var first = form.querySelector(".is-invalid");
                    if (first) {
                        first.focus();
                    }
                    return;
                }
                form.querySelectorAll('button[type="submit"]').forEach(function (btn) {
                    btn.disabled = true;
                    btn.dataset.originalText = btn.innerHTML;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';
                });
            });

            form.addEventListener("reset", function () {
                clearErrors(form);
                setTimeout(function () {
                    form.dispatchEvent(new CustomEvent("rs:reset"));
                }, 0);
            });
        });
    }

    /* ---------------------------------------------------------------
       Modales de registro / edición
       Botón "nuevo":  data-bs-toggle="modal" data-rs-new
       Botón "editar": data-bs-toggle="modal" data-rs-edit='{"campo":"valor"}'
       --------------------------------------------------------------- */
    function fillForm(form, data) {
        Object.keys(data).forEach(function (name) {
            var field = form.querySelector('[name="' + name + '"]');
            if (!field) {
                return;
            }
            var value = data[name] === null || data[name] === undefined ? "" : String(data[name]);
            if (field.type === "checkbox") {
                field.checked = value === "1";
            } else {
                field.value = value;
            }
        });
    }

    function resetFormForNew(form) {
        form.reset();
        clearErrors(form);
        form.querySelectorAll("[data-rs-id]").forEach(function (f) {
            f.value = "";
        });
        form.querySelectorAll("[data-default]").forEach(function (f) {
            f.value = f.dataset.default;
        });
        form.querySelectorAll('[data-required-new]').forEach(function (f) {
            f.required = true;
        });
    }

    function initModalForms() {
        document.querySelectorAll(".modal").forEach(function (modal) {
            modal.addEventListener("show.bs.modal", function (event) {
                var trigger = event.relatedTarget;
                var form = modal.querySelector("form[data-validate]");
                if (!trigger || !form) {
                    return;
                }
                var title = modal.querySelector(".modal-title");
                if (trigger.hasAttribute("data-rs-new")) {
                    resetFormForNew(form);
                    if (title && title.dataset.titleNew) {
                        title.textContent = title.dataset.titleNew;
                    }
                    form.dispatchEvent(new CustomEvent("rs:new"));
                } else if (trigger.hasAttribute("data-rs-edit")) {
                    resetFormForNew(form);
                    var data = {};
                    try {
                        data = JSON.parse(trigger.getAttribute("data-rs-edit"));
                    } catch (e) {
                        data = {};
                    }
                    fillForm(form, data);
                    form.querySelectorAll('[data-required-new]').forEach(function (f) {
                        f.required = false;
                    });
                    if (title && title.dataset.titleEdit) {
                        title.textContent = title.dataset.titleEdit;
                    }
                    form.dispatchEvent(new CustomEvent("rs:edit", { detail: data }));
                }
            });
        });
    }

    function initConfirmForms() {
        document.querySelectorAll("form[data-confirm]").forEach(function (form) {
            form.addEventListener("submit", function (event) {
                if (!window.confirm(form.dataset.confirm)) {
                    event.preventDefault();
                }
            });
        });
    }

    /* Reabre el modal cuando el servidor devolvió errores de validación. */
    function initAutoOpenModal() {
        var marker = document.querySelector("[data-open-modal]");
        if (!marker || typeof bootstrap === "undefined") {
            return;
        }
        var modal = document.querySelector(marker.dataset.openModal);
        if (modal) {
            var form = modal.querySelector("form[data-validate]");
            if (form) {
                form.querySelectorAll('[data-required-new]').forEach(function (f) {
                    var idField = form.querySelector("[data-rs-id]");
                    f.required = !(idField && idField.value);
                });
            }
            bootstrap.Modal.getOrCreateInstance(modal).show();
        }
    }

    function initMobileNavClose() {
        var sidebar = document.getElementById("sidebarRecursoft");
        if (!sidebar || typeof bootstrap === "undefined") {
            return;
        }

        sidebar.querySelectorAll(".nav-link").forEach(function (link) {
            link.addEventListener("click", function () {
                if (window.innerWidth < 992) {
                    var instance = bootstrap.Offcanvas.getInstance(sidebar);
                    if (instance) {
                        instance.hide();
                    }
                }
            });
        });
    }

    function initCollapsedSidebar() {
        var toggle = document.getElementById("btnCollapseSidebar");
        if (!toggle) {
            return;
        }

        var stored = window.localStorage.getItem("rs-sidebar-collapsed");
        if (stored === "1" && window.innerWidth >= 992) {
            document.body.classList.add("sidebar-collapsed");
        }

        toggle.addEventListener("click", function () {
            if (window.innerWidth < 992) {
                return;
            }
            document.body.classList.toggle("sidebar-collapsed");
            window.localStorage.setItem(
                "rs-sidebar-collapsed",
                document.body.classList.contains("sidebar-collapsed") ? "1" : "0"
            );
        });
    }
})();
