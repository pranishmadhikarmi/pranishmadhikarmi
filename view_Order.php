<html>
<head>
    <title>My Orders</title>
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
            text-transform: uppercase;
        }

        .order-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background:rgb(39, 123, 213);
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
        }

        td img {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .status {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
        }

        .status.pending {
            background: #ffcc00;
            color: black;
        }

        .status.completed {
            background: #28a745;
            color: white;
        }

        .status.cancelled {
            background: #dc3545;
            color: white;
        }

        .remove-btn {
            background: red;
            color: white;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .remove-btn:hover {
            background: darkred;
        }

        @media (max-width: 600px) {
            th, td {
                padding: 8px;
                font-size: 14px;
            }

            td img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>

<div class="order-container">
    <h1>My Orders</h1>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        include 'connection.php';
        session_start();

        if (!isset($_SESSION['user_id'])) {
            echo "<tr><td colspan='7'>Please log in to view your orders.</td></tr>";
            exit();
        }

        $id = $_SESSION['user_id'];

        $stmt = $con->prepare("SELECT oi.order_id, oi.product_name, oi.price, oi.quantity, oi.image, o.status
                               FROM orders o
                               JOIN order_items oi ON o.id = oi.order_id
                               WHERE o.user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusClass = strtolower($row["status"]); 
                echo "<tr>
                        <td>" . htmlspecialchars($row["order_id"]) . "</td>
                        <td>" . htmlspecialchars($row["product_name"]) . "</td>
                        <td>Rs " . number_format($row["price"], 2) . "</td>
                        <td>" . htmlspecialchars($row["quantity"]) . "</td>
                        <td><img src='uploads/" . htmlspecialchars($row["image"]) . "' alt='Product Image'></td>
                        <td><span class='status $statusClass'>" . htmlspecialchars($row["status"]) . "</span></td>
                        <td>
                            <form method='POST' action='delete_order.php'>
                                <input type='hidden' name='order_id' value='" . htmlspecialchars($row["order_id"]) . "'>
                                <button type='submit' class='remove-btn' onclick='return confirm(\"Are you sure you want to remove this order?\")'>Remove</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No orders found.</td></tr>";
        }

        $stmt->close();
        $con->close();
        ?>
    </table>
</div>

</body>
</html>