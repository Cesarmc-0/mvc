document.addEventListener('DOMContentLoaded', () => {

    const formulario = document.getElementById('reservaForm')
                    || document.getElementById('updateReservaForm');
    if (!formulario) return;

    function mostrarError(id, mensaje) {
        const campo = document.getElementById(id);
        if (!campo) return;
        campo.style.borderColor = '#c0392b';
        let errorEl = campo.parentElement.querySelector('.error-campo');
        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.className = 'error-campo';
            errorEl.style.cssText = 'margin-top:4px; font-size:11px; color:#c0392b; letter-spacing:0.05em;';
            campo.parentElement.appendChild(errorEl);
        }
        errorEl.textContent = mensaje;
    }

    function mostrarExito(id) {
        const campo = document.getElementById(id);
        if (!campo) return;
        campo.style.borderColor = '#27ae60';
        const errorEl = campo.parentElement.querySelector('.error-campo');
        if (errorEl) errorEl.remove();
    }

    function limpiarError(id) {
        const campo = document.getElementById(id);
        if (!campo) return;
        campo.style.borderColor = '';
        const errorEl = campo.parentElement.querySelector('.error-campo');
        if (errorEl) errorEl.remove();
    }

    function validarCampo(id) {
        const campo = document.getElementById(id);
        if (!campo) return true;
        const valor = campo.value.trim();

        switch (id) {
            case 'fecha_inicio':
                if (!valor) { mostrarError(id, 'La fecha de inicio es obligatoria.'); return false; }
                if (valor < new Date().toISOString().split('T')[0]) { mostrarError(id, 'La fecha de inicio no puede ser en el pasado.'); return false; }
                break;
            case 'fecha_fin':
                if (!valor) { mostrarError(id, 'La fecha de fin es obligatoria.'); return false; }
                const fechaInicio = document.getElementById('fecha_inicio')?.value;
                if (fechaInicio && valor <= fechaInicio) { mostrarError(id, 'La fecha de fin debe ser mayor a la de inicio.'); return false; }
                break;
            case 'id_categoria':
                if (!valor) { mostrarError(id, 'Selecciona un tipo de habitación.'); return false; }
                break;
            case 'id_habitacion':
                if (!valor) { mostrarError(id, 'Selecciona una habitación.'); return false; }
                break;
            case 'num_personas':
                if (!valor || parseInt(valor) < 1) { mostrarError(id, 'Ingresa al menos 1 persona.'); return false; }
                break;
            case 'id_metodo_pago':
                if (!valor) { mostrarError(id, 'Selecciona un método de pago.'); return false; }
                break;
        }
        mostrarExito(id);
        return true;
    }

    const campos = ['fecha_inicio', 'fecha_fin', 'id_categoria', 'id_habitacion', 'num_personas', 'id_metodo_pago'];

    campos.forEach(id => {
        const campo = document.getElementById(id);
        if (campo) {
            campo.addEventListener('blur',   () => validarCampo(id));
            campo.addEventListener('change', () => limpiarError(id));
        }
    });

    formulario.addEventListener('submit', (e) => {
        const esValido = campos.every(id => validarCampo(id));
        if (!esValido) e.preventDefault();
    });
});
