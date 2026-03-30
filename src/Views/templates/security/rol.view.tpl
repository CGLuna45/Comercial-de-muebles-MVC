<h2>{{FormTitle}}</h2>

{{if showCommitBtn}}
    <a href="index.php?page=Security_Roles">Volver a la lista</a>
{{endif showCommitBtn}}

<form method="post" action="index.php?page=Security_Rol">
    <input type="hidden" name="rolescod" value="{{rolescod}}">

    <label>Código</label>
    <input type="text" name="rolescod" value="{{rolescod}}" {{readonly}}>
    {{if rolescod_error}}<span class="error">{{rolescod_error}}</span>{{endif rolescod_error}}

    <label>Descripción</label>
    <input type="text" name="rolesdsc" value="{{rolesdsc}}" {{readonly}}>
    {{if rolesdsc_error}}<span class="error">{{rolesdsc_error}}</span>{{endif rolesdsc_error}}

    <label>Estado</label>
    <select name="rolesest" {{readonly}}>
        <option value="ACT" {{rolesest_act}}>Activo</option>
        <option value="INA" {{rolesest_ina}}>Inactivo</option>
    </select>
    {{if rolesest_error}}<span class="error">{{rolesest_error}}</span>{{endif rolesest_error}}

    {{if showCommitBtn}}
        <button type="submit">Guardar</button>
    {{endif showCommitBtn}}
    <a href="index.php?page=Security_Roles">Cancelar</a>
</form>