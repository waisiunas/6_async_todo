<?php
require_once "../database/connection.php";
$_POST = json_decode(file_get_contents("php://input"), true);
if (isset($_POST['submit'])) {
    $body = htmlspecialchars($_POST['body']);
    $id = htmlspecialchars($_POST['id']);

    if (empty($body)) {
        echo json_encode(["bodyError" => "Enter task from PHP!"]);
    } else {
        $sql = "UPDATE `tasks` SET `body` = '$body' WHERE `id` = $id";
        if ($conn->query($sql)) {
            echo json_encode(["success" => "Magic has been spelled!"]);
        } else {
            echo json_encode(["failure" => "Magic has failed to spell!"]);
        }
    }
}
