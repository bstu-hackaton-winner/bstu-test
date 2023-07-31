<?php
/**
 * @var Quiz $quiz
 * @var Session $session
 */
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iFrame</title>
    <link type="text/css" href="/css/iframe.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/pages/data-table/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/bower_components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="/js/view_stats.js"></script>
    <script src="/js/iframe.js"></script>
</head>
<body>
<div id="results_frame">
    <div id="quiz_name"><?=$quiz->get_name()?></div>
    <div id="quiz_results">
        <div id="linechart" style="width: 100%; height: 100%"></div>
    </div>
    <div id="quiz_questions">
        <select id="question_select" onchange="question_select(this)">
            <?php for($i = 0; $i < count($quiz->get_questions()); $i++) { ?>
                <option value="<?=$i?>"><?=$i+1?>.<?=$quiz->get_questions()[$i]->question?></option>
            <?php } ?>
        </select>
    </div>
</div>
<!-- data-table js -->
<script src="/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/assets/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="/assets/pages/data-table/js/jszip.min.js"></script>
<script src="/assets/pages/data-table/js/pdfmake.min.js"></script>
<script src="/assets/pages/data-table/js/vfs_fonts.js"></script>
<script src="/assets/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="/assets/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="/assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<script>
    const questions = <?=json_encode($session->get_questions())?>;
    const answers = <?=json_encode($session->get_answers())?>;
    $(document).ready(function() {
        try{
            render_answers(0, true);
        } catch (err){
            google.charts.load('current', {'packages':['corechart', 'bar']});
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(first_render);
        }
    });
</script>
</body>
</html>