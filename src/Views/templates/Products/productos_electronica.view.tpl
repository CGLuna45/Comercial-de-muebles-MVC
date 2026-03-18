<h1>Productos Electrónica</h1>

<a href="index.php?page=Products_ProductoElectronica&mode=INS">
Nuevo Producto
</a>

<table>

<thead>

<tr>
<th>ID</th>
<th>Nombre</th>
<th>Tipo</th>
<th>Precio</th>
<th>Marca</th>
<th>Fecha</th>
<th>Acciones</th>
</tr>

</thead>

<tbody>

{{foreach productos}}

<tr>

<td>{{id_producto}}</td>
<td>{{nombre}}</td>
<td>{{tipo}}</td>
<td>{{precio}}</td>
<td>{{marca}}</td>
<td>{{fecha_lanzamiento}}</td>

<td>

<a href="index.php?page=Products_ProductoElectronica&mode=DSP&id={{id_producto}}">
Ver
</a>

<a href="index.php?page=Products_ProductoElectronica&mode=UPD&id={{id_producto}}">
Editar
</a>

<a href="index.php?page=Products_ProductoElectronica&mode=DEL&id={{id_producto}}">
Eliminar
</a>

</td>

</tr>

{{endfor productos}}

</tbody>

</table>