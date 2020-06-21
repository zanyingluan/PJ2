let img = document.getElementById('uploadedImg');
let file = document.getElementById("file");
file.addEventListener("change", uploadImg, false);

let title = document.getElementById('title');
let content = document.getElementById('content');
let country = document.getElementById('country');
let city = document.getElementById('city');

let submit = document.getElementById('submit');
submit.addEventListener("click", function (e) {

    let checkCity = true;

    //image验证
    let imgSrc = img.getAttribute('src');
    if (imgSrc === '') file.setCustomValidity('Please upload a photo');
    else file.setCustomValidity('');

    //content
    let index = content.selectedIndex;
    if (index === 0) content.setCustomValidity('Please choose content');
    else content.setCustomValidity('');

    //country验证
    let countryValue = country.value;
    if (countryValue === '') country.setCustomValidity('Please enter country');
    else {
        country.setCustomValidity('');
        //检测数据库中是否存在
        let request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                if (request.responseText !== 'true') {
                    e.preventDefault();
                    checkCity = false;
                    if (request.responseText === 'false') {
                        alert('Unrecognizable country,try again');
                        e.preventDefault();
                    } else {
                        let infos = request.responseText.split('|');
                        let info = 'You may want:';
                        for (let i = 0; i < infos.length; i++) {
                            info += "\n" + infos[i];
                        }
                        alert(info);
                    }
                }
            }
        };
        request.open("GET", "../php/uploadQuery.php?Country=" + countryValue, false);
        request.send();
    }

    //city 不可为空 且数据库中有
    //city 不可为空 且数据库中有
    let cityValue = city.value;
    if (cityValue === '') city.setCustomValidity('Please enter city');
    else {
        city.setCustomValidity('');
        if (checkCity) {
            //检测数据库中是否存在
            let request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    if (request.responseText !== 'true') {
                        e.preventDefault();
                        if (request.responseText === 'false') {
                            alert('Unrecognizable city,try again');
                        } else {
                            let infos = request.responseText.split('|');
                            let info = 'You may want:';
                            for (let i = 0; i < infos.length; i++) {
                                info += "\n" + infos[i];
                            }
                            alert(info);
                        }
                    }
                }
            };
            request.open("GET", "../php/uploadQuery.php?Country="+countryValue+"&City=" + cityValue, false);
            request.send();
        }
    }
}, false);
function uploadImg(e) {
    //input是一个file对象， file对象不能直接展示的
    const input = e.target;
    //console.log(input);
    let photo = input.files[0];
    //console.log(file);
    //用fileReader从file对象读取文件，转成img标签可用格式
    let reader = new FileReader();
    reader.readAsDataURL(photo);
    //因为文件读取是一个耗时操作， 所以它在回调函数中，才能够拿到读取的结果
    reader.onload = function () {
        img.src = reader.result;
        //修改图片与样式#uploadImg
        img.style.display = "block";
        file.innerText = 'Change the photo';
    };
}