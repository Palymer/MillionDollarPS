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

ini_set( 'max_execution_time', 500 );
require_once __DIR__ . "/../include/init.php";
require( 'admin_common.php' );

if ( $_REQUEST['process'] == '1' ) {

	if ( ( $_REQUEST['banner_list'][0] ) == 'all' ) {
		// process all
		$sql = "select * from banners ";
		$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) . $sql );
		while ( $row = mysqli_fetch_array( $result ) ) {
			$BID = $row['banner_id'];
			echo process_image( $row['banner_id'] );
			publish_image( $row['banner_id'] );
			process_map( $row['banner_id'] );
		}
	} else {
		// process selected

		foreach ( $_REQUEST['banner_list'] as $key => $banner_id ) {
			# Banner ID.
			$BID = $banner_id;

			echo process_image( $banner_id );
			publish_image( $banner_id );
			process_map( $banner_id );
		}
	}

	echo "<br>Завершено.<hr>";
}

// Process images

if ( ! is_writable( SERVER_PATH_TO_ADMIN . "temp/" ) ) {
	echo "<b>Ошибка:</b> Скрипт не имеет разрешения на запись в " . SERVER_PATH_TO_ADMIN . "admin/temp/ или каталог не существует <br>";
}
$BANNER_PATH = BASE_PATH . "/" . get_banner_dir();
if ( ! is_writable( $BANNER_PATH ) ) {
	echo "<b>Ошибка:</b> Скрипт не имеет разрешения на запись в " . $BANNER_PATH . " или каталог не существует<br>";
}

$sql = "SELECT * FROM orders where approved='N' and status='completed' ";
$r = mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) );
$result = mysqli_fetch_array( $r );
$c      = mysqli_num_rows( $r );

if ( $c > 0 ) {
	echo "<h3>Примечание: There are/is $c пиксельные объявления, ожидающие утверждения. <a href='approve.php'>Одобрить пиксельную рекламу здесь.</a></h3>";
}

?>
<p>
    Здесь вы можете обрабатывать изображения. Здесь сценарий получает все разрешенные пользователем пиксели и объединяет их в одно изображение. Он автоматически публикует окончательную сетку в <?php echo $BANNER_PATH; ?> каталог, из которого подаются изображения сетки. Нажмите кнопку ниже после утверждения пикселей.
</p>
<form method='post' action='<?php echo BASE_HTTP_PATH; ?>admin/process.php'>
    <input value='1' name="process" type="hidden"/>
    <select name="banner_list[]" multiple size='3'>
        <option value="all" selected>Обработать все</option>
		<?php

		$sql = "Select * from banners";
		$res = mysqli_query( $GLOBALS['connection'], $sql );

		while ( $row = mysqli_fetch_array( $res ) ) {
			echo '<option value="' . $row['banner_id'] . '">#' . $row['banner_id'] . ' - ' . $row["name"] . '</option>' . "\n";
		}
		?>
    </select><br/>
    <input type="submit" name='submit' value="Обработать сетки"/>
</form>

