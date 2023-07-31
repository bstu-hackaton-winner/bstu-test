<?php
/**
* @var string $CURRENT_FILE
* @var User $_USER
 */
?>
<nav class="pcoded-navbar" pcoded-header-position="relative">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
		<div class="">
			<div class="main-menu-header">
				<img class="img-40" src="/assets/images/user.png" alt="User-Profile-Image">
				<div class="user-details">
					<span><?=$_USER->get_name()?></span>
					<span id="more-details"><?=$_USER->get_account_type_str();?></span>
				</div>
			</div>
		</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php if($CURRENT_FILE == 'offline_quizzes') echo("active"); ?>">
                <a href="/panel/offline.php">
                    <span class="pcoded-micon"><i class="ti-write"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Тесты</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php if($CURRENT_FILE == 'settings') echo("active"); ?>">
                <a href="/panel/settings.php">
                    <span class="pcoded-micon"><i class="ti-settings"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Настройки</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <?php if($_USER->is_admin()) { ?>
                <li>
                    <br>
                    <a href="/apanel/" style="background: darkred;">
                        <span class="pcoded-micon"><i class="fas fa-user-shield"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.dash.main">Админ панель</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>