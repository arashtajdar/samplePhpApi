<?php
 /**
 * @SWG\Info(
 *     description="This is a sample php api for Erply assignment",
 *     version="1.0.0",
 *     title="PHP SAMPLE API",
 *     @SWG\Contact(
 *         email="arashtajdar@gmail.com"
 *     )
 * )
 */
/**
 * @SWG\Tag(
 *     name="products",
 *     description="Acces to products list and do CRUD",
 * )


 */
header("Content-Type: application/json; charset=UTF-8");
$api = array();
$api["body"] = array();
$api["services"] = array();
$api["documentation"] = array();

$api["body"] = array(
    "TITLE" => "PHP SAMPLE API",
    "VERSION" => "1.0.0",
    "DESCRIPTION" => "This is a sample php api for Erply assignment",
    "CONTACTME" => "arashtajdar@gmail.com"
);

$api["services"] = array(
    "CREATE" => "/samplePhpApi/products/create.php",
    "DELETE" => "/samplePhpApi/products/delete.php",
    "READ" => "/samplePhpApi/products/read.php",
    "SEARCH" => "/samplePhpApi/products/search.php",
    "UPDATE" => "/samplePhpApi/products/update.php"
);
$api["documentation"] = array(
    "URL" => "/documentation"
);


echo json_encode($api);
