<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location:../index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM `registered_users` WHERE `id` = $id");
    header("location: manage_users.php");
}

$users = mysqli_query($con, "SELECT * FROM `registered_users` WHERE `username` != 'admin'");
?>


<html >
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage_users.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="../Manage_product/manage_products.php" >Manage Products</a></li>
                <li><a href="manage_users.php"class="active">Manage Users</a></li>
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
            <h2>Manage Users</h2>
            <div class="table-container">
                <a href="../Admin/admin.php" class="back-btn">Back</a>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($users)) : ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['full_name']; ?></td>
                                <td><?= $row['username']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <td>
                                    <a href="edit_user.php?Id=<?= $row['id']; ?>" class="btn edit-btn">Edit</a>
                                    <a href="manage_users.php?delete=<?= $row['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
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
