<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');

// Truy vấn dữ liệu khách sạn
$sql = 'SELECT * FROM `hotel`';
$result = mysqli_query($mysqli, $sql);

?>

<style>
    .city-management-container{
        width: 80%;
    }
</style>

<div class="city-management-container">
    <h2>Danh sách khách sạn</h2>

    <div class="add-button">
        <a href="hotel/create.php"><button class="add">Thêm Khách Sạn</button></a>
    </div>

    <div class="table-container"">
    <table>
        <tr>
            <th>STT</th>
            <th>Tên khách sạn</th>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Thông tin</th>
            <th>Hình Ảnh</th>
            <th>Tùy chọn</th>
        </tr>

        <?php $count = 0;
        while ($row = mysqli_fetch_array($result)) {
        $count += 1;

        // Truy vấn tất cả ảnh của khách sạn từ bảng image_hotel
        $hotel_id = $row['id'];
        $image_sql = "SELECT * FROM `image_hotel` WHERE `id_hotel` = $hotel_id";
        $image_result = mysqli_query($mysqli, $image_sql);
        $images = [];
        while ($image_row = mysqli_fetch_array($image_result)) {
            $images[] = $image_row['name']; // Lấy tên ảnh
        }
        ?>

            <tr>
                <td><?php echo $count ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['address']?></td>
                <td><?php echo $row['phone']?></td>
                <td>
                    <div class="descriptionHotel" id="desc-<?php echo $row['id']; ?>">
                        <?php echo substr($row['description'], 0, 100); ?>... <!-- Hiển thị đoạn rút gọn -->
                    </div>
                </td>
                <td>
                    <?php foreach (array_slice($images, 0, 4) as $image) { ?>
                        <img class="thumbnail" src="<?php echo "../images/hotel-detail/" . $image ?>" alt="Hotel Image" />
                    <?php } ?>
                </td>
                <td>
                    <a href="hotel/read.php?this_id=<?php echo $row['id']; ?>"><button class="details">Chi tiet</button></a>
                    <a href="hotel/delete.php?this_id=<?php echo $row['id']; ?>"><button class="delete" onclick="return confirm('Có chắc xóa thành phố này không?');">Xóa</button></a>
                    <a href="hotel/update.php?this_id=<?php echo $row['id']; ?>"><button class="edit">Sửa</button></a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>