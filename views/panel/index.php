<?php
/**
 * @var array $_SETTINGS
 * @var array $graph
 */
?>
<link rel="stylesheet" type="text/css" href="/assets/bower_components/switchery/css/switchery.min.css">
<link rel="stylesheet" type="text/css" href="/assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css" />
<link rel="stylesheet" href="/assets/bower_components/nvd3/css/nv.d3.css" type="text/css" media="all">

<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <?php include($_SERVER['DOCUMENT_ROOT']."/views/panel/menu.php"); ?>
    </div>

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-header">
                        <div class="page-header-title">
                            <h4>Панель управления</h4>
                        </div>
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                                <li class="breadcrumb-item">
                                    <a href="/">
                                        <i class="icofont icofont-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="/"><?php echo($_SETTINGS['site_name_option']); ?></a>
                                </li>
                                <li class="breadcrumb-item"><a href="/admin/">Панель</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="page-body">
                        <div class="row">
                            <?php var_dump($_SESSION); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>const graphData = <?=$graph?>;</script>
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