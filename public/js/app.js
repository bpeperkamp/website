const search = document.getElementById("search");
const button = document.getElementById("search_button");

var xhttp = new XMLHttpRequest();

const inputHandler = function (e) {
  if (e.target.value.length >= 3) {
    var value = { data: e.target.value };
    button.disabled = false;
    // Request the data from the search route
    xhttp.open("POST", "/search", true);
    xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(JSON.stringify(value));
    xhttp.onreadystatechange = function () {
      if (xhttp.readyState == 4) {
        console.log(xhttp.response);
      }
    };
  } else {
    console.log("stop searching");
    button.disabled = true;
  }
};

search.addEventListener("input", inputHandler);
