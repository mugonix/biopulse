<?php
include_once 'db.php';

apiHeaders();

$required_fields = ["robot_key"];

if (!array_keys_exists($required_fields, $_GET)) {
    echo json_encode(["status" => "error", "message" => "Robot key missing."]);
    exit();
}

$robot_key = $_GET["robot_key"];
$db = dbObj();

if ($db->connect_errno) {
    echo json_encode(["status" => "error", "message" => "Internal Error."]);
    exit();
}


if (isset($_GET["module"]) && isset($_GET["action"]) && $_GET["module"] == "accounts") {
    if ($_GET["action"] == "get_account") {
        $results = $db->query(" SELECT `user_key`, `user_pass`, `video_channel`, `instruction_channel`, `created_at` 
            FROM `accounts` WHERE `robot_key`='{$robot_key}'");

        echo json_encode(["status" => "success", "data" => $results->fetch_assoc()]);
        exit();
    }

    if ($_GET["action"] == "start_session") {
        $user_key = rand(1000, 9999) . "-" . rand(100, 999);
        $user_pass = generateRandomString(6);
        $video_channel = "cvid-chan-" . generateRandomString(12);
        $instruction_channel = "cinst-chan-" . generateRandomString(12);
        $date = (new DateTime("now"))->format("Y-m-d H:i:s");


        $results = $db->query("UPDATE `accounts` SET 
        `user_key` = '{$user_key}', `user_pass` = '{$user_pass}', 
        `video_channel` = '{$video_channel}', `instruction_channel` = '{$instruction_channel}', 
        `created_at` = '$date' WHERE `robot_key` = '{$robot_key}' AND `user_key` IS NULL");

        if ($db->affected_rows > 0) {
            echo json_encode(["status" => "success", "data" =>
                ['user_key' => $user_key, 'user_pass' => $user_pass, 'video_channel' => $video_channel,
                    'instruction_channel' => $instruction_channel, 'created_at' => $date]]);
            exit();
        }
        echo json_encode(["status" => "error", "message" => "Session not started."]);
        exit();

    }

    if ($_GET["action"] == "end_session") {
        $results = $db->query("UPDATE `accounts` SET 
        `user_key` = NULL, `user_pass` = NULL, 
        `video_channel` = NULL, `instruction_channel` = NULL, 
        `created_at` = NULL WHERE `robot_key` = '{$robot_key}'");


        echo json_encode(["status" => "success"]);
        exit();

    }
}