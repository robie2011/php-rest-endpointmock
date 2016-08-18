<?php

// AUTHENTIFICATION
$app->post('/oauth/token', function() use($app) {
    $result = array(
        "access_token" => "47bc3bbd-486c-409d-a54d-4f662113cc02",
        "expires_in" => 43199,
        "refresh_token" => "f38349f0-5142-46af-b7a4-7bbeb727e6ec",
        "scope" => "read write",
        "token_type" => "bearer",
        "languageCode" => "de"
        );
    echo json_encode($result);
});

$app->post('/oauth/register', function() use($app) {

});

$app->post('/oauth/delete', function() use($app) {

});

// MISC
$app->get('/user/telephone', function(){
    echo file_get_contents(PATH_USERTELEPHONE);
});

$app->get('/user/addresses', function(){
    echo file_get_contents(PATH_USERADDRESSES);
});

$app->get('/holidays', function(){
    echo file_get_contents(PATH_HOLIDAYS);
});

?>