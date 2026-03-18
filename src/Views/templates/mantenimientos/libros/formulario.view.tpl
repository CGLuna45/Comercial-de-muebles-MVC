<h1>{{modeDsc}}</h1>
<section class="grid row">
    <form class="depth-0 offset-3 col-6" action="index.php?page=Mantenimientos_Libros_Formulario&mode={{mode}}&id={{id}}" method="POST" >
        <div class="row align-center">
            <div class="col-4">
                <label for="id">Código</label>
            </div>
            <div class="col-8">
                <input type="text" value="{{id}}" disabled  name="idux" id="id" readonly/>
                <input type="hidden" name="id" value="{{id}}" />
                <input type="hidden" name="uuid" value="{{xsrf_token}}" />
            </div>
        </div>
         <div class="row align-center">
            <div class="col-4">
                <label for="titulo">Título</label>
            </div>
            <div class="col-8">
                <input type="text" name="titulo" id="titulo" value="{{titulo}}" placeholder="Título del Libro" {{isReadonly}} />
            </div>
        </div>
        <div class="row align-start">
            <div class="col-4">
                <label for="resumen">Resumen</label>
            </div>
            <div class="col-8">
                <textarea id="resumen" name="resumen" placeholder="Resumen del Libro" cols="40" rows="8" {{isReadonly}}>{{resumen}}</textarea>
            </div>
        </div>
         <div class="row align-center">
            <div class="col-4">
                <label for="autor">Autor</label>
            </div>
            <div class="col-8">
                <input type="text" name="autor" id="autor" value="{{autor}}" {{isReadonly}} placeholder="Autor del Libro" />
            </div>
        </div>
         <div class="row align-center">
            <div class="col-4">
                <label for="fecha_publicacion">Fecha Publicación</label>
            </div>
            <div class="col-8">
                <input type="text" name="fecha_publicacion" id="fecha_publicacion" {{isReadonly}} value="{{fecha_publicacion}}" placeholder="Publicado en" />
            </div>
        </div>
        <div class="row align-center">
            <div class="col-4">
                <label for="genero">Género</label>
            </div>
            <div class="col-8">
                <input type="text" name="genero" id="genero" value="{{genero}}" {{isReadonly}} placeholder="Generó Literario del Libro" />
            </div>
        </div>
        <div class="row align-center">
            <div class="col-4">
                <label for="precio">Precio</label>
            </div>
            <div class="col-8">
                <input type="text" name="precio" id="precio" value="{{precio}}" {{isReadonly}} placeholder="Precio del Libro" />
            </div>
        </div>
        {{if confirmToolTip}}
            <div class="error">
                {{confirmToolTip}}
            </div>
        {{endif confirmToolTip}}
        <div class="right">
            {{ifnot hideConfirm}}
            <button type="submit" name="btnEnviar">Confirmar</button>
            {{endifnot hideConfirm}}
            &nbsp;
            <button id="btnCancelar">Cancelar</button>
        </div>
    </form>
</section>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
        document.getElementById("btnCancelar").addEventListener("click", (e)=>{
            e.preventDefault();
            e.stopPropagation();
            window.location.assign("index.php?page=Mantenimientos_Libros_Listado");
        });
    });
</script>