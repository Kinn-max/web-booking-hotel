<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');

if (isset($_POST['submit'])) {
    $name = $_POST['name'];

    //chỉ lấy tên hình ảnh để gửi lên DTB
    $image = $_FILES['image']['name'];

    //đưa hình ảnh vào file images
    $image_data = $_FILES['image']['tmp_name'];

    // Kiểm tra xem tên thành phố đã tồn tại hay chưa
    $check_query = "SELECT * FROM city WHERE name = '$name'";
    $result = mysqli_query($mysqli, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Nếu có thành phố tồn tại, hiển thị thông báo lỗi
        $error_message = "Thành phố này đã tồn tại!";
    }
    else{
        $success_message = "Thêm thành công!";

        $city_data = "INSERT INTO city(name, image) VALUES('$name', '$image')";
        mysqli_query($mysqli, $city_data);

        move_uploaded_file($image_data, '../images/kham-pha-vn/' . $image);

        header('location: ../../admin/index.php?page=city');
    }
}
?>

<h2>Thêm thành phố</h2>
<form method="post" enctype="multipart/form-data">
    <label for="cityName">Tên thành phố</label>
    <input type="text" id="name" name="name" value="" placeholder="Nhập tên thành phố" required>

    <label for="image">Hình ảnh</label>
    <input type="file" id="image" name="image" value="" required>

    <button id="submit" name="submit">Xác nhận</button>
</form>
