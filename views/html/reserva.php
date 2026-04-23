<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if (!isset($_SESSION['usuario'])) {
    header('Location: ' . SITE_URL . 'index.php?action=getFormLogin');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservar — Lumière Hotels</title>
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

    select:focus, input:focus { outline: none; }
  </style>
</head>
<body class="hero-bg min-h-screen flex flex-col">

  <!-- NAV -->
  <nav class="flex items-center justify-between px-8 py-6">
    <a href="<?= SITE_URL ?>index.php" class="font-display text-2xl font-semibold tracking-wide text-white">
      Lumière
    </a>
    <div class="flex items-center gap-4">
      <span class="text-white text-sm">
        Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
      </span>
      <a href="<?= SITE_URL ?>index.php?action=logout" class="text-sm border border-white/50 text-white px-5 py-2 hover:bg-white hover:text-[#1a1610] transition-all duration-300">
        Cerrar sesión
      </a>
    </div>
  </nav>

  <!-- RESERVA CARD -->
  <main class="flex-1 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg bg-[#faf8f4]/95 backdrop-blur-md p-8 shadow-2xl">

      <!-- Header -->
      <div class="text-center mb-8">
        <p class="text-xs tracking-[0.3em] uppercase text-[#b08f5f] mb-2">Lumière Hotels</p>
        <h1 class="font-display text-4xl font-light text-[#1a1610]">Nueva Reserva</h1>
      </div>

      <!-- Mensajes -->
      <?php if (isset($_SESSION['resultado'])): ?>
        <?php foreach ($_SESSION['resultado'] as $key => $msg): ?>
          <?php if ($key === 'success'): ?>
            <div class="bg-green-100 text-green-800 text-sm px-4 py-3 mb-4 border border-green-300">
              ✅ <?= $msg ?>
            </div>
          <?php else: ?>
            <div class="bg-red-100 text-red-800 text-sm px-4 py-3 mb-2 border border-red-300">
              ❌ <?= $msg ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
        <?php unset($_SESSION['resultado']); ?>
      <?php endif; ?>

      <!-- Form -->
      <form id="reservaForm" action="<?= SITE_URL ?>index.php?action=createReserva" method="POST" class="space-y-5">

        <!-- Habitación -->
        <div>
          <label for="id_habitacion" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
            Habitación
          </label>
          <select
            id="id_habitacion"
            name="id_habitacion"
            class="w-full px-4 py-3 border border-[#e8ddc9] bg-[#faf8f4] text-sm text-[#1a1610] focus:border-[#c4a97d] transition-colors duration-200"
          >
            <option value="">Selecciona una habitación</option>
            <?php foreach ($habitaciones as $hab): ?>
              <option value="<?= $hab['id'] ?>">
                N° <?= $hab['num_habitacion'] ?> — <?= $hab['categoria'] ?> — <?= $hab['max_personas'] ?> personas — $<?= number_format($hab['precio'], 2) ?>/noche
              </option>
            <?php endforeach; ?>
          </select>
          <div id="error-id_habitacion"></div>
        </div>

        <!-- Fechas -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="fecha_inicio" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
              Fecha-Inicio
            </label>
            <input
              id="fecha_inicio"
              name="fecha_inicio"
              type="date"
              min="<?= date('Y-m-d') ?>"
              class="w-full px-4 py-3 border border-[#e8ddc9] bg-transparent text-sm text-[#1a1610] focus:border-[#c4a97d] transition-colors duration-200"
            />
            <div id="error-fecha_inicio"></div>
          </div>

          <div>
            <label for="fecha_fin" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
              Fecha-Fin
            </label>
            <input
              id="fecha_fin"
              name="fecha_fin"
              type="date"
              min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
              class="w-full px-4 py-3 border border-[#e8ddc9] bg-transparent text-sm text-[#1a1610] focus:border-[#c4a97d] transition-colors duration-200"
            />
            <div id="error-fecha_fin"></div>
          </div>
        </div>

        <!-- Número de personas -->
        <div>
          <label for="num_personas" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
            Número de personas
          </label>
          <input
            id="num_personas"
            name="num_personas"
            type="number"
            min="1"
            max="10"
            placeholder="Ej: 2"
            class="w-full px-4 py-3 border border-[#e8ddc9] bg-transparent text-sm text-[#1a1610] placeholder-[#c4a97d] focus:border-[#c4a97d] transition-colors duration-200"
          />
          <div id="error-num_personas"></div>
        </div>

        <!-- Método de pago -->
        <div>
          <label for="id_metodo_pago" class="block text-xs font-medium tracking-widest uppercase text-[#3d3422] mb-2">
            Método de pago
          </label>
          <select
            id="id_metodo_pago"
            name="id_metodo_pago"
            class="w-full px-4 py-3 border border-[#e8ddc9] bg-[#faf8f4] text-sm text-[#1a1610] focus:border-[#c4a97d] transition-colors duration-200"
          >
            <option value="">Selecciona un método</option>
            <?php foreach ($metodosPago as $metodo): ?>
              <option value="<?= $metodo['id'] ?>"><?= $metodo['nombre'] ?></option>
            <?php endforeach; ?>
          </select>
          <div id="error-id_metodo_pago"></div>
        </div>

        <!-- Submit -->
        <button
          type="submit"
          class="w-full bg-[#1a1610] text-white py-3 text-sm tracking-widest uppercase hover:bg-[#3d3422] transition-colors duration-200 mt-2"
        >
          Confirmar Reserva
        </button>

      </form>

      <!-- Volver -->
      <p class="text-center text-xs text-[#b08f5f] mt-6">
        <a href="<?= SITE_URL ?>index.php" class="underline underline-offset-4 hover:text-[#1a1610] transition-colors duration-200">
          ← Volver al inicio
        </a>
      </p>

    </div>
  </main>

</body>
</html>