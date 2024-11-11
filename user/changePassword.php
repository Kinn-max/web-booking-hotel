<section id="change-password">
    <div class="title">
        <h1>Đổi mật khẩu</h1>

        <div class="message">
            <?php if (isset($_SESSION['success_message'])): ?>
                <span class="message success" id="message"><?php echo $_SESSION['success_message']; ?></span>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <span class="message error" id="message"><?php echo $_SESSION['error_message']; ?></span>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['check_message'])): ?>
                <span class="message error"><?php echo $_SESSION['check_message']; ?></span>
            <?php unset($_SESSION['check_message']); ?>
            <?php endif; ?>
        </div>
    </div>

    <form class="change-password-form" action="user.php?id=<?php echo $this_id ?>&&page=change-password" method="POST">
        <div class="password-info">
            <div class="password-row">
                <label for="current_password" class="label">Mật khẩu hiện tại</label>
                <input type="password" id="current_password" name="current_password" class="input" onblur="checkPassword()" required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('current_password', this)"></i>
                <div id="error-password" class="error"></div>
            </div>

            <div class="password-row">
                <label for="new_password" class="label">Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" class="input" onblur="validatePassword()" required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('new_password', this)"></i>
            </div>
            <div id="error-message" class="error"></div>

            <div class="password-row">
                <label for="confirm_password" class="label">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirm_password" name="confirm_password" class="input" onblur="validateConfirmPassword()" required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
            </div>
            <div id="errorMessage" class="error"></div>

            <div class="password-row">
                <button type="submit" class="submit-button" name="change_password">Xác nhận</button>
            </div>
        </div>
    </form>
</section>

<style>
    .password-row {
        position: relative;
    }
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>

<script>
    function togglePassword(fieldId, icon) {
        var passwordField = document.getElementById(fieldId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    function validatePassword() {
        var password = document.getElementById("new_password").value;
        var errorMessage = document.getElementById("error-message");

        var error = "";
        if (!/[A-Z]/.test(password)) {
            error = "Mật khẩu phải bao gồm chữ cái viết hoa, ký tự đặc biệt, số, ít nhất 8 ký tự";
        } else if (!/[a-z]/.test(password)) {
            error = "Mật khẩu phải bao gồm chữ cái viết hoa, ký tự đặc biệt, số, ít nhất 8 ký tự";
        } else if (!/[0-9]/.test(password)) {
            error = "Mật khẩu phải bao gồm chữ cái viết hoa, ký tự đặc biệt, số, ít nhất 8 ký tự";
        } else if (!/[\W_]/.test(password)) {
            error = "Mật khẩu phải bao gồm chữ cái viết hoa, ký tự đặc biệt, số, ít nhất 8 ký tự";
        } else if (password.length < 8) {
            error = "Mật khẩu phải bao gồm chữ cái viết hoa, ký tự đặc biệt, số, ít nhất 8 ký tự";
        }

        if (error) {
            errorMessage.innerHTML = error;
            errorMessage.style.display = "block";
            return false;
        } else {
            errorMessage.style.display = "none";
            return true;
        }
    }

    function validateConfirmPassword() {
        var password = document.getElementById("new_password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        var confirmPasswordErrorMessage = document.getElementById("errorMessage");

        if (password !== confirmPassword) {
            confirmPasswordErrorMessage.innerHTML = "Mật khẩu nhập lại không khớp!";
            confirmPasswordErrorMessage.style.display = "block";
        } else {
            confirmPasswordErrorMessage.style.display = "none";
        }
    }
</script>
