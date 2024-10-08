

// Handle search 

function updateValue(field, increment) {
    const numberOfBed = document.querySelector(".number_of_bed");
    const input = document.getElementById(field);
    const inputShow = document.getElementById("rooms_count");
    let valueInput = parseInt(input.value, 10);
    if (increment === 1) {
        valueInput = valueInput + 1;
    } else {
        valueInput = Math.max(1, valueInput - 1);
    }

    input.value = valueInput;
    numberOfBed.textContent = valueInput
    inputShow.textContent = valueInput
}

// click filter
document.getElementById("search-btn").addEventListener("click", function() {
    const selectedRadio = document.querySelector(".filter-radio:checked");
    
    if (selectedRadio) {
      const queryString = `destination=${encodeURIComponent(selectedRadio.value.trim())}`;
      window.location.href = `/Booking-hotel/search.php?${queryString}`;
    } else {
      window.location.href = '/Booking-hotel/search.php';
    }
});
