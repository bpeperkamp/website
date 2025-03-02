const search = document.getElementById("search");
const button = document.getElementById("search_button");
const category_select = document.getElementById("category_select");
const category_select_form = document.getElementById("category_select_form");
const search_result = document.getElementById("search_result");
const search_result_list = document.getElementById("search_result_list");

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
        if (xhttp.status == 200) {
          var data = JSON.parse(xhttp.response);
          search_result_list.innerHTML = "";
          search_result.classList.remove("invisible");
          search_result.classList.add("visible");
          if (data.result.length > 0) {
            data.result.forEach(function (item) {
              search_result_list.innerHTML +=
                "<li><small><a href='/article/" +
                item.slug +
                "' class='link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-body-emphasis'>" +
                item.title +
                "</a></small></li>";
            });
          } else {
            search_result.classList.remove("visible");
            search_result.classList.add("invisible");
          }
        }
      }
    };
  } else {
    search_result.classList.remove("visible");
    search_result.classList.add("invisible");
    button.disabled = true;
  }
};

search.addEventListener("input", inputHandler);

// If there even is a category select input, listen to it change
if (typeof category_select != "undefined" && category_select != null) {
  category_select.addEventListener("change", function (e) {
    category_select_form.submit();
  });
}
