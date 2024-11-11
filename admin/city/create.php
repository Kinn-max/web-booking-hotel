<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $image = $_FILES['image']['name'];
    $image_data = $_FILES['image']['tmp_name'];
    $check_query = "SELECT * FROM city WHERE name = '$name'";
    $result = mysqli_query($mysqli, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Thành phố này đã tồn tại!";
    }
    else{
        $_SESSION['success_message']= "Thêm thành công!";

        $city_data = "INSERT INTO city(name, image) VALUES('$name', '$image')";
        mysqli_query($mysqli, $city_data);

        move_uploaded_file($image_data, '../images/kham-pha-vn/' . $image);

    }
}
?>

<h2>Thêm thành phố</h2>
<?php if (isset($_SESSION['error_message'])) { ?>
    <p style="color: red;"><?php echo $_SESSION['error_message']; ?></p>
    <?php unset($_SESSION['error_message']);} elseif (isset($_SESSION['success_message'])) { ?>
    <p style="color: green;"><?php echo $_SESSION['success_message']; ?></p>
    <?php unset($_SESSION['success_message']);} ?>
<form method="post" enctype="multipart/form-data">
    <label for="cityName">Tên thành phố</label>
    <input type="text" id="name" name="name" value="" placeholder="Nhập tên thành phố" required>

    <label for="image">Hình ảnh</label>
    <input type="file" id="image" name="image" value="" required>

    <button id="submit" name="submit" onclick="return confirm('Có chắc muốn thêm thành phố?')">Xác nhận</button>
</form>
