<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>


<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin.php" class="active">Dashboard</a></li>
                <li><a href="../Manage_product/manage_products.php">Manage Products</a></li>
                <li><a href="../Manage_users/manage_users.php">Manage Users</a></li>
                <li><a href="../Manage_order/manage_orders.php">Manage Orders</a></li>
                <li><a href="../View_contact/view_messages.php">View Message</a></li>
                <li><a href="../best_selling.php">Best Selling</a></li>
                <li><a href="../logout.php" onclick="return confirmLogout()">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="dashboard-header">
                <h1>Welcome, Admin</h1>
            </div>

            <div class="dashboard-cards">
                <a href="../Manage_product/manage_products.php" class="card manage-products">
                    <h3>Manage Products</h3>
                </a>
                <a href="../Manage_users/manage_users.php" class="card manage-users">
                    <h3>Manage<br> Users</h3>
                </a>
                <a href="../Manage_order/manage_orders.php" class="card manage-orders">
                    <h3>Manage Orders</h3>
                </a>
                <a href="../View_contact/view_messages.php" class="card view_messages">
                    <h3>View Messages</h3>
                </a>

                <a href="../logout.php" class="card logout" onclick="return confirmLogout()">
                     <h3>Logout</h3>
                </a>

                    <script>
                    function confirmLogout() {
                        return confirm("Are you sure you want to log out?");
                    }
                    </script>

            </div>
        </main>
    </div>
</body>
</html>
