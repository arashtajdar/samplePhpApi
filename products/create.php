<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);

$error = array();
$allIsSet = true;
if (!empty($_GET['name'])) {
    $name = $_GET['name'];
} else {
    $allIsSet = false;
    $p = "name should be defined !";
    array_push($error, $p);
};
if (!empty($_GET['price'])) {
    $price = $_GET['price'];
} else {
    $allIsSet = false;
    $p = "price should be defined !";
    array_push($error, $p);
};


// var_dump($stmt);
// exit;
if ($allIsSet) {
    $stmt = $product->create($name, $price);
//    var_dump($stmt);
//    if ($stmt[2]) {
//        $allIsSet = false;
//        array_push($error, $stmt[2]);
//    }
    if($stmt){
        $products = array();
        $products["body"] = array();
        $products["msg"] = "Successfully inserted.";
        $products["ERROR"] = false;

        $p = array(
            "ID" => $stmt,
            "NAME" => $name,
            "PRICE" => $price
        );

        array_push($products["body"], $p);

        echo json_encode($products);
    }

} else {


    echo json_encode(
        array("body" => array(), "ERROR" => $error)
    );
}
?>