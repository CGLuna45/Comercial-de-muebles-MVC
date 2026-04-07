<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{SITE_TITLE}} | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/{{FONT_AWESOME_KIT}}.js" crossorigin="anonymous"></script>
{{foreach SiteLinks}}
  <link rel="stylesheet" href="{{~BASE_DIR}}/{{this}}" />
{{endfor SiteLinks}}
{{foreach BeginScripts}}
  <script src="{{~BASE_DIR}}/{{this}}"></script>
  {{endfor BeginScripts}}

  <style>
    :root {
      --cedro: #5C4033;
      --dorado: #C5A059;
      --arena: #F7F1EB;
      --sombra: 0 8px 24px rgba(0,0,0,0.08);
      --radio: 18px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body { background: var(--arena); color: #333; display: flex; flex-direction: column; min-height: 100vh; }

    /* HEADER */
    .header {
      background: rgba(255,255,255,0.97);
      backdrop-filter: blur(8px);
      padding: 14px 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: var(--sombra);
      position: sticky;
      top: 0;
      z-index: 1001;
    }
    .logo-box { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .logo-txt { font-size: 1.6rem; color: var(--cedro); font-weight: 800; letter-spacing: 2px; }
    .nav-right { display: flex; align-items: center; gap: 18px; }
    .nav-right a { text-decoration: none; color: var(--cedro); font-weight: 700; font-size: 0.95rem; transition: 0.3s; }
    .nav-right a:hover { color: var(--dorado); }
    .username-label { color: var(--cedro); font-weight: 700; font-size: 0.95rem; }

    /* HAMBURGER */
    .menu_toggle { display: none; }
    .menu_toggle_icon {
      cursor: pointer;
      display: flex; flex-direction: column; gap: 5px;
      width: 36px; padding: 4px; z-index: 1002;
    }
    .hmb { height: 3px; width: 100%; background: var(--cedro); border-radius: 2px; transition: all 0.3s; }
    .menu_toggle:checked ~ .header .menu_toggle_icon .hrz { opacity: 0; }
    .menu_toggle:checked ~ .header .menu_toggle_icon .dgn.pt-1 { transform: rotate(135deg) translate(0, -8px); }
    .menu_toggle:checked ~ .header .menu_toggle_icon .dgn.pt-2 { transform: rotate(-135deg) translate(0, 8px); }

    /* SIDEBAR */
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: 270px; height: 100vh;
      background: var(--cedro);
      transform: translateX(-270px);
      transition: transform 250ms ease-in-out;
      z-index: 1000;
      padding-top: 70px;
      box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    }
    .menu_toggle:checked ~ .sidebar { transform: translateX(0); }
    .sidebar ul { list-style: none; padding: 1rem 0; }
    .sidebar ul li a {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0.9rem 1.5rem;
      color: rgba(255,255,255,0.85);
      text-decoration: none; font-weight: 700; font-size: 0.95rem;
      transition: background 0.2s, color 0.2s;
      border-left: 3px solid transparent;
    }
    .sidebar ul li a:hover { background: rgba(197,160,89,0.2); color: var(--dorado); border-left-color: var(--dorado); }
    .sidebar ul li.divider { border-top: 1px solid rgba(255,255,255,0.15); margin: 0.5rem 0; }

    /* MAIN */
    main { flex: 1; padding: 2.5rem 5%; }
    footer { background: var(--cedro); color: white; text-align: center; padding: 28px 20px; margin-top: auto; }

    @media(max-width: 768px) {
      .header { flex-wrap: wrap; gap: 10px; }
    }
  </style>
</head>
<body>
  <input type="checkbox" class="menu_toggle" id="menu_toggle" />

  <header class="header">
    <label for="menu_toggle" class="menu_toggle_icon">
      <div class="hmb dgn pt-1"></div>
      <div class="hmb hrz"></div>
      <div class="hmb dgn pt-2"></div>
    </label>
    <a href="index.php?page={{PRIVATE_DEFAULT_CONTROLLER}}" class="logo-box">
      <span class="logo-txt">CÉDRIKA</span>
    </a>
    <div class="nav-right">
      {{with login}}
      <span class="username-label"><i class="fas fa-user-circle"></i> {{userName}}</span>
      <a href="index.php?page=sec_logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
      {{endwith login}}
    </div>
  </header>

  <nav class="sidebar">
    <ul>
      <li><a href="index.php?page={{PRIVATE_DEFAULT_CONTROLLER}}"><i class="fas fa-home"></i> Inicio</a></li>
      {{foreach NAVIGATION}}
      <li><a href="{{nav_url}}">{{nav_label}}</a></li>
      {{endfor NAVIGATION}}
      <li class="divider"></li>
      <li><a href="index.php?page=sec_logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
    </ul>
  </nav>

  <main>
    {{{page_content}}}
  </main>

  <footer>
    <p>© {{~CURRENT_YEAR}} CÉDRIKA | Panel Administrativo</p>
  </footer>

{{foreach EndScripts}}
  <script src="{{~BASE_DIR}}/{{this}}"></script>
{{endfor EndScripts}}
<script src="{{BASE_DIR}}/public/js/modals.js"></script>

</body>
</html>