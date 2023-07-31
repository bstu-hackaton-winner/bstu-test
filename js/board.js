const socket = new WebSocket(`wss://${serverURL}:${socketPort}`);
let clientStatus = 0;
let timer = -1;

const form = $("#main_container");
let buttons = {};

let answers = null;
let questions = null;
const graph_options = {
    pieHole: 0.4,
    pieSliceText: 'label',
    chartArea: {left: 10, top: 10, width: "100%", height: "100%"},
    height: "80%",
    width: "100%"
};
let graph_data = null;

socket.onmessage = event => {
    console.log(event.data);
    let response = JSON.parse(event.data);
    console.log(response);
    if(response['error'] !== undefined){
        swal("Ошибка", response['error'], "error");
    } else {
        switch (response['action']) {
            case "client_leave":
            case "new_client":
                $("#users-count").text(response['total']);
                break;
            case "question":
                render_question(response);
                break;
            case "new_answer":
                if(response['total_users'] === response['total_answers']){
                    action("next"); return;
                }
                let label = $(".users-count");
                label.animate({"font-size": "5vh"}, 50, ()=>{
                    label.text(response['total_answers'])
                    $(".users-label").text("Ответов из " + response['total_users']);
                    label.animate({"font-size": "17vh"}, 100, () => {
                        label.animate({"font-size": "15vh"}, 100)
                    })
                })
                break;
            case "quiz_end":
                socket.onclose = () => {};
                clientStatus = 3;
                break;
            case "answers":
                answers = JSON.parse(response['answers']);
                questions = JSON.parse(response['questions']);
                console.log(answers);
                console.log(questions);
                render_answers(0, questions, answers);
                break;
            default:
                break;
        }
    }
}
socket.onopen = () => {
    socket.send(JSON.stringify({"action": "join", "token": quizToken, "leader": leaderToken}));
    start_btn.disabled = false;
}
socket.onerror = () => {
    if(clientStatus === 0){
        swal("Ошибка", "Невозможно подключиться к сессии", "error").then(() => {
            window.location = "/panel/offline.php";
        });
    } else {
        swal("Ошибка", "Соединение потерянно", "error").then(() => {
            location.reload();
        });
    }
}
socket.onclose = () => {
    swal("Ошибка", "Соединение потерянно", "error").then(() => {
        location.reload();
    });
}

function action(action){
    socket.send(JSON.stringify({"action": action}));
    lock_button($("#start_btn")[0], false);
}

function set_html(innerHTML, callback = null){
    form.animate({"opacity": 0}, () => {
        form.html(innerHTML);
        form.animate({"opacity": 1}, () => {
            if(callback !== null){
                callback();
            }
        });
    });
}
function lock_button(button, status = false){
    let spinner = "<div class=\"spinner-border spinner-border-sm\" role=\"status\">\n" +
                  "  <span class=\"sr-only\">Loading...</span>\n" +
                  "</div>";
    if(status){
        button.disabled = false;
        button.innerHTML = buttons[button.id];
    } else {
        buttons[button.id] = button.innerText;
        button.disabled = true;
        button.innerHTML = spinner;
    }
}

function render_question(question){
    let html = question_template;
    html = html.replaceAll("{{question_id}}", question['question_number']);
    html = html.replaceAll("{{question_time}}", question['question_time']);
    html = html.replaceAll("{{users_count}}", question['users_count']);

    let answers = "";
    const alphabet = "АБВГДЕЖЗ"
    for(let i = 0; i < question['answers'].length; i++){
        answers += "<li>\n" +
            "                <span class=\"letter\">" + alphabet[i] + "</span>\n" +
            "                <span class=\"text\">" + question['answers'][i] + "</span>\n" +
            "            </li>";
    }
    html = html.replaceAll("{{answers}}", answers);
    set_html(html);
    timer = question['question_time'];
    // lock_button(document.getElementById("start_btn"), true);
}
function render_answers(question_id, questions, answers){
    // Отрисовка графика
    let answers_graph = [['Ответ', 'Количество']];
    for(let i = 0; i < questions[question_id]['answers'].length; i++){
        let answers_count = 0;
        answers[question_id].forEach(el => { if(el === i) answers_count++; });
        answers_graph.push([questions[question_id]['answers'][i], answers_count])
    }

    graph_data = google.visualization.arrayToDataTable(answers_graph);

    let template = final_template;
    template = template.replaceAll("{{current_question}}", questions[question_id]['question']);
    let questions_graph = "";
    for(let i = 1; i <= questions.length; i++){
        questions_graph += `<li class='question_button' onclick='render_answers(${i-1}, questions, answers, this);'>\n` +
        `                 <span class=\"letter\">${i}</span>\n` +
        `                 <span class=\"text\">Вопрос ${i}</span>\n` +
        "             </li>";
    }
    template = template.replaceAll("{{questions}}", questions_graph);

    set_html(template, () => {
        let chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(graph_data, graph_options);
    });
}

function tick(){
    if(timer > 0){
        timer--;
        $(".question_timer").text(timer);
        if(timer < 1){
            action("next");
            timer = -1;
        }
    }
}

google.charts.load('current', {'packages':['corechart']});
// google.charts.setOnLoadCallback(drawChart);

$(document).ready(() => {
    setInterval(tick, 1000);
});