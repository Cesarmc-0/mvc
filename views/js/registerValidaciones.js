/**
 * registerValidaciones.js
 * Clase con un método por cada validación del formulario de registro.
 */

class RegisterValidaciones {

    // ── Cada método valida un campo y retorna true/false ──────────────────

    validarNombre() {
        const valor = document.getElementById('nombre').value.trim();

        if (valor === '') {
            this.mostrarError('nombre', 'El nombre es obligatorio.');
            return false;
        }
        if (valor.length < 3) {
            this.mostrarError('nombre', 'El nombre debe tener al menos 3 caracteres.');
            return false;
        }

        this.mostrarExito('nombre');
        return true;
    }

    validarTipoDocumento() {
    const valor = document.getElementById('tipo_documento_id').value;

    if (valor === '') {
        this.mostrarError('tipo_documento_id', 'El tipo de documento es obligatorio.');
        return false;
    }

    this.mostrarExito('tipo_documento_id');
    return true;
}

    validarNumeroDocumento() {
        const valor = document.getElementById('numero_documento').value.trim();
        const regex = /^[a-zA-Z0-9]+$/;

        if (valor === '') {
            this.mostrarError('numero_documento', 'El número de documento es obligatorio.');
            return false;
        }
        if (!regex.test(valor)) {
            this.mostrarError('numero_documento', 'Solo letras y números, sin espacios.');
            return false;
        }

    this.mostrarExito('numero_documento');
    return true;
}

    validarEmail() {
        const valor = document.getElementById('email').value.trim();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (valor === '') {
            this.mostrarError('email', 'El correo es obligatorio.');
            return false;
        }
        if (!regex.test(valor)) {
            this.mostrarError('email', 'Ingresa un correo válido. Ej: correo@ejemplo.com');
            return false;
        }

        this.mostrarExito('email');
        return true;
    }

    validarPassword() {
        const valor       = document.getElementById('password').value;
        const tieneMayus  = /[A-Z]/.test(valor);
        const tieneEspec  = /[!@#$%^&*(),.?":{}|<>_\-]/.test(valor);

        if (valor === '') {
            this.mostrarError('password', 'La contraseña es obligatoria.');
            return false;
        }
        if (valor.length < 8) {
            this.mostrarError('password', 'La contraseña debe tener al menos 8 caracteres.');
            return false;
        }
        if (!tieneMayus) {
            this.mostrarError('password', 'La contraseña debe tener al menos una mayúscula.');
            return false;
        }
        if (!tieneEspec) {
            this.mostrarError('password', 'La contraseña debe tener al menos un carácter especial. Ej: !@#$%');
            return false;
        }

        this.mostrarExito('password');
        return true;
    }

    validarConfirmPassword() {
        const password        = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;

        if (passwordConfirm === '') {
            this.mostrarError('password_confirm', 'Debes confirmar tu contraseña.');
            return false;
        }
        if (password !== passwordConfirm) {
            this.mostrarError('password_confirm', 'Las contraseñas no coinciden.');
            return false;
        }

        this.mostrarExito('password_confirm');
        return true;
    }

    // ── Métodos de UI: pintar error o éxito bajo el campo ─────────────────

    mostrarError(id, mensaje) {
        const input = document.getElementById(id);
        input.style.borderColor = '#c0392b'; // borde rojo

        // Busca si ya hay un mensaje de error, si no lo crea
        let errorEl = input.parentElement.querySelector('.error-msg');
        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.className = 'error-msg';
            errorEl.style.cssText = 'color:#c0392b; font-size:11px; margin-top:5px;';
            input.parentElement.appendChild(errorEl);
        }
        errorEl.textContent = mensaje;
    }

    mostrarExito(id) {
        const input = document.getElementById(id);
        input.style.borderColor = '#27ae60'; // borde verde

        // Si había un error, lo borra
        const errorEl = input.parentElement.querySelector('.error-msg');
        if (errorEl) errorEl.remove();
    }

    limpiarError(id) {
        const input = document.getElementById(id);
        input.style.borderColor = ''; // vuelve al color original

        const errorEl = input.parentElement.querySelector('.error-msg');
        if (errorEl) errorEl.remove();
    }
}

// ── Inicializar cuando el HTML esté listo ─────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    const v    = new RegisterValidaciones();
    const form = document.getElementById('registerForm');

    // Al enviar el formulario, corre todas las validaciones
form.addEventListener('submit', (e) => {
    const nombreOk    = v.validarNombre();
    const tipoOk      = v.validarTipoDocumento();      
    const documentoOk = v.validarNumeroDocumento();    
    const emailOk     = v.validarEmail();
    const passOk      = v.validarPassword();
    const confirmOk   = v.validarConfirmPassword();

    if (!nombreOk || !tipoOk || !documentoOk || !emailOk || !passOk || !confirmOk) {
        e.preventDefault();
    }
});

    // Validar campo por campo cuando el usuario sale del input (blur)
    document.getElementById('nombre').addEventListener('blur', () => v.validarNombre());
    document.getElementById('email').addEventListener('blur',  () => v.validarEmail());
    document.getElementById('password').addEventListener('blur', () => v.validarPassword());
    document.getElementById('password_confirm').addEventListener('blur', () => v.validarConfirmPassword());
    document.getElementById('tipo_documento_id').addEventListener('blur', () => v.validarTipoDocumento());
document.getElementById('numero_documento').addEventListener('blur',  () => v.validarNumeroDocumento());

    // Limpiar error mientras el usuario escribe
   ['nombre', 'tipo_documento_id', 'numero_documento', 'email', 'password', 'password_confirm'].forEach((id) => {
    document.getElementById(id).addEventListener('input', () => v.limpiarError(id));
});
});
