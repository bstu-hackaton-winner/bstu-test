const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

function renderError(text, force = false){
    document.getElementById("error_place").innerHTML = `<div class="alert alert-danger" role="alert" style="margin-top: 25px;"><b>Ошибка:</b> ${text}</div>`;
    if(force){
        swal("Ошибка", text, "error");
    }
}


function verify_form(){
    let form = document.forms[0];

    if(form.passwd.value.length < 6){
        renderError("Пароль должен быть не короче 6 символов");
        return;
    }
    if(form.passwd.value !== form.passwd_re.value){
        renderError("Пароли не совпадают");
        return;
    }
    if(form.name.value === 0){
        renderError("Введите ваше имя");
        return;
    }
    if(form.name.value.trim().split(" ").length < 2){
        renderError("Введите имя и фамилию");
        return;
    }
    if(!re.test(form.mail.value)){
        renderError("Введите корректную почту");
        return;
    }

    form.submit();
}

function send_activation(token){
    document.getElementById("send_email").onclick = () => { swal("Информация", "Вы уже запрашивали повторную отправку письма", "info"); };
    document.getElementById("send_email").textContent = "Отправка письма...";
    let req = new XMLHttpRequest();
    req.open("POST", "/activation.php?retoken=" + token);
    req.addEventListener("readystatechange", () => {
        if(req.responseText === "") return;
        document.getElementById("send_email").textContent = "Ошибка";
        if(req.responseText === "OK"){
            document.getElementById("send_email").textContent = "Письмо успешно отправленно";
            swal("Успех", "Письмо успешно отправленно", "success");
        } else {
            renderError("Письмо не может быть отправленно, свяжитесь с администрацией", true)
        }
    });
    req.send();
}