<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{SITE_TITLE}}</title>
  <style>
    :root {
      --cedro: #5C4033;
      --dorado: #C5A059;
      --arena: #F7F1EB;
      --blanco: #ffffff;
      --gris: #777;
      --sombra: 0 8px 24px rgba(0, 0, 0, 0.08);
      --radio: 18px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: var(--arena);
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(8px);
      padding: 18px 7%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: var(--sombra);
      position: sticky;
      top: 0;
      z-index: 1000;
      font-family: 'Segoe UI', sans-serif;
    }

    .header * {
      font-family: 'Segoe UI', sans-serif;
    }

    .logo-box {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }

    .logo-img {
      width: 52px;
      height: 52px;
      object-fit: contain;
    }

    .logo-txt {
      font-size: 1.7rem;
      color: var(--cedro);
      font-weight: 800;
      letter-spacing: 2px;
    }

    .nav-menu {
      display: flex;
      align-items: center;
      gap: 22px;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .nav-menu a {
      text-decoration: none;
      color: var(--cedro);
      font-weight: 700;
      font-size: 0.95rem;
      transition: 0.3s;
      white-space: nowrap;
    }

    .nav-menu a:hover {
      color: var(--dorado);
    }

    .badge {
      background: #d35400;
      color: white;
      padding: 3px 8px;
      border-radius: 999px;
      font-size: 0.78rem;
      margin-left: 6px;
    }

    /* Sobrescribir estilos globales conflictivos de appstyle.css */
    header nav {
      position: static !important;
      transform: none !important;
      width: auto !important;
      margin-top: 0 !important;
      bottom: auto !important;
      height: auto !important;
      background: transparent !important;
      box-shadow: none !important;
    }

    .menu_toggle_icon, .hmb, header input[type="checkbox"] {
      display: none !important;
    }

    main {
      flex: 1;
      padding: 40px 20px;
    }

    body > footer {
      background: var(--cedro) !important;
      color: #ffffff !important;
      text-align: center;
      padding: 35px 20px;
      margin-top: 40px;
      display: block;
    }

    body > footer p {
      color: #ffffff !important;
      margin: 0;
    }

    @media(max-width: 768px) {
      .header {
        flex-direction: column;
        gap: 15px;
      }

      .nav-menu {
        flex-wrap: wrap;
        justify-content: center;
      }
    }
  </style>
  <link rel="stylesheet" href="public/css/appstyle.css" />
  {{if FONT_AWESOME_KIT}}
  <script src="https://kit.fontawesome.com/{{FONT_AWESOME_KIT}}.js" crossorigin="anonymous"></script>
  {{endif FONT_AWESOME_KIT}}
  {{foreach SiteLinks}}
  <link rel="stylesheet" href="{{~BASE_DIR}}/{{this}}" />
  {{endfor SiteLinks}}
  {{foreach BeginScripts}}
  <script src="{{~BASE_DIR}}/{{this}}"></script>
  {{endfor BeginScripts}}
</head>

<body>
  <header class="header">
    <a href="index.php" class="logo-box">
      <img src="img/logo-cedrika.png" alt="logo" class="logo-img">
      <span class="logo-txt">CÉDRIKA</span>
    </a>

    <nav class="nav-menu">
      <a href="index.php">Inicio</a>
      <a href="catalogo.php">Catálogo</a>
      <a href="carrito.php">🛒 Carrito {{foreach CartCount}}<span class="badge">{{this}}</span>{{endfor CartCount}}</a>
      {{if login}}
      <a href="index.php?page=Security_Perfil" style="color: var(--cedro); font-weight:700; text-decoration:none;">Hola, {{userName}}</a>
      <a href="index.php?page=Sec_Logout">Cerrar Sesión</a>
      {{else}}
      <a href="index.php?page=Sec_Login"><i class="fas fa-sign-in-alt"></i>&nbsp;Iniciar Sesión</a>
      <a href="index.php?page=Sec_Register"><i class="fas fa-sign-in-alt"></i>&nbsp;Crear Cuenta</a>
      {{endif login}}
    </nav>
  </header>

  <main>
    {{{page_content}}}
  </main>

  <footer>
    <p>© {{~CURRENT_YEAR}} CÉDRIKA | La Ceiba, Honduras</p>
  </footer>

  {{foreach EndScripts}}
  <script src="{{~BASE_DIR}}/{{this}}"></script>
  {{endfor EndScripts}}
</body>

</html>