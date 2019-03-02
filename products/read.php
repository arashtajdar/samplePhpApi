<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);

$length = !empty($_GET['length']) ? $_GET['length'] : 10;
$start = !empty($_GET['start']) ? $_GET['start'] : 0;
$stmt = $product->read($start, $length);
// echo $stmt;
// exit;
$count = $stmt->rowCount();
if ($count > 0) {


    $products = array();
    $products["body"] = array();
    $products["count"] = $count;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // var_dump($row);
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