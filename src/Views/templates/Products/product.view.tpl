<style>
  .form-card {
    background: #fff; border-radius: 18px;
    box-shadow: 0 4px 20px rgba(92,64,51,0.08);
    padding: 2rem; max-width: 650px; margin: 0 auto;
  }
  .form-card h2 { color: var(--cedro); font-size: 1.6rem; font-weight: 800; margin-bottom: 1.5rem; }
  .form-field { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.2rem; }
  .form-field label { color: var(--cedro); font-weight: 700; font-size: 0.9rem; }
  .form-field input, .form-field textarea, .form-field select {
    border: 1px solid #ddd; border-radius: 10px;
    padding: 0.7rem 0.9rem; font-size: 0.95rem;
    background: #fff; outline: none; width: 100%;
    font-family: inherit;
  }
  .form-field input:focus, .form-field textarea:focus, .form-field select:focus {
    border-color: var(--dorado);
    box-shadow: 0 0 0 3px rgba(197,160,89,0.15);
  }
  .form-field input[disabled], .form-field input[readonly] {
    background: #f5f5f5; color: #999; cursor: not-allowed;
  }
  .form-field textarea { resize: vertical; min-height: 100px; }
  .form-field .error {
    color: #c0392b; font-size: 0.85rem;
    background: #fdecea; padding: 0.4rem 0.7rem;
    border-radius: 8px; border: 1px solid rgba(192,57,43,0.2);
  }
  .form-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; }
  .btn-confirmar {
    background: var(--dorado); color: #fff; border: none;
    padding: 0.75rem 1.5rem; border-radius: 999px;
    font-weight: 700; cursor: pointer; font-size: 0.95rem;
    transition: background 0.2s;
  }
  .btn-confirmar:hover { background: var(--cedro); }
  .btn-cancelar {
    background: #fff; color: var(--cedro);
    border: 1px solid var(--cedro);
    padding: 0.75rem 1.5rem; border-radius: 999px;
    font-weight: 700; cursor: pointer; font-size: 0.95rem;
    transition: all 0.2s;
  }
  .btn-cancelar:hover { background: var(--cedro); color: #fff; }
</style>

<div class="form-card">
  <h2>{{FormTitle}}</h2>
  {{with product}}
  <form action="index.php?page=Products_Product&mode={{~mode}}&id={{productId}}" method="POST">
    <input type="hidden" name="mode" value="{{~mode}}" />
    <input type="hidden" name="productId" value="{{productId}}" />
    <input type="hidden" name="token" value="{{~product_xss_token}}" />

    <div class="form-field">
      <label for="productId">Código</label>
      <input readonly disabled type="text" name="productId" id="productIdD" value="{{productId}}" />
    </div>

    <div class="form-field">
  <label for="categoriaId">Categoría</label>
  <select name="categoriaId" id="categoriaId" {{if ~readonly}} disabled {{endif ~readonly}}>
    {{foreach ~categorias}}
    <option value="{{categoriaId}}" {{if selected}}selected{{endif selected}}>{{categoriaNombre}}</option>
    {{endfor ~categorias}}
  </select>
</div>

    <div class="form-field">
      <label for="productName">Nombre del Producto</label>
      <input {{~readonly}} type="text" name="productName" id="productName" placeholder="Nombre del producto" value="{{productName}}" />
      {{if productName_error}}<div class="error">{{productName_error}}</div>{{endif productName_error}}
    </div>

    <div class="form-field">
      <label for="productDescription">Descripción</label>
      <textarea {{~readonly}} name="productDescription" id="productDescription" placeholder="Descripción del producto">{{productDescription}}</textarea>
      {{if productDescription_error}}<div class="error">{{productDescription_error}}</div>{{endif productDescription_error}}
    </div>

    <div class="form-field">
      <label for="productPrice">Precio (L.)</label>
      <input {{~readonly}} type="number" name="productPrice" id="productPrice" placeholder="0.00" value="{{productPrice}}" />
      {{if productPrice_error}}<div class="error">{{productPrice_error}}</div>{{endif productPrice_error}}
    </div>

    <div class="form-field">
      <label for="productImgUrl">URL de Imagen</label>
      <input {{~readonly}} type="text" name="productImgUrl" id="productImgUrl" placeholder="img/nombre-imagen.jpg" value="{{productImgUrl}}" />
      {{if productImgUrl_error}}<div class="error">{{productImgUrl_error}}</div>{{endif productImgUrl_error}}
    </div>

    <div class="form-field">
      <label for="productStatus">Estado</label>
      <select name="productStatus" id="productStatus" {{if ~readonly}} disabled {{endif ~readonly}}>
        <option value="ACT" {{productStatus_act}}>Activo</option>
        <option value="INA" {{productStatus_ina}}>Inactivo</option>
      </select>
    </div>
    {{endwith product}}

    <div class="form-actions">
      {{if showCommitBtn}}
      <button class="btn-confirmar" type="submit" name="btnConfirmar">Confirmar</button>
      {{endif showCommitBtn}}
      <button class="btn-cancelar" type="button" id="btnCancelar">
        {{if showCommitBtn}}Cancelar{{endif showCommitBtn}}
        {{ifnot showCommitBtn}}Regresar{{endifnot showCommitBtn}}
      </button>
    </div>
  </form>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("btnCancelar").addEventListener("click", (e) => {
      e.preventDefault();
      window.location.assign("index.php?page=Products_Products");
    });
  });
</script>