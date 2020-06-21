function email_check() {
    let email_input = document.getElementById("email").value;
    let email_check_div = document.getElementById("email-check");

    //邮箱格式的正则判断
    let regex1 = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/
    if(regex1.test(email_input)){
        email_check_div.innerHTML = "<h4>邮箱格式正确！</h4>";
        email_check_div.style.color = "green";

    }else{
        email_check_div.innerHTML = "<h4>*请输入符合格式的邮箱!</h4>"
        email_check_div.style.color = "red";
    }

}

function username_check() {
    let username_input = document.getElementById("username").value;
    let username_check_div = document.getElementById("username-check");

    //用户名格式的正则判断
    let regex2 = /^[a-zA-Z0-9_-]{4,16}$/
    if(regex2.test(username_input)){
        username_check_div.innerHTML = "<h4>用户名格式正确！</h4>"
        username_check_div.style.color = "green";
    }else{
        username_check_div.innerHTML = "<h4>请输入符合格式的用户名!</h4>"
        username_check_div.style.color = "red";
    }

}

function password1_check() {
    let password1_input = document.getElementById("password1").value;
    let password1_check_div = document.getElementById("password1-check");

    //密码格式的正则判断
    let regex3 = /^[0-9A-Za-z]{8,16}$/
    if(regex3.test(password1_input)){
        password1_check_div.innerHTML = "<h4>密码格式正确！</h4>";
        password1_check_div.style.color = "green";

    }else{
        password1_check_div.innerHTML = "<h4>*请输入符合格式的密码!</h4>"
        password1_check_div.style.color = "red";
    }

}

function password2_check(){
    let password1 = document.getElementById("password1").value;
    let password2 = document.getElementById("password2").value;
    let password2_check_div = document.getElementById("password2-check");
    //判断两次密码是否相等
    if(password2 === ""){
        password2_check_div.innerHTML = "<h4>*请输入密码</h4>"
        password2_check_div.style.color = "red";
    } else if(password1 !== password2){
        password2_check_div.innerHTML = "<h4>*两次密码不相同！</h4>"
        password2_check_div.style.color = "red";

    }else{
        password2_check_div.innerHTML = "<h4>两次密码相同！</h4>"
        password2_check_div.style.color = "green";
    }
}

function submit_check() {
    let password1 = document.getElementById("password1").value;
    let password2 = document.getElementById("password2").value;
    if(password1 !== password2){
        return false;
    }else{
        return true;
    }
}