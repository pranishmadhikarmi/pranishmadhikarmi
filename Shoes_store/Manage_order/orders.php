<?php
include '../connection.php'; // Ensure you have a database connection

$query = "SELECT * FROM orders ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Payment</th>
            <th>Total Price</th>
            <th>Items</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['payment_method'] ?></td>
                <td>Rs <?= number_format($row['total_price'], 2) ?></td>
                <td>
                    <?php
                    $order_id = $row['id'];
                    $items_result = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
                    while ($item = $items_result->fetch_assoc()) {
                        echo "{$item['product_name']} (x{$item['quantity']}) - Rs {$item['price']}<br>";
                    }
                    ?>
                </td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
