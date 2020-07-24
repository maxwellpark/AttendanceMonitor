// script for opening and closing the modal window

let modal = document.getElementById("modal-container");

window.onclick = (event) => {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

document.addEventListener("onkeyup", (event) => {
  if (event.key == "Escape") {
    modal.style.display = "none";
  }
});

document.addEventListener("onkeyup", (event) => {
  if (event.keyCode == 27) {
    document.getElementsById("modal-container").style.display = "none";
  }
});
