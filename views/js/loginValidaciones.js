/**
 * loginValidaciones.js
 * Validaciones del formulario de login — Lumière Hotels
 *
 * Patrón: Reglas declarativas por campo.
 * Cada campo define sus propias reglas; el Validator las ejecuta.
 */

// ─── 1. REGLAS REUTILIZABLES ────────────────────────────────────────────────
const Rules = {
  required: (value) => value.trim() !== '',
  email:    (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()),
  minLen:   (min)  => (value) => value.trim().length >= min,
};

// ─── 2. CONFIGURACIÓN DEL FORMULARIO ────────────────────────────────────────
// Solo defines QUÉ validar y QUÉ mensaje mostrar. Nada más.
const loginConfig = {
  formId: 'loginForm',
  fields: [
    {
      id:    'email',
      rules: [
        { fn: Rules.required, msg: 'El correo electrónico es obligatorio.' },
        { fn: Rules.email,    msg: 'Ingresa un correo electrónico válido.' },
      ],
    },
    {
      id:    'password',
      rules: [
        { fn: Rules.required,    msg: 'La contraseña es obligatoria.' },
        { fn: Rules.minLen(6),   msg: 'La contraseña debe tener al menos 6 caracteres.' },
      ],
    },
  ],
};

// ─── 3. MOTOR DE VALIDACIÓN ──────────────────────────────────────────────────
class Validator {
  constructor(config) {
    this.config = config;
    this.form   = document.getElementById(config.formId);
    if (!this.form) return;
    this._init();
  }

  _init() {
    // Validar al enviar
    this.form.addEventListener('submit', (e) => {
      const isValid = this._validateAll();
      if (!isValid) e.preventDefault();
    });

    // Validar campo a campo al perder foco (blur)
    this.config.fields.forEach(({ id }) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('blur',  () => this._validateField(id));
        input.addEventListener('input', () => this._clearError(id));
      }
    });
  }

  _validateAll() {
    let isValid = true;
    this.config.fields.forEach(({ id }) => {
      const fieldOk = this._validateField(id);
      if (!fieldOk) isValid = false;
    });
    return isValid;
  }

  _validateField(id) {
    const fieldConfig = this.config.fields.find((f) => f.id === id);
    const input       = document.getElementById(id);
    if (!fieldConfig || !input) return true;

    // Ejecutar cada regla en orden
    for (const rule of fieldConfig.rules) {
      if (!rule.fn(input.value)) {
        this._showError(id, rule.msg);
        return false;
      }
    }

    this._showSuccess(id);
    return true;
  }

  // ─── UI: mostrar / limpiar errores ─────────────────────────────────────────
  _showError(id, message) {
    const input = document.getElementById(id);
    const wrap  = input.closest('div');

    input.style.borderColor = '#c0392b';

    let errorEl = wrap.querySelector('.field-error');
    if (!errorEl) {
      errorEl = document.createElement('p');
      errorEl.className = 'field-error';
      errorEl.style.cssText =
        'margin-top:6px; font-size:11px; color:#c0392b; letter-spacing:0.05em;';
      wrap.appendChild(errorEl);
    }
    errorEl.textContent = message;
  }

  _showSuccess(id) {
    const input = document.getElementById(id);
    const wrap  = input.closest('div');

    input.style.borderColor = '#7a9e7e';
    const errorEl = wrap.querySelector('.field-error');
    if (errorEl) errorEl.remove();
  }

  _clearError(id) {
    const input = document.getElementById(id);
    const wrap  = input.closest('div');

    input.style.borderColor = '';
    const errorEl = wrap.querySelector('.field-error');
    if (errorEl) errorEl.remove();
  }
}

// ─── 4. INICIALIZAR ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  new Validator(loginConfig);
});
