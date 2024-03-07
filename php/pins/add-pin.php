<?php

// TODO - Use prepared statements for this
session_start();

global $conn;

if(!isset($_POST)) return;

if(!isset($_SESSION) && !isset($_SESSION["user"])) return;

$email = base64_decode($_SESSION["user"]);

// Get the associated id from the database
require_once("../../auth/database.php");
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
file_put_contents('log.txt', "Query for user", FILE_APPEND);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

$user_id = $user["id"];

try {

    // TODO Better checks for blank values / defaults
// Add the provided pin data to the database
    $type = $_POST["pin_type"];
    $name = $_POST["pin_name"];
    $lat = $_POST["pin_lat"];
    $lon = $_POST["pin_lon"];
    $altitude = $_POST["pin_altitude"];
    $color = $_POST["color"];
    $info = json_encode($_POST["info"]);

    $stmt = $conn->prepare("INSERT INTO `pins`(`name`, `pin_type`, `pos_x`, `pos_y`, `pos_altitude`, `owner`, `info`, `color`) VALUES (?, ?,?,?,?,?,?,?)");
    $stmt->bind_param("ssddiiss", $name, $type, $lat, $lon, $altitude, $user_id, $info, $color);
    $stmt->execute();

//    $stmt->close();
//    $conn->close();

} catch(Throwable $e) {
    file_put_contents('log.txt', 'Failed'.$e, FILE_APPEND);
    die;
}
$result = ["response" => "Success"];
echo json_encode($result);
