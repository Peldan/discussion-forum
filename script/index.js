//Skriptet som körs för index.html-sidan. Eftersom det första man ser på index-sidan är kategorier så används här get_categories.php för att hämta ut dem via ett XMLHttpRequest
//vars svar anropar response-funktionen, som lägger till innehåll i tabellen för varje rad i svaret. 
let catrequest = new XMLHttpRequest();
$(document).ready( () => {
  let categoryTable = document.getElementById("categories");
  catrequest.addEventListener("load", response);
  function response() {
    let response = JSON.parse(this.responseText);
    for(let i = 0; i < response.length; i++){
      let newRow = document.createElement("tr");
      let titleCell = document.createElement("td");
      let countCell = document.createElement("td");
      titleCell.innerHTML = '<a href="forums.php?category=' + response[i]['id'] + '">' + response[i]['name'] + '</a>';
      countCell.innerHTML = response[i]['topic_amount'];
      newRow.appendChild(titleCell);
      newRow.appendChild(countCell);
      categoryTable.appendChild(newRow);
    }
  }
  catrequest.open('GET', 'script/get_categories.php');
  catrequest.send();
});
