<section class="fullCenter login-wrapper">
  <form class="login-card" method="post" action="index.php?page=Sec_Register" novalidate>
    <h1 class="section-title">Crea tu cuenta</h1>

    <div class="form-group">
      <label for="txtEmail">Correo electrónico</label>
      <input class="input" type="email" id="txtEmail" name="txtEmail" value="{{txtEmail}}" required autocomplete="email" placeholder="usuario@dominio.com" />
      {{if errorEmail}}
        <div class="error">{{errorEmail}}</div>
      {{endif errorEmail}}
    </div>

    <div class="form-group">
      <label for="txtPswd">Contraseña</label>
      <input class="input" type="password" id="txtPswd" name="txtPswd" value="{{txtPswd}}" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
      {{if errorPswd}}
        <div class="error">{{errorPswd}}</div>
      {{endif errorPswd}}
    </div>

    {{if generalError}}
      <div class="error general-error">{{generalError}}</div>
    {{endif generalError}}

    <div class="actions">
      <button class="btn-primary" id="btnSignin" type="submit">Crear Cuenta</button>
    </div>

    <p class="note">¿Ya tienes cuenta? <a href="index.php?page=sec_login">Inicia sesión</a></p>
  </form>
</section>
