<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');

$sql = 'SELECT * FROM hotel';
$result = mysqli_query($mysqli, $sql);

$city_sql = 'SELECT * FROM city';
$city_result = mysqli_query($mysqli, $city_sql);
if (isset($_SESSION['success'])) {
    echo "<script>alert('Cập nhật thành công');</script>";
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    echo "<script>alert('Cập nhật thất bại');</script>";
    unset($_SESSION['error']);
}
$service = [];
$query_service = "SELECT * FROM `service`";
if ($sql = $mysqli->query($query_service)) {
    while ($row = $sql->fetch_assoc()) {
        $service[] = $row;
    }
}
?>

<style>
    .city-management-container {
        width: 80%;
    }
</style>

<div class="city-management-container">
    <h2>Danh sách khách sạn</h2>

    <div class="add-button">
        <button class="add" onclick="openAddPopup()">Thêm Khách Sạn</button>
    </div>
    <?php if (isset($_SESSION['error_hotel_message'])) { ?>
        <div class='error-message' style='color: red;'><?php echo $_SESSION['error_hotel_message'] ?></div>
        <?php unset($_SESSION['error_hotel_message']) ?>
    <?php } elseif (isset($_SESSION['success_hotel_message'])) { ?>
        <div class='success-message' style='color: green;background-color: #d4edda'><?php echo $_SESSION['success_hotel_message'] ?></div>
        <?php unset($_SESSION['success_hotel_message']) ?>
    <?php } ?>
    <div class="table-container">
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
                $hotel_id = $row['id'];
                $image_sql = "SELECT * FROM `image_hotel` WHERE `id_hotel` = $hotel_id";
                $image_result = mysqli_query($mysqli, $image_sql);
                $images = [];
                while ($image_row = mysqli_fetch_array($image_result)) {
                    $images[] = $image_row['name'];
                }
            ?>

                <tr>
                    <td><?php echo $count ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo $row['address'] ?></td>
                    <td><?php echo $row['phone'] ?></td>
                    <td>
                        <div class="descriptionHotel" id="desc-<?php echo $row['id']; ?>">
                            <?php echo substr($row['description'], 0, 100); ?>...
                        </div>
                    </td>
                    <td>
                        <?php foreach (array_slice($images, 0, 4) as $image) { ?>
                            <img class="thumbnail" src="<?php echo "../images/hotel-detail/" . $image ?>" alt="Hotel Image" />
                        <?php } ?>
                    </td>
                    <td>
                        <button class="details" onclick="openEditPopup('<?php echo $row['id']; ?>')">Chi tiết</button>
                        <a href="hotel/delete.php?this_id=<?php echo $row['id']; ?>"><button class="delete" onclick="return confirm('Có chắc xóa khách sạn này không?');">Xóa</button></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

<!-- Cửa sổ pop-up -->
<div id="popupForm" class="popup-form">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">×</span>
        <h3 id="popupTitle">Thêm Khách Sạn Mới</h3>
        <form id="popupFormContent" method="POST" action="hotel/create.php" enctype="multipart/form-data">
            <input type="hidden" id="hotelId" name="hotelId">

            <label for="hotelName">Tên khách sạn:</label>
            <input type="text" id="hotelName" name="hotelName" placeholder="Nhập tên khách sạn" required>

            <label for="hotelAddress">Địa chỉ:</label>
            <input type="text" id="hotelAddress" name="hotelAddress" placeholder="Địa chỉ khách sạn" required>

            <label for="hotelPhone">Số điện thoại:</label>
            <input type="text" id="hotelPhone" name="hotelPhone" placeholder="Số điện thoại" required>

            <label for="hotelDescription">Mô tả:</label>
            <textarea id="hotelDescription" name="hotelDescription" rows="4" required placeholder="Mô tả khách sạn"></textarea>

            <label>Thuộc thành phố</label>
            <select id="option" name="city" required>
                <?php while ($rowCity = mysqli_fetch_assoc($city_result)) { ?>
                    <option value="<?php echo $rowCity['id'] ?>"><?php echo $rowCity['name'] ?></option>
                <?php } ?>
            </select>

            <label for="current-job-role">Dịch vụ</label>
            <select multiple id="current-job-role" class="sd-CustomSelect" name="service[]" style="width: 100%;">
                <?php
                foreach ($service as $row) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>

            <label for="hotelImage">Chọn hình ảnh mới (nếu có):</label>
            <input type="file" id="hotelImage" name="hotelImage[]" accept="image/*" multiple>

            <div id="currentImagesContainer"></div>

            <button type="submit" name="submit" onclick="return confirm('Xin hãy xác nhận')">xác nhận</button>
        </form>
    </div>
</div>

<script>
    function openAddPopup() {
        document.getElementById('popupTitle').textContent = 'Thêm Khách Sạn Mới';
        document.getElementById('popupFormContent').action = 'hotel/create.php';
        document.getElementById('popupFormContent').reset();
        document.getElementById('currentImagesContainer').innerHTML = '';
        document.getElementById('popupForm').style.display = 'flex';
    }

    function openEditPopup(hotelId) {
        document.getElementById('popupTitle').textContent = 'Chi tiết khách sạn';
        document.getElementById('popupFormContent').action = 'hotel/update.php';
        document.getElementById('hotelId').value = hotelId;

        fetch(`hotel/read.php?id=${hotelId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('hotelName').value = data.name;
                document.getElementById('hotelAddress').value = data.address;
                document.getElementById('hotelPhone').value = data.phone;
                document.getElementById('hotelDescription').value = data.description;

                const citySelect = document.getElementById('option');
                citySelect.value = data.city_id;

                const currentImagesContainer = document.getElementById('currentImagesContainer');
                currentImagesContainer.innerHTML = '';
                data.images.forEach(image => {
                    const img = document.createElement('img');
                    img.src = `../images/hotel-detail/${image}`;
                    img.classList.add('thumbnail');
                    currentImagesContainer.appendChild(img);
                });
            });

        document.getElementById('popupForm').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('popupForm').style.display = 'none';
    }
</script>