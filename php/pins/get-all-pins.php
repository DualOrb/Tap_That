<?php

// Changed mind of design half way, but keeping this in for now til I feel like refactoring the existing code

global $conn;

if(!isset($_GET)) return;

if(!isset($_SESSION) && !isset($_SESSION["user"])) return;

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "accounts";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

try {
    $email = base64_decode($_SESSION["user"]);

// Get the associated id from the database
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $rest = $stmt->get_result();
    $user = $rest->fetch_assoc();

    $stmt->free_result();
    $stmt->close();

    $user_id = $user["id"];

// Get all pins associated with the id
    $stmt2 = $conn->prepare("SELECT * FROM `pins` WHERE `owner` = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();

    $rest = $stmt2->get_result();
    $result = ["data" => $rest->fetch_all(MYSQLI_ASSOC)];

    $stmt2->free_result();
} catch(Throwable $e) {
    file_put_contents('log.txt', 'Failed'.$e, FILE_APPEND);
    die;
}

$result["response"] = "success";
// echo json_encode($result);

$counter = 1;
// Build out the accordion of pins
foreach ($result["data"] as $pin) {
    $info = json_decode($pin["info"], true);
    // file_put_contents('log.txt', json_encode($info), FILE_APPEND);
    echo '<div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn" data-toggle="collapse" data-target="#collapse'.$pin["id"].'" aria-expanded="true" aria-controls="collapse'.$pin["id"].'">
                            '.$counter.' '.$pin["name"].'
                        </button>
                    </h5>
                </div>

                <div id="collapse'.$pin["id"].'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body pin-body">
                        <h6><strong>Type: </strong><span class="data-field pin-type">'.strtoupper($pin["pin_type"]).'</span></h6>
                        <h6><strong>Latitude: </strong><span data-lat='.$pin["pos_x"].' class="data-field pin-lat">'.$pin["pos_x"].'</span></h6>
                        <h6><strong>Longitude: </strong><span data-lon='.$pin["pos_y"].' class="data-field pin-lon">'.$pin["pos_y"].'</span></h6>
                        <h6><strong>Altitude: </strong><span class="data-field pin-altitude">'.$pin["pos_altitude"].'</span></h6>
                        <hr>
                        <h6><strong>Health: </strong><span class="data-field">'.$info["tree_health"].'</span></h6>
                        <h6><strong>Num Taps: </strong><span class="data-field">'.$info["num_taps"].'</span></h6>
                        <h6><strong>Description: </strong><span class="data-field">'.$info["pin_desc"].'</span></h6>

                    </div>
                </div>
            </div>';
    $counter++;
}


