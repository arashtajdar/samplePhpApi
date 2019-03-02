<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);

$error = array();
$allIsSet = true;
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $allIsSet = false;
    $p = "Id should be defined for deleting !";
    array_push($error, $p);
};

//if ($stmt[2]) {
//    $allIsSet = false;
//    array_push($error, $stmt[2]);
//}

if($allIsSet){
    $stmt = $product->remove($id);
    if ($stmt == 0) {
        $allIsSet = false;
        $p = "Id is not in database !";
        array_push($error, $p);
    }
    if ($stmt) {

        $products = array();
        $products["body"] = array();
        $products["msg"] = "Successfully removed.";
        $products["ERROR"] = false;

        $p = array(
            "ID" => $id
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



?>