<?php
session_start();
include('connection.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['username'] != 'admin') {
    header("location:index.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$best_selling = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `best_selling` WHERE `Id`=$id"));

if (isset($_POST['update_best_selling'])) {
    $name = trim($_POST['name']);
    $quantity = (int)$_POST['quantity'];
    $price = (int)$_POST['price'];
    $actual_price = (int)$_POST['actual_price'];
    $discount = (int)$_POST['discount'];

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../upload/" . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
           
            $stmt = $con->prepare("UPDATE `best_selling` 
                                   SET `name`=?, `quantity`=?, `price`=?, `image`=?, `actual_price`=?, `discount`=? 
                                   WHERE `Id`=?");
            $stmt->bind_param("sissiii", $name, $quantity, $price, $image, $actual_price, $discount, $id);
        } else {
            echo "<script>alert('Failed to upload image');</script>";
            exit();
        }
    } else {
        
        $stmt = $con->prepare("UPDATE `best_selling` 
                               SET `name`=?, `quantity`=?, `price`=?, `actual_price`=?, `discount`=? 
                               WHERE `Id`=?");
        $stmt->bind_param("siiiii", $name, $quantity, $price, $actual_price, $discount, $id);
    }

 
    if ($stmt->execute()) {
        header("location:best_selling.php");
        exit();
    } else {
        echo "<script>alert('Failed to update product');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Best Selling</title>
    <link rel="stylesheet" href="../Manage_product/edit_product.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="../Manage_users/manage_users.php">Manage Users</a></li>
                <li><a href="../Manage_order/manage_orders.php">Manage Orders</a></li>
                <li><a href="../View_contact/view_messages.php">View Messages</a></li>
                <li><a href="../best_selling.php" class="active">Best Selling</a></li>
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
                        <input type="text" name="name" value="<?= htmlspecialchars($best_selling['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="<?=  htmlspecialchars($best_selling['quantity']); ?>" required min="1">
                    </div>

                    <div class="form-group">
                        <label>Price (Rs)</label>
                        <input type="number" name="price" value="<?= htmlspecialchars($best_selling['price']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Actual Price (Rs)</label>
                        <input type="number" name="actual_price" value="<?= htmlspecialchars($best_selling['actual_price']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Discount (%)</label>
                        <input type="number" name="discount" value="<?= htmlspecialchars($best_selling['discount']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="image" id="imageUpload">
                        <div class="image-preview">
                            <img id="preview" src="../upload/<?= htmlspecialchars($best_selling['image']); ?>" width="100">
                        </div>
                    </div>

                    <button type="submit" name="update_best_selling" class="btn btn-primary">Update Best Selling</button>
                    <a href="best_selling.php" class="btn btn-secondary">Back</a>
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
