<section class="container">
    <table class="">
        <thead>
            <tr>
                <th>Código</th>
                <th>Título</th>
                <th>Género</th>
                <th>
                    <a href="index.php?page=Mantenimientos-Libros-Formulario&mode=INS&id=0">Nuevo</a>
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach libros}}
            <tr>
                <td>{{id}}</td>
                <td>{{titulo}}</td>
                <td>{{genero}}</td>
                <td>
                    <a href="index.php?page=Mantenimientos-Libros-Formulario&mode=DSP&id={{id}}">Mostrar</a>
                    <br/>
                    <a href="index.php?page=Mantenimientos-Libros-Formulario&mode=UPD&id={{id}}">Actualizar</a>
                    <br/>
                    <a href="index.php?page=Mantenimientos-Libros-Formulario&mode=DEL&id={{id}}">Eliminar</a>
                </td>
            </tr>
            {{endfor libros}}
        </tbody>
    </table>
</section>