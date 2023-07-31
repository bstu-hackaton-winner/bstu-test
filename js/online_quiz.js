const socket = new WebSocket(`wss://${serverURL}:${socketPort}`);
const quizTitle = header_text.textContent;
let clientStatus = 0; 
// 0 - не подключен, 1 - ожидание лидера, 2 - ожидание ответа, 3 - ожидание вопроса, 4 - заверешен

function set_cookie(name, value, expires, path, domain, secure){
    let cookie_string = name + "=" + escape(value);
    cookie_string += "; expires=" + expires.toGMTString();
    if(path) cookie_string += "; path=" + escape (path);
    if(domain) cookie_string += "; domain=" + escape (domain);
    if(secure) cookie_string += "; secure";
    document.cookie = cookie_string;
}

function delete_cookie(cookie_name){
    const cookie_date = new Date();  // Текущая дата и время
    cookie_date.setTime(cookie_date.getTime() - 1);
    document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}

function get_cookie(cookie_name){
    const results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');
    if (results)
        return (unescape(results[2]));
    else
        return null;
}

socket.onmessage = function(event){
    console.log(event);
    let response = JSON.parse(event.data);
    if(response['error'] !== undefined){
        let message = response['error'];
        error_place.innerHTML = `<div class="alert alert-danger" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
            ${message}
         </div>`;
    } else {
        if(response['result'] === "OK"){
            switch(clientStatus){
                case 0:
                    // Кука переподключения
                    let reconnect_json = {};
                    reconnect_json['quiz_id'] = quizID;
                    reconnect_json['uuid'] = response['uuid'];
                    reconnect_json['token'] = response['token'];
                    reconnect_json = window.btoa(JSON.stringify(reconnect_json));
                    let expires = new Date();
                    expires.setHours(expires.getHours() + 1)
                    set_cookie("current_quiz", reconnect_json, expires);

                    clientStatus = 1;
                    quiz_content.innerHTML = `
                    <div class="form-group">
                        <i class="fas fa-user-clock wait-leader" aria-hidden="true"></i><h1>Ожидание организатора</h1>
                    </div>
                    <div id="error_place"></div>
                    <div class="form-group send">
                        <a href="#" class="action-link" onclick="leaveSession();">Покинуть опрос</a>
                    </div>`;
                    break;
                default:
                    error_place.innerHTML =
                    `<div class="alert alert-success" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg></svg>
                        Успешно
                     </div>`;
                    break;
            }
        }
        else if(response['action'] !== undefined){
            switch(response['action']){
                case 'started':
                    if(clientStatus === 1){
                        clientStatus = 3;
                        // quiz_content.innerHTML = "Опрос начался";
                    }
                    break;
                case "question":
                    let title = response['text'];
                    let answers = '';
                    let answer_id = 0;
                    response['answers'].forEach(el => {
                        answers += `
                        <input type="radio" class="btn-check answer-button" name="options-outlined" id="answer_${answer_id}" autocomplete="off">
                        <label class="btn btn-outline-success" for="answer_${answer_id}" answer-id="${answer_id}" style="margin-bottom: 10px;" onclick="sendAnswer(this);">
                            ${el}
                        </label>`;
                        answer_id++;
                    });
                    let html = `
                    <h2>${title}</h2>
                    ${answers}
                    <div id="error_place"></div>
                    <div class="form-group send">
                        <a href="#" class="action-link" onclick="leaveSession();">Покинуть опрос</a>
                    </div>`;
                    $("#quiz_content").animate({"opacity": 0}, 500, () => {
                        header_text.textContent = 'Вопрос №' + response['question_number'];
                        quiz_content.innerHTML = html;
                        $("#quiz_content").animate({"opacity": 1}, 500);
                    });
                    clientStatus = 2;
                    break;
                case "quiz_end":
                    socket.onclose = function(){
                        error_place.html =
                        `<div class="alert alert-primary d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                            Сессия закончилась, соединение с сервером закрыто
                        </div>`;
                    };
                    delete_cookie("current_quiz");
                    header_text.textContent = quizTitle;
                    quiz_content.innerHTML = `
                    <i class="fas fa-check-square wait-leader success" aria-hidden="true"></i><h1>Опрос завершился</h1>
                    <div id="error_place"></div>
                    <div class="form-group send">
                        <a class="disconnect" href="/"><button type="button" class="btn disconnect-button btn-default">Покинуть опрос</button></a>
                    </div>
                    `;
                    break;
                default:
                    swal("Неизвестное событие", event.data, "info");
                    break;
            }
        } else {
            swal("Неизвестное событие", event.data, "info");
        }
    }
}

socket.onopen = function(){
    join_button.disabled = false;
    socket.onclose = function(){
        swal("Подключение потеряно", "Подключение к сессии опроса разорвано, обновите страницу для переподключения", "error").then(() => {
            window.location = "";
        });
    }
}

function joinQuiz(){
    socket.send(JSON.stringify({'action': 'join', 'token': quizToken}));
}

function joinSession(){
    let json = JSON.parse(window.atob(get_cookie('current_quiz')));
    socket.send(JSON.stringify({'action': 'rejoin', 'uuid': json['uuid'], 'reconnect_token': json['token'], 'token': quizToken}));
}

function leaveSession(){
    swal({
        title: "Вы уверены?",
        text: "Ваши ответы не будут удалены, но переподключиться будет нельзя",
        icon: "warning",
        dangerMode: true,
        buttons: {
            cancel: {
                text: "Отмена",
                visible: true,
                value: false,
                closeModal: true
            },
            confirm: {
                text: "Отключится",
                visible: true,
                value: true,
                closeModal: false
            }
        }
    }).then(result => {
        if (result) {
            let json = JSON.parse(window.atob(get_cookie('current_quiz')));
            socket.send(JSON.stringify({'action': 'leave', 'uuid': json['uuid'], 'reconnect_token': json['token'], 'token': quizToken}));
            delete_cookie("current_quiz");
            window.location = "/index.php";
        }
    });
}

function sendAnswer(el){
    console.log(el);
    let answer_id = parseInt(el.attributes['answer-id'].value);
    let answer_input = el.attributes['for'].value;
    document.getElementById(answer_input).checked = true;
    if(isNaN(answer_id) || answer_id < 0){
        swal("Ошибка", "Произошла внутренняя ошибка, перезайдите в опрос", "error");
    } else {
        socket.send(JSON.stringify({"action": "answer", "answer_id": answer_id}));
        Array.prototype.slice.call(document.getElementsByClassName("answer-button")).forEach(el => {
            el.disabled = true;
        });
        clientStatus = 3;
    }
}