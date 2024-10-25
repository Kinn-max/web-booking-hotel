<?php
include("../config/connect.php");
$sql = "SELECT * FROM booking";

$sql_city_final = mysqli_query($mysqli, $sql);
?>

<div class="table-container">
    <table class="styled-table">
        <thead>
            <tr>
            <th>Stt</th>
            <th>Tên khách hàng</th>
            <th>Phòng</th>
            <th>Khách sạn</th>
            <th>Ngày đặt phòng</th>
            <th>Ngày trả phòng</th>
            <th>Ngày tạo</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
        </thead>
    <?php 
    $stt = 1;
    while ($result = mysqli_fetch_array($sql_city_final)) { 
        $tmp = null;
    ?>
    <tr>
        <td><?php echo $stt++; ?></td>
        <td>  
            <?php
                 $sql_user = "SELECT a.fullname FROM user a WHERE a.id = " . $result["id_user"];
                 $sql_user_final = mysqli_query($mysqli, $sql_user);
                 if ($row_user = mysqli_fetch_array($sql_user_final)) {
                    echo $row_user["fullname"];
                } else {
                    echo "Không tìm thấy tên người dùng";
                } 
             ?> 
        </td>
        <td>  
        <?php
                $sql_room = "SELECT a.name, a.id_hotel FROM room a WHERE a.id = " . $result["id_room"];
                $sql_room_final = mysqli_query($mysqli, $sql_room);
                if ($row_room = mysqli_fetch_array($sql_room_final)) {
                    $tmp = $row_room["id_hotel"]; 
                    echo $row_room["name"]; 
                } 
            ?> 
        </td>
        <td>
        <?php
            if ($tmp) { 
                $sql_hotel = "SELECT a.name FROM hotel a WHERE a.id = " . $tmp;
                $sql_hotel_final = mysqli_query($mysqli, $sql_hotel);
                if ($row_hotel = mysqli_fetch_array($sql_hotel_final)) {
                    echo $row_hotel["name"]; 
                } else {
                    echo "Không tìm thấy khách sạn";
                } 
            } else {
                echo "Không tìm thấy khách sạn";
            }
            ?> 
        </td>
        <td>   
            <?php echo $result["check_in_date"]; ?>
        </td>
        <td>   
             <?php echo $result["check_out_date"]; ?>
        </td>
        <td>   
          <?php echo $result["create_at"]; ?>
        </td>
        <td>   
             <?php echo $result["total_price"]; ?>
        </td>
        <td>   
            <?php echo $result["booking_status"]; ?>
        </td>
    </tr>
    <?php } ?>
    </table>
</div>
