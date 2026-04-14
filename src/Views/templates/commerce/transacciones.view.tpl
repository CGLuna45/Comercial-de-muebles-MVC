<style>
  .crud-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
  .crud-header h2 { color: var(--cedro); font-size: 1.8rem; font-weight: 800; }
  .filtros {
    background: #fff; border-radius: 14px; padding: 1.2rem 1.5rem;
    box-shadow: 0 2px 12px rgba(92,64,51,0.07);
    display: flex; gap: 1rem; align-items: flex-end;
    flex-wrap: wrap; margin-bottom: 1.5rem;
  }
  .filtro-group { display: flex; flex-direction: column; gap: 0.3rem; }
  .filtro-group label { color: var(--cedro); font-weight: 700; font-size: 0.9rem; }
  .filtro-group input, .filtro-group select {
    border: 1px solid #ddd; border-radius: 10px;
    padding: 0.6rem 0.8rem; font-size: 0.95rem;
    background: #fff; outline: none;
  }
  .btn-filtrar {
    background: var(--cedro); color: #fff; border: none;
    padding: 0.65rem 1.2rem; border-radius: 10px;
    font-weight: 700; cursor: pointer; font-size: 0.95rem;
  }
  .tabla-wrapper { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(92,64,51,0.07); overflow: hidden; }
  .tabla-wrapper table { width: 100%; border-collapse: collapse; }
  .tabla-wrapper thead { background: var(--cedro); color: #fff; }
  .tabla-wrapper thead th { padding: 1rem 1.2rem; text-align: left; font-size: 0.9rem; }
  .tabla-wrapper tbody tr { border-bottom: 1px solid #f0ece8; }
  .tabla-wrapper tbody td { padding: 0.9rem 1.2rem; font-size: 0.95rem; }
  .right { text-align: right; }
  .badge-ok { background: #e6f4ea; color: #2d7a3a; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
  .badge-mid { background: #fff3d6; color: #8a6d1b; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
  .badge-err { background: #fdecea; color: #c0392b; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
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
  .empty-state {
    padding: 0.7rem 0.2rem;
    color: #777;
  }
</style>

<div class="crud-header">
  <h2>Transacciones de Clientes</h2>
</div>

<form class="filtros" action="index.php" method="get">
  <input type="hidden" name="page" value="Commerce_Transacciones">
  <div class="filtro-group">
    <label for="partial">Buscar</label>
    <input type="text" name="partial" id="partial" value="{{partial}}" placeholder="Cliente, email u orden PayPal">
  </div>
  <div class="filtro-group">
    <label for="status">Estado</label>
    <select name="status" id="status">
      <option value="EMP" {{status_EMP}}>Todos</option>
      <option value="ACT" {{status_ACT}}>Activa</option>
      <option value="COM" {{status_COM}}>Completada</option>
      <option value="CAN" {{status_CAN}}>Cancelada</option>
      <option value="ERR" {{status_ERR}}>Error</option>
    </select>
  </div>
  <button type="submit" class="btn-filtrar">Filtrar</button>
</form>

{{if totalTransactions}}
<div class="tabla-wrapper">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Orden PayPal</th>
        <th class="right">Total</th>
        <th>Estado</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      {{foreach transactions}}
      <tr>
        <td>{{transaccionId}}</td>
        <td>{{usuarioNombre}}<br><small>{{usuarioEmail}}</small></td>
        <td>{{paypalOrderId}}</td>
        <td class="right">L {{transaccionTotal}}</td>
        <td><span class="trx-status" data-status="{{transaccionStatus}}">{{transaccionStatus}}</span></td>
        <td>{{transaccionFecha}}</td>
      </tr>
      <tr>
        <td colspan="6">
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
<div class="tabla-wrapper" style="padding:1.5rem;">No hay transacciones registradas.</div>
{{endifnot totalTransactions}}

<script>
  document.querySelectorAll('.trx-status').forEach(el => {
    const st = (el.dataset.status || '').toUpperCase();
    if (st === 'COM' || st === 'ACT' || st === 'PAG') el.className = 'badge-ok';
    else if (st === 'CAN' || st === 'ERR' || st === 'INA') el.className = 'badge-err';
    else el.className = 'badge-mid';
    el.textContent = st || 'N/A';
  });
</script>
