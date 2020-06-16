
var cities = new Array(3);
cities[0] = new Array("Shanghai","Kunming","Beijing","Yantai");
cities[1] = new Array("Tokyo","Oksaka","Kamakura","Oktawa");
cities[2] = new Array("Roma","Milan","Venice","Florence");
cities[3] = new Array("New York","San Francisco","Washington","Hawaii");

function changeCity(val){

    //7.获取第二个下拉列表
    var cityEle = document.getElementById("city");

    //9.清空第二个下拉列表的option内容
    cityEle.options.length=0;

    //2.遍历二维数组中的省份
    for(var i=0;i<cities.length;i++){
        //注意，比较的是角标
        if(val==i){
            //3.遍历用户选择的省份下的城市
            for(var j=0;j<cities[i].length;j++){
                //alert(cities[i][j]);
                //4.创建城市的文本节点
                var textNode = document.createTextNode(cities[i][j]);
                //5.创建option元素节点
                var opEle = document.createElement("option");
                //6.将城市的文本节点添加到option元素节点
                opEle.appendChild(textNode);
                //8.将option元素节点添加到第二个下拉列表中去
                cityEle.appendChild(opEle);
            }
        }
    }
}
