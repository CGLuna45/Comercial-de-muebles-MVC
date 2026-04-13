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
</style>

<div class="crud-header">
  <h2>Compras de Stock</h2>
</div>

<form class="filtros" action="index.php" method="get">
  <input type="hidden" name="page" value="Commerce_Compras">
  <div class="filtro-group">
    <label for="partial">Buscar</label>
    <input type="text" name="partial" id="partial" value="{{partial}}" placeholder="Producto, categoria o codigo">
  </div>
  <div class="filtro-group">
    <label for="status">Estado de inventario</label>
    <select name="status" id="status">
      <option value="EMP" {{status_EMP}}>Todos</option>
      <option value="AGO" {{status_AGO}}>Agotado</option>
      <option value="BAJ" {{status_BAJ}}>Bajo</option>
      <option value="OK" {{status_OK}}>Estable</option>
    </select>
  </div>
  <button type="submit" class="btn-filtrar">Filtrar</button>
</form>

{{if totalPurchases}}
<div class="tabla-wrapper">
  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th>Categoria</th>
        <th class="right">Precio</th>
        <th class="right">Stock actual</th>
        <th class="right">Sugerido comprar</th>
      </tr>
    </thead>
    <tbody>
      {{foreach purchases}}
      <tr>
        <td>#{{productId}} - {{productName}}</td>
        <td>{{categoria}}</td>
        <td class="right">L {{precio}}</td>
        <td class="right">{{stock}}</td>
        <td class="right">{{sugeridoComprar}}</td>
      </tr>
      {{endfor purchases}}
    </tbody>
  </table>
</div>
{{pagination}}
{{endif totalPurchases}}

{{ifnot totalPurchases}}
<div class="tabla-wrapper" style="padding:1.5rem;">No hay productos para mostrar en compras de stock.</div>
{{endifnot totalPurchases}}
