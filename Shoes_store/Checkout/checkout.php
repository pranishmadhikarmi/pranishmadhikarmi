<?php
include '../connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('User not logged in. Please login first.'); window.location.href = '../login.php';</script>";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $payment_method = isset($_POST['cod']) ? 'Cash on Delivery' : 'Unknown';

    // Validate input fields
    if (empty($name) || empty($email) || empty($address) || empty($phone)) {
        echo "<script>alert('Please fill in all details.'); window.history.back();</script>";
        exit();
    }

    if (!isset($_POST['cart_data']) || empty($_POST['cart_data'])) {
        echo "<script>alert('Your cart is empty.'); window.history.back();</script>";
        exit();
    }

    $cart_data = json_decode($_POST['cart_data'], true);
    if (empty($cart_data)) {
        echo "<script>alert('Invalid cart data.'); window.history.back();</script>";
        exit();
    }

    $total_price = 0;

    // Start transaction
    $con->begin_transaction();

    try {
        // Calculate total price and check stock availability
        foreach ($cart_data as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];

            // Fetch product quantity from database
            $stmt = $con->prepare("SELECT quantity FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if (!$row) {
                throw new Exception("Product ID: $product_id not found in database.");
            }

            if ($row['quantity'] < $quantity) {
                throw new Exception("Not enough stock for Product ID: $product_id. Available: {$row['quantity']}, Requested: $quantity");
            }

            $total_price += $item['price'] * $quantity;
        }

        // Insert order details into 'orders' table
        $stmt = $con->prepare("INSERT INTO orders (user_id, name, email, address, phone, payment_method, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing order insertion query: " . $con->error);
        }
        $stmt->bind_param("isssssd", $user_id, $name, $email, $address, $phone, $payment_method, $total_price);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting order: " . $stmt->error);
        }
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert order items into 'order_items' table
        $stmt = $con->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing order items insertion query: " . $con->error);
        }

        foreach ($cart_data as $item) {
            $stmt->bind_param("iisid", $order_id, $item['id'], $item['name'], $item['quantity'], $item['price']);
            if (!$stmt->execute()) {
            throw new Exception("Error inserting order items: " . $stmt->error);
            }
        }
        $stmt->close();

        // Update product stock in 'products' table
        foreach ($cart_data as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];

            // Fetch the latest stock before updating
            $stmt = $con->prepare("SELECT quantity FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if (!$row) {
                throw new Exception("Product ID: $product_id not found while updating stock.");
            }

            $new_quantity = $row['quantity'] - $quantity;

            $update_stmt = $con->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            if (!$update_stmt) {
                throw new Exception("Error preparing stock update query: " . $con->error);
            }
            $update_stmt->bind_param("ii", $new_quantity, $product_id);
            if (!$update_stmt->execute()) {
                throw new Exception("Error updating product stock: " . $update_stmt->error);
            }
            $update_stmt->close();
        }

        // Commit transaction
        $con->commit();

        // Clear cart after order is placed
        echo "<script>
            alert('Order placed successfully with Cash on Delivery!');
            localStorage.removeItem('cart');
            window.location.href = '../index.php';
        </script>";

    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        error_log("Order Error: " . $e->getMessage());
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
}
?>
