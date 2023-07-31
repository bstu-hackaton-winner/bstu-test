<?php
/**
 * @var string $notify
 * @var User $_USER
 * @var array $options
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
        <?php include($_SERVER['DOCUMENT_ROOT']."/views/admin/menu.php"); ?>
    </div>

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="card">
                    <div class="card-header">
                        <h5>Параметры сайта</h5>
                        <span>Не забудьте сохранить изменения</span>
                    </div>
                    <div class="card-block">
                        <?php echo($notify); ?>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="edit"/>
                            <input type="hidden" name="token" value="<?=$_SESSION['token']?>"/>
                            <?php foreach(array_keys($options) as $option_key){ $option = $options[$option_key]; ?>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?=$option['display_name']?><br><small><?=$option['name']?></small></label>
                                    <div class="col-sm-8">
                                        <input name="<?=$option_key?>" type="text" value="<?=$option['value']?>" class="form-control" placeholder="value" min="0" max="1000000">
                                        <input type="hidden" name="options[]" value="<?=$option_key?>"/>
                                    </div>
                                </div>
                            <?php } ?>
                            <button type="submit" class="btn btn-success">Отправить</button>
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