<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shoes_store');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete orders.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // Delete only if the order belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);

    if ($stmt->execute()) {
        echo "Order deleted successfully.";
        header("Location: view_Order.php"); // Redirect to orders page
    } else {
        echo "Failed to delete order.";
    }

    $stmt->close();
}

$conn->close();
?>
