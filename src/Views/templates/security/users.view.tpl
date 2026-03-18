<h2>Usuarios</h2>

<a href="index.php?page=Security_User&mode=INS">Nuevo Usuario</a>
<section class="WWList">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        {{foreach users}}
        <tr>
            <td>{{usercod}}</td>
            <td>{{username}}</td>
            <td>{{useremail}}</td>
            <td>{{userest}}</td>
            <td>
                <a class="center" href="index.php?page=Security_User&mode=DSP&id={{usercod}}">Ver</a>
                <a class="center" href="index.php?page=Security_User&mode=UPD&id={{usercod}}">Editar</a>
                <a class="center" href="index.php?page=Security_User&mode=DEL&id={{usercod}}">Eliminar</a>
            </td>
        </tr>
        {{endfor users}}
    </tbody>
</table>

{{pagination}}
</section>