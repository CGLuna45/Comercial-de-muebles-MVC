<style>
  .perfil-header {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 20px rgba(92,64,51,0.08);
    padding: 1.6rem 1.8rem;
    margin-bottom: 1.5rem;
  }
  .perfil-header h2 {
    color: var(--cedro);
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 0.4rem;
  }
  .perfil-header p {
    color: #777;
    line-height: 1.6;
  }
  .perfil-grid {
    display: grid;
    grid-template-columns: minmax(280px, 360px) 1fr;
    gap: 1.5rem;
    align-items: start;
  }
  .perfil-card, .historial-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 20px rgba(92,64,51,0.08);
    overflow: hidden;
  }
  .perfil-card .card-head, .historial-card .card-head {
    padding: 1rem 1.3rem;
    background: var(--cedro);
    color: #fff;
    font-weight: 700;
  }
  .perfil-card .card-body {
    padding: 1.3rem;
    display: grid;
    gap: 0.9rem;
  }
  .dato {
    padding-bottom: 0.85rem;
    border-bottom: 1px solid #f0ece8;
  }
  .dato span {
    display: block;
    font-size: 0.82rem;
    color: #777;
    margin-bottom: 0.2rem;
  }
  .dato strong {
    color: var(--cedro);
    font-size: 0.98rem;
  }
  .name-form {
    display: grid;
    gap: 0.55rem;
  }
  .name-form input {
    width: 100%;
    border: 1px solid #d9d1ca;
    border-radius: 10px;
    padding: 0.6rem 0.7rem;
    font-size: 0.95rem;
    color: #3c2d23;
  }
  .name-form button {
    border: none;
    background: var(--dorado);
    color: #3c2d23;
    font-weight: 700;
    border-radius: 10px;
    padding: 0.6rem 0.9rem;
    cursor: pointer;
  }
  .profile-msg-ok {
    background: #e6f4ea;
    color: #1f6f34;
    padding: 0.6rem 0.75rem;
    border-radius: 10px;
    font-size: 0.9rem;
  }
  .profile-msg-err {
    background: #fdecea;
    color: #b53325;
    padding: 0.6rem 0.75rem;
    border-radius: 10px;
    font-size: 0.9rem;
  }
  .tabla-wrapper { overflow: hidden; }
  .tabla-wrapper table { width: 100%; border-collapse: collapse; }
  .tabla-wrapper thead { background: var(--cedro); color: #fff; }
  .tabla-wrapper thead th { padding: 1rem 1.1rem; text-align: left; font-size: 0.9rem; }
  .tabla-wrapper tbody tr { border-bottom: 1px solid #f0ece8; }
  .tabla-wrapper tbody td { padding: 0.9rem 1.1rem; font-size: 0.95rem; }
  .trx-details {
    margin-top: 0.75rem;
    background: #faf7f2;
    border: 1px solid #eee1d4;
    border-radius: 12px;
    overflow: hidden;
  }
  .trx-details summary {
    cursor: pointer;
    padding: 0.8rem 1rem;
    font-weight: 700;
    color: var(--cedro);
    list-style: none;
  }
  .trx-details summary::-webkit-details-marker { display: none; }
  .trx-details-body {
    padding: 0 1rem 0.9rem;
  }
  .trx-mini-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.2rem;
  }
  .trx-mini-table th,
  .trx-mini-table td {
    padding: 0.55rem 0.6rem;
    border-bottom: 1px solid #efe4da;
    font-size: 0.88rem;
    text-align: left;
  }
  .trx-mini-table th.right,
  .trx-mini-table td.right {
    text-align: right;
  }
  .right { text-align: right; }
  .badge-ok { background: #e6f4ea; color: #2d7a3a; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
  .badge-mid { background: #fff3d6; color: #8a6d1b; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
  .badge-err { background: #fdecea; color: #c0392b; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
  .empty-state {
    padding: 1.2rem 1.3rem;
    color: #777;
  }
  @media (max-width: 920px) {
    .perfil-grid { grid-template-columns: 1fr; }
  }
</style>

<div class="perfil-header">
  <h2>Mi perfil</h2>
  <p>Desde aquí podés revisar tu información personal y el historial de tus transacciones.</p>
</div>

<div class="perfil-grid">
  <section class="perfil-card">
    <div class="card-head">Información del usuario</div>
    <div class="card-body">
      {{if hasProfileSuccess}}
      <div class="profile-msg-ok">{{profileSuccess}}</div>
      {{endif hasProfileSuccess}}
      {{if hasProfileError}}
      <div class="profile-msg-err">{{profileError}}</div>
      {{endif hasProfileError}}
      <div class="dato">
        <span>Nombre</span>
        <form method="post" action="index.php?page=Security_Perfil" class="name-form">
          <input type="text" name="userNombre" value="{{userNombre}}" maxlength="80" required>
          <button type="submit">Guardar nombre</button>
        </form>
      </div>
      <div class="dato">
        <span>Email</span>
        <strong>{{userEmail}}</strong>
      </div>
      <div class="dato">
        <span>Estado</span>
        <strong>{{userStatus}}</strong>
      </div>
      <div class="dato">
        <span>Tipo</span>
        <strong>{{userTipo}}</strong>
      </div>
    </div>
  </section>

  <section class="historial-card">
    <div class="card-head">Mis transacciones</div>
    {{if totalTransactions}}
    <div class="tabla-wrapper">
      <table>
        <thead>
          <tr>
            <th>Orden PayPal</th>
            <th class="right">Total</th>
            <th>Estado</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          {{foreach transactions}}
          <tr>
            <td>{{paypalOrderId}}</td>
            <td class="right">L {{transaccionTotal}}</td>
            <td><span class="trx-status" data-status="{{transaccionStatus}}">{{transaccionStatus}}</span></td>
            <td>{{transaccionFecha}}</td>
          </tr>
          <tr>
            <td colspan="4">
              <details class="trx-details">
                <summary>Ver detalles</summary>
                <div class="trx-details-body">
                  {{if details}}
                  <table class="trx-mini-table">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th class="right">Cantidad</th>
                        <th class="right">Precio</th>
                        <th class="right">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      {{foreach details}}
                      <tr>
                        <td>{{productName}}</td>
                        <td class="right">{{transDetalleCantidad}}</td>
                        <td class="right">L {{transDetallePrecio}}</td>
                        <td class="right">L {{transDetalleSubtotal}}</td>
                      </tr>
                      {{endfor details}}
                    </tbody>
                  </table>
                  {{endif details}}
                  {{ifnot details}}
                  <div class="empty-state">No hay detalles para esta transacción.</div>
                  {{endifnot details}}
                </div>
              </details>
            </td>
          </tr>
          {{endfor transactions}}
        </tbody>
      </table>
    </div>
    {{pagination}}
    {{endif totalTransactions}}

    {{ifnot totalTransactions}}
    <div class="empty-state">Aún no hay transacciones registradas para tu usuario.</div>
    {{endifnot totalTransactions}}
  </section>
</div>

<script>
  document.querySelectorAll('.trx-status').forEach(el => {
    const st = (el.dataset.status || '').toUpperCase();
    if (st === 'COM' || st === 'ACT' || st === 'PAG') el.className = 'badge-ok';
    else if (st === 'CAN' || st === 'ERR' || st === 'INA') el.className = 'badge-err';
    else el.className = 'badge-mid';
    el.textContent = st || 'N/A';
  });
</script>
