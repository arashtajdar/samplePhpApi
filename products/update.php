<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);
$id = !empty($_GET['id']) ? $_GET['id'] : 10;

$error = array();
$idIsSet = false;
$aValueIsSet = false;

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $idIsSet = true;
} else {
    $p = "ID should be defined !";
    array_push($error, $p);
};


$name = false;
$price = false;
if (!empty($_GET['name'])) {
    $name = $_GET['name'];
    $aValueIsSet = true;
}
if (!empty($_GET['price'])) {
    $price = $_GET['price'];
    $aValueIsSet = true;
}
if (!$aValueIsSet) {
    $p = "at leat one value should be defined !";
    array_push($error, $p);
}
if(is_numeric($price)){
    if ($idIsSet && $aValueIsSet ) {
        $stmt = $product->update($id, $name, $price);
        $count = $stmt->rowCount();

        if($stmt && $count){
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
        }else {


            echo json_encode(
                array("body" => array(), "ERROR" => $error)
            );
        }

    } else {


        echo json_encode(
            array("body" => array(), "ERROR" => $error)
        );
    }
}else{
    $p = "price should be numeric";
    array_push($error, $p);

    echo json_encode(
        array("body" => array(), "ERROR" => $error)
    );
}

?>