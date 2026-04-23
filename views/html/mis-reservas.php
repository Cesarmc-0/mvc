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
  <title>Mis Reservas — Lumière Hotels</title>
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
    body          { font-family: var(--font-body); background-color: #f5f0e8; }
    .font-display { font-family: var(--font-display); }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- NAV -->
  <nav class="flex items-center justify-between px-8 py-5 bg-[#1a1610]">
    <a href="<?= SITE_URL ?>index.php" class="font-display text-2xl font-semibold tracking-wide text-white">
      Lumière
    </a>
    <div class="flex items-center gap-4">
      <span class="text-white/70 text-sm">
        <a>Tus reservas, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?> </a>
      </span>
      <a href="<?= SITE_URL ?>index.php?action=getFormCreateReserva"
         class="text-sm border border-white/50 text-white px-5 py-2 hover:bg-white hover:text-[#1a1610] transition-all duration-300">
        Nueva reserva
      </a>
      <a href="<?= SITE_URL ?>index.php?action=logout"
         class="text-sm border border-white/30 text-white/70 px-5 py-2 hover:bg-white hover:text-[#1a1610] transition-all duration-300">
        Cerrar sesión
      </a>
    </div>
  </nav>

  <!-- CONTENIDO -->
  <main class="flex-1 px-6 py-10 max-w-5xl mx-auto w-full">

    <!-- Header -->
    <div class="mb-8">
      <p class="text-xs tracking-[0.3em] uppercase text-[#b08f5f] mb-1">Panel</p>
      <h1 class="font-display text-4xl font-light text-[#1a1610]">Mis Reservas</h1>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_SESSION['resultado'])): ?>
      <?php foreach ($_SESSION['resultado'] as $key => $msg): ?>
        <?php if ($key === 'success'): ?>
          <div class="bg-green-100 text-green-800 text-sm px-4 py-3 mb-6 border border-green-300">
            ✅ <?= $msg ?>
          </div>
        <?php else: ?>
          <div class="bg-red-100 text-red-800 text-sm px-4 py-3 mb-6 border border-red-300">
            ❌ <?= $msg ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php unset($_SESSION['resultado']); ?>
    <?php endif; ?>

    <!-- Sin reservas -->
    <?php if (empty($reservas)): ?>
      <div class="bg-white border border-[#e8ddc9] px-8 py-12 text-center">
        <p class="text-[#b08f5f] font-display text-2xl mb-2">No tienes reservas aún</p>
        <p class="text-sm text-[#8b7355] mb-6">Explora nuestras habitaciones y haz tu primera reserva.</p>
        <a href="<?= SITE_URL ?>index.php?action=getFormCreateReserva"
           class="inline-block bg-[#1a1610] text-white px-8 py-3 text-sm tracking-widest uppercase hover:bg-[#3d3422] transition-colors duration-200">
          Reservar ahora
        </a>
      </div>

    <!-- Tabla de reservas -->
    <?php else: ?>
      <div class="bg-white border border-[#e8ddc9] overflow-x-auto">
        <table class="w-full text-sm text-[#1a1610]">
          <thead class="bg-[#1a1610] text-white text-xs tracking-widest uppercase">
            <tr>
              <th class="px-6 py-4 text-left">#</th>
              <th class="px-6 py-4 text-left">Habitación</th>
              <th class="px-6 py-4 text-left">Categoría</th>
              <th class="px-6 py-4 text-left">Fecha-Inicio</th>
              <th class="px-6 py-4 text-left">Fecha-Fin</th>
              <th class="px-6 py-4 text-left">Personas</th>
              <th class="px-6 py-4 text-left">Precio</th>
              <th class="px-6 py-4 text-left">Estado</th>
              <th class="px-6 py-4 text-left">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#e8ddc9]">
            <?php foreach ($reservas as $reserva): ?>
              <tr class="hover:bg-[#faf8f4] transition-colors duration-150">
                <td class="px-6 py-4 text-[#b08f5f]"><?= $reserva['id'] ?></td>
                <td class="px-6 py-4 font-medium">N° <?= $reserva['num_habitacion'] ?></td>
                <td class="px-6 py-4"><?= $reserva['categoria'] ?></td>
                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($reserva['fecha_inicio'])) ?></td>
                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($reserva['fecha_fin'])) ?></td>
                <td class="px-6 py-4 text-center"><?= $reserva['num_personas'] ?></td>
                <td class="px-6 py-4">$<?= number_format($reserva['precio'], 0, ',', '.') ?></td>
                <td class="px-6 py-4">
                  <?php
                    $colores = [
                      'pendiente'  => 'bg-yellow-100 text-yellow-800',
                      'confirmada' => 'bg-green-100  text-green-800',
                      'cancelada'  => 'bg-red-100    text-red-800',
                      'activo'     => 'bg-blue-100   text-blue-800',
                    ];
                    $color = $colores[strtolower($reserva['estado'])] ?? 'bg-gray-100 text-gray-800';
                  ?>
                  <span class="px-3 py-1 text-xs rounded-full <?= $color ?>">
                    <?= ucfirst($reserva['estado']) ?>
                  </span>
                </td>
                <td class="px-6 py-4">
                  <?php if (strtolower($reserva['estado']) !== 'cancelada'): ?>
                    <a href="<?= SITE_URL ?>index.php?action=cancelarReserva&id=<?= $reserva['id'] ?>"
                       onclick="return confirm('¿Seguro que deseas cancelar esta reserva?')"
                       class="text-xs text-red-600 border border-red-300 px-3 py-1 hover:bg-red-50 transition-colors duration-200">
                      Cancelar
                    </a>
                  <?php else: ?>
                    <span class="text-xs text-gray-400">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </main>

  <!-- FOOTER -->
  <footer class="bg-[#1a1610] text-white/50 text-xs text-center py-6">
    © 2026 Lumière Hotels. Todos los derechos reservados.
  </footer>

</body>
</html>