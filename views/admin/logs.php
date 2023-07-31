<?php
/**
 * @var Quiz $quiz
 * @var array $logs
 * @var string $notify
 * @var mysqli $link
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/bower_components/switchery/css/switchery.min.css">
<link rel="stylesheet" type="text/css" href="/assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/css/quiz_list.css" />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
                            <h5>Логи сайта</h5>
                            <span>Какой то полезный текст</span>
                        </div>
                    </div>
                    <div class="card-block">
                        <?=$notify?>
                        <table id="logs_table" class="table table-hover">
                            <thead>
                            <tr>
                                <th style="width: 30px">#</th>
                                <th>Событие</th>
                                <th style="width: 150px">Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach($logs as $log) { ?>
                                    <tr>
                                        <td><?=$log->get_id();?></td>
                                        <td class="td-warp"><?=$log->get_value();?></td>
                                        <td class="td-warp"><?=$log->get_date();?></td>
                                    </tr>
                                <?php } ?>
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

    <script>
        let table = null;
        $(document).ready(function() {
            // $.noConflict(true);
            $('#logs_table').DataTable({
                "paging": true,
                "ordering": true,
                "bLengthChange": true,
                "info": true,
                "searching": true,
                "order": [[ 0, "desc" ]],
                "language": {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Russian.json'
                }
            });
        } );
    </script>