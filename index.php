<?php

require_once 'include.php';
require 'vendor/autoload.php';

function uploadFile () {
    if (!isset($_FILES['file'])) {
        echo "No files uploaded!!";
        return;
    }
    $imgs = array();

    file_put_contents("c:/tmp/test.txt", print_r($_FILES['file'], true));
    $file = $_FILES['file'];
    $extension = substr($file["name"], strrpos($file["name"], "."));
    $name = uniqid('file-'.date('U').'-') . $extension;
    $targetFilename = PATH_MEDIA_FOLDER . $name;

    $moveResult = move_uploaded_file($file["tmp_name"], $targetFilename);
    if ($file["error"] === 0 && $moveResult) {
        return $name;
    }

    return null;
}

$app = new \Slim\Slim();
$corsOptions = array(
    "origin" => "*",
    //"exposeHeaders" => array("X-My-Custom-Header", "X-Another-Custom-Header"),
    "maxAge" => 1728000,
    "allowCredentials" => True,
    "allowMethods" => array("POST, GET, OPTIONS, DELETE"),
    "allowHeaders" => array("x-requested-with", "authorization", "accept", "content-type")
    );

$cors = new \CorsSlim\CorsSlim($corsOptions);
$app->add($cors);

require_once "./index.servicerequests.php";
require_once "./index.offers.php";
require_once "./index.bicycle.php";
require_once "./index.misc.php";

$app->response->headers->set("Content-Type", "application/json");
$app->response->headers->set("Access-Control-Allow-Origin", "*");
$app->run();

?>