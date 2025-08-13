<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location:../index.php");
    exit();
}

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    mysqli_query($con, "UPDATE orders SET status='$status' WHERE id='$order_id'");
    header("Location: manage_orders.php");
}

// Delete order
if (isset($_GET['delete'])) {
    $order_id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM orders WHERE id='$order_id'");
    header("Location: manage_orders.php");
}

// Fetch orders
$query = "SELECT * FROM orders ORDER BY created_at DESC";
$result = $con->query($query);?>

<html >
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="manage_orders.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="../Manage_product/manage_products.php">Manage Products</a></li>
                <li><a href="../Manage_users/manage_users.php">Manage Users</a></li>
                <li><a href="manage_orders.php" class="active">Manage Orders</a></li>
                <li><a href="../View_contact/view_messages.php">View Message</a></li>
                <li><a href="../best_selling.php">Best Selling</a></li>
                <li><a href="../logout.php" onclick="return confirmLogout()">Logout</a></li>
            </ul>
        </aside>
        <script>
                    function confirmLogout() {
                        return confirm("Are you sure you want to log out?");
                    }
                </script>

<main class="main-content"> 
    <h1 class="page-title">Manage Orders</h1>
    <div class="table-container">
        <table class="order-table">
            <thead>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Payment Method</th>
                <th>Total Price</th>
                <th>Items</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td>Rs <?= number_format($row['total_price'], 2) ?></td>
                        <td>
                            <?php
                            $order_id = $row['id'];
                            $items_result = $con->query("SELECT * FROM order_items WHERE order_id = $order_id");
                            while ($item = $items_result->fetch_assoc()) {
                                echo "{$item['product_name']} (x{$item['quantity']}) - Rs {$item['price']}<br>";
                            }
                            ?>
                        </td>
                        <td>
                            <select class="status-dropdown" data-order-id="<?= $row['id'] ?>">
                                <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                <option value="Processing" <?= ($row['status'] == 'Processing') ? 'selected' : '' ?>>Processing</option>
                                <option value="Shipped" <?= ($row['status'] == 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= ($row['status'] == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                                <option value="Cancelled" <?= ($row['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="delete_order.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

    </div>
</body>
</html>
