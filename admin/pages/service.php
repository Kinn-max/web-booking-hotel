<?php
$service = [];
$query_service = "SELECT * FROM `service`";
if ($sql = $mysqli->query($query_service)) {
    while ($row = $sql->fetch_assoc()) {
        $service[] = $row;
    }
}

$service_to_update = null;
if (isset($_GET['update_id'])) {
    $update_id = $_GET['update_id'];
    $query_get_service = "SELECT * FROM `service` WHERE id = $update_id";
    $result = $mysqli->query($query_get_service);
    if ($result) {
        $service_to_update = $result->fetch_assoc();
    }
}

if (isset($_POST['save'])) {
    $name = $_POST['service-name'];
    $icon = $_POST['icon'];
    $service_id = $_POST['service-id'];

    if ($service_id) {
        $query_update_service = "UPDATE `service` SET `name`='$name', `icon`='$icon' WHERE `id`=$service_id;";
        $mysqli->query($query_update_service);
    } else {
        $query_insert_service = "INSERT INTO `service`(`id`, `name`, `icon`) VALUES (NULL,'$name','$icon');";
        $mysqli->query($query_insert_service);
    }

    echo "<script>window.location.href = '/web-booking-hotel/admin/index.php?page=service';</script>";
    exit();
}

if (isset($_GET['delete_id'])) {
    $service_id = $_GET['delete_id'];

    $delete_services = "DELETE FROM `service_room` WHERE `service_id` = '$service_id'";
    $mysqli->query($delete_services);

    $delete_service = "DELETE FROM `service` WHERE `id` = '$service_id'";
    $mysqli->query($delete_service);

    echo "<script>window.location.href = '/web-booking-hotel/admin/index.php?page=service';</script>";
    exit();
}
?>

<div class="container-service">
    <h2>Thêm dịch vụ</h2>

    <button id="openDialogBtn" class="add">Thêm</button>

    <div id="serviceDialog" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Dịch vụ</h2>

            <div class="form-service-hotel">
                <form action="" method="post">
                    <input type="hidden" name="service-id" value="<?php echo $service_to_update ? $service_to_update['id'] : ''; ?>">
                    <div class="form-group">
                        <label for="service-name">Tên dịch vụ: </label>
                        <input type="text" name="service-name" class="input-form" id="service-name" required>
                    </div>
                    <div class="form-group">
                        <label>Icon: </label>
                        <input type="text" id="iconpicker" class="input-form" name="icon">
                    </div>
                    <div>
                        <button class="save" type="submit" name="save">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên dịch vụ</th>
                    <th>Icon</th>
                    <th>Tùy chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($service); $i++) {
                    echo "<tr>
                            <td>" . ($i + 1) . "</td>
                            <td>" . $service[$i]['name'] . "</td>" .
                        '<td><i class="' . $service[$i]['icon'] . '"></i></td>' .
                        "<td><a href='/web-booking-hotel/admin/index.php?page=service&update_id=" . $service[$i]['id'] . "' class='btn-update'>Chỉnh sửa</a>
                        | <a href='/web-booking-hotel/admin/index.php?page=service&delete_id=" . $service[$i]['id'] . "' class='btn-delete'>Xóa</a>
                        </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>