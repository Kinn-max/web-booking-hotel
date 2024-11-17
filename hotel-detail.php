<?php
include("config/connect.php");
session_start();
$loggedIn = false;  // Đảm bảo rằng biến này luôn được khai báo
$rowUser = null;     // Đảm bảo rằng biến này luôn được khai báo

if (isset($_SESSION['userEmail'])) {
    $loggedIn = true;
    $email = $_SESSION['userEmail'];
    $user = "SELECT * FROM user WHERE email = '$email'";
    $userName = $mysqli->query($user);
    $rowUser = $userName->fetch_assoc();
} 

$notifyValue = "";
if (isset($_POST['book'])) {
    if (isset($_POST['checkInDate']) && isset($_POST['checkOutDate'])) {
        $checkInDate = $_POST['checkInDate'];
        $checkOutDate = $_POST['checkOutDate'];
        $roomId = $_POST['roomId'];
        $createAt = date("Y-m-d");
        $totalPriceFinal = $_POST['totalPriceFinal'];
        $checkInDateTimestamp = strtotime($checkInDate);
        $checkOutDateTimestamp = strtotime($checkOutDate);
        $status = "Đang đặt";

        if ($rowUser != null) {
            $userId = $rowUser["id"];
        }

        if ($rowUser == null) {
            $notifyValue .= "- Bạn chưa đăng nhập.<br>";
        }
        if ($checkOutDateTimestamp <= $checkInDateTimestamp) {
            $notifyValue .= "- Ngày trả phòng phải sau ngày nhận phòng.<br>";
        }

        if ($notifyValue == "") {
            
            if ($rowUser != null) {
                $sql_in = "INSERT INTO booking(id_user, id_room, check_in_date, check_out_date, create_at, total_price, booking_status) 
                        VALUES ('" . $userId . "', '" . $roomId . "', '" . $checkInDate . "', '" . $checkOutDate . "', '" . $createAt . "', '" . $totalPriceFinal . "', '" . $status . "')";
                mysqli_query($mysqli, $sql_in);
                $notifyValue .= "- Đặt khách sạn thành công!";
            } else {
                $notifyValue .= "- Bạn chưa đăng nhập.";
            }
        }
    } else {
        echo "Thiếu thông tin cần thiết!";
    }
}

$slug = $_REQUEST['slug'];

$sql = "SELECT * FROM `hotel` WHERE `slug` = '$slug'";

$data = null;
if ($r = $mysqli->query($sql)) {
    while ($row = $r->fetch_assoc()) {
        $data = $row;
    }
}
$query = "SELECT `name` FROM `image_hotel` WHERE `id_hotel` = " . $data['id'];
$images = [];
if ($r = $mysqli->query($query)) {
    while ($row = $r->fetch_assoc()) {
        $images[] = $row;
    }
}

$query_service = "SELECT * FROM `service_hotel` INNER JOIN `service` ON `service_hotel`.`service_id` = `service`.`id` WHERE `hotel_id` = " . $data['id'];
$services = [];
if ($r = $mysqli->query($query_service)) {
    while ($row = $r->fetch_assoc()) {
        $services[] = $row;
    }
}

$query_room = "SELECT *, room.id AS room_id FROM room LEFT JOIN booking ON room.id = booking.id_room WHERE room.id_hotel = " . $data['id'] . " AND (booking.booking_status IS NULL OR booking.booking_status != 'Đang đặt')";
$rooms = [];
if ($r = $mysqli->query($query_room)) {
    while ($row = $r->fetch_assoc()) {
        $rooms[] = $row;
    }
}

$services_room = [];
foreach ($rooms as $room) {
    $query_service_room = "SELECT * FROM `service_room` INNER JOIN `service` ON `service_room`.`service_id` = `service`.`id` WHERE `room_id` = " . $room['room_id'];
    if ($r = $mysqli->query($query_service_room)) {
        while ($row = $r->fetch_assoc()) {
            $services_room[$room['id']][] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>7Seasons Apartments Budapest</title>
    <link rel="stylesheet" href="css/hotel-detail.css" />
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/index.css" />
    <script
        src="https://kit.fontawesome.com/e9ee262283.js"
        crossorigin="anonymous"></script>
</head>

<body>
    <!-- header -->
    <div class="header_main">
        <div class="header">
            <div class="header_logo">
            <a href="/web-booking-hotel/index.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 180 30">
                        <path fill="#fff"
                            d="M70.6 2.73999C70.602 2.19808 70.7646 1.66892 71.0673 1.21943C71.3701 0.769947 71.7993 0.420321 72.3007 0.214768C72.8021 0.00921437 73.3532 -0.0430342 73.8843 0.064629C74.4155 0.172292 74.9027 0.435032 75.2845 0.819622C75.6663 1.20421 75.9255 1.69338 76.0293 2.22527C76.133 2.75716 76.0768 3.30788 75.8676 3.80779C75.6584 4.3077 75.3056 4.73434 74.8539 5.03377C74.4022 5.3332 73.8719 5.49197 73.33 5.48999C72.9702 5.48868 72.6141 5.41651 72.2822 5.2776C71.9503 5.13869 71.649 4.93576 71.3955 4.6804C71.1419 4.42504 70.9412 4.12225 70.8047 3.78931C70.6683 3.45637 70.5987 3.09982 70.6 2.73999V2.73999ZM116.5 23.77C117.044 23.772 117.577 23.6124 118.031 23.3114C118.484 23.0104 118.838 22.5816 119.048 22.0793C119.257 21.577 119.313 21.0238 119.208 20.4897C119.103 19.9555 118.842 19.4646 118.458 19.079C118.074 18.6934 117.584 18.4305 117.05 18.3236C116.516 18.2167 115.963 18.2705 115.46 18.4784C114.957 18.6862 114.527 19.0387 114.224 19.4911C113.922 19.9436 113.76 20.4757 113.76 21.02C113.76 21.7476 114.048 22.4456 114.562 22.961C115.075 23.4764 115.772 23.7673 116.5 23.77V23.77ZM25.7 6.72999C24.0129 6.74775 22.3688 7.26426 20.9746 8.21447C19.5805 9.16469 18.4986 10.5061 17.8653 12.0699C17.2319 13.6337 17.0754 15.3499 17.4154 17.0025C17.7554 18.6551 18.5767 20.1701 19.776 21.3569C20.9752 22.5436 22.4988 23.349 24.1548 23.6717C25.8108 23.9944 27.5253 23.8199 29.0824 23.1702C30.6395 22.5205 31.9695 21.4247 32.9051 20.0206C33.8406 18.6166 34.3399 16.9672 34.34 15.28C34.3768 14.1396 34.1778 13.0038 33.7555 11.9438C33.3331 10.8839 32.6965 9.92248 31.8855 9.11989C31.0744 8.3173 30.1064 7.69078 29.0421 7.27955C27.9778 6.86831 26.84 6.68122 25.7 6.72999V6.72999ZM25.7 19.83C23.35 19.83 21.7 17.96 21.7 15.28C21.7 12.6 23.34 10.73 25.7 10.73C28.06 10.73 29.7 12.6 29.7 15.28C29.7 17.96 28.11 19.83 25.7 19.83ZM65.3 15.71C65.1321 15.3716 64.9128 15.0613 64.65 14.79L64.5 14.63L64.66 14.48C64.9116 14.2078 65.1423 13.917 65.35 13.61L69.74 7.05999H64.41L61.1 12.19C60.9586 12.3442 60.782 12.4621 60.5852 12.5334C60.3885 12.6048 60.1774 12.6277 59.97 12.6H59.22V2.90999C59.22 0.979993 58.01 0.709993 56.71 0.709993H54.48V23.58H59.21V16.72H59.65C60.19 16.72 60.56 16.78 60.73 17.08L63.35 21.97C63.5773 22.5089 63.9785 22.9563 64.4895 23.2408C65.0006 23.5253 65.5922 23.6306 66.17 23.54H69.8L67.09 19.07L65.3 15.71ZM88.27 6.68999C87.3747 6.67014 86.4851 6.83782 85.6584 7.18226C84.8318 7.5267 84.0863 8.04028 83.47 8.68999L83.18 8.97999L83.08 8.57999C82.9261 8.08803 82.6021 7.66692 82.166 7.39207C81.7299 7.11723 81.2102 7.0066 80.7 7.07999H78.57V23.57H83.29V15.97C83.275 15.2919 83.373 14.6159 83.58 13.97C83.7979 13.1302 84.2923 12.3883 84.9836 11.8639C85.6748 11.3396 86.5225 11.0634 87.39 11.08C88.85 11.08 89.39 11.85 89.39 13.86V21.05C89.335 21.3921 89.3619 21.7424 89.4686 22.072C89.5753 22.4017 89.7586 22.7013 90.0036 22.9463C90.2487 23.1914 90.5483 23.3747 90.878 23.4814C91.2076 23.5881 91.5579 23.615 91.9 23.56H94.12V13.07C94.15 8.89999 92.12 6.68999 88.27 6.68999V6.68999ZM73.39 7.05999H71.17V23.58H75.87V9.57999C75.9234 9.24041 75.8964 8.89304 75.7913 8.56576C75.6862 8.23848 75.5058 7.94038 75.2647 7.69537C75.0236 7.45037 74.7284 7.26527 74.4028 7.15493C74.0773 7.04459 73.7304 7.01208 73.39 7.05999V7.05999ZM44.16 6.72999C42.4729 6.74775 40.8288 7.26426 39.4346 8.21447C38.0405 9.16469 36.9586 10.5061 36.3253 12.0699C35.6919 13.6337 35.5354 15.3499 35.8754 17.0025C36.2154 18.6551 37.0367 20.1701 38.236 21.3569C39.4352 22.5436 40.9588 23.349 42.6148 23.6717C44.2708 23.9944 45.9853 23.8199 47.5424 23.1702C49.0995 22.5205 50.4295 21.4247 51.3651 20.0206C52.3006 18.6166 52.7999 16.9672 52.8 15.28C52.8368 14.1396 52.6378 13.0038 52.2155 11.9438C51.7931 10.8839 51.1565 9.92248 50.3455 9.11989C49.5344 8.3173 48.5664 7.69078 47.5021 7.27955C46.4378 6.86831 45.3 6.68122 44.16 6.72999V6.72999ZM44.16 19.83C41.81 19.83 40.16 17.96 40.16 15.28C40.16 12.6 41.8 10.73 44.16 10.73C46.52 10.73 48.16 12.6 48.16 15.28C48.16 17.96 46.57 19.83 44.16 19.83ZM144.89 6.72999C143.203 6.74775 141.559 7.26426 140.165 8.21447C138.77 9.16469 137.689 10.5061 137.055 12.0699C136.422 13.6337 136.265 15.3499 136.605 17.0025C136.945 18.6551 137.767 20.1701 138.966 21.3569C140.165 22.5436 141.689 23.349 143.345 23.6717C145.001 23.9944 146.715 23.8199 148.272 23.1702C149.829 22.5205 151.16 21.4247 152.095 20.0206C153.031 18.6166 153.53 16.9672 153.53 15.28C153.567 14.1396 153.368 13.0038 152.945 11.9438C152.523 10.8839 151.887 9.92248 151.075 9.11989C150.264 8.3173 149.296 7.69078 148.232 7.27955C147.168 6.86831 146.03 6.68122 144.89 6.72999V6.72999ZM144.89 19.83C142.54 19.83 140.89 17.96 140.89 15.28C140.89 12.6 142.53 10.73 144.89 10.73C147.25 10.73 148.89 12.6 148.89 15.28C148.89 17.96 147.3 19.83 144.89 19.83ZM109.74 7.01999C109.356 6.98285 108.97 7.05749 108.627 7.23491C108.285 7.41233 108.001 7.68497 107.81 8.01999L107.69 8.26999L107.47 8.07999C106.253 7.08344 104.711 6.57072 103.14 6.63999C98.75 6.63999 95.78 9.94999 95.78 14.87C95.78 19.79 98.85 23.22 103.23 23.22C104.521 23.2791 105.795 22.9061 106.85 22.16L107.21 21.88V22.34C107.21 24.55 105.78 25.77 103.21 25.77C102.131 25.755 101.062 25.5555 100.05 25.18C99.8562 25.0813 99.6419 25.0295 99.4244 25.0287C99.2069 25.0279 98.9923 25.0782 98.7977 25.1754C98.6032 25.2727 98.4342 25.4143 98.3043 25.5887C98.1745 25.7632 98.0874 25.9657 98.05 26.18L97.14 28.46L97.47 28.63C99.2593 29.5195 101.232 29.9783 103.23 29.97C107.23 29.97 111.9 27.91 111.9 22.14V7.01999H109.74ZM104.06 19.11C101.5 19.11 100.58 16.86 100.58 14.76C100.58 13.83 100.81 10.76 103.81 10.76C105.3 10.76 107.3 11.18 107.3 14.86C107.3 18.38 105.54 19.11 104.06 19.11ZM13.09 11.85L12.4 11.47L13 10.97C13.6103 10.4334 14.0951 9.76919 14.42 9.02435C14.7449 8.27951 14.9019 7.47231 14.88 6.65999C14.88 3.05999 12.09 0.739993 7.79 0.739993H2.31C1.69606 0.762953 1.11431 1.02048 0.684566 1.45953C0.254821 1.89857 0.00981021 2.48571 0 3.09999L0 23.5H7.88C12.67 23.5 15.77 20.89 15.77 16.84C15.8104 15.8446 15.583 14.8566 15.1116 13.9789C14.6403 13.1012 13.9421 12.3661 13.09 11.85V11.85ZM4.37 6.07999C4.37 5.01999 4.82 4.51999 5.8 4.45999H7.8C8.16093 4.42761 8.52456 4.47504 8.8651 4.59892C9.20565 4.7228 9.51476 4.9201 9.77052 5.17681C10.0263 5.43353 10.2224 5.74338 10.345 6.08438C10.4676 6.42538 10.5137 6.78919 10.48 7.14999C10.5194 7.51629 10.4791 7.88679 10.3616 8.23598C10.2442 8.58517 10.0524 8.90477 9.79964 9.17277C9.54684 9.44077 9.23898 9.65082 8.89723 9.78844C8.55549 9.92606 8.18798 9.988 7.82 9.96999H4.37V6.07999ZM8.2 19.64H4.37V15.06C4.37 14.06 4.76 13.57 5.59 13.45H8.2C8.99043 13.4949 9.7337 13.8406 10.2774 14.4161C10.8211 14.9916 11.124 15.7533 11.124 16.545C11.124 17.3367 10.8211 18.0984 10.2774 18.6739C9.7337 19.2494 8.99043 19.595 8.2 19.64ZM174.53 6.73999C173.558 6.74366 172.6 6.96575 171.726 7.38984C170.852 7.81393 170.084 8.42915 169.48 9.18999L169.14 9.62999L168.87 9.13999C168.437 8.355 167.787 7.71128 166.998 7.2857C166.209 6.86012 165.314 6.67067 164.42 6.73999C163.604 6.75328 162.798 6.93308 162.054 7.26838C161.309 7.60368 160.641 8.08742 160.09 8.68999L159.65 9.16999L159.48 8.53999C159.323 8.07152 159.008 7.67282 158.587 7.41334C158.167 7.15386 157.669 7.05005 157.18 7.11999H155.18V23.57H159.64V16.31C159.646 15.6629 159.727 15.0187 159.88 14.39C160.31 12.63 161.49 10.74 163.47 10.93C164.69 11.05 165.29 11.99 165.29 13.82V23.57H169.81V16.31C169.791 15.6345 169.875 14.9601 170.06 14.31C170.42 12.64 171.65 10.92 173.56 10.92C174.94 10.92 175.45 11.7 175.45 13.81V21.17C175.45 22.83 176.19 23.57 177.85 23.57H180V13.07C180 8.86999 178.15 6.73999 174.53 6.73999ZM133.69 17.86C132.51 19.095 130.913 19.8471 129.21 19.97C128.593 20.0073 127.974 19.914 127.395 19.6962C126.816 19.4784 126.29 19.141 125.85 18.706C125.41 18.271 125.067 17.7482 124.843 17.1716C124.619 16.5951 124.519 15.9778 124.55 15.36C124.498 14.7504 124.575 14.1365 124.776 13.5588C124.978 12.981 125.299 12.4524 125.719 12.0076C126.14 11.5629 126.649 11.212 127.215 10.978C127.78 10.744 128.388 10.6322 129 10.65C129.84 10.65 130.8 10.95 130.95 11.46V11.55C131.048 11.8986 131.258 12.2056 131.547 12.424C131.835 12.6425 132.188 12.7605 132.55 12.76H135V10.61C135 7.76999 131.39 6.73999 129 6.73999C123.81 6.73999 120 10.37 120 15.35C120 20.33 123.73 23.97 128.86 23.97C130.178 23.9562 131.479 23.6722 132.683 23.1355C133.887 22.5989 134.969 21.821 135.86 20.85L134 17.58L133.69 17.86Z">
                        </path>
                    </svg>
                </a>
            </div>
            <div class="header_btn">
                <?php if ($loggedIn): ?>
                    <div class="navbar" onclick="toggleDropdown()">
                        <div class="user-info">
                            <span class="user-name"> Chào mừng, <?php echo $rowUser['fullname']?> </span>
                            <span class="user-status"></span>
                            <div class="dropdown-menu" id="dropdownMenu">
                                <a href="user/user.php?id=<?php echo $rowUser['id']?>&&page=profile">
                                    <span class="icon">👤</span> Thông tin cá nhân
                                </a>
                                <a href="user/user.php?id=<?php echo $rowUser['id']?>&&page=book-history">
                                    <span class="icon">💼</span> Lịch sử chuyến đi
                                </a>
                                <a href="user/user.php?id=<?php echo $rowUser['id']?>&&page=change-password">
                                    <span class="icon">🔐</span> Đổi mật khẩu
                                </a>
                                <a href="auth/logout.php">
                                    <span class="icon">🚪</span> Đăng xuất
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="auth/register.php"><button class="header_btn-item">Đăng ký</button></a>
                    <a href="auth/login.php"><button class="header_btn-item">Đăng nhập</button></a>
                <?php endif; ?>
            </div>
        </div>

        </div>
        <div class="select_date">
        <form action="search.php" method="GET">
          <div class="select_date_form">
              <div class="header_input_form" style="flex: 2">
                  <i class="fa-solid fa-bed"></i>
                  <input
                      class="input_form"
                      type="text"
                      name="destination"
                      placeholder="Bạn muốn đến đâu?"
                  />
              </div>
              <div class="header_input_form" style="flex: 1">
                  <i class="fa-solid fa-calendar-days"></i>
                  <input class="input_form" type="date" name="date" />
              </div>
              <div class="header_input_form" style="flex: 1">
                <button  type="button" class="header_dropdown_form">
                    <span>
                        <i class="fa-solid fa-bed icon-dropdown"></i>
                        <span class="number_of_bed">1</span> <!-- Hiển thị số giường -->
                    </span>
                    <div class="connect_two-element"></div>
                    <span><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="hover-hidden"></div>
                <div class="options">
                    <div class="input-adult df">
                        <div class="label-adult df">
                            <label for="">Giường</label>
                        </div>
                        <div class="select-adult">
                            <button type="button" class="pointer-hover" onclick="updateValue('rooms', -1)">-</button>
                            <span id="rooms_count">1</span>
                            <input type="hidden" name="rooms" id="rooms" value="1">
                            <button type="button" onclick="updateValue('rooms', 1)">+</button>
                        </div>
                    </div>
                    <button type="button" class="confirm-option" onclick="closeOptions()">Xong</button>
                </div>
            </div>
              <button class="header_btn_form" type="submit">Tìm</button>
          </div>
        </form>
        </div>
        </div>
    </div>
    <!-- end header -->

    <!-- body -->
    <div class="notify_among">
        <span class="text-notify_among"></span>
        <div class="control_notify"></div>
    </div>
    <div class="container">
        <div class="row mt-4 df">
            <div class="header_detail">
                <h2 class="hotel_name"><?php echo $data['name'] ?></h2>
                <div class="hotel_address df">
                    <i class="fa-solid fa-location-dot"></i>
                    <p><?php echo $data['address'] ?> - </p>
                    <a href=<?php echo "https://www.google.com/maps/search/?api=1&query=" .  $data['slug'] ?> target="blank">Hiển thị bản đồ</a>
                </div>
            </div>
            <div class="action df">
                <div class="action_icon">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <div class="action_icon">
                    <i class="fa-solid fa-up-right-from-square"></i>
                </div>
                <div class="action_btn">
                    <button>Đặt ngay</button>
                </div>
            </div>
        </div>
        <div class="row mt-4 container-detail">
            <div class="content-right">
                <div class="first-row-img df">
                    <div class="first-col-img">
                        <?php
                        for ($i = 0; $i < 2; $i++) {
                            echo '<img src="./images/hotel-detail/' .  $images[$i]['name'] . '" alt="" srcset="" />';
                        }
                        ?>
                    </div>
                    <div class="second-col-img">
                        <img src=<?php echo "./images/hotel-detail/" .  $images[2]['name'] ?> alt="" srcset="" />
                    </div>
                </div>
                <div class="second-row-img">
                    <?php
                    for ($i = 3; $i < count($images); $i++) {
                        echo '<img src="./images/hotel-detail/' .  $images[$i]['name'] . '" alt="" srcset="" />';
                    }
                    ?>
                </div>
            </div>
            <div class="content-left">
                <div class="map">
                    <iframe src="https://maps.google.com/maps?q=<?php echo $data['name'] ?>&output=embed"
                        width="255"
                        height="475"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        <div class="row mt-8 container-description">
            <div class="description">
                <p>
                    <?php echo $data['description'] ?>
                </p>
            </div>
            <div class="service-favor">
                <h3>Các tiện nghi được ưa chuộng nhất</h3>
                <div class="services">
                    <?php
                    for ($i = 0; $i < count($services); $i++) {
                        echo '<i class="' . $services[$i]['icon'] . '"></i>
                    <span>' . $services[$i]['name'] . '</span>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row mt-8 container-room df">
            <h2>DANH SÁCH PHÒNG</h2>
            <?php
            for ($i = 0; $i < count($rooms); $i++) {
                echo '<div class="card-room mt-4">
                <div class="card-content">
                    <div class="image-room">
                        <img src="./images/rooms/' . $rooms[$i]['image'] . '" alt="" srcset="" />
                    </div>
                    <div class="info-room">
                        <label for="">' . $rooms[$i]['name'] . '</label>
                        <p class="mt-8">
                            <i class="fa-solid fa-bed"></i>
                            ' . $rooms[$i]['description'] . '
                        </p>
                        <hr />
                        <div class="more-info">
                            <div class="benefits">
                                <div class="benefits-list">';
                for ($j = 0; $j < count($services_room[$rooms[$i]['id']]) / 2; $j++) {
                    echo '<div class="benefits-column">';
                    $max_services = min(4, count($services_room[$rooms[$i]['id']]) - ($j * 4));
                    for ($z = 0; $z < $max_services; $z++) {
                        $service_index = $j * 4 + $z;
                        echo '<div>
                                            <p>' . $services_room[$rooms[$i]['id']][$service_index]['name'] . '</p>
                                        </div>';
                    }
                    echo '</div>';
                }
                echo '</div>
                </div>
                <div class="price-room">
                    <span>' . $rooms[$i]['price'] . '<small>/ 1 đêm</small></span>
                    
                    <button id="showDetail' . $i . '" class="showDetailBtn" onclick="showModel( \'' . addslashes($rooms[$i]['id']) . '\',\'' . $rooms[$i]['image'] . '\', \'' . addslashes($rooms[$i]['name']) . '\', \'' . addslashes($rooms[$i]['description']) . '\', \'' . addslashes($rooms[$i]['price']) . '\')">Chọn phòng</button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>';
            }
            ?>
        </div>
        <div id="roomModal" class="show-model" style="display:none;">
            <div class="head-model">
                <div onclick="closeModal()" class="close-model">x</div>
            </div>
            <div class="card-content card-content-model">
                <div class="image-model">
                    <img id="modalImage" src="" alt="" />
                </div>
                <div class="info-room info-room-model">
                    <label id="modalName"></label>
                    <p class="mt-8">
                        <i class="fa-solid fa-bed"></i>
                        <span id="modalDescription"></span>
                    <div id="price-model" style="font-size: 2.4rem;color : #c0392b; margin: 5px 0"></div>
                    </p>
                    <div class="form-model">
                        <form action="" method="post">
                            <div class="both-form">
                                <label for="checkInDate" class="font-label">Ngày nhận phòng:</label>
                                <input type="date" id="checkInDate" name="checkInDate">
                                <label for="checkOutDate" class="font-label">Ngày trả phòng:</label>
                                <input type="date" id="checkOutDate" name="checkOutDate">
                                <br>
                            </div>
                            <div>
                                <input type="text" hidden id="roomId" name="roomId">
                                <input type="text" hidden id="totalPrice" name="totalPrice">
                                <input type="text" hidden id="totalPriceFinal" name="totalPriceFinal">
                            </div>
                            <p id="result"></p>
                            <div class="bottom-final">
                                <div class="step-final">
                                    <span class="number-rent-hotel" style="font-size: 1.2rem;display:flex">Số đêm: <p style="color: red;" id="numberOfDate"></p></span>
                                    <span class="total-price--model" style="display:flex">
                                        Tổng tiền:<p style="color: red;" id="totalPriceText"></p>
                                    </span>
                                    <br>
                                    <div class="price-room">
                                        <button type="submit" name="book" value="book" class="showDetailBtn" style="width:120px">
                                            Đặt ngay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

    </div>
    <!-- end body -->
    <!-- footer -->
    <div id="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Hỗ trợ</h3>
                <ul>
                    <li>
                        <a href="#">Các câu hỏi thường gặp về virus corona (COVID-19)</a>
                    </li>
                    <li><a href="#">Quản lí các chuyến đi của bạn</a></li>
                    <li><a href="#">Liên hệ Dịch vụ Khách hàng</a></li>
                    <li><a href="#">Trung tâm thông tin bảo mật</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Khám phá thêm</h3>
                <ul>
                    <li><a href="#">Chương trình khách hàng thân thiết Genius</a></li>
                    <li><a href="#">Ưu đãi theo mùa và dịp lễ</a></li>
                    <li><a href="#">Bài viết về du lịch</a></li>
                    <li><a href="#">Booking.com dành cho Doanh Nghiệp</a></li>
                    <li><a href="#">Traveller Review Awards</a></li>
                    <li><a href="#">Cho thuê xe hơi</a></li>
                    <li><a href="#">Tìm chuyến bay</a></li>
                    <li><a href="#">Đặt nhà hàng</a></li>
                    <li><a href="#">Booking.com dành cho Đại Lý Du Lịch</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Điều khoản và cài đặt</h3>
                <ul>
                    <li><a href="#">Bảo mật & Cookie</a></li>
                    <li><a href="#">Điều khoản và điều kiện</a></li>
                    <li><a href="#">Tranh chấp đối tác</a></li>
                    <li><a href="#">Chính sách chống Nô lệ Hiện đại</a></li>
                    <li><a href="#">Chính sách về Quyền con người</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Dành cho đối tác</h3>
                <ul>
                    <li><a href="#">Đăng nhập vào trang Extranet</a></li>
                    <li><a href="#">Trợ giúp đối tác</a></li>
                    <li><a href="#">Đăng chỗ nghỉ của Quý vị</a></li>
                    <li><a href="#">Trở thành đối tác phân phối</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Về chúng tôi</h3>
                <ul>
                    <li><a href="#">Đăng nhập vào trang Extranet</a></li>
                    <li><a href="#">Trợ giúp đối tác</a></li>
                    <li><a href="#">Đăng chỗ nghỉ của Quý vị</a></li>
                    <li><a href="#">Trở thành đối tác phân phối</a></li>
                </ul>
            </div>
        </div>
        <hr class="footer-line" />
        <div class="footer-sub">
            Booking.com là một phần của Booking Holdings Inc., tập đoàn đứng đầu thế
            giới về du lịch trực tuyến và các dịch vụ liên quan.
        </div>
        <div class="footer-sub">
            Bản quyền © 1996 - 2024 Booking.com™. Bảo lưu mọi quyền.
        </div>
    </div>
    <div id="footer">
        <div class="social-list">
            <a href=""><i class="fa-brands fa-facebook-f"></i></a>
            <a href=""><i class="fa-brands fa-instagram"></i></a>
            <a href=""><i class="fa-brands fa-youtube"></i></a>
            <a href=""><i class="fa-brands fa-pinterest-p"></i></a>
            <a href=""><i class="fa-brands fa-twitter"></i></a>
            <a href=""><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </div>
    <!-- end -->
    <script src="js/search.js"></script>
    <script>
        function showModel(id, image, name, description, price) {
            document.getElementById("modalImage").src = './images/rooms/' + image;
            document.getElementById("modalName").innerText = name;
            document.getElementById("modalDescription").innerText = description;
            document.getElementById("price-model").innerText = price + " đ";
            document.getElementById("roomId").value = id;
            let priceStr = price.replace(/,/g, "");
            let pricefinal = parseFloat(priceStr);
            document.getElementById("totalPrice").value = pricefinal;

            document.getElementById("roomModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("roomModal").style.display = "none";
        }

        document.getElementById('checkInDate').addEventListener('change', calculateDays);
        document.getElementById('checkOutDate').addEventListener('change', calculateDays);
        const numberOfDate = document.getElementById("numberOfDate");
        const totalPriceText = document.getElementById("totalPriceText");

        function calculateDays() {
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            var checkInDate = new Date(document.getElementById('checkInDate').value);
            var checkOutDate = new Date(document.getElementById('checkOutDate').value);
            var valuetotal = document.getElementById("totalPrice").value;

            if (checkInDate && checkOutDate) {
                if (checkInDate >= today) {
                    if (checkOutDate > checkInDate) {
                        var timeDifference = checkOutDate - checkInDate;
                        var dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24));

                        document.getElementById('result').innerHTML = "Số ngày: " + dayDifference + " ngày";
                        numberOfDate.innerHTML = dayDifference + " đêm";

                        var totalPrice = valuetotal * dayDifference;
                        document.getElementById("totalPriceFinal").value = totalPrice;
                        totalPriceText.innerHTML = totalPrice + " đ";
                    } else {
                        document.getElementById('result').innerHTML = "Ngày trả phòng phải sau ngày nhận phòng!";
                        numberOfDate.innerHTML = "";
                        totalPriceText.innerHTML = "";
                    }
                } else {
                    document.getElementById('result').innerHTML = "Ngày nhận phòng phải từ hôm nay trở đi!";
                    numberOfDate.innerHTML = "";
                    totalPriceText.innerHTML = "";
                }
            } else {
                document.getElementById('result').innerHTML = "";
                numberOfDate.innerHTML = "";
                totalPriceText.innerHTML = "";
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            function updateNotification() {
                var notifyAmongValue = "<?php echo $notifyValue ?>"
                const notifyAmong = document.querySelector(".notify_among");
                var textNotifyAmong = document.querySelector(".text-notify_among");
                var progressBar = document.querySelector(".control_notify");

                if (notifyAmongValue.length > 0) {

                    notifyAmong.classList.add("active-show");
                    textNotifyAmong.innerHTML = notifyAmongValue;
                    setTimeout(function() {
                        hideNotification()
                    }, 3000);
                    var width = progressBar.clientWidth;
                    var widthDele = width / 200;
                    var interval = setInterval(function() {
                        if (width <= 0) {
                            clearInterval(interval);
                        } else {
                            width -= widthDele;
                            progressBar.style.width = width + "px";
                        }
                    }, 20);
                }
            }

            function hideNotification() {
                const notifyAmong = document.querySelector(".notify_among");
                notifyAmong.classList.remove("active-show");
            }
            updateNotification();
        });

    function toggleDropdown() {
            var dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('active');
        }
    </script>
</body>

</html>