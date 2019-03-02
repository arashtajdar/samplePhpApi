<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);

$length = !empty($_GET['length']) ? $_GET['length'] : 10;
$start = !empty($_GET['start']) ? $_GET['start'] : 0;
$name = !empty($_GET['name']) ? $_GET['name'] : "";
$price_range = "";
if (!empty($_GET['minPrice'])) {
    $min = $_GET['minPrice'];
    $price_range .= " AND PRICE >= " . $min;
}
if (!empty($_GET['maxPrice'])) {
    $max = $_GET['maxPrice'];
    $price_range .= " AND PRICE <= " . $max;
}
$search_result = $product->search($start, $length, $name, $price_range);
//var_dump($search_result);
$count = $search_result->rowCount();

if ($count > 0) {
    $products = array();
    $products["body"] = array();
    $products["count"] = $count;

    while ($row = $search_result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $p = array(
            "ID" => $ID,
            "NAME" => $NAME,
            "PRICE" => $PRICE
        );

        array_push($products["body"], $p);
    }

    echo json_encode($products);
} else {

    echo json_encode(
        array("body" => array(), "count" => 0)
    );
}
?>