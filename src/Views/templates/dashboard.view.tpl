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
    <div class="icon"><i class="fas fa-couch"></i></div>
    <h3>Productos</h3>
    <p>Administrá el catálogo de muebles, precios y disponibilidad.</p>
    <span class="arrow">Gestionar →</span>
  </a>
  <a href="index.php?page=Security_Users" class="dash-card">
    <div class="icon"><i class="fas fa-users"></i></div>
    <h3>Usuarios</h3>
    <p>Administrá las cuentas de usuarios registrados en el sistema.</p>
    <span class="arrow">Gestionar →</span>
  </a>
</div>