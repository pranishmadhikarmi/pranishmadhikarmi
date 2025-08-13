<?php
$servername = "localhost";
$username = "root"; 
$password = "";  
$database = "shoes_store"; 

header('Content-Type: application/json; charset=UTF-8');

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

if (isset($_GET['q'])) {
    $search = "%" . $_GET['q'] . "%"; 

    $sql = "SELECT * FROM products WHERE name LIKE ? LIMIT 5";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();

        $output = [];
        while ($row = $result->fetch_assoc()) {
            $output[] = [
                "id" => $row["id"],
                "name" => $row["name"],
                "quantity" => $row["quantity"],
                "price" => number_format($row["price"], 2),
                "image" => !empty($row["image"]) ? "../uploads/" . $row["image"] : "../uploads/default.jpg"
            ];
        }
        

        echo json_encode($output);
        $stmt->close(); 
    } else {
        echo json_encode(["error" => "Query preparation failed"]);
    }
} else {
    echo json_encode([]);
}

$conn->close(); 
?>
