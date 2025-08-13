<?php
include '../connection.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $order_id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        echo "Order status updated!";
    } else {
        echo "Error updating status!";
    }
}
?>
