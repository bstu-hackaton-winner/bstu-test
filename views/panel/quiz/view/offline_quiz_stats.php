<?php
/**
 * @var Quiz $quiz
 * @var array $answers
 * @var string $notify
 * @var mysqli $link
 * @var User $_USER
 * @var string $_TOKEN_IFRAME_
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/bower_components/switchery/css/switchery.min.css">
<link rel="stylesheet" type="text/css" href="/assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/css/quiz_list.css" />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="/js/uagent.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/menu.php"); ?>
    </div>

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="row">
                    <div class="col-md-12 col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <div style="float: left;">
                                    <h5>График ответов</h5>
                                    <span>Опрос «<?=$quiz->get_name()?>»</span>
                                </div>
                            </div>
                            <div class="card-block">
                                <?=$notify?>
                                <div id="linechart_tab" class="tab-pane active" role="tabpanel">
                                    <div id="linechart">
                                        <h1>Выберите вопрос в списке</h1>
                                    </div>
                                    <div id="answer_table_wrapper"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div style="float: left;">
                                    <h5>Список вопросов</h5>
                                </div>
                            </div>
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table class="table long-table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Вопрос</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php for($i = 0; $i < count($quiz->get_questions()); $i++){ ?>
                                            <tr>
                                                <td><?=$i+1?></td>
                                                <td><span onclick="render_answers_offline(<?=$i?>)" class="select-question"><?=$quiz->get_questions()[$i]->question?></span></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <button class="btn btn-info" style="width: 100%" onclick="view_iframe(
                                        <?=$quiz->get_id()?>, 0, `<?=md5($_TOKEN_IFRAME_."_".$quiz->get_id())?>`
                                    );"><i class="fas fa-code"></i> Код вставки</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="float: left;">
                            <h5>Сессии опроса ID<?=$quiz->get_id()?> «<?=$quiz->get_name()?>»</h5>
                            <span>Показаны последние 100 сессий</span>
                        </div>
                    </div>
                    <div class="card-block">
                        <?=$notify?>
                        <table id="simpletable" class="table table-hover">
                            <thead>
                            <tr>
                                <th style="width: 30px">#</th>
                                <th>Дата прохождения</th>
                                <?php if($_USER->get_sub()->get_special_features()) { ?>
                                    <th>Устройство</th>
                                    <th>Браузер</th>
                                <?php } ?>
                                <th style="width: 175px">Отпечаток</th>
                                <th style="width: 100px">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach($answers as $answer) { ?>
                                    <tr id="session_1">
                                        <td><?=$i?></td>
                                        <td class="td-warp"><?=$answer->get_date()?></td>
                                        <?php if($_USER->get_sub()->get_special_features()) { ?>
                                        <td>
                                            <div class="user-agent-cell">
                                                <script>
                                                    fields = document.getElementsByClassName("user-agent-cell");
                                                    fields[fields.length-1].innerHTML = get_device(`<?=$answer->get_agent()?>`);
                                                </script>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-agent-cell">
                                                <script>
                                                    fields = document.getElementsByClassName("user-agent-cell");
                                                    fields[fields.length-1].innerHTML = get_browser(`<?=$answer->get_agent()?>`);
                                                </script>
                                            </div>
                                        </td>
                                        <?php } ?>
                                        <td class="td-warp"><?=$answer->get_sign()?></td>
                                        <td class="action_column">
                                            <button class="btn btn-danger btn-icon" onclick="remove_session(<?=$answer->get_id()?>);">
                                                <i class="icofont icofont-ui-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/assets/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/assets/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="/assets/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="/assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="/assets/bower_components/modernizr/js/modernizr.js"></script>
    <script type="text/javascript" src="/assets/bower_components/modernizr/js/css-scrollbars.js"></script>
    <!-- classie js -->
    <script type="text/javascript" src="/assets/bower_components/classie/js/classie.js"></script>
    <!-- i18next.min.js -->
    <script type="text/javascript" src="/assets/bower_components/i18next/js/i18next.min.js"></script>
    <script type="text/javascript" src="/assets/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
    <script type="text/javascript" src="/assets/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="/assets/bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
    <!-- Custom js -->
    <script src="/assets/pages/data-table/js/data-table-custom.js"></script>
    <script type="text/javascript" src="/assets/js/script.js"></script>
    <script src="/assets/js/pcoded.min.js"></script>
    <script src="/assets/js/demo-12.js"></script>
    <script src="/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/assets/js/jquery.mousewheel.min.js"></script>
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

    <script src="/js/panel.js"></script>
    <script src="/js/view_stats.js"></script>
    <script>
        const questions = <?=json_encode($quiz->get_questions())?>;
        const answers = <?=json_encode($answers_list)?>;
    </script>

    <script>
        // let table = null;
        // $(document).ready(function() {
        //     // $.noConflict(true);
        //     table = $('#simpletable').DataTable({
        //         "paging": true,
        //         "ordering": true,
        //         "bLengthChange": true,
        //         "info": true,
        //         "searching": true,
        //         "language": {
        //             url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Russian.json'
        //         }
        //     });
        // } );
    </script>