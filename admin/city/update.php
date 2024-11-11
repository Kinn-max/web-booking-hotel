<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');
session_start();
if (isset($_POST['cityId'])) {
    $cityId = $_POST['cityId'];
    $cityName = $_POST['cityName'];

    if (isset($_FILES['cityImage']) && $_FILES['cityImage']['error'] == 0) {
        $image = $_FILES['cityImage']['name'];
        $image_tmp_name = $_FILES['cityImage']['tmp_name'];

        move_uploaded_file($image_tmp_name, $_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/images/kham-pha-vn/' . $image);
    } else {
        $query = "SELECT image FROM city WHERE id = '$cityId'";
        $result = mysqli_query($mysqli, $query);
        $row = mysqli_fetch_assoc($result);
        $image = $row['image'];
    }

    $updateQuery = "UPDATE city SET name = '$cityName', image = '$image' WHERE id = '$cityId'";

    if (mysqli_query($mysqli, $updateQuery)) {
        $_SESSION['update-success'] = "City Updated Successfully";
        header('Location: /web-booking-hotel/admin/index.php?page=city');
    } else {
        $_SESSION['update-false'] = "City Updated False";
    }
} else {
    echo "Dữ liệu không hợp lệ.";
}
?>
