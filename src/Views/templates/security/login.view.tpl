<section class="fullCenter login-wrapper">
  <form class="login-card" method="post"
    action="index.php?page=Sec_Login{{if redirto}}&redirto={{redirto}}{{endif redirto}}" novalidate>
    <h1 class="section-title">Iniciar Sesión</h1>

    <div class="form-group">
      <label for="txtEmail">Correo electrónico</label>
      <input class="input" type="email" id="txtEmail" name="txtEmail" value="{{txtEmail}}" required autocomplete="email"
        placeholder="usuario@dominio.com" />
      {{if errorEmail}}
      <div class="error">{{errorEmail}}</div>
      {{endif errorEmail}}
    </div>

    <div class="form-group">
      <label for="txtPswd">Contraseña</label>
      <input class="input" type="password" id="txtPswd" name="txtPswd" value="{{txtPswd}}" required
        autocomplete="current-password" placeholder="********" />
      {{if errorPswd}}
      <div class="error">{{errorPswd}}</div>
      {{endif errorPswd}}
    </div>

    {{if generalError}}
    <div class="error general-error">{{generalError}}</div>
    {{endif generalError}}

    <div class="actions">
      <button class="btn-primary" id="btnLogin" type="submit">Iniciar Sesión</button>
    </div>

    <p class="note">¿Aún no tienes cuenta? <a href="index.php?page=sec_register">Regístrate</a></p>
  </form>
</section>