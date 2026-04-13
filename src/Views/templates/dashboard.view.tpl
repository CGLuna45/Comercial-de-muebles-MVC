<style>
  .dashboard-header { margin-bottom: 2rem; }
  .dashboard-header h2 { color: var(--cedro); font-size: 2rem; font-weight: 800; }
  .dashboard-header p { color: #777; margin-top: 0.5rem; }
  .dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
  }
  .dash-card {
    background: #fff; border-radius: 18px;
    padding: 2rem 1.5rem;
    box-shadow: 0 4px 20px rgba(92,64,51,0.08);
    text-decoration: none; color: inherit;
    display: flex; flex-direction: column; gap: 1rem;
    border: 1px solid rgba(92,64,51,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .dash-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(92,64,51,0.15); }
  .dash-card .icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: var(--arena);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: var(--cedro);
  }
  .dash-card .icon svg {
    width: 1.4rem;
    height: 1.4rem;
    fill: currentColor;
    display: block;
  }
  .dash-card h3 { color: var(--cedro); font-size: 1.1rem; }
  .dash-card p { color: #888; font-size: 0.9rem; line-height: 1.5; }
  .dash-card .arrow { margin-top: auto; color: var(--dorado); font-weight: 700; font-size: 0.9rem; }
</style>

<div class="dashboard-header">
  <h2>Panel de Administración</h2>
  <p>Bienvenido, {{userName}}. Desde aquí podés gestionar todo el sistema.</p>
</div>

<div class="dashboard-cards">
  <a href="index.php?page=Products_Products" class="dash-card">
    <div class="icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 11a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v3a2 2 0 0 1-2 2h-1v2H7v-2H6a2 2 0 0 1-2-2v-3zm4-5h8a2 2 0 0 1 2 2v1H6V8a2 2 0 0 1 2-2zm0 10h8v2H8v-2z"/></svg></div>
    <h3>Productos</h3>
    <p>Administrá el catálogo de muebles, precios y disponibilidad.</p>
    <span class="arrow">Gestionar →</span>
  </a>
  <a href="index.php?page=Security_Users" class="dash-card">
    <div class="icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm-8 1a3 3 0 1 0-3-3 3 3 0 0 0 3 3zm8 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zM5 14c-2.21 0-5 1.11-5 3.5V20h5v-1.5c0-1.08.33-2.07.91-2.94C5.58 15.05 5.29 14.51 5 14z"/></svg></div>
    <h3>Usuarios</h3>
    <p>Administrá las cuentas de usuarios registrados en el sistema.</p>
    <span class="arrow">Gestionar →</span>
  </a>
  <a href="index.php?page=Commerce_Transacciones" class="dash-card">
    <div class="icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 3h12a1 1 0 0 1 1 1v16l-3-2-3 2-3-2-3 2-3-2V4a1 1 0 0 1 1-1zm2 4v2h8V7H8zm0 4v2h8v-2H8zm0 4v2h5v-2H8z"/></svg></div>
    <h3>Transacciones de clientes</h3>
    <p>Revisá compras realizadas por clientes y su estado de pago.</p>
    <span class="arrow">Ver historial →</span>
  </a>
  <a href="index.php?page=Commerce_Compras" class="dash-card">
    <div class="icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7 4h10l1 4h2a1 1 0 0 1 1 1v1H2V9a1 1 0 0 1 1-1h2l2-4zm-2 7l1 8h12l1-8H5zm4 2h2v4H9v-4zm4 0h2v4h-2v-4z"/></svg></div>
    <h3>Compras de stock</h3>
    <p>Consultá necesidades de abastecimiento segun inventario.</p>
    <span class="arrow">Ver compras →</span>
  </a>
</div>