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

require_once __DIR__ . "/../include/init.php";
require( 'admin_common.php' );

$BID = $f2->bid();
?>

    <p>
        Пакеты: Здесь вы можете добавить различные комбинации цена / срок действия / максимальное количество заказов в ваши сетки, называемые «Пакеты». Пакеты, добавленные в сетку, перезаписывают настройки по умолчанию для цены, срока действия и максимальных заказов сетки. После выбора пикселей в сетке пользователь будет выбирать, какой пакет он хочет. После того, как пакет выбран, скрипт рассчитает окончательную цену заказа.
        <i> Осторожно: пакеты игнорируют ценовые зоны, т. е. если в сетке есть пакеты, то ценовые зоны будут игнорироваться для этой сетки. </i> </p>
    <hr>
<?php
$sql = "Select * from banners ";
$res = mysqli_query( $GLOBALS['connection'], $sql );
?>

    <form name="bidselect" method="post" action="packs.php">

        Выберите сетку: <select name="BID" onchange="mds_submit(this)">
            <option></option>
			<?php
			while ( $row = mysqli_fetch_array( $res ) ) {

				if ( ( $row['banner_id'] == $BID ) && ( $BID != 'all' ) ) {
					$sel = 'selected';
				} else {
					$sel = '';
				}
				echo '<option ' . $sel . ' value=' . $row['banner_id'] . '>' . $row['name'] . '</option>';
			}
			?>
        </select>
    </form>
<?php

if ( $BID != '' ) {
	$banner_data = load_banner_constants( $BID );
	?>
    <hr>

    <b>Ид сетки:</b> <?php echo $BID; ?><br>
    <b>Имя сетки</b>: <?php echo $banner_data['G_NAME']; ?><br>
    <b>Стандартная цена за 100:</b> <?php echo $banner_data['G_PRICE']; ?><br>

    <input type="button" style="background-color:#66FF33" value="New Package..." onclick="mds_load_page('packs.php?new=1&BID=<?php echo $BID; ?>', true)"><br>

    Список строк, которые помечены как пользовательские цены.<br>

	<?php

	function validate_input() {

		$error = "";
		if ( trim( $_REQUEST['price'] ) == '' ) {
			$error .= "<b>- Цена не указана</b><br>";
		} else if ( ! is_numeric( $_REQUEST['price'] ) ) {
			$error .= "<b>- Цена должна быть числом.</b><br>";
		}

		if ( trim( $_REQUEST['description'] ) == '' ) {
			$error .= "<b>- Описание пусто</b><br>";
		}

		if ( trim( $_REQUEST['currency'] ) == '' ) {
			$error .= "<b>- Валюта пуста</b><br>";
		}

		if ( trim( $_REQUEST['max_orders'] ) == '' ) {
			$error .= "<b>- Макс заказов пуст</b><br>";
		} else if ( ! is_numeric( $_REQUEST['max_orders'] ) ) {
			$error .= "<b>- Максимальное количество заказов должно быть числом</b><br>";
		}

		if ( trim( $_REQUEST['days_expire'] ) == '' ) {
			$error .= "<b>- Дни, которые истекают, пустые</b><br>";
		} else if ( ! is_numeric( $_REQUEST['days_expire'] ) ) {
			$error .= "<b>- Дни до истечения должны быть числом.</b><br>";
		}

		return $error;
	}

	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'delete' ) {

		$sql    = "SELECT * FROM orders where package_id='" . intval( $_REQUEST['package_id'] ) . "'";
		$result = mysqli_query( $GLOBALS['connection'], $sql );
		if ( ( mysqli_num_rows( $result ) > 0 ) && ( $_REQUEST['really'] == '' ) ) {
			echo "<font color='red'>Невозможно удалить пакет: этот пакет является частью другого заказа</font> (<a href='packs.php?BID=$BID&package_id=" . $_REQUEST['package_id'] . "&action=delete&really=yes'>Нажмите здесь, чтобы удалить в любом случае</a>)";
		} else {

			$sql = "DELETE FROM packages WHERE package_id='" . intval( $_REQUEST['package_id'] ) . "' ";
			mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );
		}
	}

	function set_to_default( $package_id ) {

		global $BID;

		$sql = "SELECT * FROM packages where is_default='Y' and banner_id=" . intval( $BID );
		$result = mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );
		$row         = mysqli_fetch_array( $result );
		$old_default = $row['package_id'];

		$sql = "UPDATE packages SET is_default='N' WHERE banner_id=" . intval( $BID );

		mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );
		$sql = "UPDATE packages SET is_default='Y' WHERE package_id='" . intval( $package_id ) . "' AND banner_id=" . intval( $BID );
		mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );

		if ( $old_default == '' ) {

			// update previous orders which are blank, to the default.
			// in the 1.7.0 database, all orders must have packages

			$sql = "UPDATE orders SET package_id=" . intval( $package_id ) . " WHERE package_id=0 AND banner_id=" . intval( $BID );
			mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );
		}
	}

	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'default' ) {
		set_to_default( $_REQUEST['package_id'] );
	}

	if ( isset( $_REQUEST['submit'] ) && $_REQUEST['submit'] != '' ) {

		$error = validate_input();

		if ( $error != '' ) {

			echo "<p>";
			echo "<font color='red'>Ошибка: невозможно сохранить из-за следующих ошибок:</font><br>";
			echo $error;
			echo "</p>";
		} else {

			// calculate block id..

			$_REQUEST['block_id_from'] = ( $_REQUEST['row_from'] - 1 ) * $banner_data['G_WIDTH'];
			$_REQUEST['block_id_to']   = ( ( ( $_REQUEST['row_to'] ) * $banner_data['G_HEIGHT'] ) - 1 );

			$sql = "REPLACE INTO packages(package_id, banner_id, price, currency, days_expire,  max_orders, description, is_default) VALUES ('" . intval( $_REQUEST['package_id'] ) . "', '" . intval( $BID ) . "', '" . floatval( $_REQUEST['price'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['currency'] ) . "', '" . intval( $_REQUEST['days_expire'] ) . "',  '" . intval( $_REQUEST['max_orders'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['description'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['is_default'] ) . "')";

			//echo $sql;

			mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );

			$_REQUEST['new']    = '';
			$_REQUEST['action'] = '';
			//print_r ($_REQUEST);

			// if no default package exists, set the last inserted banner to default

			if ( ! get_default_package( $BID ) ) {
				set_to_default( mysqli_insert_id( $GLOBALS['connection'] ) );
			}
		}
	}

	?>

	<?php

	$result = mysqli_query( $GLOBALS['connection'], "select * FROM packages  where banner_id=" . intval( $BID ) ) or die ( mysqli_error( $GLOBALS['connection'] ) );

	if ( mysqli_num_rows( $result ) > 0 ) {
		?>

        <table width="800" cellSpacing="1" cellPadding="3" bgColor="#d9d9d9" border="0">
            <tr>
                <td><b><font face="Arial" size="2">ИД пакета</font></b></td>
                <td><b><font face="Arial" size="2">Описание</font></b></td>
                <td><b><font face="Arial" size="2">Дней</font></b></td>
                <td><b><font face="Arial" size="2">Цена</font></b></td>
                <td><b><font face="Arial" size="2">Валюта</font></b></td>
                <td><b><font face="Arial" size="2">Макс заказов</font></b></td>
                <td><b><font face="Arial" size="2">Стандартно</font></b></td>
                <td><b><font face="Arial" size="2">Действие</font></b></td>
            </tr>
			<?php
			while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
				?>

                <tr bgcolor="#ffffff">

                    <td><font face="Arial" size="2"><?php echo $row['package_id']; ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['description']; ?></font></td>
                    <td><font face="Arial" size="2"><?php if ( $row['days_expire'] == 0 ) {
								echo 'unlimited';
							} else {
								echo $row['days_expire'];
							} ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['price']; ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['currency']; ?></font></td>
                    <td><font face="Arial" size="2"><?php if ( $row['max_orders'] == 0 ) {
								echo 'unlimited';
							} else {
								echo $row['max_orders'];
							} ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['is_default']; ?></font></td>

                    <td nowrap><font face="Arial" size="2"><a href="packs.php?package_id=<?php echo $row['package_id']; ?>&BID=<?php echo $BID; ?>&action=edit">Редактировать</a> <?php if ( $row['is_default'] != 'Y' ) { ?>| <a href="<?php echo $_SERVER['PHP_SELF']; ?>?package_id=<?php echo $row['package_id']; ?>&BID=<?php echo $BID; ?>&action=default">Установить стандартом</a><?php } ?> |
                            <a href="packs.php?package_id=<?php echo $row['package_id']; ?>&BID=<?php echo $BID; ?>&action=delete" onclick="return confirmLink(this, 'Delete, are you sure?');">Удалить</a></font></td>

                </tr>

				<?php
			}
			?>
        </table>

		<?php
	} else {
		echo "Для этой сетки нет пакетов.<br>";
	}

	?>

	<?php

	if ( isset( $_REQUEST['new'] ) && $_REQUEST['new'] == '1' ) {
		echo "<h4>Новый пакет:</h4>";
	}
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
		echo "<h4>Редактировать пакет:</h4>";

		$sql = "SELECT * FROM packages WHERE `package_id`='" . intval( $_REQUEST['package_id'] ) . "' ";
		$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
		$row = mysqli_fetch_array( $result );

		if ( $error == '' ) {
			$_REQUEST['BID']         = $row['banner_id'];
			$_REQUEST['package_id']  = $row['package_id'];
			$_REQUEST['days_expire'] = $row['days_expire'];
			$_REQUEST['price']       = $row['price'];
			$_REQUEST['currency']    = $row['currency'];
			$_REQUEST['price_id']    = $row['price_id'];
			$_REQUEST['description'] = $row['description'];
			$_REQUEST['max_orders']  = $row['max_orders'];
			$_REQUEST['is_default']  = $row['is_default'];
		}
	}

	if ( ( isset( $_REQUEST['new'] ) && $_REQUEST['new'] != '' ) || ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) ) {

		?>
        <form action='packs.php' method="post">
            <input type="hidden" value="<?php echo $row['package_id'] ?>" name="package_id">
            <input type="hidden" value="<?php echo $_REQUEST['new'] ?>" name="new">
            <input type="hidden" value="<?php echo $_REQUEST['action'] ?>" name="action">
            <input type="hidden" value="<?php echo $_REQUEST['is_default'] ?>" name="is_default">
            <input type="hidden" value="<?php echo $BID; ?>" name="BID">
            <table border="0" cellSpacing="1" cellPadding="3" bgColor="#d9d9d9">

                <tr bgcolor="#ffffff">
                    <td><font size="2">Имя:</font></td>
                    <td><input size="15" type="text" name="description" value="<?php echo $_REQUEST['description']; ?>">Введите описательное имя для пакета. Например, «30 долларов за 100 дней».</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Цена за блок:</font></td>
                    <td><input size="5" type="text" name="price" value="<?php echo $_REQUEST['price']; ?>">Цена за блок (<?php echo( $banner_data['BLK_WIDTH'] * $banner_data['BLK_HEIGHT'] ); ?> пиксели). Десятичные.</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Валюта:</font></td>
                    <td><select size="1" name="currency"><?php currency_option_list( $_REQUEST['currency'] ); ?>Цена валюты</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Дней до конца:</font></td>
                    <td><input size="5" type="text" name="days_expire" value="<?php echo $_REQUEST['days_expire']; ?>">Сколько дней? (Введите 0, чтобы использовать сетку по умолчанию)</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Максимум заказов:</font></td>
                    <td><input size="5" type="text" name="max_orders" value="<?php echo $_REQUEST['max_orders']; ?>">Сколько раз можно заказать этот пакет? (Введите 0 для неограниченного)</td>
                </tr>

            </table>
            <input type="submit" name="submit" value="Подтвердить">
        </form>

		<?php
	}
}
