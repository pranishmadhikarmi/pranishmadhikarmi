<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("location: ../login.php");
    exit();
}
$id = $_SESSION['user_id']; 
$username = $_SESSION['username']; // Fetch username from session

// Database connection
$conn = new mysqli('localhost', 'root', '', 'shoes_store');
if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

// Fetch user email to match orders (since orders table does not have user_id)
$user_stmt = $conn->prepare("SELECT email FROM registered_users WHERE id = ?");
$user_stmt->bind_param("i", $id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows === 0) {
    die("User not found.");
}
$user = $user_result->fetch_assoc();
$user_email = $user['email']; // Get user email

// Fetch user orders with product details
$stmt = $conn->prepare("
    SELECT orders.id, orders.total_price, orders.status, orders.created_at, orders.payment_method, 
           order_items.product_name, order_items.quantity, products.image 
    FROM orders 
    JOIN order_items ON orders.id = order_items.order_id
    JOIN products ON products.name = order_items.product_name
    WHERE orders.email = ?
    ORDER BY orders.created_at DESC
");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
<head>
    <title>View Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h1>Your Orders</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image"></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td>Rs <?php echo htmlspecialchars($row['total_price']); ?></td>
                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <form action="delete_order.php" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this order?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$user_stmt->close();
$conn->close();
?>
