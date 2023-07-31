<?php
/**
 * @var array $quizzes
 * @var Quiz $quiz
 * @var string $notify
 * @var mysqli $link
 * @var User $current_user
 * @var bool $allow_edit
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/bower_components/switchery/css/switchery.min.css">
<link rel="stylesheet" type="text/css" href="/assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css" />

<style>
    .notify a{
        color: white;
        text-decoration: none;
    }
    .action_buttons{
        display: flex;
    }
    .swal-text{ text-align: center; }
</style>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/views/admin/menu.php"); ?>
    </div>

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="card">
                    <div class="card-header">
                        <div style="float: left;">
                            <h5>Редактирование опроса «<?=$quiz->get_name()?>»</h5>
                            <span>Опрос ID<?=$quiz->get_id()?>. Владелец: <?=$current_user->get_name()?> (ID<?=$current_user->get_id()?>)</span>
                        </div>
                    </div>
                    <div class="card-block">
                        <?=$notify?>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="edit"/>
                            <input type="hidden" name="quiz_id" value="<?=$quiz->get_id()?>"/>
                            <input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Название опроса</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="quiz_name" maxlength="50" value="<?=$quiz->get_name()?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Описание опроса</label>
                                <div class="col-sm-10">
                                    <textarea rows="5" cols="5" class="form-control" maxlength="500" name="quiz_desc"><?=$quiz->get_desc()?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">SEO ссылка</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="quiz_link" maxlength="15" value="<?=$quiz->get_seo_link()?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Дата отключения</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="quiz_disable_date" value="<?=$quiz->get_deactivation_date()?>">
                                </div>
                                 в
                                <div class="col-sm-3">
                                    <input type="time" class="form-control" name="quiz_disable_time" value="<?=$quiz->get_deactivation_time()?>">
                                </div>
                            </div>
                            <div class="border-checkbox-section">
                                <div class="border-checkbox-group border-checkbox-group-primary">
                                    <input class="border-checkbox" type="checkbox" id="active_checkbox" name="quiz_active" <?=$quiz->is_active() ? "checked" : ""?>>
                                    <label class="border-checkbox-label" for="active_checkbox">Активен (доступен для прохождения)</label>
                                </div>
                            </div>
                            <br>
                            <h4 class="sub-title">Вопросы</h4>
                            <button class="btn btn-info btn-block" type="button" onclick="createQuestion();"><i class="ti-plus"></i> Добавить вопрос</button>
                            <br>
                            <div id="questions_list">
                            <?php
                                $questions = $quiz->get_questions();
                                for($i = 0; $i < count($questions); $i++){
                            ?>
                                <div class="card" id="question_<?=$i?>">
                                    <div class="card-header">
                                        <h5>Вопрос <?=$i+1?></h5>
                                    </div>
                                    <div class="card-block">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Вопрос</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control question_name" name="questions[]" maxlength="100" value="<?=$questions[$i]->question?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Время на ответ</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control question_time" name="questions_time[]" value="<?=$questions[$i]->time?>" max="600" min="5">
                                            </div>
                                        </div>
                                        <div class="border-checkbox-section">
                                            <div class="border-checkbox-group border-checkbox-group-primary">
                                                <input class="border-checkbox free_type" type="checkbox" id="free_input_<?=$i?>" name="free_input[]" value="<?=$i?>" <?=$questions[$i]->is_free ? "checked" : ""?>>
                                                <label class="border-checkbox-label" for="free_input_<?=$i?>">Свободный ввод текста (ответы будут недоступны)</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="answers_list">
                                            <?php for($k = 0; $k < count($questions[$i]->answers); $k++){ ?>
                                            <div class="form-group row" id="answer_<?=$k?>_<?=$i?>">
                                                <label class="col-sm-2 col-form-label"><?=$k == 0 ? "Ответы" : ""?></label>
                                                <div class="col-sm-10 action_buttons">
                                                    <input type="text" maxlength="100" class="form-control answer_text" name="answers_<?=$i?>[]" value="<?=$questions[$i]->answers[$k]?>">
                                                    <button class="btn btn-danger" type="button" onclick="removeAnswer(<?=$i?>, <?=$k?>);"><i class="icofont icofont-ui-delete"></i></button>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10 action_buttons">
                                                <button class="btn btn-info btn-block" type="button" onclick="createAnswer(<?=$i?>);"><i class="ti-plus"></i> Добавить ответ</button>
                                                <button class="btn btn-danger" type="button" onclick="removeQuestion(<?=$i?>);"><i class="icofont icofont-ui-delete"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                            <button class="btn btn-success btn-block" ><i class="ti-save"></i> Сохранить</button>
                            <br>
                            <a href="/apanel/offline.php?user=<?=$current_user->get_id()?>"><button class="btn btn-info btn-block" type="button"><i class="ti-arrow-left"></i> Назад</button></a>
                        </form>
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
<script src="/js/edit_quiz_offline.js"></script>

<script>
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).forEach(el => $(el).tooltip());
    const maxQuestionCount = <?=$current_user->get_sub()->get_max_questions()?>;
    const maxAnswersCount = <?=$current_user->get_sub()->get_max_answers()?>;
    const allowEdit = <?=$allow_edit ? "true" : "false"?>;
</script>