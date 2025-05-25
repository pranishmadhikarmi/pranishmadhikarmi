<?php
include 'db_connect.php'; // Ensure database connection

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name'], $data['price'], $data['quantity'], $data['image'])) {
    $name = $data['name'];
    $price = $data['price'];
    $quantity = $data['quantity'];
    $image = $data['image'];

    $stmt = $conn->prepare("INSERT INTO cart (name, price, quantity, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $price, $quantity, $image);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid data"]);
}
?>
