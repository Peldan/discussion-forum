$(document).ready(()=>{
    //Skript för vänstra kolumnen som använder sig av XMLHttpRequest för att hämta väderdata från SMHIs API. APIt tar lat-lon-koordinater, varför dessa hämtas
    //från webbläsaren och sparas i en kaka. Vidare tar APIt maximalt emot lan-lon-koordinater med 6 decimal-precision, så de rundas till 6 decimaler.
    //dbRequest togs bort visuellt pga inte intressant nog, men är fungerande kod för att hämta ut antalet Registrerade användare.
    let dbRequest = new XMLHttpRequest();
    let weatherRequest = new XMLHttpRequest();
    dbRequest.addEventListener('load', dbResponse);
    weatherRequest.addEventListener('load', weatherResponse);
    let table = document.getElementById("infotable");
    getLocation();
    function dbResponse(){
/*        let dbInfoRow = document.createElement("tr");
        let response = JSON.parse(this.responseText);
        let textCell = document.createElement("td");
        let dataCell = document.createElement("td");
        textCell.innerHTML = "Registrerade användare: ";
        dataCell.innerHTML = response['users'];
        dbInfoRow.appendChild(textCell);
        dbInfoRow.appendChild(dataCell);
        table.appendChild(dbInfoRow);*/
    }
    function getLocation(){
        if(getCookie() === undefined){
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(getWeather);
            }
        } else {
            getWeather(getCookie());
        }
    }


    function getWeather(pos){ //Hämtar väderdata från SMHI. Använder getCookie-metoden för att avgöra huruvida platsdata redan finns sparad i kakorna hos användaren.
        let lat, lon;
        if(getCookie() === undefined){
            lat = pos.coords.latitude.toFixed(6);
            lon = pos.coords.longitude.toFixed(6);
            setLocationCookie(lat, lon);
        } else {
            lat = parseFloat(pos.lat).toFixed(6);
            lon = parseFloat(pos.lon).toFixed(6);
        }
        weatherRequest.open('GET', 'https://opendata-download-metfcst.smhi.se/api/category/pmp3g/version/2/geotype/point/lon/' + lon +'/lat/' + lat +'/data.json');
        weatherRequest.send();
    }

    function setLocationCookie(lat, lon){
        //sparar ner användarens platsdata som cookie, lever i 2h.
        let date = new Date();
        date.setTime(date.getTime() + (2 * 60 * 60 * 1000)); //2 timmar i millisekunder
        let cookieObj = {lat: lat, lon: lon };
        document.cookie = "location" + "=" + JSON.stringify(cookieObj) + "; expires="+date.toUTCString() + "; path=/";
    }

    function getCookie(){
        //delar upp kakan i delar och gör om den relevanta delen till ett JavaScript-objekt
        let parts = document.cookie.split(";");
        for(let i = 0; i< parts.length; i++){
            if(parts[i].includes("location")){
                return JSON.parse(parts[i].split("=")[1]);
            }
        }
    }

    function weatherResponse(){
      //Anropas när väder-requesten svarar. Använder datat för att bygga upp en tabell med snyggt presenterad data. Längst ner
      //finns en if-sats för att göra om SMHIs interna kategorisering (1-6) till naturligt språk.
        let response = JSON.parse(this.responseText);
        //let titleRow = document.createElement("tr");
       // let textCell = document.createElement("td");
       // let dataCell = document.createElement("td");
        //titleRow.appendChild(textCell);
        //titleRow.appendChild(dataCell);
        //table.appendChild(titleRow);
        for(let i = 0; i < 3; i++){
            let row = document.createElement("tr");
            let textCell = document.createElement("td");
            textCell.setAttribute("class", "tableTextCell");
            let dataCell = document.createElement("td");
            dataCell.setAttribute("class", "tableDataCell");
            if(i === 0){
                textCell.innerHTML = "Temp";
                dataCell.innerHTML = response['timeSeries'][0]['parameters'][11]['values'][0] + "°C";
            }
            else if(i == 1){
                textCell.innerHTML = "Vind";
                dataCell.innerHTML = response['timeSeries'][0]['parameters'][14]['values'][0] + "m/s";
            }
            else if(i == 2){
                textCell.innerHTML = "Nederbörd";
                let category = response['timeSeries'][0]['parameters'][1]['values'][0];
                if (category === 0){
                    category = "Ingen";
                }
                else if (category === 1){
                    category = "Snö";
                }
                else if (category === 2){
                    category = "Snö & regn";
                }
                else if(category == 3){
                    category = "Regn";
                }
                else if(category == 4){
                    category = "Duggregn";
                }
                else if(category == 5 || category == 6){
                    category = "Hagel";
                }
                dataCell.innerHTML = category;
            }
            row.appendChild(textCell);
            row.appendChild(dataCell);
            table.appendChild(row);
        }
    }

    dbRequest.open('GET', 'script/populate_sidebar.php');
    dbRequest.send();
});
