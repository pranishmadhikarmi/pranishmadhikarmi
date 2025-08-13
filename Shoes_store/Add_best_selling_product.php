<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'shoes_store');

if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $quantity = (int)$_POST['quantity'];
    $price = (int)$_POST['price'];  
    $actual_price = (int)$_POST['actual_price'];  
    $discount = (int)$_POST['discount'];

    if (empty($name) || empty($quantity) || empty($price) || empty($actual_price) || empty($discount)) {
        die("All fields are required.");
    }

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../Project/upload/" . basename($image);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("Failed to upload image.");
        }
    } else {
        die("Please upload an image.");
    }

    $stmt = $conn->prepare("INSERT INTO best_selling (name, quantity, image, price, actual_price, discount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisiii", $name, $quantity, $image, $price, $actual_price, $discount);

    if ($stmt->execute()) {
        echo "<script> window.location.href='best_selling.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<html>
<head>
    <title>Add Best Selling Product</title>
    <link rel="stylesheet" href="../Manage_product/add_product.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="../Manage_product/manage_products.php" >Manage Products</a></li>
                <li><a href="../Manage_users/manage_users.php">Manage Users</a></li>
                <li><a href="../Manage_order/manage_orders.php">Manage Orders</a></li>
                <li><a href="../View_contact/view_messages.php">View Message</a></li>
                <li><a href="../best_selling.php"class="active">Best Selling</a></li>
                <li><a href="../logout.php" onclick="return confirmLogout()">Logout</a></li>
            </ul>
        </aside>
        <script>
                    function confirmLogout() {
                        return confirm("Are you sure you want to log out?");
                    }
                </script>
                
        <main class="main-content">
            <h2 class="page-title">Add Best Selling</h2>
            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" required min="1">
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" required>
                    </div>

                    <div class="form-group">
                        <label>Actual Price</label>
                        <input type="number" name="actual_price" required>
                    </div>

                    <div class="form-group">
                        <label>Discount</label>
                        <input type="number" name="discount" required>
                    </div>

                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" name="image" accept="image/*" required>
                    </div>

                    <button type="submit" name="add_best_selling_product" class="btn-submit">Add Best Selling</button>
                </form>


            </div>
        </main>
    </div>
</body>
</html>


