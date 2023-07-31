<?php
/**
 * @var string $notify
 * @var User $_USER
 */
?>
<link rel="stylesheet" type="text/css" href="/assets/bower_components/switchery/css/switchery.min.css">

<style>
    .action_column{
        text-align: center !important;
    }
    .action_column button{
        font-size: 16px;
    }
    .action_column i{
        padding-left: 5px;
    }
    .td-warp{
        white-space: normal;
    }
    td{
        vertical-align: middle !important;
    }

    .border-checkbox {
        display: none;
    }
    .border-checkbox-section .border-checkbox-group .border-checkbox-label::after {
        content: "";
        display: block;
        width: 5px;
        height: 11px;
        opacity: .9;
        border-right: 2px solid #eee;
        border-top: 2px solid #eee;
        position: absolute;
        left: 5px;
        top: 11px;
        -webkit-transform: scaleX(-1) rotate(135deg);
        transform: scaleX(-1) rotate(135deg);
        -webkit-transform-origin: left top;
        transform-origin: left top;
    }
    .border-checkbox-section .border-checkbox-group .border-checkbox-label::before {
        content: "";
        display: block;
        border: 2px solid #1abc9c;
        width: 20px;
        height: 20px;
        position: absolute;
        left: 0
    }
    .element-margin{
        margin-top: 5px;
    }
</style>

<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <?php include($_SERVER['DOCUMENT_ROOT']."/views/panel/menu.php"); ?>
    </div>

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="card">
                    <div class="card-header">
                        <h5>Настройки аккаунта</h5>
                        <span>Не забудьте сохранить изменения</span>
                    </div>
                    <div class="card-block">
                        <?=$notify?>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="change_profile"/>
                            <input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
                            <h4 class="sub-title">Настойка профиля</h4>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Имя и фамилия</label>
                                <div class="col-sm-10">
                                    <input name="profile_name" type="text" class="form-control" minlength="3" maxlength="50" value="<?=$_USER->get_name()?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Телефон</label>
                                <div class="col-sm-10">
                                    <input name="profile_phone" type="tel" class="form-control" minlength="11" maxlength="20" value="<?=$_USER->get_phone()?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Сохранить профиль</button><br><br>
                        </form>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="change_passwd"/>
                            <input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
                            <h4 class="sub-title">Смена пароля</h4>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Старый пароль</label>
                                <div class="col-sm-10">
                                    <input name="old_pass" type="password" class="form-control" minlength="1" maxlength="50" autofocus required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Новый пароль</label>
                                <div class="col-sm-10">
                                    <input name="new_pass" type="password" class="form-control" minlength="1" maxlength="50" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Повтор пароля</label>
                                <div class="col-sm-10">
                                    <input name="new_pass_repeat" type="password" class="form-control" minlength="1" maxlength="50" required>
                                </div>
                            </div>

                            <button type="button" onclick="check_passwd();" class="btn btn-success">Сменить пароль</button>
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

    <script src="/js/settings.js"></script>