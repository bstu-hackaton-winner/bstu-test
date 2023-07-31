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
					<span id="more-details">Администратор</span>
				</div>
			</div>
		</div>
<!--        <ul class="pcoded-item pcoded-left-item">-->
<!--            <li class="--><?php //if($CURRENT_FILE == 'index') echo("active"); ?><!--">-->
<!--                <a href="/apanel/">-->
<!--                    <span class="pcoded-micon"><i class="fa fa-home"></i></span>-->
<!--                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Главная страница</span>-->
<!--                    <span class="pcoded-mcaret"></span>-->
<!--                </a>-->
<!--            </li>-->
<!--        </ul>-->
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php if($CURRENT_FILE == 'users') echo("active"); ?>">
                <a href="/apanel/users.php">
                    <span class="pcoded-micon"><i class="fa fa-users"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Пользователи</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation" menu-title-theme="theme5">Система</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="<?php if($CURRENT_FILE == 'media') echo("active"); ?>">
                <a href="/apanel/media.php">
                    <span class="pcoded-micon"><i class="fa fa-file-image-o"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Медиафайлы</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php if($CURRENT_FILE == 'logs') echo("active"); ?>">
                <a href="/apanel/logs.php">
                    <span class="pcoded-micon"><i class="fa fa-file-text"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Логи</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="<?php if($CURRENT_FILE == 'settings') echo("active"); ?>">
                <a href="/apanel/settings.php">
                    <span class="pcoded-micon"><i class="fa fa-gear"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Настройки</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>