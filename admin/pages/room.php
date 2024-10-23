<?php
$room = [];
$query_room = "SELECT *, room.name AS room_name, hotel.name AS hotel_name, room.id AS room_id FROM room INNER JOIN hotel ON hotel.id = room.id_hotel";
if ($sql = $mysqli->query($query_room)) {
    while ($row = $sql->fetch_assoc()) {
        $room[] = $row;
    }
}

$hotel = [];
$query_hotel = "SELECT * FROM `hotel`";
if ($sql = $mysqli->query($query_hotel)) {
    while ($row = $sql->fetch_assoc()) {
        $hotel[] = $row;
    }
}

$service = [];
$query_service = "SELECT * FROM `service`";
if ($sql = $mysqli->query($query_service)) {
    while ($row = $sql->fetch_assoc()) {
        $service[] = $row;
    }
}

if (isset($_POST['save'])) {
    $name = $_POST['room-name'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $hotel = $_POST['hotel'];
    $photo = $_POST['photo'];
    $services = isset($_POST['service']) ? $_POST['service'] : [];

    // Xử lý khi có upload file mới
    if (!empty($_FILES["image"]["name"])) {
        $original_filename = $_FILES["image"]["name"];
        $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
        $filename = pathinfo($original_filename, PATHINFO_FILENAME) . '_' . time() . '.' . $file_extension;
        $tempname = $_FILES["image"]["tmp_name"];
        $folder = $_SERVER['DOCUMENT_ROOT'] . "/web-booking-hotel/images/rooms/" . $filename;
        move_uploaded_file($tempname, $folder);

        if (!empty($photo) || $photo != null) {
            $old_image_path = $_SERVER['DOCUMENT_ROOT'] . "/web-booking-hotel/images/rooms/" . $photo;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
    } else {
        // Giữ lại ảnh cũ nếu không upload ảnh mới
        $filename = $photo;
    }

    if (isset($_POST['room-id']) && !empty($_POST['room-id'])) {
        // Update existing room
        $room_id = $_POST['room-id'];
        $update_room = "UPDATE `room` SET 
            `id_hotel` = '$hotel',
            `name` = '$name',
            `num_of_people` = '$quantity',
            `description` = '$description',
            `price` = '$price',
            `image` = '$filename'
            WHERE `id` = '$room_id'";

        $mysqli->query($update_room);

        if (!empty($services)) {
            $delete_old_services = "DELETE FROM `service_room` WHERE room_id = '$room_id'";
            $mysqli->query($delete_old_services);
        }
    } else {
        // Insert new room
        $insert_room = "INSERT INTO `room`(`id`, `id_hotel`, `name`, `num_of_people`, `description`, `price`, `image`) 
            VALUES (NULL,'$hotel','$name','$quantity','$description','$price','$filename')";
        $mysqli->query($insert_room);
        $room_id = $mysqli->insert_id;
    }

    if (!empty($services)) {
        foreach ($services as $service_id) {
            $insert_service = "INSERT INTO `service_room`(`room_id`, `service_id`) VALUES ('$room_id', '$service_id')";
            $mysqli->query($insert_service);
        }
    }

    echo "<script>window.location.href = '/web-booking-hotel/admin/index.php?page=room';</script>";
    exit();
}

if (isset($_GET['delete_id'])) {
    $room_id = $_GET['delete_id'];

    $query_image = "SELECT `image` FROM `room` WHERE `id` = '$room_id'";
    $result = $mysqli->query($query_image);
    $image = $result->fetch_assoc();

    if (!empty($image) || $image != null) {
        $old_image_path = $_SERVER['DOCUMENT_ROOT'] . "/web-booking-hotel/images/rooms/" . $image['image'];
        if (file_exists($old_image_path)) {
            unlink($old_image_path);
        }
    }

    $delete_services = "DELETE FROM `service_room` WHERE `room_id` = '$room_id'";
    $mysqli->query($delete_services);

    $delete_room = "DELETE FROM `room` WHERE `id` = '$room_id'";
    $mysqli->query($delete_room);

    echo "<script>window.location.href = '/web-booking-hotel/admin/index.php?page=room';</script>";
    exit();
}
?>

<div class="container-room">
    <h2>Add Room</h2>

    <button id="openDialogBtn" class="add">Add Room</button>

    <div id="roomDialog" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="title-form"></h2>

            <div class="form-room">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="room-id">
                    <input type="hidden" name="photo">
                    <div class="form-group">
                        <label for="room-name">Room name: </label>
                        <input type="text" name="room-name" class="input-form" id="room-name" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity people: </label>
                        <input type="text" id="quantity" class="input-form" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label>Description: </label>
                        <textarea name="description" id="description" class="input-form"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Price: </label>
                        <input type="text" id="price" class="input-form" name="price" placeholder="X,XXX,XXX" required>
                    </div>
                    <div class="form-group">
                        <label>Image: </label>
                        <input type="file" id="image" class="input-form" name="image">
                    </div>
                    <div class="sd-multiSelect form-group">
                        <label for="current-job-role">Service</label>
                        <select multiple id="current-job-role" class="sd-CustomSelect" name="service[]">
                            <?php
                            foreach ($service as $row) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Hotel</label>
                        <select name="hotel" class="select-form">
                            <?php
                            foreach ($hotel as $row) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <button class="add" type="submit" name="save">
                        </button>
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
                    <th style="width: 40%;">Image</th>
                    <th>Room name</th>
                    <th>Hotel</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($room); $i++) {
                    echo "<tr>
                            <td>" . ($i + 1) . "</td>
                            <td><img class='thumbnail' src='../images/rooms/" . $room[$i]['image'] . "'></td>
                            <td>" . $room[$i]['room_name'] . "</td>" .
                        '<td>' . $room[$i]['hotel_name'] . '</td>' .
                        "<td><a href='/web-booking-hotel/admin/index.php?page=room&update_id=" . $room[$i]['room_id'] . "' class='btn-update'>Update</a>
                         | <a href='/web-booking-hotel/admin/index.php?page=room&delete_id=" . $room[$i]['room_id'] . "' class='btn-delete'>Delete</a>
                        </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    <?php if (isset($_GET['update_id'])): ?>
        modal.style.display = "block";
    <?php endif; ?>
</script>