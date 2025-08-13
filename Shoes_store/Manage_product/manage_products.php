<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location:../index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM `products` WHERE `id` = $id");
    header("location:manage_products.php");
}

$products = mysqli_query($con, "SELECT * FROM `products`ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="manage_products.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="/Manage_product/manage_products.php" class="active">Manage Products</a></li>
                <li><a href="../Manage_users/manage_users.php">Manage Users</a></li>
                <li><a href="../Manage_order/manage_orders.php">Manage Orders</a></li>
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
    <h2 class="page-title">Manage Products</h2>
    <div class="actions">
        <a href="add_product.php" class="btn btn-add">➕ Add Product</a>
    </div>

    <div class="table-container">
        <table class="product-table">
        <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php while ($row = mysqli_fetch_assoc($products)) : ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td>Rs <?= number_format($row['price'], 2); ?></td>
            <td><?= $row['quantity']; ?></td> 
            <td>
                <img src="../uploads/<?= $row['image']; ?>" alt="Product Image" style="width: 70px; height: 70px; object-fit: cover;">
            </td>
            <td>
                <a href="edit_product.php?id=<?= $row['id']; ?>" class="edit-btn">✏️ Edit</a>
                <a href="manage_products.php?delete=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

        </table>
    </div>
</main>

    </div>
</body>
</html>
