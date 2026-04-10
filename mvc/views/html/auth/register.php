<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registro — Lumière Hotels</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet"
  />
  <style>
    :root {
      --font-display: 'Cormorant Garamond', serif;
      --font-body:    'DM Sans', sans-serif;
    }
    body          { font-family: var(--font-body); }
    .font-display { font-family: var(--font-display); }

    .hero-bg {
      background-image:
        linear-gradient(to bottom, rgba(26,22,16,0.65) 0%, rgba(26,22,16,0.55) 100%),
        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1800&auto=format&fit=crop&q=80');
      background-size: cover;
      background-position: center;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up { animation: fadeUp 0.7s ease forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.25s; }

    input:focus { outline: none; }
  </style>
</head>
<body class="hero-bg min-h-screen flex flex-col">

  <!-- NAV -->
  <nav class="flex items-center justify-between px-8 py-6">
    <a href="/html/home.php" class="font-display text-2xl font-semibold tracking-wide text-white">
      Lumière
    </a>
    <a href="/html/login.php" class="text-sm border border-white/50 text-white px-5 py-2 hover:bg-white hover:text-[#1a1610] transition-all duration-300">
      Iniciar sesión
    </a>
  </nav>

  <!-- REGISTER CARD -->
  <main class="flex-1 flex items-center justify-center px-4 py-12">
    <div class="animate-fade-up delay-1 w-full max-w-md bg-[#faf8f4]/95 backdrop-blur-md p-8 shadow-2xl">

      <!-- Header -->
      <div class="text-center mb-8">
        <p class="text-xs tracking-[0.3em] uppercase text-[#b08f5f] mb-2">Bienvenido</p>
        <h1 class="font-display text-4xl font-light text-[#1a1610]">Crear cuenta</h1>
      </div>

      <!-- Form -->
      <form      href="<?= SITE_URL ?>index.php?action=getFormCreateUser"
          type="submit"
          class="w-full bg-[#1a1610] text-white py-3 text-sm tracking-widest uppercase hover:bg-[#3d3422] transition-colors duration-200 mt-2"
        >

        <!-- Nombre -->
        <div>
          <label for="nombre" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
            Nombre completo
          </label>
          <input
            id="nombre"
            name="nombre"
            type="text"
            placeholder="Tu nombre"
            class="w-full px-4 py-3 border border-[#e8ddc9] bg-transparent font-body text-sm text-[#1a1610] placeholder-[#c4a97d] focus:border-[#c4a97d] transition-colors duration-200"
          />
        </div>

        <!-- Tipo de documento -->
<div>
  <label for="tipo_documento_id" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
    Tipo de documento
  </label>
  <select
    id="tipo_documento_id"
    name="tipo_documento_id"
    class="w-full px-4 py-3 border border-[#e8ddc9] bg-transparent font-body text-sm text-[#1a1610] focus:border-[#c4a97d] transition-colors duration-200"
  >
    <option value="">Selecciona un tipo</option>
    <option value="1">CC — Cédula de Ciudadanía</option>
    <option value="2">TI — Tarjeta de Identidad</option>
    <option value="3">CE — Cédula de Extranjería</option>
    <option value="4">NIT</option>
    <option value="5">PA — Pasaporte</option>
  </select>
</div>

<!-- Número de documento -->
<div>
  <label for="numero_documento" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
    Número de documento
  </label>
  <input
    id="numero_documento"
    name="numero_documento"
    type="text"
    placeholder="Tu número de documento"
    class="w-full px-4 py-3 border border-[#e8ddc9] bg-transparent font-body text-sm text-[#1a1610] placeholder-[#c4a97d] focus:border-[#c4a97d] transition-colors duration-200"
  />
</div>
        

        <!-- Email -->
        <div>
          <label for="email" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
            Correo electrónico
          </label>
          <input
            id="email"
            name="email"
            type="email"
            placeholder="correo@ejemplo.com"
            class="w-full px-4 py-3 border border-[#e8ddc9] bg-transparent font-body text-sm text-[#1a1610] placeholder-[#c4a97d] focus:border-[#c4a97d] transition-colors duration-200"
          />
        </div>

      <!-- Contraseña -->
<div class="relative">
  <label for="password" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
    Contraseña
  </label>

  <input
    id="password"
    name="password"
    type="password"
    placeholder="••••••••"
    class="w-full px-4 py-3 pr-12 border border-[#e8ddc9] bg-transparent font-body text-sm text-[#1a1610] placeholder-[#c4a97d] focus:border-[#c4a97d] transition-colors duration-200"
  />

  <button
    type="button"
    onclick="togglePassword('password')"
    class="absolute right-3 top-10 text-[#8b7355] hover:text-[#3d3422]"
  >
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
      <path stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
  </button>
</div>

      <!-- Confirmar contraseña -->
      <div class="relative">
    <label for="password_confirm" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
    Confirmar contraseña
  </label>

  <input
    id="password_confirm"
    name="password_confirm"
    type="password"
    placeholder="••••••••"
    class="w-full px-4 py-3 pr-12 border border-[#e8ddc9] bg-transparent font-body text-sm text-[#1a1610] placeholder-[#c4a97d] focus:border-[#c4a97d] transition-colors duration-200"
  />

  <button
    type="button"
    onclick="togglePassword('password_confirm')"
    class="absolute right-3 top-10 text-[#8b7355] hover:text-[#3d3422]"
  >
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
      <path stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
   </button>
      </div>
        

        <!-- Submit -->
        <button
 
          Crear cuenta
        </button>

      </form>

      <!-- Footer del form -->
      <p class="text-center text-xs text-[#b08f5f] mt-6">
        ¿Ya tienes cuenta?
        <a href="<?= SITE_URL ?>index.php?action=getFormLoginUser" class="underline underline-offset-4 hover:text-[#1a1610] transition-colors duration-200">
          Inicia sesión
        </a>
      </p>

    </div>
  </main>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>
  <script src="<?= SITE_URL ?>views/js/registerValidaciones.js"></script>
</body>
</html>