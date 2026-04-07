<style>
  .crud-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
  .crud-header h2 { color: var(--cedro); font-size: 1.8rem; font-weight: 800; }
  .btn-nuevo {
    background: var(--dorado); color: #fff; border: none;
    padding: 0.7rem 1.4rem; border-radius: 999px;
    font-weight: 700; cursor: pointer; text-decoration: none;
    font-size: 0.95rem; transition: background 0.2s;
  }
  .btn-nuevo:hover { background: var(--cedro); color: #fff; }
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
  .filtro-group input:focus, .filtro-group select:focus { border-color: var(--dorado); }
  .btn-filtrar {
    background: var(--cedro); color: #fff; border: none;
    padding: 0.65rem 1.2rem; border-radius: 10px;
    font-weight: 700; cursor: pointer; font-size: 0.95rem;
    transition: background 0.2s;
  }
  .btn-filtrar:hover { background: var(--dorado); }
  .tabla-wrapper {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 12px rgba(92,64,51,0.07); overflow: hidden;
  }
  .tabla-wrapper table { width: 100%; border-collapse: collapse; }
  .tabla-wrapper thead { background: var(--cedro); color: #fff; }
  .tabla-wrapper thead th { padding: 1rem 1.2rem; text-align: left; font-size: 0.9rem; }
  .tabla-wrapper thead th a { color: #fff; text-decoration: none; }
  .tabla-wrapper tbody tr { border-bottom: 1px solid #f0ece8; transition: background 0.15s; }
  .tabla-wrapper tbody tr:hover { background: var(--arena); }
  .tabla-wrapper tbody td { padding: 0.9rem 1.2rem; font-size: 0.95rem; }
  .badge-act { background: #e6f4ea; color: #2d7a3a; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
  .badge-ina { background: #fdecea; color: #c0392b; padding: 3px 10px; border-radius: 999px; font-size: 0.82rem; font-weight: 700; }
  .acciones { display: flex; gap: 0.5rem; }
  .btn-editar, .btn-eliminar {
    padding: 0.4rem 0.9rem; border-radius: 999px;
    font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: 0.2s;
  }
  .btn-editar { background: var(--arena); color: var(--cedro); border: 1px solid var(--cedro); }
  .btn-editar:hover { background: var(--cedro); color: #fff; }
  .btn-eliminar { background: #fdecea; color: #c0392b; border: 1px solid #c0392b; }
  .btn-eliminar:hover { background: #c0392b; color: #fff; }
  .empty-state {
    background: #fff; border-radius: 16px;
    box-shadow: 0 2px 12px rgba(92,64,51,0.07);
    padding: 3rem; text-align: center; color: #aaa;
  }
  .empty-state span { font-size: 2.5rem; display: block; margin-bottom: 0.5rem; }
  .empty-state p { font-size: 1rem; font-weight: 600; }
</style>

<div class="crud-header">
  <h2>Productos</h2>
  <a href="index.php?page=Products-Product&mode=INS" class="btn-nuevo">+ Nuevo Producto</a>
</div>

<form class="filtros" action="index.php" method="get">
  <input type="hidden" name="page" value="Products_Products">
  <div class="filtro-group">
    <label for="partialName">Nombre</label>
    <input type="text" name="partialName" id="partialName" value="{{partialName}}" placeholder="Buscar...">
  </div>
  <div class="filtro-group">
    <label for="status">Estado</label>
    <select name="status" id="status">
      <option value="EMP" {{status_EMP}}>Todos</option>
      <option value="ACT" {{status_ACT}}>Activo</option>
      <option value="INA" {{status_INA}}>Inactivo</option>
    </select>
  </div>
  <div class="filtro-group">
  <label for="categoriaId">Categoría</label>
  <select name="categoriaId" id="categoriaId">
    <option value="0">Todas</option>
    {{foreach ~categorias}}
    <option value="{{categoriaId}}" {{selected}}>{{categoriaNombre}}</option>
    {{endfor ~categorias}}
  </select>
</div>
  <button type="submit" class="btn-filtrar">Filtrar</button>
</form>

{{if totalProducts}}
<div class="tabla-wrapper">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {{foreach products}}
      <tr>
        <td>{{productId}}</td>
        <td>{{productName}}</td>
        <td>L. {{productPrice}}</td>
        <td>
          <span class="badge-status" data-status="{{productStatusDsc}}">{{productStatusDsc}}</span>
        </td>
        <td>
          <div class="acciones">
            <a href="index.php?page=Products-Product&mode=UPD&id={{productId}}" class="btn-editar">Editar</a>
            <a href="index.php?page=Products-Product&mode=DEL&id={{productId}}" class="btn-eliminar">Eliminar</a>
          </div>
        </td>
      </tr>
      {{endfor products}}
    </tbody>
  </table>
</div>
{{pagination}}
{{endif totalProducts}}

{{ifnot totalProducts}}
<div class="empty-state">
  <span>🔍</span>
  <p>No se encontraron productos con esa búsqueda.</p>
</div>
{{endifnot totalProducts}}

<script>
document.querySelectorAll('.badge-status').forEach(badge => {
    if (badge.dataset.status === 'Activo') {
        badge.classList.add('badge-act');
    } else {
        badge.classList.add('badge-ina');
    }
});
</script>