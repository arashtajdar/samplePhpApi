<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);
$id = !empty($_GET['id']) ? $_GET['id'] : 10;

$error = array();

try{
    $name = !empty($_GET['name'])?$_GET['name']:null;
    $price = !empty($_GET['price'])?$_GET['price']:null;
    $id = !empty($_GET['id'])?$_GET['id']:null;
    if(!$id) {
        throw new Exception("id should be defined  !");
    }else if (!$name && !$price){
        throw new Exception("at least one value (price or name) should be defined to be updated !");
    }else if($price && !is_numeric($price)) {
        throw new Exception("price should be numeric !");
    }

    $stmt = $product->update($id, $name, $price);
    $count = $stmt->rowCount();
    $products = array();
    $products["body"] = array();
    $products["msg"] = "Successfully updated.";
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