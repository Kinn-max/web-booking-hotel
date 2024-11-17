<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');

$sql = 'SELECT * FROM `city`';
$result = mysqli_query($mysqli, $sql);

if (isset($_SESSION['update-success'])) {
    echo "<script>alert('Cập nhật thành công');</script>";
    unset($_SESSION['update-success']);
} else if (isset($_SESSION['update-false'])) {
    echo "<script>alert('Cập nhật thất bại');</script>";
    unset($_SESSION['update-false']);
}
?>

<h2>Danh sách thành phố</h2>
<div class="table-container">
    <table>
        <tr>
            <th>STT</th>
            <th>Tên thành phố</th>
            <th>Hình ảnh</th>
            <th>Tùy chọn</th>
        </tr>
        <?php $count = 0;
        while ($row = mysqli_fetch_array($result)) {
            $count += 1; ?>
            <tr>
                <td><?php echo $count ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><img class="thumbnail" src="<?php echo "../images/cities/" . $row['image'] ?>" alt="City Image" width="100"></td>
                <td>
                    <a href="city/delete.php?this_id=<?php echo $row['id'] ?>"><button class="delete" onclick="return confirm('Có chắc xóa thành phố này không?');">Xóa</button></a>
                    <button class="edit" onclick="openPopup(<?php echo $row['id'] ?>, '<?php echo $row['name'] ?>', '<?php echo $row['image'] ?>')">Sửa</button>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<div id="popupForm" class="popup-form">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">×</span>
        <h3>Sửa thông tin thành phố</h3>
        <form id="editForm" method="POST" action="city/update.php" enctype="multipart/form-data">
            <input type="hidden" id="cityId" name="cityId">
            <label for="cityName">Tên thành phố:</label>
            <input type="text" id="cityName" name="cityName" required>
            <label for="cityImage">Hình ảnh (chọn tệp mới):</label>
            <input type="file" id="cityImage" name="cityImage" accept="image/*">
            <div id="currentImageContainer"></div>
            <button type="submit" onclick="return confirm('Có chắc muốn lưu thay đổi?')">Lưu thay đổi</button>
        </form>

    </div>
</div>

<style>
    .popup-form {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .popup-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        text-align: center;
        position: relative;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 30px;
        cursor: pointer;
    }

    .popup-form input {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
    }

    .thumbnail {
        max-width: 100%;
        height: auto;
    }
</style>

<script>
    function openPopup(id, name, image) {
        document.getElementById('popupForm').style.display = 'flex';
        document.getElementById('cityId').value = id;
        document.getElementById('cityName').value = name;

        var currentImageContainer = document.getElementById('currentImageContainer');
        currentImageContainer.innerHTML = '<img src="/web-booking-hotel/images/kham-pha-vn/' + image + '" alt="Current City Image" width="200">';

        document.getElementById('newImagePreview').innerHTML = '';
    }

    function closePopup() {
        document.getElementById('popupForm').style.display = 'none';
    }
</script>