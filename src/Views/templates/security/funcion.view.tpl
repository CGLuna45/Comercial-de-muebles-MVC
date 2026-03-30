<h2>{{FormTitle}}</h2>

{{if showCommitBtn}}
    <a href="index.php?page=Security_Funciones">Volver a la lista</a>
{{endif showCommitBtn}}

<form method="post" action="index.php?page=Security_Funcion">
    <input type="hidden" name="fncod" value="{{fncod}}">

    <label>Código</label>
    <input type="text" name="fncod" value="{{fncod}}" {{readonly}}>
    {{if fncod_error}}<span class="error">{{fncod_error}}</span>{{endif fncod_error}}

    <label>Descripción</label>
    <input type="text" name="fndsc" value="{{fndsc}}" {{readonly}}>
    {{if fndsc_error}}<span class="error">{{fndsc_error}}</span>{{endif fndsc_error}}

    <label>Estado</label>
    <select name="fnest" {{readonly}}>
        <option value="ACT" {{fnest_act}}>Activo</option>
        <option value="INA" {{fnest_ina}}>Inactivo</option>
    </select>
    {{if fnest_error}}<span class="error">{{fnest_error}}</span>{{endif fnest_error}}

    <label>Tipo</label>
    <select name="fntyp" {{readonly}}>
        <option value="MNU" {{fntyp_mnu}}>Menú</option>
        <option value="FNC" {{fntyp_fnc}}>Función</option>
        <option value="CTL" {{fntyp_ctl}}>Controlador</option>
    </select>
    {{if fntyp_error}}<span class="error">{{fntyp_error}}</span>{{endif fntyp_error}}

    {{if showCommitBtn}}
        <button type="submit">Guardar</button>
    {{endif showCommitBtn}}
    <a href="index.php?page=Security_Funciones">Cancelar</a>
</form>