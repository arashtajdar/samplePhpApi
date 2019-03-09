<?php
/**
 * @SWG\Get(
 *     path="/samplePhpApi/products/search.php?start={start}&length={length}&name={name}&minPrice={minPrice}&maxPrice={maxPrice}",
 *     summary="search in products",
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
 *     @SWG\Parameter(
 *         name="name",
 *         in="path",
 *         description="search keyword",
 *         required=true,
 *         @SWG\Schema(
 *             type="string"
 *         )
 *     ),
 *     @SWG\Parameter(
 *         name="minPrice",
 *         in="path",
 *         description="minimum price",
 *         required=false,
 *         @SWG\Schema(
 *             type="integer",
 *             format="int32"
 *         )
 *     ),
 *     @SWG\Parameter(
 *         name="maxPrice",
 *         in="path",
 *         description="maximum price",
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
    $name = (!empty($_GET['name']) && $_GET['name'] !== 'undefined') ? $_GET['name'] : null;
    if(!$name){
        throw new Exception("You should specify a name to search");
    }elseif(strlen($name)<3){
        throw new Exception("at least 3 characters are required for name to search");
    }
    $price_range = "";
    if (!empty($_GET['minPrice']) && $_GET['minPrice'] !== 'undefined' ) {
        $min = $_GET['minPrice'];
        $price_range .= " AND PRICE >= " . $min;
    }
    if (!empty($_GET['maxPrice']) && $_GET['maxPrice'] !== 'undefined') {
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

