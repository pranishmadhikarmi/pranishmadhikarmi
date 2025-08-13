<?php
include '../connection.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    $con->query("DELETE FROM order_items WHERE order_id = $order_id");
    $con->query("DELETE FROM orders WHERE id = $order_id");

    header("Location: manage_orders.php");
}
?>
