
function linkage() {
    let countryMenu = document.getElementById("countryMenu");
    let cityMenu = document.getElementById("cityMenu");
    let index = countryMenu.selectedIndex;
    let country = countryMenu.options[index].value;
    cityMenu.length = 1;
    if(index !== 0){
        let request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if(request.readyState === 4 && request.status ===200){
                let info = request.responseText.split("|");
                for(let i = 0 ;i < info.length;i++){
                    if(info[i]!== 'null'){
                        let infos = info[i].split('&')
                        let cityCode = infos[0];
                        let cityName = infos[1];
                        cityMenu[cityMenu.length] = new Option(cityName,cityCode);
                    }
                }
            }
        };
        request.open("GET", "../php/linkedFilter.php?country=" + country, true);
        request.send();
    }
}

function changeValue() {
    let contentValue = document.getElementById("contentMenu").value;
    let cityValue = document.getElementById("cityMenu").value;
    let countryValue = document.getElementById("countryMenu").value;
    let filter = document.getElementById("filter");
    let link = "Browser.php?page=1" + "&Content=" + contentValue + "&Country=" +countryValue+ "&City="+ cityValue +"&filter=filter";
    filter.setAttribute("href",link);
}


