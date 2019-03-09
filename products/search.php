<?php
/**
 * @SWG\Get(
 *     path="/api/v1/search",
 *     @SWG\Response(response="200", description="To search by name")
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
    $length = !empty($_GET['length']) ? $_GET['length'] : 10;
    $start = !empty($_GET['start']) ? $_GET['start'] : 0;
    $name = !empty($_GET['name']) ? $_GET['name'] : null;
    if(!$name){
        throw new Exception("You should specify a name to search");
    }elseif(strlen($name)<3){
        throw new Exception("at least 3 characters are required for name to search");
    }
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
    $count = $search_result->rowCount();
    if(!$count){
        throw new Exception("No result found !");
    }
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

}catch (Exception $e){
    array_push($error, $e->getMessage());
    echo json_encode(
        array("ERROR" => $error)
    );
}

