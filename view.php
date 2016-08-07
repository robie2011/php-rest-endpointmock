<?php
require_once 'include.php';
$serviceRequets = get_objects_from_file(PATH_SERVICEREQUESTS_DATA);
$bicycles = get_objects_from_file(PATH_BICYCLE_DATA);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  </head>
  <body class="container">
    <div class="row">
        <h1>ServiceRequests</h1>
        <?php 
            foreach ($serviceRequets as $s) {
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
  </body>
</html>

