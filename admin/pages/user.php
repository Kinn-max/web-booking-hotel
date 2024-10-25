<?php
$user = [];
$query_user = "SELECT * FROM user WHERE role = 'user'";
if ($sql = $mysqli->query($query_user)) {
    while ($row = $sql->fetch_assoc()) {
        $user[] = $row;
    }
}
?>

<div class="container-user">
    <h2>Danh sách người dùng</h2>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($user); $i++) {
                    echo "<tr>
                            <td>" . ($i + 1) . "</td>
                            <td>" . $user[$i]['fullname'] . "</td>" .
                        '<td>' . $user[$i]['email'] . '</td>' .
                        '<td>' . $user[$i]['phone'] . '</td>' .
                        "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>