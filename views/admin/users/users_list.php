<?php
/**
 * @var Quiz $quiz
 * @var array $users
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
                            <h5>Список пользователей сайта</h5>
                            <span>Показаны только организаторы, а не участники</span>
                        </div>
                    </div>
                    <div class="card-block">
                        <?=$notify?>
                        <table id="users_table" class="table table-hover">
                            <thead>
                            <tr>
                                <th style="width: 30px">ID</th>
                                <th>Имя</th>
                                <th>Почта</th>
                                <th>Телефон</th>
                                <th>Тип аккаунта</th>
                                <th>Статус</th>
                                <th style="width: 100px">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach($users as $user) { ?>
                                    <tr id="session_1">
                                        <td><?=$user->get_id()?></td>
                                        <td><?=$user->get_name()?></td>
                                        <td><?=$user->get_mail()?></td>
                                        <td><?=$user->get_phone()?></td>
                                        <?php if($user->is_admin()) { ?>
                                            <td style="color: red">Админ</td>
                                        <?php } else { ?>
                                            <td><?=$user->get_account_type_str();?></td>
                                        <?php } ?>
                                        <td><?=
                                            [
                                                "<span style='color: red'>Заблокирован</span>",
                                                "<span style='color: green'>Активен</span>",
                                                "<span style='color: goldenrod'>Активация</span>"
                                            ][$user->get_account_status()]
                                        ?></td>
                                        <td class="action_column">
                                            <a href="?edit=<?=$user->get_id()?>"><button data-bs-toggle="tooltip" title="Редактировать" class="btn btn-warning btn-icon">
                                                <i class="icofont icofont-edit"></i>
                                            </button></a>
                                            <a href="offline.php?user=<?=$user->get_id()?>"><button data-bs-toggle="tooltip" title="Тесты" class="btn btn-info btn-icon">
                                                <i class="icofont icofont-computer"></i>
                                            </button></a>
                                            <button onclick="remove_user(<?=$user->get_id()?>);" data-bs-toggle="tooltip" title="Удалить" class="btn btn-danger btn-icon">
                                                <i class="far fa-trash-alt"></i>
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
    <!-- classie js -->
    <script type="text/javascript" src="/assets/bower_components/classie/js/classie.js"></script>
    <!-- NVD3 chart -->
    <script src="/assets/bower_components/d3/js/d3.js"></script>
    <script src="/assets/bower_components/nvd3/js/nv.d3.js"></script>
    <script src="/assets/pages/chart/nv-chart/js/stream_layers.js"></script>
    <!-- echart js -->
    <script src="/assets/pages/chart/echarts/js/echarts-all.js" type="text/javascript"></script>
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

    <script src="/js/apanel/users.js"></script>

    <script>
        [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).forEach(el => $(el).tooltip());
        let table = null;
        $(document).ready(function() {
            // $.noConflict(true);
            $('#users_table').DataTable({
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