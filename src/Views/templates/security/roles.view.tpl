<h2>Roles</h2>

<section class="grid">
    <div class="row">
        <form class="col-12 col-m-8" action="index.php" method="get">
            <div class="flex align-center">
                <div class="col-8 row">
                    <input type="hidden" name="page" value="Security_Roles">
                    <label class="col-3" for="partialName">Nombre o Código</label>
                    <input class="col-9" type="text" name="partialName" id="partialName" value="{{partialName}}" />
                    <label class="col-3" for="status">Estado</label>
                    <select class="col-9" name="status" id="status">
                        <option value="EMP" {{status_EMP}}>Todos</option>
                        <option value="ACT" {{status_ACT}}>Activo</option>
                        <option value="INA" {{status_INA}}>Inactivo</option>
                    </select>
                </div>
                <div class="col-4 align-end">
                    <button type="submit">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>
                    {{ifnot OrderByRolescod}}
                    <a href="index.php?page=Security_Roles&orderBy=rolescod&orderDescending=0">Código <i class="fas fa-sort"></i></a>
                    {{endifnot OrderByRolescod}}
                    {{if OrderByRolescodDesc}}
                    <a href="index.php?page=Security_Roles&orderBy=clear&orderDescending=0">Código <i class="fas fa-sort-down"></i></a>
                    {{endif OrderByRolescodDesc}}
                    {{if OrderByRolescod}}
                    <a href="index.php?page=Security_Roles&orderBy=rolescod&orderDescending=1">Código <i class="fas fa-sort-up"></i></a>
                    {{endif OrderByRolescod}}
                </th>
                <th class="left">
                    {{ifnot OrderByRolesdsc}}
                    <a href="index.php?page=Security_Roles&orderBy=rolesdsc&orderDescending=0">Descripción <i class="fas fa-sort"></i></a>
                    {{endifnot OrderByRolesdsc}}
                    {{if OrderByRolesdscDesc}}
                    <a href="index.php?page=Security_Roles&orderBy=clear&orderDescending=0">Descripción <i class="fas fa-sort-down"></i></a>
                    {{endif OrderByRolesdscDesc}}
                    {{if OrderByRolesdsc}}
                    <a href="index.php?page=Security_Roles&orderBy=rolesdsc&orderDescending=1">Descripción <i class="fas fa-sort-up"></i></a>
                    {{endif OrderByRolesdsc}}
                </th>
                <th>
                    {{ifnot OrderByRolesest}}
                    <a href="index.php?page=Security_Roles&orderBy=rolesest&orderDescending=0">Estado <i class="fas fa-sort"></i></a>
                    {{endifnot OrderByRolesest}}
                    {{if OrderByRolesestDesc}}
                    <a href="index.php?page=Security_Roles&orderBy=clear&orderDescending=0">Estado <i class="fas fa-sort-down"></i></a>
                    {{endif OrderByRolesestDesc}}
                    {{if OrderByRolesest}}
                    <a href="index.php?page=Security_Roles&orderBy=rolesest&orderDescending=1">Estado <i class="fas fa-sort-up"></i></a>
                    {{endif OrderByRolesest}}
                </th>
                <th><a href="index.php?page=Security_Rol&mode=INS">Nuevo</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach roles}}
            <tr>
                <td>{{rolescod}}</td>
                <td>{{rolesdsc}}</td>
                <td>
                    {{if rolesest == 'ACT'}}Activo{{endif rolesest == 'ACT'}}
                    {{if rolesest == 'INA'}}Inactivo{{endif rolesest == 'INA'}}
                </td>
                <td class="center">
                    <a href="index.php?page=Security_Rol&mode=DSP&id={{rolescod}}">Ver</a>
                    &nbsp;
                    <a href="index.php?page=Security_Rol&mode=UPD&id={{rolescod}}">Editar</a>
                    &nbsp;
                    <a href="index.php?page=Security_Rol&mode=DEL&id={{rolescod}}">Eliminar</a>
                </td>
            </tr>
            {{endfor roles}}
        </tbody>
    </table>
    {{pagination}}
</section>