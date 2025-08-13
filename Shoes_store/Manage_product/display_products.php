<?php
include 'connection.php'; 

$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<html>
<head>
    <title>Product List</title>
</head>
<body>
    <h2>Available Products</h2>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div>
            <h3><?php echo $row['name']; ?></h3>
            <p>Price: $<?php echo $row['price']; ?></p>
            <img src="../uploads/<?php echo $row['image']; ?>" width="100" alt="<?php echo $row['name']; ?>">
        </div>
        <hr>
    <?php } ?>
</body>
</html>

