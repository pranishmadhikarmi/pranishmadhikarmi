<?php
include "../connection.php";

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


$total_sql = "SELECT COUNT(*) as total FROM products";
$total_result = $con->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

$sql = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$result = $con->query($sql);


$response = [
    "products" => "",
    "totalPages" => $total_pages
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {   
        $response["products"] .= '<div class="product-grid">';
        $response["products"] .= '<div class="product_card">';
        $response["products"] .= '<img src="/image/' . htmlspecialchars($row['image']) . '" alt="Product Image">';
        $response["products"] .= '<h3>' . htmlspecialchars($row['name']) . '</h3>';
        $response["products"] .= '<p class="price">Rs ' . number_format($row['price'], 2) . '</p>';
        
        // Display Quantity Left
        if ($row['quantity'] > 0) {
            $response["products"] .= '<p class="stock">Stock Left: ' . $row['quantity'] . '</p>';
            $response["products"] .= '<br><button class="add-to-cart" data-name="' . htmlspecialchars($row['name']) . '" data-price="' . $row['price'] . '" data-image="' . htmlspecialchars($row['image']) . '">Add to Cart</button>';
        } else {
            $response["products"] .= '<p class="out-of-stock" style="color:red;">Out of Stock</p>';
        }

        $response["products"] .= '</div></div>';
    }
}


echo json_encode($response);
?>
