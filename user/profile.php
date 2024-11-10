<section>
    <link rel="stylesheet" href="../css/user.css">
    <div class="title">
        <h1>Thông tin cá nhân</h1>

        <div class="messages">
            <?php if (isset($_SESSION['success_message'])): ?>
                <span class="message success" id="message"><?php echo $_SESSION['success_message']; ?></span>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <span class="message error" id="message"><?php echo $_SESSION['error_message']; ?></span>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        </div>
    </div>

    <form class="profile" action="" method="POST">
        <div class="profile-info">
            <div class="profile-row">
                <label for="name" class="label">Tên</label>
                <input type="text" id="name" name="name" class="input" value="<?php echo $userInfo['fullname']?>">
            </div>
            <div class="profile-row">
                <label for="email" class="label">Địa chỉ email</label>
                <input type="email" id="email" name="email" class="input" value="<?php echo $userInfo['email']?>">
            </div>
            <div class="profile-row">
                <label for="phone" class="label">Số điện thoại</label>
                <input type="text" id="phone" name="phone" class="input" value="<?php echo $userInfo['phone']?>">
            </div>
            <div class="profile-row">
                <label for="birthday" class="label">Ngày sinh</label>
                <input type="date" id="birthday" name="birthday" class="input" value="<?php echo $userInfo['birthday']?>">
            </div>
            <div class="profile-row">
                <label for="gender" class="label">Giới tính</label>
                <select id="gender" name="gender" class="input">
                    <option value="" <?php echo $userInfo['sex'] == '' ? 'selected' : ''; ?>>Chọn giới tính</option>
                    <option value="Nam" <?php echo $userInfo['sex'] == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                    <option value="Nữ" <?php echo $userInfo['sex'] == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                    <option value="Khác" <?php echo $userInfo['sex'] == 'Khác' ? 'selected' : ''; ?>>Khác</option>
                </select>
            </div>
            <div class="profile-row">
                <label for="address" class="label">Địa chỉ</label>
                <input type="text" id="address" name="address" class="input" placeholder="Nhập địa chỉ" value="<?php echo $userInfo['address']?>">
            </div>
            <div class="profile-row">
                <button class="submit-button" name="update">Lưu thay đổi</button>
            </div>
        </div>
    </form>
</section>
