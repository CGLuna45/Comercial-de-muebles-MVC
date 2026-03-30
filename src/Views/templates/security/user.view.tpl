<h2>{{FormTitle}}</h2>

<a href="index.php?page=Security_User&mode=INS">Nuevo Usuario</a>


<form method="post" action="index.php?page=Security_User&mode={{mode}}{{if usercod}}&id={{usercod}}{{endif usercod}}" class="user-form">
    <input type="hidden" name="usercod" value="{{usercod}}">
    
    <label>Nombre</label>
    <input type="text" name="username" value="{{username}}" {{readonly}}>
    {{if username_error}}<span class="error">{{username_error}}</span>{{endif username_error}}

    <label>Email</label>
    <input type="email" name="useremail" value="{{useremail}}" {{readonly}}>
    {{if useremail_error}}<span class="error">{{useremail_error}}</span>{{endif useremail_error}}

    <label>Password</label>
    <input type="password" name="userpswd" value="{{userpswd}}" {{readonly}}>
    {{if userpswd_error}}<span class="error">{{userpswd_error}}</span>{{endif userpswd_error}}

   <label>Estado</label>
<select name="userest" {{readonly}}>
    <option value="ACT" {{userest_act}}>Activo</option>
    <option value="INA" {{userest_ina}}>Inactivo</option>
</select>

<label>Tipo</label>
<select name="usertipo" {{readonly}}>
    <option value="NOR" {{usertipo_nor}}>Normal</option>
    <option value="ADM" {{usertipo_adm}}>Administrador</option>
    <option value="CON" {{usertipo_con}}>Consultor</option>
</select>

    {{if showCommitBtn}}
        <button type="submit">Guardar</button>
    {{endif showCommitBtn}}
    <a href="index.php?page=Security_Users">Cancelar</a>
</form>