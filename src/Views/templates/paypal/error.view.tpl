<style>
	.pay-cancel {
		max-width: 760px;
		margin: 40px auto;
		background: #ffffff;
		border-radius: 18px;
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
		padding: 26px;
		color: #5C4033;
		text-align: center;
	}

	.pay-cancel h2 {
		margin: 0 0 10px 0;
	}

	.pay-cancel p {
		margin: 0 0 16px 0;
	}

	.pay-cancel a {
		display: inline-block;
		text-decoration: none;
		border-radius: 999px;
		padding: 11px 20px;
		font-weight: 700;
		color: #ffffff;
		background: #C5A059;
		margin: 0 6px;
	}
</style>

<section class="pay-cancel">
	<h2>Pago no completado</h2>
	<p>{{message}}</p>
	<a href="index.php?page=Checkout_Checkout">Intentar de nuevo</a>
	<a href="carrito.php">Volver al carrito</a>
</section>