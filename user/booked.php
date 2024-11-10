<section>
    <div class="title">
        <h1>Chuyến đi của bạn</h1>
    </div>
    <?php while ($row_booking = $result_booking->fetch_assoc()) { ?>
        <a href="booking-detail.php?id_booking=<?php echo $row_booking['booking_id']; ?>" class="select-booked" target="_blank">
        <div class="booking-card">
            <div class="booking-info">
                <img src="<?php echo $row_booking['image_name'] ? '../images/hotel-detail/' . htmlspecialchars($row_booking['image_name']) : 'path/to/images/default.jpg'; ?>" alt="Hotel Image" class="hotel-image">
                <div class="details">
                    <div class="hotel-name"><?php echo htmlspecialchars($row_booking['hotel_name']) . " Hotel " . htmlspecialchars($row_booking['city_name']); ?></div>
                    <div class="date"><?php echo htmlspecialchars($row_booking['check_in_date']) . " - " . htmlspecialchars($row_booking['check_out_date']); ?></div>
                    <div class="status"><?php echo htmlspecialchars($row_booking['booking_status'])?></div>
                </div>
            </div>
            <div class="price">
                <div class="hotel-price"><?php echo "VND " . number_format($row_booking['total_price']); ?></div>
            </div>
        </div>
        </a>
    <?php } ?>
</section>

<script>
    function toggleDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById("dropdownMenu");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    document.addEventListener("click", function(event) {
        const dropdown = document.getElementById("dropdownMenu");
        const optionsButton = document.querySelector(".options-button");

        if (!optionsButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });
</script>
