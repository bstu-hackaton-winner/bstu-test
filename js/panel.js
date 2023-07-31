function remove_quiz(id){
    swal({
        title: "Вы уверены?",
        text: "Это действие невозможно отменить",
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
                text: "Удалить",
                visible: true,
                value: true,
                closeModal: false
            }
        }
    }).then(result => {
        if(result) {
            $.ajax("/panel/api.php",{
                url: "/panel/api.php",
                method: "POST",
                data: {
                    "token": ctoken,
                    "action": "remove_quiz",
                    "value": id
                },
                success: () => {
                    location.reload();
                },
                fail: () => {
                    swal("Ошибка", "Удалить опрос не получилось, попробуйте позднее", "error").then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}

function start_session(id){
    swal({
        title: "Вы уверены?",
        text: "Вы действительно хотите запустить сессию опроса?",
        icon: "info",
        buttons: {
            cancel: {
                text: "Отмена",
                visible: true,
                value: false,
                closeModal: true
            },
            confirm: {
                text: "Создать",
                visible: true,
                value: true,
                closeModal: false
            }
        }
    }).then(result => {
        if(result) {
            $.ajax("/panel/api.php",{
                url: "/panel/api.php",
                method: "POST",
                data: {
                    "token": ctoken,
                    "action": "start_session",
                    "value": id
                },
                success: (response) => {
                    connect_session(response.session_id, response.leaderToken);
                },
                error: (response) => {
                    swal("Ошибка", response.responseJSON['error'], "error").then(() => {
                        location.reload();
                    })
                }
            });
        }
    });
}

function stop_session(id){
    swal({
        title: "Вы уверены?",
        text: "Вы действительно хотите прервать сессию опроса?",
        icon: "info",
        buttons: {
            cancel: {
                text: "Отмена",
                visible: true,
                value: false,
                closeModal: true
            },
            confirm: {
                text: "Прервать",
                visible: true,
                value: true,
                closeModal: false
            }
        }
    }).then(result => {
        if(result) {
            $.ajax("/panel/api.php",{
                url: "/panel/api.php",
                method: "POST",
                data: {
                    "token": ctoken,
                    "action": "stop_session",
                    "value": id
                },
                success: () => {
                    location.reload();
                },
                error: (response) => {
                    swal("Ошибка", response.responseJSON['error'], "error").then(() => {
                        location.reload();
                    })
                }
            });
        }
    });
}

function connect_session(id, token){
    let form = document.createElement("form");
    let csrf_token_input = document.createElement("input");
    let session_id_input = document.createElement("input");
    let leader_token_input = document.createElement("input");

    csrf_token_input.name = "csrf_token";
    session_id_input.name = "session_id";
    leader_token_input.name = "leader_token";

    csrf_token_input.value = ctoken;
    session_id_input.value = id;
    leader_token_input.value = token;

    form.appendChild(csrf_token_input);
    form.appendChild(session_id_input);
    form.appendChild(leader_token_input);

    form.action = "/board.php";
    form.method = "POST";
    document.body.appendChild(form);
    form.submit();
}

function session_in_progress(){
    swal("Эта сессия еще идет", "Результаты можно будет получить только после завершения", "info");
}

function remove_session(id){
    swal({
        title: "Вы уверены?",
        text: "Это действие невозможно отменить",
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
                text: "Удалить",
                visible: true,
                value: true,
                closeModal: false
            }
        }
    }).then(result => {
        if(result) {
            $.ajax("/panel/api.php",{
                url: "/panel/api.php",
                method: "POST",
                data: {
                    "token": ctoken,
                    "action": "remove_session",
                    "value": id
                },
                success: () => {
                    location.reload();
                },
                fail: () => {
                    swal("Ошибка", "Удалить не получилось, попробуйте позднее", "error").then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}

function quizzes_count_limit(){
    swal({
        title: 'Ограничение',
        text: 'Вы достигли ограничения количества опросов, приобретите подписку',
        icon: 'info',
        buttons: ["Отмена", "Приобрести"]

    }).then(result => {
        if(result) window.location = "/panel/subscription.php";
    });
}

function quiz_locked(){
    swal({
        title: 'Опрос заблокирован',
        text: 'Вы достигли ограничения количества опросов, приобретите подписку для разблокировки',
        icon: 'info',
        buttons: ["Отмена", "Приобрести"]

    }).then(result => {
        if(result) window.location = "/panel/subscription.php";
    });
}

function show_quiz_link($link, $term_link){
    swal({
        title: "Ссылка на опрос",
        text: "Скопируйте ссылку ниже и используйте ее для входа в опрос",
        content: {
            element: "input",
            attributes: {
                value: $link,
                type: "text",
            },
        },
        dangerMode: true,
        buttons: ["Ссылка для терминала", "Закрыть"]
    }).then(result => {
        if(result == null){
            swal({
                title: "Ссылка на опрос",
                text: "Эта ссылка не защищается от накрутки, рекомендуется использовать на общедоступных устройствах",
                content: {
                    element: "input",
                    attributes: {
                        value: $term_link,
                        type: "text",
                    },
                },
                dangerMode: true,
                button: "Закрыть"
            });
        }
    });
}