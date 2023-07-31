<?php
/**
 * @var array $quizzes
 * @var string $seo_link_base
 * @var string $notify
 * @var mysqli $link
 * @var User $_USER
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/bower_components/switchery/css/switchery.min.css">
<link rel="stylesheet" type="text/css" href="/assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/css/quiz_list.css" />

<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/views/panel/menu.php"); ?>
    </div>

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="card">
                    <div class="card-header">
                        <div style="float: left;">
                            <h5>Мои тесты</h5>
                            <span>Количество тестов не ограничено</span>
                        </div>
                        <form method="POST" action="" class="create-quiz-title">
                            <?php if($_USER->get_sub()->get_max_quizzes() > 0 &&
                                $_USER->get_sub()->get_max_quizzes() <= Quiz::get_user_quizzes_count($link)) { ?>
                                <button class="btn btn-info btn-round disabled" type="button" onclick="quizzes_count_limit()"><i class="ti-plus"></i> Создать</button>
                            <?php } else { ?>
                                <input type="hidden" name="action" value="create_quiz"/>
                                <input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
                                <button class="btn btn-info btn-round" ><i class="ti-plus"></i> Создать</button>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="card-block">
                        <?=$notify?>
                        <div class="card-deck">
                            <div style="flex-wrap: wrap;display: flex;width: 100%;">
                                <?php
                                if(count($quizzes) == 0){ ?>
                                    <div class="no_quizzes">
                                        <i class="fas fa-inbox"></i>
                                        <h1>Нет опросов</h1>
                                    </div>
                                <?php }
                                foreach($quizzes as $quiz) { ?>
                                    <div class="card quiz-card">
                                        <div class="card-header">
                                            <h5 class="card-title"><?=$quiz->get_name()?></h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text"><?=$quiz->get_desc()?></p>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Опрошено: <?=$quiz->get_users_count()?> человек</li>
                                            <li class="list-group-item" style="background-color: #EEE;">
                                                <?php if($quiz->is_locked($link, $_USER->get_sub()->get_max_quizzes())){ ?>
                                                    <div class="actions">
                                                        Опрос заблокирован
                                                        <div class="actions-icons">
                                                            <i onclick="quiz_locked();" data-bs-toggle="tooltip" title="Опрос заблокирован" style="color: #122D52;margin-right: 7px;" class="fas fa-lock"></i>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="actions">
                                                        Действия:
                                                        <div class="actions-icons">
                                                            <i onclick="show_quiz_link(`<?=$seo_link_base.$quiz->get_seo_link()?>`, `<?=$seo_link_base.$quiz->get_terminal_link()?>`);" data-bs-toggle="tooltip" title="Ссылка на опрос" style="color: #122D52;" class="fas fa-link"></i>
                                                            <a href="?edit=<?=$quiz->get_id()?>"><i data-bs-toggle="tooltip" title="Изменить" style="color: #3498DB;" class="fas fa-edit"></i></a>
                                                            <a href="?stats=<?=$quiz->get_id()?>"><i data-bs-toggle="tooltip" title="Ответы" style="color: #2ecc71;" class="fas fa-chart-pie"></i></a>
                                                            <i onclick="remove_quiz(<?=$quiz->get_id()?>);" data-bs-toggle="tooltip" title="Удалить" style="color: #e74c3c;" class="far fa-trash-alt"></i>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <form method="POST" action="" class="create-quiz-bottom">
                            <?php if($_USER->get_sub()->get_max_quizzes() > 0 &&
                                $_USER->get_sub()->get_max_quizzes() >= Quiz::get_user_quizzes_count($link)) { ?>
                                <button class="btn btn-info btn-round disabled" type="button" onclick="quizzes_count_limit()"><i class="ti-plus"></i> Создать</button>
                            <?php } else { ?>
                                <input type="hidden" name="action" value="create_quiz"/>
                                <input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
                                <button class="btn btn-info btn-round" ><i class="ti-plus"></i> Создать</button>
                            <?php } ?>
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

    <script>
        [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).forEach(el => $(el).tooltip());
    </script>