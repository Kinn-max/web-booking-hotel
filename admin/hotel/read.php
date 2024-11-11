<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');

if (isset($_GET['id'])) {
    $hotelId = $_GET['id'];

    $sql = "SELECT * FROM hotel WHERE id = $hotelId";
    $result = mysqli_query($mysqli, $sql);
    $hotel = mysqli_fetch_assoc($result);

    $city_id = $hotel['id_city'];

    $imageSql = "SELECT name FROM image_hotel WHERE id_hotel = $hotelId";
    $imageResult = mysqli_query($mysqli, $imageSql);
    $images = [];
    while ($imageRow = mysqli_fetch_assoc($imageResult)) {
        $images[] = $imageRow['name'];
    }

    echo json_encode([
        'id' => $hotel['id'],
        'name' => $hotel['name'],
        'address' => $hotel['address'],
        'phone' => $hotel['phone'],
        'description' => $hotel['description'],
        'city_id' => $city_id,
        'images' => $images
    ]);
}
?>
