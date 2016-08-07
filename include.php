<?php
const PATH_BICYCLE_DATA = "data/bicycles.json";
const PATH_SERVICEREQUESTS_DATA = "data/serviceRequests.json";
const PATH_SERVICEPACKAGES = "data/servicePackages.json";
const PATH_USERTELEPHONE = "data/user_telephones.json";
const PATH_MEDIA_FOLDER = "data/media/";

function get_objects_from_file($pathToFile) {
    $data = file_get_contents($pathToFile);
    return json_decode($data);
}

function put_objects_into_file($objects, $pathToFile) {
    $data = json_encode($objects);
    file_put_contents($pathToFile, $data);
}
?>