<h1>Producto Electrónico</h1>

<form method="post">

<input type="hidden" name="mode" value="{{mode}}">
<input type="hidden" name="id_producto" value="{{id_producto}}">

<label>Nombre</label>
<input type="text" name="nombre" value="{{nombre}}">

<label>Tipo</label>
<input type="text" name="tipo" value="{{tipo}}">

<label>Precio</label>
<input type="number" step="0.01" name="precio" value="{{precio}}">

<label>Marca</label>
<input type="text" name="marca" value="{{marca}}">

<label>Fecha Lanzamiento</label>
<input type="date" name="fecha_lanzamiento" value="{{fecha_lanzamiento}}">

<button type="submit">
Guardar
</button>

<a href="index.php?page=Products_ProductosElectronica">
Cancelar
</a>

</form>