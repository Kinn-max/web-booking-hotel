<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');

$this_id = $_GET['this_id'];

$sql = "DELETE FROM hotel WHERE id = '$this_id'";
mysqli_query($mysqli, $sql);

header('location: ../../admin/index.php?page=hotel');
