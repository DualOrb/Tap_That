<?php

session_start();
global $conn;

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
    file_put_contents('log.txt', "got user id", FILE_APPEND);
// Get all pins associated with the id
    $stmt2 = $conn->prepare("SELECT * FROM `pins` WHERE `owner` = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();

    $rest = $stmt2->get_result();
    $result = ["data" => $rest->fetch_all(MYSQLI_ASSOC)];

    $stmt2->free_result();
    file_put_contents('log.txt', "reached end".json_encode($result), FILE_APPEND);
} catch(Throwable $e) {
    file_put_contents('log.txt', 'Failed'.$e, FILE_APPEND);
    die;
}

$result["response"] = "success";
echo json_encode($result);
