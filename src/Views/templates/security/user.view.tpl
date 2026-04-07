<style>
  .form-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 20px rgba(92,64,51,0.08); padding: 2rem; max-width: 650px; margin: 0 auto; }
  .form-card h2 { color: var(--cedro); font-size: 1.6rem; font-weight: 800; margin-bottom: 1.5rem; }
  .form-field { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.2rem; }
  .form-field label { color: var(--cedro); font-weight: 700; font-size: 0.9rem; }
  .form-input, .form-select { border: 1px solid #ddd; border-radius: 10px; padding: 0.7rem 0.9rem; font-size: 0.95rem; background: #fff; outline: none; width: 100%; font-family: inherit; transition: border-color 0.2s; box-sizing: border-box; }
  .form-input:focus, .form-select:focus { border-color: var(--dorado); box-shadow: 0 0 0 3px rgba(197,160,89,0.15); }
  .form-input[readonly], .form-select:disabled { background: #f5f5f5; color: #999; cursor: not-allowed; border-color: #e0e0e0; }
  .error { color: #c0392b; font-size: 0.85rem; background: #fdecea; padding: 0.4rem 0.7rem; border-radius: 8px; }
  .self-note { color: #7a6033; font-size: 0.82rem; background: #fdf6e3; border: 1px solid #e8d5a0; padding: 0.35rem 0.7rem; border-radius: 8px; }
  .form-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; }
  .btn-confirmar { background: var(--dorado); color: #fff; border: none; padding: 0.75rem 1.5rem; border-radius: 999px; font-weight: 700; cursor: pointer; font-size: 0.95rem; transition: background 0.2s; }
  .btn-confirmar:hover { background: var(--cedro); }
  .btn-eliminar-confirm { background: #c0392b; color: #fff; border: none; padding: 0.75rem 1.5rem; border-radius: 999px; font-weight: 700; cursor: pointer; font-size: 0.95rem; transition: background 0.2s; }
  .btn-eliminar-confirm:hover { background: #922b21; }
  .btn-cancelar { background: #fff; color: var(--cedro); border: 1px solid var(--cedro); padding: 0.75rem 1.5rem; border-radius: 999px; font-weight: 700; text-decoration: none; font-size: 0.95rem; transition: all 0.2s; display: inline-flex; align-items: center; }
  .btn-cancelar:hover { background: var(--cedro); color: #fff; }
</style>

<div class="form-card">
  <h2>{{FormTitle}}</h2>

  <form method="post" autocomplete="off" action="index.php?page=Security_User&mode={{mode}}&id={{u_usercod}}">

    <input type="hidden" name="usercod" value="{{u_usercod}}">

    <div class="form-field">
      <label>Nombre</label>
      {{fieldNombre}}
      {{if errorNombre}}<div class="error">{{errorNombre}}</div>{{endif errorNombre}}
    </div>

    <div class="form-field">
      <label>Email</label>
      {{fieldEmail}}
      {{if errorEmail}}<div class="error">{{errorEmail}}</div>{{endif errorEmail}}
    </div>

    {{if is_insert}}
    <div class="form-field">
      <label>Password</label>
      {{fieldPswd}}
      {{if errorPswd}}<div class="error">{{errorPswd}}</div>{{endif errorPswd}}
    </div>
    {{endif is_insert}}

    <div class="form-field">
      <label>Estado</label>
      {{fieldEstado}}
      {{if errorEstado}}<div class="error">{{errorEstado}}</div>{{endif errorEstado}}
      {{warningEstado}}
    </div>

    <div class="form-field">
      <label>Tipo</label>
      {{fieldTipo}}
      {{if errorTipo}}<div class="error">{{errorTipo}}</div>{{endif errorTipo}}
      {{warningTipo}}
    </div>

    <div class="form-actions">
      {{commitBtn}}
      <a href="index.php?page=Security_Users" class="btn-cancelar">Cancelar</a>
    </div>

  </form>
</div>