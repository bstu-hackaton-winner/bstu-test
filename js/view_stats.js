let current_question_id = -1;

const graph_options = {
    pieHole: 0.4,
    pieSliceText: 'label',
    chartArea: {width: "100%", height: "100%"},
    height: "80%",
    width: "100%"
};
const linear_graph_options = {
    legend: { position: "none" }
};

google.load("visualization", "1", {packages:["corechart"]});
google.charts.load('current', {packages: ['corechart', 'bar']});

function render_answers(question_id = -1, iframe = false){
    if(question_id < 0 && current_question_id < 0) return;
    if(question_id < 0) question_id = current_question_id;
    else current_question_id = question_id;

    if(answers.length === 0){
        if(!iframe)
            $("#piechart").html("<h1>Нет ответов</h1>");
        $("#linechart").html("<h1>Нет ответов</h1>");
        return;
    }

    // Отрисовка графика
    let answers_graph_pie = [['Ответ', 'Количество']];
    let answers_graph_linear = [];
    let total_answers_count = 0;
    for(let i = 0; i < questions[question_id]['answers'].length; i++){
        let answers_count = 0;
        answers[question_id].forEach(el => { if(el === i) answers_count++; total_answers_count++; });
        answers_graph_pie.push([questions[question_id]['answers'][i], answers_count]);
        answers_graph_linear.push([questions[question_id]['answers'][i], answers_count]);
    }
    console.log(answers_graph_pie);
    if(!total_answers_count){
        if(!iframe)
            $("#piechart").html("<h1>Нет ответов</h1>");
        $("#linechart").html("<h1>Нет ответов</h1>");
    } else {
        if(!iframe) {
            let graph_data = google.visualization.arrayToDataTable(answers_graph_pie);
            let chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(graph_data, graph_options);
        }

        let data = new google.visualization.DataTable();
        data.addColumn('string', 'Ответы');
        data.addColumn('number', 'Ответы');
        data.addRows(answers_graph_linear);

        let materialChart = new google.charts.Bar(document.getElementById('linechart'));
        materialChart.draw(data, linear_graph_options);
    }
}

function render_answers_offline(question_id = -1, iframe=false){
    if(question_id < 0 && current_question_id < 0) return;
    if(question_id < 0) question_id = current_question_id;
    else current_question_id = question_id;

    if(answers.length === 0){
        $("#linechart").html("<h1>Нет ответов</h1>");
        return;
    }

    $("#linechart").hide();
    $("#answer_table_wrapper").show();
    let rows = [];
    let counter = answers[question_id].length;
    answers[question_id].forEach(el => {
        el = questions[question_id]['is_free'] ? el : questions[question_id]['answers'][el];
        rows.push(`
        <tr>
            <td style="width: 50px">${counter}</td>
            <td>${el}</td>
        </tr>`);
        counter--;
    });
    $("#answer_table_wrapper").html(`
    <table id="answers_table" class="table table-hover long-table" style="width: 100%; height: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Ответ</th>
            </tr>
        </thead>
        <tbody>
            ${rows.join("\n")}
        </tbody>
    </table>
    `);
    if(iframe){
        table = $('#answers_table').DataTable({
            "paging": true,
            "ordering": true,
            "bLengthChange": false,
            "info": false,
            "searching": false,
            "language": {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Russian.json'
            }
        });
    } else {
        table = $('#answers_table').DataTable({
            "paging": true,
            "ordering": true,
            "bLengthChange": true,
            "info": true,
            "searching": true,
            "language": {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Russian.json'
            }
        });
    }

}

function view_iframe(quiz_id, session_id = 0, token){
    swal({
        title: "Код вставки результатов",
        text: "Скопируйте код ниже и используйте ее для встраивания результатов опроса на ваш сайт",
        content: {
            element: "input",
            attributes: {
                value: `<iframe src='//${location.hostname}/iframe.php?stid=${quiz_id}&sid=${session_id}&sqrt=${token}'></iframe>`,
                type: "text",
            },
        },
        dangerMode: true,
        buttons: ["Закрыть"]
    })
}