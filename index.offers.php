<?php
$app->post('/servicerequest/:id/offer', function($id) use($app) {
    $serviceRequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
    $serviceRequest = find_by_id($serviceRequests, $id);
    $serviceRequest->hasUpdates = 1;

    $offer = array(
        "id" => date("U"),
        "insertDate" => date("Y/m/d H:i:s"), 
        "appointmentDate" => "2016/10/14 08:30", 
        "providerName" => "Dummy Provider GmbH",
        "offerStateId" => 1,
        "message" => "Test Offer");
    
    array_push($serviceRequest->offers, $offer);
    put_objects_into_file($serviceRequests, PATH_SERVICEREQUESTS_DATA);
    $app->response->headers->set("Location", $_SERVER['HTTP_REFERER']);
    return;
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
    $serviceRequest->serviceRequestStateId = 2;
    $offer = find_by_id($serviceRequest->offers, $offerid);
    $offer->offerStateId = 2;
    put_objects_into_file($serviceRequests, PATH_SERVICEREQUESTS_DATA);
    echo json_encode($serviceRequest);
});

?>