<?php
/**
 * @SWG\Get(
 *     path="/samplePhpApi/products/delete.php?id={id}",
 *     summary="delete products by ID",
 *     tags={"products"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id of record to delete",
 *         required=true,
 *         @SWG\Schema(
 *             type="integer",
 *             format="int32"
 *         )
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="ok"
 *     ),
 *     @SWG\Response(
 *         response=404,
 *         description="ERROR : Not found"
 *     ),
 *     @SWG\Response(
 *         response="default",
 *         description="unexpected error"
 *     )
 * )
 */
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);

$error = array();

try {
    $id = !empty($_GET['id'])?$_GET['id']:null;
    $stmt = $product->remove($id);
    $count = $stmt->rowCount();
    if(!$id) {
        throw new Exception("Id should be defined for deleting !");
    }elseif(!is_numeric($id)){
        throw new Exception("Id should be Numeric !");
    }elseif(!$count) {
        throw new Exception("Id is not in dataabse");
    }
    $products = array();
    $products["body"] = array();
    $products["msg"] = "Successfully removed.";
    $products["ERROR"] = false;
    $p = array(
        "ID" => $id
    );
    array_push($products["body"], $p);
    echo json_encode($products);
}
//catch exception
catch(Exception $e) {
    array_push($error, $e->getMessage());
    echo json_encode(
        array("body" => array(), "ERROR" => $error)
    );
}
