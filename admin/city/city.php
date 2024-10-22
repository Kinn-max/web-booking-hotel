<div class="city-management-container">
    <?php include ('create.php');?>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } elseif (isset($success_message)) {?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php } ?>

    <?php include ('read.php'); ?>
</div>