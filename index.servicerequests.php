<?php

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
    $postData["insertDate"] = date("Y-m-d H:i:s");
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
    $serviceRequest->hasUpdates = 1;

    $userName = "Dummy User";
    if(isset($_POST["userName"])) {
        $userName = $_POST["userName"];
        $postData = $_POST;
    }

    $comment = array(
        'insertDate' => date("Y-m-d H:i:s"),
        "userName" => $userName,
        "comment" => $postData["comment"]);
    array_push($serviceRequest->comments, $comment);

    put_objects_into_file($serviceRequets, PATH_SERVICEREQUESTS_DATA);

    if(isset($_POST["userName"])) {
        $app->response->headers->set("Location", $_SERVER['HTTP_REFERER']);
        return;
    }
    echo json_encode($serviceRequest);
});

$app->post('/servicerequest/:id/upload', function($id) use($app) {
    $filename = uploadFile();
    $targetFilename = PATH_MEDIA_FOLDER . $filename;

    $serviceRequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    $serviceRequest = find_by_id($serviceRequests, $id);

    $mimeType = getMimeType($targetFilename);

    $media = array(
        "id"=> date("U"),
        "fileName" => $filename,
        "content-type" => $mimeType,
        "mediaType" => "whatever",
        "crypticName" => $filename
        );
    
    array_push($serviceRequest->media, $media);
    put_objects_into_file($serviceRequests, PATH_SERVICEREQUESTS_DATA);
    
    echo json_encode($media);
});

?>