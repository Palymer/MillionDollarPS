<?php
/**
 * @package       mds
 * @copyright     (C) Copyright 2020 Ryan Rhode, All rights reserved.
 * @author        Ryan Rhode, ryan@milliondollarscript.com
 * @version       2020.05.13 12:41:15 EDT
 * @license       This program is free software; you can redistribute it and/or modify
 *        it under the terms of the GNU General Public License as published by
 *        the Free Software Foundation; either version 3 of the License, or
 *        (at your option) any later version.
 *
 *        This program is distributed in the hope that it will be useful,
 *        but WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *        GNU General Public License for more details.
 *
 *        You should have received a copy of the GNU General Public License along
 *        with this program;  If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 *
 *  * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *        Million Dollar Script
 *        A pixel script for selling pixels on your website.
 *
 *        For instructions see README.txt
 *
 *        Visit our website for FAQs, documentation, a list team members,
 *        to post any bugs or feature requests, and a community forum:
 *        https://milliondollarscript.com/
 *
 */

define( 'MAIN_PHP', '1' );

require_once __DIR__ . "/../include/init.php";
require_once 'admin_common.php';

?><!DOCTYPE html>
<html lang="">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Админ панель</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_HTTP_PATH; ?>admin/css/admin.css?ver=<?php echo filemtime( BASE_PATH . "/admin/css/admin.css" ); ?>">
    <script src="<?php echo BASE_HTTP_PATH; ?>vendor/components/jquery/jquery.min.js?ver=<?php echo filemtime( BASE_PATH . "/vendor/components/jquery/jquery.min.js" ); ?>"></script>
    <script src="<?php echo BASE_HTTP_PATH; ?>vendor/components/jqueryui/jquery-ui.min.js?ver=<?php echo filemtime( BASE_PATH . "/vendor/components/jqueryui/jquery-ui.min.js" ); ?>"></script>
    <link rel="stylesheet" href="<?php echo BASE_HTTP_PATH; ?>vendor/components/jqueryui/themes/smoothness/jquery-ui.min.css?ver=<?php echo filemtime( BASE_PATH . "/vendor/components/jqueryui/themes/smoothness/jquery-ui.min.css" ); ?>" type="text/css"/>
    <script src="<?php echo BASE_HTTP_PATH; ?>vendor/jquery-form/form/dist/jquery.form.min.js?ver=<?php echo filemtime( BASE_PATH . "/vendor/jquery-form/form/dist/jquery.form.min.js" ); ?>"></script>
    <script src="<?php echo BASE_HTTP_PATH; ?>admin/js/admin.js?ver=<?php echo filemtime( BASE_PATH . "/admin/js/admin.js" ); ?>"></script>

</head>
<body>
<div class="admin-container">
    <div class="admin-menu">
        <img src="https://milliondollarscript.com/logo.gif" alt="Million Dollar Script logo" style="max-width:100%;"/>
        <br>
        <a href="main.php">Основная информация</a><br/>
        <a href="<?php echo BASE_HTTP_PATH; ?>" target="_blank">Просмотр сайта</a></a><br/>
        <hr>
        <b>Инвентаризация</b><br/>
        + <a href="inventory.php">Управление сетками</a><br/>
        &nbsp;&nbsp;|- <a href="packs.php">Управление сетками</a><br/>
        &nbsp;&nbsp;|- <a href="price.php">Ценовые зоны</a><br/>
        &nbsp;&nbsp;|- <a href="nfs.php">Не для продажи</a><br/>
        &nbsp;&nbsp;|- <a href="blending.php">Фон</a><br/>
        - <a href="gethtml.php">Получить HTML код</a><br/>

        <hr>
        <b>Рекламодатели</b><br/>
        - <a href="customers.php">Список рекламодателей</a><br/>
        <span>Текущие Заказы:</span><br>
        - <a href="orders.php?show=WA">Заказы: Ожидание</a><br/>
        - <a href="orders.php?show=CO">Заказы: Завершенные</a><br/>
        <span>Старые Заказы:</span><br>
        - <a href="orders.php?show=EX">Заказы: Прошедшие</a><br/>
        - <a href="orders.php?show=CA">Заказы: Отмененые</a><br/>
        - <a href="orders.php?show=DE">Заказы: Удаленные</a><br/>
        <span>Map:</span><br>
        - <a href="ordersmap.php">Карта заказов</a><br/>
        <span>Транзакции:</span><br>
        - <a href="transactions.php">История транзакций</a><br/>
        <hr>
        <b>Пиксели</b><br/>
        - <a href="approve.php?app=N">Утвердить пиксели</a><br/>
        - <a href="approve.php?app=Y">Отклонить пиксели</a><br/>
        - <a href="process.php">Пиксели в процессе</a><br/>
        <hr>
        <b>Отчеты</b><br/>
        - <a href="ads.php">Список объявлений</a><br/>
        - <a href="list.php">Лучшие рекламодатели</a><br/>
        - <a href="email_queue.php">Исходящие Email</a><br/>
        <!--
		- <a href="expr.php">Напоминания об истечении срока действия</a><br/>
		-->
        <span>Клики:</span><br>
        - <a href="top.php">Топ кликов</a><br/>
        - <a href="clicks.php">Отчеты по кликам</a><br/>
        <hr>
        <b>Настройки</b><br/>
        - <a href="edit_config.php">Настройки</a><br/>
        - <a href="language.php">Язык<br/>
        - <a href="currency.php">Валюты</a><br/>
        - <a href="payment.php">Модули оплаты</a><br/>
        - <a href="adform.php">Форма объявления</a><br/>
        <hr>
        <b>Выход</b><br/>
        - <a href="logout.php">Выход</a><br/>
        <hr>
        <b>Информация</b><br/>
        - <a href="info.php">Системная информация</a><br/>
        - <a href="https://chwh.ru">Разработчик</a><br/>

        <br/>
        <small>Copyright <?php date( 'Y' ); ?>, see <a href="../LICENSE.txt">LICENSE.txt</a> for license information.<br/>
            <br/>
            MDS дата билда:<br/><?php echo VERSION_INFO; ?></small>
    </div>
    <div class="admin-content"></div>
</div>
</body>
</html>