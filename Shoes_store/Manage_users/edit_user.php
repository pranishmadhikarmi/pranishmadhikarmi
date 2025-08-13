<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location:../index.php");
    exit();
}

$Id = $_GET['Id'];
$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `registered_users` WHERE `Id`=$Id"));

if (isset($_POST['update_user'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    $query = "UPDATE `registered_users` SET `full_name`='$full_name', `email`='$email', `username`='$username' WHERE `Id`=$Id";

    if (mysqli_query($con, $query)) {
        header("location: manage_users.php");
    } else {
        echo "<script>alert('Failed to update user');</script>";
    }
}
?>


<html >
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="edit_user.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="../Manage_product/manage_products.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
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
            <h2>Edit User</h2>
            <a href="/Manage_users/manage_users.php" class="back-btn">Back</a>

            <form action="" method="POST" class="form-container">
                <div class="input-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="input-field" value="<?= $user['full_name']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input-field" value="<?= $user['email']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="input-field" value="<?= $user['username']; ?>" required>
                </div>
                <button type="submit" name="update_user" class="submit-btn">Update User</button>
            </form>
        </main>
    </div>
</body>
</html>
