function validateForm(inputID, labelID) {
  if (document.getElementById(inputID).value == "") {
    document.getElementById(labelID).innerText = "Student ID is required";
    return false;
  } else {
    document.getElementById(labelID).innerText = "";
    return true;
  }
}
