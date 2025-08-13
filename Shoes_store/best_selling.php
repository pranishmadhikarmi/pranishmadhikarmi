<?php
session_start();
include('connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location:index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM `best_selling` WHERE `id` = $id");
    header("location:best_selling.php");
}

$best_selling = mysqli_query($con, "SELECT * FROM `best_selling`ORDER BY id DESC");
?>

<html >
<head>
    <title>Best Selling Products</title>
    <link rel="stylesheet" href="../Manage_product/manage_products.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="/Manage_product/manage_products.php" >Manage Products</a></li>
                <li><a href="../Manage_users/manage_users.php">Manage Users</a></li>
                <li><a href="../Manage_order/manage_orders.php">Manage Orders</a></li>
                <li><a href="../View_contact/view_messages.php">View Message</a></li>
                <li><a href="best_selling.php" class="active">Best Selling</a></li>
                <li><a href="logout.php" onclick="return confirmLogout()">Logout</a></li>
            </ul>
        </aside>
        <script>
                    function confirmLogout() {
                        return confirm("Are you sure you want to log out?");
                    }
                </script>  

        <main class="main-content">
            <h2 class="page-title">Best Selling Products</h2>
            <div class="actions">
                <a href="Add_best_selling_product.php" class="btn btn-add">➕ Add Best Selling Products</a>
            </div>

            <div class="table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Actual Price</th>
                            <th>Discount</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($best_selling)) : ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['name']; ?></td>
                                <td><?= $row['quantity']; ?></td> 
                                <td>Rs <?= number_format($row['price'], 2); ?></td>
                                <td>Rs <?= number_format($row['actual_price'], 2); ?></td>
                                <td><?= $row['discount']; ?>%</td>
                                <td>
                                      <img src="../uploads/<?= $row['image']; ?>" alt="Product Image" style="width: 70px; height: 70px; object-fit: cover;">
                                 </td>
                                <td>
                                    <a href="edit_best_selling.php?id=<?= $row['id']; ?>" class="edit-btn">✏️ Edit</a>
                                    <a href="../best_selling.php?delete=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
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
