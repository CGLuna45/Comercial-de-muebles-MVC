<style>
  .checkout-wrap {
    max-width: 980px;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .checkout-head {
    background: #5C4033;
    color: #ffffff;
    padding: 24px;
  }

  .checkout-head h2 {
    margin: 0;
    font-size: 1.4rem;
  }

  .checkout-body {
    padding: 24px;
  }

  .checkout-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .checkout-table th,
  .checkout-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #eee;
    text-align: left;
  }

  .checkout-table th {
    color: #5C4033;
    font-weight: 700;
  }

  .checkout-summary {
    background: #F7F1EB;
    border-radius: 12px;
    padding: 18px;
    margin: 14px 0 20px 0;
  }

  .checkout-summary p {
    margin: 6px 0;
    color: #5C4033;
  }

  .checkout-summary .total {
    font-size: 1.2rem;
    font-weight: 800;
  }

  .checkout-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .btn-main,
  .btn-alt {
    border: 0;
    border-radius: 999px;
    padding: 12px 22px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
  }

  .btn-main {
    background: #C5A059;
    color: #ffffff;
  }

  .btn-alt {
    background: #5C4033;
    color: #ffffff;
  }

  .checkout-error {
    background: #fdeaea;
    color: #a52222;
    border: 1px solid #f3c7c7;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 16px;
  }
</style>

<section class="checkout-wrap">
  <div class="checkout-head">
    <h2>Resumen de pago con PayPal</h2>
    <p>Productos en carrito: {{cartCount}}</p>
  </div>

  <div class="checkout-body">
    {{if generalError}}
    <div class="checkout-error">{{generalError}}</div>
    {{endif generalError}}

    <table class="checkout-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        {{foreach cartItems}}
        <tr>
          <td>{{name}}</td>
          <td>L {{price}}</td>
          <td>{{quantity}}</td>
          <td>L {{lineSubtotal}}</td>
        </tr>
        {{endfor cartItems}}
      </tbody>
    </table>

    <div class="checkout-summary">
      <p>Subtotal: <strong>L {{subtotal}}</strong></p>
      <p>ISV estimado (15%): <strong>L {{tax}}</strong></p>
      <p class="total">Total: L {{total}}</p>
    </div>

    <form action="index.php?page=Checkout_Checkout" method="post">
      <div class="checkout-actions">
        <button class="btn-main" type="submit">Proceder con PayPal</button>
        <a class="btn-alt" href="carrito.php">Volver al carrito</a>
      </div>
    </form>
  </div>
</section>
