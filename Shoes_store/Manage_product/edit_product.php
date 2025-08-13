<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location:../index.php");
    exit();
}

$id = $_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `products` WHERE `Id`=$id"));

if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity']; 

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $query = "UPDATE `products` SET `name`='$name', `price`='$price', `quantity`='$quantity', `image`='$image' WHERE `Id`=$id";
    } else {
        $query = "UPDATE `products` SET `name`='$name', `price`='$price', `quantity`='$quantity' WHERE `Id`=$id";
    }

    if (mysqli_query($con, $query)) {
        header("location:manage_products.php");
    } else {
        echo "<script>alert('Failed to update product');</script>";
    }
}
?>

<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="edit_product.css">
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
            <h2 class="page-title">Edit Product</h2>
            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?= $product['name']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Price (Rs)</label>
                        <input type="number" name="price" value="<?= $product['price']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="<?= $product['quantity']; ?>" required min="1">
                    </div>

                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="image" id="imageUpload">
                        <div class="image-preview">
                            <img id="preview" src="../uploads/<?= $product['image']; ?>" width="100">
                        </div>
                    </div>

                    <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                    <a href="manage_products.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById('preview').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
</body>
</html>
