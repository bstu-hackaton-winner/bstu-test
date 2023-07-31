<div>
    <h1>Вопрос №{{question_id}}</h1>
</div>
<div class="link">
    <h2>Осталось <span style="color:red" class="question_timer">{{question_time}}</span> секунд на ответ</h2>
</div>
<div class="table-container">
    <div class="answers">
        <ul>
            {{answers}}
            <!--<li>-->
            <!--    <span class="letter">Г</span>-->
            <!--    <span class="text">Ответ 4</span>-->
            <!--</li>-->
        </ul>
    </div>
    <div class="question_actions">
        <div class="users">
            <div class="info-block">
                <span class="users-count">0</span>
                <span class="users-label">Ответов из {{users_count}}</span>
            </div>
        </div>
        <div class="btn-block"><button class="btn btn-info" id="start_btn" onclick="action('next');">Далее</button></div>
    </div>
</div>