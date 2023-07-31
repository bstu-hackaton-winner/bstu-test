const quizTitle = header_text.textContent;
let question_number = 0;
let current_question = null;
let answers = [];

function startQuiz(){
    nextQuestion();
}

function leaveSession(){
    swal({
        title: "Вы уверены?",
        text: "Ваши ответы будут безвозвратно утерянны",
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
                text: "Покинуть",
                visible: true,
                value: true,
                closeModal: false
            }
        }
    }).then(result => {
        if (result) {
            window.location = "/index.php";
        }
    });
}

function saveAnswer(){
    if(current_question === null) return true;
    if(current_question['is_free']){
        let answer = document.getElementsByTagName("textarea")[0].value;
        if(answer === ""){
            swal("Внимание", "Вы не написали ответ", "info");
            return false;
        } else {
            answers.push(answer);
        }
    } else {
        let answer_id = -1;
        Array.prototype.slice.call(document.getElementsByName("answer")).forEach(el => {
            if(el.checked) answer_id = parseInt(el.attributes['answer-id'].value);
        });
        if (isNaN(answer_id) || answer_id < 0) {
            swal("Ошибка", "Вы не выбрали вариант ответа", "info");
            return false;
        } else {
            answers.push(answer_id);
        }
    }
    return true;
}

function nextQuestion(){
    if(!saveAnswer()) return;
    if(question_number >= questions.length){
        endQuiz();
        return;
    }
    let question = questions[question_number];
    current_question = question;
    let title = question['question'];
    let answers = '';
    let answer_id = 0;

    if(question['is_free']){
        answers = `
        <textarea rows="10" cols="5" placeholder="Введите ваш ответ (до 500 символов)" maxlength="500"></textarea>
        `;
    } else {
        question['answers'].forEach(el => {
            answers += `
            <input type="radio" class="btn-check answer-button" name="answer" id="answer_${answer_id}" answer-id="${answer_id}"  autocomplete="off">
            <label class="btn btn-outline-success" for="answer_${answer_id}" style="margin-bottom: 10px;">
                ${el}
            </label>`;
            answer_id++;
        });
    }
    let html = `
        <h2>${title}</h2>
        ${answers}
        <div id="error_place"></div>
        <div class="form-group send">
            <button id="join_button" type="button" class="btn button_answer btn-success" onclick="nextQuestion();">Ответить</button>
        </div>`;
    $("#quiz_content").animate({"opacity": 0}, 500, () => {
        header_text.textContent = 'Вопрос №' + question_number;
        quiz_content.innerHTML = html;
        $("#quiz_content").animate({"opacity": 1}, 500);
    });
    question_number++;

}

function endQuiz(){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/q/offline.php", true);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.send(JSON.stringify({
        "quiz": quizID,
        "agent": navigator.userAgent,
        "questions": questions,
        "answers": answers,
        "csrf": document.querySelector('meta[name="csrf_token"]').content,
        "token": quizToken
    }));

    header_text.textContent = quizTitle;
    quiz_content.innerHTML = `
    <i class="fas fa-check-square wait-leader success" aria-hidden="true"></i><br>
    <h1>Опрос завершился</h1>
    <h3>Спасибо за участие</h3>
    <div id="error_place"></div>
    <div class="form-group send">
        <a class="disconnect" href="/"><button type="button" class="btn disconnect-button btn-default">Покинуть опрос</button></a>
    </div>
    `;
}