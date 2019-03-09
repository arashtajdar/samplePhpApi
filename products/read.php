<?php
	/**
	 *
	 * @SWG\Get(
	 * 		path="/api/v1/read",
	 * 		tags={"products"},
	 * 		summary="Read all data",
	 *      @SWG\Response(response="200", description="To search by name"),
	 *      @SWG\Response(response="401", description="To search by name")

	 * 	)
	 *
	 */

header("Content-Type: application/json; charset=UTF-8");

include_once '../config/db.php';
include_once '../entities/product.php';

$dbclass = new DatabaseClass();
$connection = $dbclass->connect();

$product = new Product($connection);
$error = array();

try{
    $length = !empty($_GET['length']) ? $_GET['length'] : 10;
    $start = !empty($_GET['start']) ? $_GET['start'] : 0;
    $stmt = $product->read($start, $length);
    $count = $stmt->rowCount();
    if(!$count){
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

