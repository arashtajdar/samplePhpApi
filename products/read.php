<?php
/**
 * @SWG\Get(
 *     path="/samplePhpApi/products/read.php?start={start}&length={length}",
 *     summary="List products",
 *     tags={"products"},
 *     @SWG\Parameter(
 *         name="start",
 *         in="path",
 *         description="start of records to display (zero based)",
 *         required=false,
 *         @SWG\Schema(
 *             type="integer",
 *             format="int32"
 *         )
 *     ),
 *     @SWG\Parameter(
 *         name="length",
 *         in="path",
 *         description="length of records",
 *         required=false,
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

try{
    $length = (!empty($_GET['length']) && $_GET['length'] !== 'undefined') ? $_GET['length'] : 10;
    $start = (!empty($_GET['start']) && $_GET['start'] !== 'undefined') ? $_GET['start'] : 0;
    $stmt = $product->read($start, $length);
    $count = $stmt->rowCount();
    if ($length && !is_numeric($length)){
        throw new Exception("length should be numeric");
    }elseif ($start && !is_numeric($start)){
        throw new Exception("start should be numeric");
    }elseif(!$count){
        throw new Exception("No result found !");
    }
    $products = array();
    $products["body"] = array();
    $products["count"] = $count;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $p = array(
            "ID" => $ID,
            "NAME" => $NAME,
            "PRICE" => $PRICE
        );
        array_push($products["body"], $p);
    }
    echo json_encode($products);
}catch (Exception $e){
    array_push($error, $e->getMessage());
    echo json_encode(
        array("ERROR" => $error)
    );
}

