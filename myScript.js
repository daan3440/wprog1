function showMenu() {
  var x = document.getElementById("topNav");
  if (x.className === "nav") {
    x.className += " responsive";
  } else {
    x.className = "nav";
  }
}


// function getArtTitle() {
//   var x = document.getElementById("arttitle").value;
//   document.getElementById("artfoot").innerHTML = x;
// }
