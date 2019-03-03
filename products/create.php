<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);

$error = array();

try{
    $name = !empty($_GET['name'])?$_GET['name']:null;
    $price = !empty($_GET['price'])?$_GET['price']:null;

    if(!$name && !$price) {
        throw new Exception("name and price should be defined  !");
    }elseif(!$price) {
        throw new Exception("Price should be defined  !");
    }elseif(!$name) {
        throw new Exception("name should be defined  !");
    }elseif(!is_numeric($price)){
        throw new Exception("Price should be numeric");
    }
    $stmt = $product->create($name, $price);
    $id = $connection->lastInsertId();
    $products = array();
    $products["body"] = array();
    $products["msg"] = "Successfully inserted.";
    $products["ERROR"] = false;
    $p = array(
        "ID" => $id,
        "NAME" => $name,
        "PRICE" => $price
    );
    array_push($products["body"], $p);
    echo json_encode($products);

}catch (Exception $e){
    array_push($error, $e->getMessage());
    echo json_encode(
        array("ERROR" => $error)
    );
}

?>