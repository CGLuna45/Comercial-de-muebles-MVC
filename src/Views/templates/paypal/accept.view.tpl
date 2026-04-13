<style>
  .invoice-wrap {
    max-width: 1100px;
    margin: 36px auto;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    color: #5C4033;
  }

  .invoice-head {
    background: linear-gradient(135deg, #5C4033, #7a5841);
    color: #ffffff;
    padding: 28px 30px;
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
  }

  .invoice-brand {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .invoice-brand img {
    width: 56px;
    height: 56px;
    object-fit: contain;
  }

  .invoice-brand h2 {
    margin: 0;
    font-size: 1.55rem;
    letter-spacing: 1px;
  }

  .invoice-meta {
    text-align: right;
    line-height: 1.6;
  }

  .invoice-body {
    padding: 28px 30px 34px;
  }

  .invoice-status {
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 20px;
    font-weight: 700;
  }

  .invoice-ok {
    background: #eaf7ef;
    color: #1e7a3f;
    border: 1px solid #bee2ca;
  }

  .invoice-warn {
    background: #fdeaea;
    color: #9d2323;
    border: 1px solid #f1c1c1;
  }

  .invoice-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
  }

  .invoice-card {
    background: #f7f1eb;
    border-radius: 14px;
    padding: 18px 20px;
  }

  .invoice-card h3 {
    margin: 0 0 10px 0;
    font-size: 1rem;
    color: #5C4033;
  }

  .invoice-card p {
    margin: 4px 0;
  }

  .invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin: 8px 0 24px;
    background: #ffffff;
  }

  .invoice-table th,
  .invoice-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #ece4dc;
    text-align: left;
    vertical-align: middle;
  }

  .invoice-table th {
    background: #f7f1eb;
    color: #5C4033;
    font-weight: 800;
  }

  .invoice-table .right {
    text-align: right;
  }

  .invoice-summary {
    margin-left: auto;
    max-width: 420px;
    background: #f7f1eb;
    border-radius: 14px;
    padding: 18px 20px;
    margin-bottom: 20px;
  }

  .invoice-summary p {
    margin: 6px 0;
    display: flex;
    justify-content: space-between;
    gap: 10px;
  }

  .invoice-summary .total {
    font-size: 1.15rem;
    font-weight: 800;
    border-top: 1px dashed #d7c7b7;
    padding-top: 10px;
    margin-top: 10px;
  }

  .invoice-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 14px;
  }

  .invoice-btn {
    display: inline-block;
    text-decoration: none;
    border-radius: 999px;
    padding: 11px 20px;
    font-weight: 700;
    color: #ffffff;
    background: #C5A059;
  }

  .invoice-btn.secondary {
    background: #5C4033;
  }

  .invoice-json {
    margin-top: 18px;
    padding: 16px;
    border-radius: 12px;
    background: #fbfbfb;
    border: 1px solid #e8dfd4;
    overflow-x: auto;
    display: none;
  }

  .invoice-note {
    margin-top: 18px;
    color: #7b6a5b;
    font-size: 0.95rem;
  }

  @media (max-width: 768px) {
    .invoice-grid {
      grid-template-columns: 1fr;
    }

    .invoice-meta {
      text-align: left;
    }
  }
</style>

<section class="invoice-wrap">
  <div class="invoice-head">
    <div class="invoice-brand">
      <img src="img/logo-cedrika.png" alt="Cédrika">
      <div>
        <h2>CÉDRIKA</h2>
        <div>Factura de compra</div>
      </div>
    </div>
    <div class="invoice-meta">
      <div><strong>Factura:</strong> {{invoiceNumber}}</div>
      <div><strong>Fecha:</strong> {{paymentDate}}</div>
      <div><strong>Orden:</strong> {{paymentId}}</div>
    </div>
  </div>

  <div class="invoice-body">
    {{if isSuccess}}
    <div class="invoice-status invoice-ok">{{message}}</div>
    {{endif isSuccess}}
    {{ifnot isSuccess}}
    <div class="invoice-status invoice-warn">{{message}}</div>
    {{endifnot isSuccess}}

    <div class="invoice-grid">
      <div class="invoice-card">
        <h3>Datos del cliente</h3>
        <p><strong>Nombre:</strong> {{payerName}}</p>
        <p><strong>Correo:</strong> {{payerEmail}}</p>
      </div>
      <div class="invoice-card">
        <h3>Resumen de pago</h3>
        <p><strong>Estado:</strong> {{if isSuccess}}Completado{{endif isSuccess}}{{ifnot isSuccess}}Pendiente{{endifnot isSuccess}}</p>
        <p><strong>Método:</strong> PayPal</p>
        <p><strong>Moneda:</strong> USD</p>
      </div>
    </div>

    <table class="invoice-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th class="right">Precio</th>
          <th class="right">Cantidad</th>
          <th class="right">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        {{foreach items}}
        <tr>
          <td>{{name}}</td>
          <td class="right">L {{price}}</td>
          <td class="right">{{quantity}}</td>
          <td class="right">L {{lineSubtotal}}</td>
        </tr>
        {{endfor items}}
      </tbody>
    </table>

    <div class="invoice-summary">
      <p><span>Subtotal</span><strong>L {{subtotal}}</strong></p>
      <p><span>ISV estimado</span><strong>L {{tax}}</strong></p>
      <p class="total"><span>Total pagado</span><strong>L {{total}}</strong></p>
    </div>

    <div class="invoice-actions">
      <a class="invoice-btn" href="index.php">Ir al inicio</a>
      <a class="invoice-btn secondary" href="catalogo.php">Seguir comprando</a>
    </div>

    {{if orderjson}}
    <div class="invoice-json">
      <pre>{{orderjson}}</pre>
    </div>
    {{endif orderjson}}

    <div class="invoice-note">
      Gracias por su compra. Esta factura resume la orden procesada en PayPal.
    </div>
  </div>
</section>
