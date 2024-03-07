<?php

// TODO - Use prepared statements for this

global $conn;
if(!isset($_POST)) return;

if(!isset($_SESSION) && !isset($_SESSION["user"])) return;

$email = base64_decode($_SESSION["user"]);

// Get the associated id from the database
require_once("../../auth/database.php");
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

$user_id = $user["id"];

// Add the provided pin data to the database
$type = $_POST["pin_type"];
$lat = $_POST["pin_lat"];
$lon = $_POST["pin_lon"];
$altitude = $_POST["pin_altitude"];
$owner_id = $_POST["pin_owner_id"];
$desc = $_POST["pin_desc"];
$sql = "INSERT INTO `pins` (`id`, `pin_type`, `pos_x`, `pos_y`, `pos_altitude`, `owner`, `info`) VALUES (NULL, $type, $lat, $lon, $altitude, $owner_id, $desc)";
$result = mysqli_query($conn, $sql);
$pin = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Check if success
if($pin) {
    echo "success";
} else {
    echo "fail";
}

die;