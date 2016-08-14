<?php
$app->get('/bicycleProfile', function () {
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
    echo json_encode($bicycles);
});

$app->get('/bicycleProfile/:id', function($id) {
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
    $bicycle = find_by_id($bicycles, $id);
    echo json_encode($bicycle);
});

$app->delete('/bicycleProfile/:id', function($id) {
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
    $bicycle = find_by_id($bicycles, $id);
    $index = array_search($bicycle, $bicycles);
    unset($bicycles[$index]);
    put_objects_into_file($bicycles, PATH_BICYCLE_DATA);
});

$app->post('/bicycleProfile/:id/media', function ($id){
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
    $filename = uploadFile();
    $bike = find_by_id($bicycles, $id);
    
    $media = array(
        "id"=> date("U"),
        "fileName" => $filename,
        "content-type" => "image/jpeg",
        "mediaType" => "whatever",
        "crypticName" => $filename
        );
    array_push($bike->media, $media);
    put_objects_into_file($bicycles, PATH_BICYCLE_DATA);
    echo json_encode($media);
});

$app->post('/bicycleProfile', function () use ($app) {
    $bicycles = get_objects_from_file(PATH_BICYCLE_DATA);    
    $postData = json_decode($app->request->getBody(), true);
    $postData["id"] = date("U");    
    $postData["media"] = array();    
    array_push($bicycles, $postData);
    put_objects_into_file($bicycles, PATH_BICYCLE_DATA);
    echo json_encode($postData);
});

?>