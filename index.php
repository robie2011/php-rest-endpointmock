<?php

require_once 'include.php';

require 'vendor/autoload.php';

function find_by_id($array_objects, $idToFind) {
    $element = null;
    foreach ($array_objects as $object) {
        if ($object->id == $idToFind) {
            $element = $object;
            break;
        }
    }
    return $element;
}

function uploadFile () {
    if (!isset($_FILES['file'])) {
        echo "No files uploaded!!";
        return;
    }
    $imgs = array();

    $file = $_FILES['file'];
    $name = uniqid('img-'.date('Ymd').'-');
    $extension = substr($file["name"], strpos($file["name"], "."));
    $targetFilename = PATH_MEDIA_FOLDER . $name . $extension;

    $moveResult = move_uploaded_file($file["tmp_name"], $targetFilename);
    if ($file["error"] === 0 && $moveResult) {
        echo "OK";   
    }
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

$app->get('/bicycleProfile/:id', function($id) {
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
    $bicycle = find_by_id($bicycles, $id);
    echo json_encode($bicycle);
});

//  SERVICE REQUETS
$app->get('/services/servicepackages', function(){
    echo file_get_contents(PATH_SERVICEPACKAGES);
});

$app->get('/servicerequest', function() {
    echo file_get_contents(PATH_SERVICEREQUESTS_DATA);
});

$app->post('/servicerequest', function() use($app){
    $postData = json_decode($app->request->getBody(), true);
    $postData["id"] = date("U");
    $postData["insertDate"] = date("Y-m-d H:m:s");
    $postData["media"] = array();
    $postData["serviceRequestStateId"] = 1;
    $postData["hasUpdates"] = 0;
    $postData["comments"] = [];
    $postData["offers"] = [];

    // adding new properties to service
    $servicePackages = get_objects_from_file(PATH_SERVICEPACKAGES);
    $service = find_by_id($servicePackages, $postData["serviceId"]);
    $postData["translatedName"] = $service->translatedName;
    $postData["servieTypeId"] = $service->serviceType;

    // adding new data to file
    $servicerequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    array_push($servicerequests, $postData);
    put_objects_into_file($servicerequests, PATH_SERVICEREQUESTS_DATA);

    echo json_encode($postData);
});

$app->get('/servicerequest/:id', function ($id) {
    $serviceRequets = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    $serviceRequest = find_by_id($serviceRequets, $id);
    $serviceRequest->hasUpdates = 0;
    put_objects_into_file($serviceRequets, PATH_SERVICEREQUESTS_DATA);
    echo json_encode($serviceRequest);
});

$app->post('/servicerequest/:id/comment', function($id) use($app) {
    $postData = json_decode($app->request->getBody(), true);
    $serviceRequets = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    $serviceRequest = find_by_id($serviceRequets, $id);
    
    $comment = array('insertDate' => date("Y-m-d H:m:s"), "userName" => "Dummy User AG", "comment" => $postData["comment"]);
    array_push($serviceRequest->comments, $comment);

    put_objects_into_file($serviceRequets, PATH_SERVICEREQUESTS_DATA);
    echo json_encode($serviceRequest);
});

// Offers
$app->post('/servicerequest/:id/offer', function($id) use($app) {
    $postData = json_decode($app->request->getBody(), true);
    $serviceRequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    $serviceRequest = find_by_id($serviceRequests, $id);
    $serviceRequest->hasUpdates = 1;

    $offer = array(
        "id" => date("U"),
        "insertDate" => date("Y-m-d H:m:s"), 
        "appointmentDate" => "2016-10-14 08:30", 
        "providerName" => "Dummy Provider GmbH",
        "offerStateId" => 1,
        "message" => "Test Offer");
    
    array_push($serviceRequest->offers, $offer);
    put_objects_into_file($serviceRequests, PATH_SERVICEREQUESTS_DATA);
    $app->response->headers->set("Location", "/view.php");
    echo json_encode($serviceRequest);
});

$app->get('/servicerequest/:id/serviceoffers/:offerid/deny', function($id, $offerid){
    $serviceRequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    $serviceRequest = find_by_id($serviceRequests, $id);
    $offer = find_by_id($serviceRequest->offers, $offerid);
    $offer->offerStateId = 3;
    put_objects_into_file($serviceRequests, PATH_SERVICEREQUESTS_DATA);
    echo json_encode($serviceRequest);
});

$app->get('/servicerequest/:id/serviceoffers/:offerid/accept', function($id, $offerid){
    $serviceRequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    $serviceRequest = find_by_id($serviceRequests, $id);
    $offer = find_by_id($serviceRequest->offers, $offerid);
    $offer->offerStateId = 2;
    put_objects_into_file($serviceRequests, PATH_SERVICEREQUESTS_DATA);
    echo json_encode($serviceRequest);
});

// BICYCLE
$app->get('/bicycleProfile', function () {
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
    echo json_encode($bicycles);
});

$app->post('/bicycleProfile', function () use ($app) {
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);    
    $postData = json_decode($app->request->getBody(), true);
    $postData["id"] = date("U");    
    array_push($bicycles, $postData);
    put_objects_into_file($bicycles, PATH_BICYCLE_DATA);
    echo json_encode($postData);
});

// MEDIA
$app->post('/api/media', 'uploadFile');

// AUTHENTIFICATION
$app->post('/oauth/token', function() use($app) {
    $result = array(
        "access_token" => "47bc3bbd-486c-409d-a54d-4f662113cc02",
        "expires_in" => 43199,
        "refresh_token" => "f38349f0-5142-46af-b7a4-7bbeb727e6ec",
        "scope" => "read write",
        "token_type" => "bearer"
        );
    echo json_encode($result);
});

$app->get('/user/telephone', function(){
    echo file_get_contents(PATH_USERTELEPHONE);
});

$app->response->headers->set("Content-Type", "application/json");
$app->response->headers->set("Access-Control-Allow-Origin", "*");
$app->run();

?>