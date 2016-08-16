<?php
require_once 'include.php';
if (isset($_POST) && isset($_POST["action"])) {
    if ($_POST["action"] == "reset_requets") {
        put_objects_into_file([], PATH_SERVICEREQUESTS_DATA);
    }
    if ($_POST["action"] == "reset_bikes") {
        put_objects_into_file([], PATH_BICYCLE_DATA);
    }
    if ($_POST["action"] == "reset_media") {
        $files = glob(PATH_MEDIA_FOLDER ."*"); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file))
                unlink($file); // delete file
            }
    }
}

$serviceRequests = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
$bicycles = get_objects_from_file(PATH_BICYCLE_DATA);
$servicePackages = get_objects_from_file(PATH_SERVICEPACKAGES);

require_once 'header.php';
?>

    <div class="row">
        <h1>Actions</h1>
        <form action="view.php" method="POST">
            <button class="btn btn-danger" type="submit" name="action" value="reset_requets">Reset Service Requests</button>
            <button class="btn btn-danger" type="submit" name="action" value="reset_bikes">Reset Bikes</button>
            <button class="btn btn-danger" type="submit" name="action" value="reset_media">Reset Media</button>
        </form>
        <h1>ServiceRequests</h1>
        <?php 
            foreach ($serviceRequests as $s) {
                echo "<div class=\"col-md-6\">";
                echo "<pre>";
                echo $json_string = json_encode($s, JSON_PRETTY_PRINT);
                $id = $s->id;                
                echo "</pre>";
                echo "<form action=\"/servicerequest/$id/offer\" method=\"POST\">";
                echo "<button class=\"btn btn-primary\" type=\"submit\">Send Offer</button>";
                echo "</form>";
                echo "</div>";
            }
        ?>
    </div>
    <div class="row">
        <h1>Bicycles</h1>
        <?php 
            foreach ($bicycles as $s) {
                echo "<pre class=\"col-md-4\">";
                echo $json_string = json_encode($s, JSON_PRETTY_PRINT);
                echo "</pre>";
            }
        ?>
    </div>

    <div class="row">
        <h1>Service Packages</h1>
        <?php 
            foreach ($servicePackages as $s) {
                echo "<pre class=\"col-md-4\">";
                echo $json_string = json_encode($s, JSON_PRETTY_PRINT);
                echo "</pre>";
            }
        require_once 'footer.php';
            ?>
