
<html >
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="checkoutForm.css">
</head>
<body>

    <div class="checkout-container">
        <h2>Checkout</h2>

        <div id="cart-summary"></div>

        <div class="cart-total">
            <strong>Total: Rs <span id="total-price">0</span></strong>
        </div>

        <form id="checkout-form" action="../checkout_product.php" method="POST">
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" >

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" >

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" >

    <label for="phone">Phone Number:</label>
    <input type="tel" id="phone" name="phone" >

    <div class="payment-method">
        <input type="checkbox" id="cod" name="cod" value="1">
        <label for="cod">Cash on Delivery</label>
    </div>

    <input type="hidden" id="cart-data" name="cart_data">

    <button type="submit" id="place-order" class="place-order">Place Order</button>
    
</form>

    </div>

    <script src="checkout.js"></script>
</body>
</html>
