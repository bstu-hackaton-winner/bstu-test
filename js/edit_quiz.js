function createQuestion(){
    let question_id = document.getElementById("questions_list").childElementCount;
    if(question_id >= maxQuestionCount) {
        swal({
            title: "Ограничение",
            text: "Вы достигли максимального количества вопросов, приобретите подписку для расширения возможностей",
            icon: "info",
            buttons: ["Отмена", "Приобрести"]
        }).then(result => {
            if (result) {
                swal({
                    title: "Вы уверены?",
                    text: "Вы потеряете несохраненые изменения",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ["Отмена", "Продолжить"]
                }).then(result => {
                    console.log(result);
                    if (result) window.location = "/panel/subscription.php";
                })
            }
        });
        return;
    }
    let html = `
    <div class="card" id="question_${question_id}">
        <div class="card-header">
            <h5>Вопрос ${question_id+1}</h5>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Вопрос</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control question_name" name="questions[]" maxlength="100" value="Вопрос номер ${question_id+1}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Время на ответ</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control question_time" name="questions_time[]" value="30" max="600" min="5">
                </div>
            </div>
            <div class="answers_list">
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10 action_buttons">
                    <button class="btn btn-info btn-block" type="button" onclick="createAnswer(${question_id});"><i class="ti-plus"></i> Добавить ответ</button>
                    <button class="btn btn-danger" onclick="removeQuestion(${question_id});"><i class="icofont icofont-ui-delete"></i></button>
                </div>
            </div>
        </div>
    </div>`;
    document.getElementById("questions_list").innerHTML += html;
}

function createAnswer(question_id){
    let answers_list_el = document.getElementById(`question_${question_id}`)
        .getElementsByClassName("answers_list")[0];
    let answer_id = answers_list_el.childElementCount;
    if(answer_id >= maxAnswersCount){
        swal({
            title: "Ограничение",
            text: "Вы достигли максимального количества ответов, приобретите подписку для расширения возможностей",
            icon: "info",
            buttons: ["Отмена", "Приобрести"]
        }).then(result => {
            if(result){
                swal({
                    title: "Вы уверены?",
                    text: "Вы потеряете несохраненые изменения",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ["Отмена", "Продолжить"]
                }).then(result => {
                    if(result) window.loaction = "/panel/subscription.php";
                })
            }
        })
        return;
    }
    $('input').attr('value', function() {
        return $(this).val();
    });
    let html = `
    <div class="form-group row" id="answer_${answer_id}_${question_id}">
        <label class="col-sm-2 col-form-label">${answer_id === 0 ? "Ответы" : ""}</label>
        <div class="col-sm-10 action_buttons">
            <input type="text" maxlength="100" class="form-control answer_text" name="answers_${question_id}[]" value="Ответ ${answer_id+1}">
            <button class="btn btn-danger" type="button" onclick="removeAnswer(${question_id}, ${answer_id});"><i class="icofont icofont-ui-delete"></i></button>
        </div>
    </div>`;
    answers_list_el.innerHTML += html;
}

function removeQuestion(question_id){
    let questions_list_el = document.getElementById("questions_list");
    let questions = Array.prototype.slice.call(questions_list_el.children);
    let html = '';
    let offset = 0;
    for(let i = 0; i < questions.length; i++){
        if(i !== question_id){
            html += `
            <div class="card" id="question_${i-offset}">
                <div class="card-header">
                    <h5>Вопрос ${i+1-offset}</h5>
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Вопрос</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control question_name" name="questions[]" value="${
                                questions[i].getElementsByClassName("question_name")[0].value
                            }" maxlength="100" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Время на ответ</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control question_time" name="questions_time[]" value="${
                                questions[i].getElementsByClassName("question_time")[0].value
                            }" max="600" min="5">
                        </div>
                    </div>
                    <div class="answers_list">
                    ${removeAnswer(parseInt(questions[i].id.split("_")[1]), -1, i-offset)}
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10 action_buttons">
                            <button class="btn btn-info btn-block" type="button" onclick="createAnswer(${i-offset});"><i class="ti-plus"></i> Добавить ответ</button>
                            <button class="btn btn-danger" type="button" onclick="removeQuestion(${i-offset});"><i class="icofont icofont-ui-delete"></i></button>
                        </div>
                    </div>
                </div>
            </div>`;
        } else {
            offset++;
        }
    }
    questions_list_el.innerHTML = html;
}

function removeAnswer(question_id, answer_id, answer_question_id = -1){
    let answers_list_el = document.getElementById(`question_${question_id}`)
        .getElementsByClassName("answers_list")[0];
    if(answer_question_id < 0) answer_question_id = question_id;
    let answers = Array.prototype.slice.call(answers_list_el.children);
    let html = '';
    let offset = 0;
    for(let i = 0; i < answers.length; i++){
        if(i !== answer_id){
            html += `
            <div class="form-group row" id="answer_${i-offset}_${answer_question_id}">
                <label class="col-sm-2 col-form-label">${i-offset === 0 ? "Ответы" : ""}</label>
                <div class="col-sm-10 action_buttons">
                    <input type="text" class="form-control answer_text" name="answers_${answer_question_id}[]" value="${answers[i].getElementsByClassName("answer_text")[0].value}">
                    <button class="btn btn-danger" type="button" onclick="removeAnswer(${answer_question_id}, ${i-offset});"><i class="icofont icofont-ui-delete"></i></button>
                </div>
            </div>`;
        } else {
            offset++;
        }
    }
    if(answer_id === -1) return html;
    answers_list_el.innerHTML = html;
}