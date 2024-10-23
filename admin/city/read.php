<?php
include($_SERVER['DOCUMENT_ROOT'] . '/web-booking-hotel/config/connect.php');

    //truy vấn dữ liệu
    $sql = 'SELECT * FROM `city`';
    $result = mysqli_query($mysqli, $sql);
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
            while ($row = mysqli_fetch_array($result)) { $count += 1;?>
                <tr>
                    <td><?php echo $count ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td><img class="thumbnail" src="<?php echo "../images/kham-pha-vn/" . $row['image'] ?>" alt="City Image" /></td>
                    <td>
                        <a href="city/delete.php?this_id=<?php echo $row['id'] ?>"><button class="delete" onclick="return confirm('Có chắc xóa thành phố này không?');">Xóa</button></a>
                        <a href="city/update.php?this_id=<?php echo $row['id'] ?>"><button class="edit">Sửa</button></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>