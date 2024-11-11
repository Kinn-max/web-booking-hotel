<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');
session_start();
if (isset($_POST['submit'])) {
    $name = $_POST['hotelName'];
    $address = $_POST['hotelAddress'];
    $phone = $_POST['hotelPhone'];
    $description = $_POST['hotelDescription'];
    $id_city = $_POST['city'];

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    $check_sql = "SELECT * FROM hotel WHERE name = '$name' AND address = '$address' AND id_city = '$id_city'";
    $check_result = mysqli_query($mysqli, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error_hotel_message'] = "Khách sạn đã tồn tại!";
        header('Location: ../index.php?page=hotel');
        exit();
    } else {
        $sql = "INSERT INTO hotel (id_city, name, address, phone, description, slug) 
                VALUES ('$id_city', '$name', '$address', '$phone', '$description', '$slug')";

        if (mysqli_query($mysqli, $sql)) {
            $hotel_id = mysqli_insert_id($mysqli);

            if (!empty($_FILES["hotelImage"]["name"][0])) {
                foreach ($_FILES['hotelImage']['name'] as $key => $image_name) {
                    $image_tmp = $_FILES['hotelImage']['tmp_name'][$key];
                    $image_path = $image_name;

                    move_uploaded_file($image_tmp, '../../images/hotel-detail/' . $image_path);

                    $image_sql = "INSERT INTO image_hotel (id_hotel, name) VALUES ('$hotel_id', '$image_path')";
                    mysqli_query($mysqli, $image_sql);
                }
            }

            $_SESSION['success_hotel_message'] = 'Thêm khách sạn thành công!';
            header('Location: ../index.php?page=hotel');
            exit();
        } else {
            echo "Lỗi: " . mysqli_error($mysqli);
        }
    }
}
?>
