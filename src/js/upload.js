
function uploadImg(file,imgNum){
    var widthImg = 500; //显示图片的width
    var heightImg = 300; //显示图片的height
    var div = document.getElementById(imgNum);
    if (file.files && file.files[0]){
        div.innerHTML ='<img id="upImg">'; //生成图片
        var img = document.getElementById('upImg'); //获得用户上传的图片节点
        img.onload = function(){
            img.width = widthImg;
            img.height = heightImg;
        }
        var reader = new FileReader(); //判断图片是否加载完毕
        reader.onload = function(evt){
            if(reader.readyState === 2){ //加载完毕后赋值
                img.src = evt.target.result;
            }
        }
        reader.readAsDataURL(file.files[0]);
    }
}