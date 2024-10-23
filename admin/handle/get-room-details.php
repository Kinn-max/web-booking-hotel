<?php
include("../../config/connect.php");

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];
    $query_get_room = "SELECT * FROM `room` WHERE id = $room_id";
    $result = $mysqli->query($query_get_room);
    if ($result) {
        $room = $result->fetch_assoc();
        echo json_encode($room);
    }
}
