<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
// Proteger la página — si no hay sesión, redirige al login
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
  <title>Lumière Hotels</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="<?= SITE_URL ?>views/styles/home.css"/>
</head>
<body>

  <!-- NAV -->
  <nav class="nav" aria-label="Navegación principal">
    <a href="<?= SITE_URL ?>index.php" class="nav__brand" aria-label="Ir al inicio - Lumière Hotels">Lumière</a>

    <ul class="nav__links" role="list">
      <li><a href="#destinos">Destinos</a></li>
      <li><a href="#habitaciones">Habitaciones</a></li>
      <li><a href="#experiencias">Experiencias</a></li>
      <li><a href="#contacto">Contacto</a></li>
    </ul>

    <!-- Bienvenida + acciones del usuario -->
    <div style="display:flex; align-items:center; gap:12px;">
      <span style="color:white; font-size:14px;">
        Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
      </span>
      <a href="<?= SITE_URL ?>index.php?action=getFormCreateReserva" class="nav__login">
        Reservar
      </a>
      <a href="<?= SITE_URL ?>index.php?action=logout" class="nav__login" style="border-color:rgba(255,255,255,0.4);">
        Cerrar sesión
      </a>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero" aria-labelledby="hero-title">
    <p class="hero__eyebrow animate-fade-up animate-delay-1">Hoteles de lujo · Experiencias únicas</p>
    <h1 id="hero-title" class="hero__title animate-fade-up animate-delay-2">
      Tu refugio perfecto<br/>te espera
    </h1>
    <p class="hero__subtitle animate-fade-up animate-delay-3">
      Reserva en los hoteles más exclusivos del mundo con precios inmejorables.
    </p>

    <!-- Buscador -->
    <form class="search-card animate-fade-up animate-delay-4" action="/buscar" method="GET" role="search">
      <div class="search-card__grid">

        <div class="search-card__field">
          <label for="destino">Destino</label>
          <div class="search-card__input-wrapper">
            <svg class="search-card__icon" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <input id="destino" name="destino" type="search" placeholder="Ciudad o hotel…" autocomplete="off"/>
          </div>
        </div>

        <div class="search-card__field">
          <label for="checkin">Check-in</label>
          <input id="checkin" name="checkin" type="date"/>
        </div>

        <div class="search-card__field">
          <label for="checkout">Check-out</label>
          <input id="checkout" name="checkout" type="date"/>
        </div>

        <div class="search-card__field">
          <label for="huespedes">Huéspedes</label>
          <div class="search-card__select-row">
            <select id="huespedes" name="huespedes">
              <option value="1">1 adulto</option>
              <option value="2">2 adultos</option>
              <option value="3">3 adultos</option>
              <option value="4">4+ adultos</option>
            </select>
            <button type="submit" class="btn-primary">Buscar</button>
          </div>
        </div>

      </div>
    </form>
  </section>

  <!-- DESTINOS -->
  <section class="destinations" id="destinos" aria-labelledby="destinations-title">
    <div class="destinations__header">
      <div>
        <p class="destinations__eyebrow" aria-hidden="true">Colección</p>
        <h2 id="destinations-title" class="destinations__title">Destinos destacados</h2>
      </div>
      <a href="/destinos" class="destinations__link">Ver todos</a>
    </div>

    <ul class="destinations__grid" role="list">

      <li class="card">
        <a href="/hotel/grand-palace" class="card__link" aria-label="Ver Grand Palace en París">
          <div class="card__image card__image--paris" role="img" aria-label="Foto del Grand Palace en París">
            <span class="card__badge card__badge--gold">Más popular</span>
          </div>
          <div class="card__body">
            <div>
              <h3 class="card__name">Grand Palace</h3>
              <p class="card__location">París, Francia · <span aria-label="5 estrellas">★★★★★</span></p>
            </div>
            <div class="card__price">
              <p class="card__price-label">Desde</p>
              <p class="card__price-value">€280<span>/noche</span></p>
            </div>
          </div>
        </a>
      </li>

      <li class="card card--offset">
        <a href="/hotel/azure-resort" class="card__link" aria-label="Ver Azure Resort en Santorini">
          <div class="card__image card__image--santorini" role="img" aria-label="Foto del Azure Resort en Santorini"></div>
          <div class="card__body">
            <div>
              <h3 class="card__name">Azure Resort</h3>
              <p class="card__location">Santorini, Grecia · <span aria-label="5 estrellas">★★★★★</span></p>
            </div>
            <div class="card__price">
              <p class="card__price-label">Desde</p>
              <p class="card__price-value">€420<span>/noche</span></p>
            </div>
          </div>
        </a>
      </li>

      <li class="card">
        <a href="/hotel/villa-serena" class="card__link" aria-label="Ver Villa Serena en Toscana">
          <div class="card__image card__image--toscana" role="img" aria-label="Foto de Villa Serena en Toscana">
            <span class="card__badge card__badge--dark">Oferta</span>
          </div>
          <div class="card__body">
            <div>
              <h3 class="card__name">Villa Serena</h3>
              <p class="card__location">Toscana, Italia · <span aria-label="4 estrellas">★★★★☆</span></p>
            </div>
            <div class="card__price">
              <p class="card__price-label">Desde</p>
              <p class="card__price-value">€195<span>/noche</span></p>
            </div>
          </div>
        </a>
      </li>

    </ul>
  </section>

  <!-- BENEFICIOS -->
  <section class="benefits" aria-label="Nuestros beneficios">
    <ul class="benefits__grid" role="list">

      <li class="benefit">
        <svg class="benefit__icon" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <h3 class="benefit__title">Precio garantizado</h3>
        <p class="benefit__text">Encontramos el mejor precio o te devolvemos la diferencia.</p>
      </li>

      <li class="benefit">
        <svg class="benefit__icon" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="benefit__title">Cancelación flexible</h3>
        <p class="benefit__text">Cambia o cancela tu reserva sin costo hasta 48h antes.</p>
      </li>

      <li class="benefit">
        <svg class="benefit__icon" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <h3 class="benefit__title">Soporte 24/7</h3>
        <p class="benefit__text">Nuestro equipo está disponible en cualquier momento para ayudarte.</p>
      </li>

    </ul>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <a href="<?= SITE_URL ?>index.php" class="footer__brand" aria-label="Ir al inicio - Lumière Hotels">Lumière Hotels</a>
    <p class="footer__copy">
      <small>© 2026 Lumière. Todos los derechos reservados.</small>
    </p>
    <nav class="footer__links" aria-label="Enlaces legales">
      <a href="/privacidad">Privacidad</a>
      <a href="/terminos">Términos</a>
      <a href="/cookies">Cookies</a>
    </nav>
  </footer>

</body>
</html>