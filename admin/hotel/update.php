<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotelId = $_POST['hotelId'];
    $hotelName = $_POST['hotelName'];
    $hotelAddress = $_POST['hotelAddress'];
    $hotelPhone = $_POST['hotelPhone'];
    $hotelDescription = $_POST['hotelDescription'];
    $id_city = $_POST['city'];

    $updateSql = "UPDATE hotel SET name = '$hotelName', address = '$hotelAddress', phone = '$hotelPhone', description = '$hotelDescription', id_city = '$id_city' WHERE id = '$hotelId'";

    if (mysqli_query($mysqli, $updateSql)) {
        if (!empty($_FILES["hotelImage"]["name"][0])) {
            $delete_sql = "DELETE FROM image_hotel WHERE id_hotel = '$hotelId'";
            mysqli_query($mysqli, $delete_sql);

            foreach ($_FILES['hotelImage']['name'] as $key => $image_name) {
                $image_tmp = $_FILES['hotelImage']['tmp_name'][$key];
                $image_path = $image_name;

                move_uploaded_file($image_tmp, '../../images/hotel-detail/' . $image_path);
                $image_sql = "INSERT INTO image_hotel (id_hotel, name) VALUES ('$hotelId', '$image_path')";
                mysqli_query($mysqli, $image_sql);
            }
        }
        $_SESSION['success'] = "Hotel updated successfully";
        header('location: ../index.php?page=hotel');
    } else {
        $_SESSION['error'] = "Update error";
    }
}
?>
