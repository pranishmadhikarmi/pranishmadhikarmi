<?php
include 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cart_data'])) {
        $cart_data = json_decode($_POST['cart_data'], true);
        $user_id = $_SESSION['user_id'];

        if (!empty($cart_data)) {
            $con->begin_transaction(); 

            $order_query = "INSERT INTO orders (user_id, total_price, payment_method) VALUES (?, ?, 'Cash on Delivery')";
            $stmt_order = $con->prepare($order_query);

            $total_price = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart_data));

            $stmt_order->bind_param("id", $user_id, $total_price);
            $stmt_order->execute();
            $order_id = $stmt_order->insert_id; 

            $stmt_items = $con->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");

            foreach ($cart_data as $item) {
                $product_id = isset($item['product_id']) ? $item['product_id'] : 0; // ✅ Prevent error
                $product_name = $item['name'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                $stmt_items->bind_param("iisid", $order_id, $product_id, $product_name, $quantity, $price);
                $stmt_items->execute();
            }

            $con->commit(); 
            echo "Order placed successfully!";
        } else {
            echo "Cart is empty!";
        }
    } else {
        echo "No cart data received!";
    }
} else {
    echo "Invalid request!";
}

$con->close();
?>