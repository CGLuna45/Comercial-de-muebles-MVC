<h1>Hello World</h1>

<form action="index.php?page=Productos-Hello" method="POST">
    <label for="txtNombre">Nombre Completo</label>
    <input type="text" 
        name="txtNombre" 
        id="txtNombre"
        placeholder="Nombre Completo"
        value="{{txtNombre}}"
    />
    <br/>
    <button type="submit" name="btnEnviar">Enviar</button>
</form>

{{if mensajeFinal}}
    <section>
        {{mensajeFinal}}
    </section>
{{endif mensajeFinal}}