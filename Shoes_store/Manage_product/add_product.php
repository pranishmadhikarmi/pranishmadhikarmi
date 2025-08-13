<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location: ../index.php");
    exit();
}


if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity']; 

    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $query = "INSERT INTO `products`(`name`, `price`, `image`, `quantity`) 
              VALUES ('$name', '$price', '$image', '$quantity')";

    if (mysqli_query($con, $query)) {
        header("location: manage_products.php");
    } else {
        echo "<script>alert('Failed to add product');</script>";
    }
}

?>

<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="add_product.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="manage_products.php" class="active">Manage Products</a></li>
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
            <h2 class="page-title">Add Product</h2>
            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" required min="1">
                    </div>


                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" name="image" accept="image/*" required>
                    </div>

                    <button type="submit" name="add_product" class="btn-submit">Add Product</button>
                </form>


            </div>
        </main>
    </div>
</body>
</html>


