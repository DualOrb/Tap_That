<?php

// TODO - Use prepared statements for this
session_start();

global $conn;
file_put_contents('log.txt', "1", FILE_APPEND);

if(!isset($_GET)) return;
file_put_contents('log.txt', "2", FILE_APPEND);

if(!isset($_SESSION) && !isset($_SESSION["user"])) return;
file_put_contents('log.txt', "3", FILE_APPEND);

try {
    $email = base64_decode($_SESSION["user"]);

// Get the associated id from the database
    require_once("../../auth/database.php");
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $rest = $stmt->get_result();
    $user = $rest->fetch_assoc();
    //$stmt->close();
//    $sql = "SELECT * FROM users WHERE email = '$email'";
//    $result = mysqli_query($conn, $sql);
//    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
//    $result->close();
    $stmt->free_result();
    $stmt->close();

    $user_id = $user["id"];

// Get all pins associated with the id
    $stmt2 = $conn->prepare("SELECT * FROM `pins` WHERE `owner` = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();

//    $stmt->close();
//    $conn->close();
    // file_put_contents('log.txt', json_encode($stmt2->get_result()->fetch_assoc()), FILE_APPEND);
    $rest = $stmt2->get_result();
    $result = ["data" => $rest->fetch_all(MYSQLI_ASSOC)];

    file_put_contents('log.txt', json_encode($rest->fetch_all(MYSQLI_ASSOC)), FILE_APPEND);
    $stmt2->free_result();
} catch(Throwable $e) {
    file_put_contents('log.txt', 'Failed'.$e, FILE_APPEND);
    die;
}

$result["response"] = "success";
echo json_encode($result);