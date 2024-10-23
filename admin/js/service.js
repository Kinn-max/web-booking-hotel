$(document).ready(function () {
  $("#iconpicker").iconpicker()
})

var modal = document.getElementById("serviceDialog")
var btn = document.getElementById("openDialogBtn")
var close = document.getElementsByClassName("close")[0]
var updateButtons = document.querySelectorAll(".btn-update")

btn.onclick = function () {
  document.querySelector("form").reset()
  document.querySelector('input[name="service-id"]').value = ""
  document.querySelector('button[name="save"]').innerText = "ADD"
  modal.style.display = "block"
}

updateButtons.forEach(function (updateButton) {
  updateButton.onclick = function (e) {
    e.preventDefault()

    // Fetch service data from the table row
    var row = this.closest("tr")
    var serviceId = this.getAttribute("href").split("update_id=")[1] // Get ID from URL
    var serviceName = row.cells[1].innerText // Get service name from the row
    var serviceIconClass = row.cells[2].querySelector("i").className // Get icon class from the row

    // Populate form with service data
    document.querySelector('input[name="service-id"]').value = serviceId
    document.querySelector('input[name="service-name"]').value = serviceName
    document.querySelector('input[name="icon"]').value = serviceIconClass

    // Update button label
    document.querySelector('button[name="save"]').innerText = "Update"

    // Show the modal
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
