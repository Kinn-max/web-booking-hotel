$(document).ready(function () {
  $(".sd-CustomSelect").multipleSelect({
    selectAll: false,
    onOptgroupClick: function (view) {
      $(view).parents("label").addClass("selected-optgroup")
    },
  })
})

var modal = document.getElementById("roomDialog")
var btn = document.getElementById("openDialogBtn")
var close = document.getElementsByClassName("close")[0]
var updateButtons = document.querySelectorAll(".btn-update")

btn.onclick = function () {
  document.querySelector('h2[class="title-form"]').innerText = "Add Room"
  document.querySelector("form").reset()
  document.querySelector('input[name="room-id"]').value = ""
  document.querySelector('button[name="save"]').innerText = "ADD"
  modal.style.display = "block"
}

updateButtons.forEach(function (updateButton) {
  updateButton.onclick = function (e) {
    e.preventDefault()

    // Get the row of the selected room
    var roomId = this.getAttribute("href").split("update_id=")[1] // Get the room ID from the URL

    // Fetch the room data via AJAX if you want to get more fields like description, quantity, etc.
    fetch(`/web-booking-hotel/admin/handle/get-room-details.php?id=${roomId}`)
      .then((response) => response.json())
      .then((data) => {
        // Populate the form with the fetched data
        document.querySelector('h2[class="title-form"]').innerText =
          "Update Room"

        document.querySelector('input[name="room-id"]').value = roomId
        document.querySelector('input[name="room-name"]').value = data.name
        document.querySelector('input[name="quantity"]').value =
          data.num_of_people
        document.querySelector('textarea[name="description"]').value =
          data.description
        document.querySelector('input[name="price"]').value = data.price

        document.querySelector('input[name="photo"]').value = data.image

        // Set the hotel dropdown to the correct value
        document.querySelector('select[name="hotel"]').value = data.id_hotel

        // Change the button text to "Update"
        document.querySelector('button[name="save"]').innerText = "Update"

        // Display the modal
        modal.style.display = "block"
      })
      .catch((error) => console.error("Error fetching room details:", error))

    modal.style.display = "block"
  }
})

close.onclick = function () {
  modal.style.display = "none"
}

window.onclick = function (e) {
  if (e.target == modal) {
    modal.style.display = "none"
  }
}
