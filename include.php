<?php
date_default_timezone_set("Europe/Zurich"); 

const PATH_BICYCLE_DATA = "data/bicycles.json";
const PATH_SERVICEREQUESTS_DATA = "data/serviceRequests.json";
const PATH_SERVICEPACKAGES = "data/servicePackages.json";
const PATH_USERTELEPHONE = "data/user_telephones.json";
const PATH_USERADDRESSES = "data/user_addresses.json";
const PATH_HOLIDAYS = "data/holidays.json";
const PATH_MEDIA_FOLDER = "downloadMedia/";

function get_objects_from_file($pathToFile) {
    $data = file_get_contents($pathToFile);
    return json_decode($data);
}

function put_objects_into_file($objects, $pathToFile) {
    $data = json_encode($objects, JSON_PRETTY_PRINT);
    file_put_contents($pathToFile, $data);
}

function getMimeType($pathToFile){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    return finfo_file($finfo, $pathToFile);
}

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


function time2str($ts)
{
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

?>