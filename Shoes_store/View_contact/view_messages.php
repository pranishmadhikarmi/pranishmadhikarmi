<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shoes_store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<html>
<head>
    <title>View Messages</title>
    <link rel="stylesheet" href="view_messages.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="../Admin/admin.php">Dashboard</a></li>
                <li><a href="../Manage_product/manage_products.php">Manage Products</a></li>
                <li><a href="../Manage_users/manage_users.php">Manage Users</a></li>
                <li><a href="../Manage_order/manage_orders.php">Manage Orders</a></li>
                <li><a href="view_messages.php"class="active">View Messages</a></li>
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
            <div class="dashboard-header">
                <h1>View Messages</h1>
            </div>

            <div class="message-table">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo nl2br($row['message']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
