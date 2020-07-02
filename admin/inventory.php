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

if ( isset( $_REQUEST['reset_image'] ) && $_REQUEST['reset_image'] != '' ) {

	$default = get_default_image( $_REQUEST['reset_image'] );

	$sql = "UPDATE banners SET `" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['reset_image'] ) . "`='" . mysqli_real_escape_string( $GLOBALS['connection'], $default ) . "' WHERE banner_id='" . $BID . "' ";

	mysqli_query( $GLOBALS['connection'], $sql );
}

function display_reset_link( $BID, $image_name ) {

	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
		?>
        <a onclick="return confirmLink(this, 'Сбросить это изображение по умолчанию, вы уверены?');" href='inventory.php?action=edit&BID=<?php echo $BID; ?>&reset_image=<?php echo $image_name; ?>'><font color='red'>x</font></a>
		<?php
	}
}

function is_allowed_grid_file( $image_name ) {

	$ALLOWED_EXT = 'png';
	$parts       = explode( '.', $_FILES[ $image_name ]['name'] );
	$ext         = strtolower( array_pop( $parts ) );
	$ext_list    = preg_split( "/[\s,]+/i", ( $ALLOWED_EXT ) );
	if ( ! in_array( $ext, $ext_list ) ) {
		return false;
	} else {
		return true;
	}
}

function validate_input() {

	$error = "";

	if ( isset( $_REQUEST['name'] ) && $_REQUEST['name'] == '' ) {
		$error .= "- Имя сетки не заполнено<br>";
	}

	if ( isset( $_REQUEST['grid_width'] ) && $_REQUEST['grid_width'] == '' ) {
		$error .= "- Ширина сетки не заполнена<br>";
	}

	if ( isset( $_REQUEST['grid_height'] ) && $_REQUEST['grid_height'] == '' ) {
		$error .= "- Высота сетки не заполнена<br>";
	}

	if ( isset( $_REQUEST['days_expire'] ) && $_REQUEST['days_expire'] == '' ) {
		$error .= "- Дни истечения не заполнены<br>";
	}

	if ( isset( $_REQUEST['max_orders'] ) && $_REQUEST['max_orders'] == '' ) {
		$error .= "- Максимальное количество заказов на одного клиента не заполнено<br>";
	}

	if ( isset( $_REQUEST['price_per_block'] ) && $_REQUEST['price_per_block'] == '' ) {
		$error .= "- Цена за блок не заполнена<br>";
	}

	if ( isset( $_REQUEST['currency'] ) && $_REQUEST['currency'] == '' ) {
		$error .= "- Валюта не заполнена<br>";
	}

	if ( isset( $_REQUEST['block_width'] ) && ! is_numeric( $_REQUEST['block_width'] ) ) {
		$error .= "- Ширина блока недействительна<br>";
	}

	if ( isset( $_REQUEST['block_height'] ) && ! is_numeric( $_REQUEST['block_height'] ) ) {
		$error .= "- Высота блока недействительна<br>";
	}

	if ( isset( $_REQUEST['max_blocks'] ) && ! is_numeric( $_REQUEST['max_blocks'] ) ) {
		$error .= "- Максимальное количество блоков недействительно<br>";
	}

	if ( isset( $_REQUEST['min_blocks'] ) && ! is_numeric( $_REQUEST['min_blocks'] ) ) {
		$error .= "- Минимум блоков не действует<br>";
	}

	if ( isset( $_FILES['grid_block'] ) && $_FILES['grid_block']['tmp_name'] != '' ) {
		if ( ! is_allowed_grid_file( 'grid_block' ) ) {
			$error .= "- Блок сетки должен быть действительным файлом PNG.<br>";
		}
	}

	if ( isset( $_FILES['nfs_block'] ) && $_FILES['nfs_block']['tmp_name'] != '' ) {
		if ( ! is_allowed_grid_file( 'nfs_block' ) ) {
			$error .= "- Не для продажи Блок должен быть действительным файлом PNG.<br>";
		}
	}

	if ( isset( $_FILES['usr_grid_block'] ) && $_FILES['usr_grid_block']['tmp_name'] != '' ) {
		if ( ! is_allowed_grid_file( 'usr_grid_block' ) ) {
			$error .= "- Не для продажи Блок должен быть действительным файлом PNG.<br>";
		}
	}

	if ( isset( $_FILES['usr_nfs_block'] ) && $_FILES['usr_nfs_block']['tmp_name'] != '' ) {
		if ( ! is_allowed_grid_file( 'usr_nfs_block' ) ) {
			$error .= "- Блок пользователя Не для продажи должен быть в формате PNG.<br>";
		}
	}

	if ( isset( $_FILES['usr_ord_block'] ) && $_FILES['usr_ord_block']['tmp_name'] != '' ) {
		if ( ! is_allowed_grid_file( 'usr_ord_block' ) ) {
			$error .= "- Заказанный блок пользователя должен быть действительным файлом PNG.<br>";
		}
	}

	if ( isset( $_FILES['usr_res_block'] ) && $_FILES['usr_res_block']['tmp_name'] != '' ) {
		if ( ! is_allowed_grid_file( 'usr_res_block' ) ) {
			$error .= "- Зарезервированный блок пользователя должен быть действительным файлом PNG.<br>";
		}
	}

	if ( isset( $_FILES['usr_sol_block'] ) && $_FILES['usr_sol_block']['tmp_name'] != '' ) {
		if ( ! is_allowed_grid_file( 'usr_sol_block' ) ) {
			$error .= "- Проданный блок пользователя должен быть действительным файлом PNG.<br>";
		}
	}

	return $error;
}

function is_default() {
	if ( isset( $_REQUEST['BID'] ) && $_REQUEST['BID'] == 1 ) {
		return true;
	}

	return false;
}

if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'delete' ) {
	if ( is_default() ) {
		echo "<b>Невозможно удалить</b> - это сетка по умолчанию!<br>";
	} else {

		// check orders..

		$sql = "SELECT * FROM orders where status <> 'deleted' and banner_id=" . $BID;
		//echo $sql;
		$res = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
		if ( mysqli_num_rows( $res ) == 0 ) {

			$sql = "DELETE FROM blocks WHERE banner_id='" . $BID . "' ";
			mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );

			$sql = "DELETE FROM prices WHERE banner_id='" . $BID . "' ";
			mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );

			$sql = "DELETE FROM banners WHERE banner_id='" . $BID . "' ";
			mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );

			// DELETE ADS
			$sql = "select * FROM ads where banner_id='" . $BID . "' ";
			$res2 = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
			while ( $row2 = mysqli_fetch_array( $res2 ) ) {

				delete_ads_files( $row2['ad_id'] );
				$sql = "DELETE from ads where ad_id='" . intval( $row2['ad_id'] ) . "' ";
				mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) . $sql );
			}

			@unlink( SERVER_PATH_TO_ADMIN . "../banners/main" . $BID . ".jpg" );
			@unlink( SERVER_PATH_TO_ADMIN . "../banners/main" . $BID . ".png" );
			@unlink( SERVER_PATH_TO_ADMIN . "temp/background" . $BID . ".png" );
		} else {
			echo "<font color='red'><b>Невозможно удалить</b></font> - эта сетка содержит несколько заказов в базе данных.<br>";
		}
	}
}

function get_banner_image_data( $b_row, $image_name ) {

	$uploaddir = SERVER_PATH_TO_ADMIN . "temp/";
//print_r($_FILES);
	if ( $_FILES[ $image_name ]['tmp_name'] ) {
		// a new image was uploaded
		$uploadfile = $uploaddir . md5( session_id() ) . $image_name . $_FILES[ $image_name ]['name'];
		move_uploaded_file( $_FILES[ $image_name ]['tmp_name'], $uploadfile );
		$fh       = fopen( $uploadfile, 'rb' );
		$contents = fread( $fh, filesize( $uploadfile ) );
		fclose( $fh );
		//imagecreatefrompng($uploadfile); 
		$contents = addslashes( base64_encode( $contents ) );
		//echo "$image_name<b>$contents</b><br>";
		unlink( $uploadfile );
	} else if ( $b_row[ $image_name ] != '' ) {
		// use the old image
		$contents = addslashes( ( $b_row[ $image_name ] ) );
//echo "using the old file<p>";
	} else {
//echo "using the default file $image_name<p>";
		$contents = addslashes( get_default_image( $image_name ) );
	}

//echo "$image_name<b>$contents</b><br>";
	return $contents;
}

function get_banner_image_sql_values( $BID ) {

	# , grid_block, nfs_block, tile, usr_grid_block, usr_nfs_block, usr_ord_block, usr_res_block, usr_sel_block, usr_sol_block 

	// get banner
	if ( $BID ) {
		$sql = "SELECT * FROM `banners` WHERE `banner_id`='" . intval( $BID ) . "' ";
		//echo "<p>$sql</p>";
		$result = mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) );
		$row = mysqli_fetch_array( $result );
		//print_r($row);
	}

	$sql_str = ", '" . get_banner_image_data( $row, 'grid_block' ) . "' , '" . get_banner_image_data( $row, 'nfs_block' ) . "', '" . get_banner_image_data( $row, 'tile' ) . "', '" . get_banner_image_data( $row, 'usr_grid_block' ) . "', '" . get_banner_image_data( $row, 'usr_nfs_block' ) . "', '" . get_banner_image_data( $row, 'usr_ord_block' ) . "', '" . get_banner_image_data( $row, 'usr_res_block' ) . "', '" . get_banner_image_data( $row, 'usr_sel_block' ) . "', '" . get_banner_image_data( $row, 'usr_sol_block' ) . "'";

	return $sql_str;
}

if ( isset( $_REQUEST['submit'] ) && $_REQUEST['submit'] != '' ) {

	$error = validate_input();

	if ( $error != '' ) {

		echo "<font color='red'>Ошибка: невозможно сохранить из-за следующих ошибок:</font><br>";
		echo $error;
	} else {

		//	$sql = "REPLACE INTO currencies(code, name, rate, sign, decimal_places, decimal_point, thousands_sep) VALUES ('".$_REQUEST['code']."', '".$_REQUEST['name']."', '".$_REQUEST['rate']."',  '".$_REQUEST['sign']."', '".$_REQUEST['decimal_places']."', '".$_REQUEST['decimal_point']."', '".$_REQUEST['thousands_sep']."') ";

		//echo $sql;grid_block, nfs_block, tile, usr_grid_block, usr_nfs_block, usr_ord_block, usr_res_block, usr_sel_block, usr_sol_block 

		//$image_sql_fields = get_banner_image_sql_fields($BID);
		$image_sql_fields = ', grid_block, nfs_block, tile, usr_grid_block, usr_nfs_block, usr_ord_block, usr_res_block, usr_sel_block, usr_sol_block ';
		$image_sql_values = get_banner_image_sql_values( $BID );
		$now              = ( gmdate( "Y-m-d H:i:s" ) );

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'new' ) {
			$sql = "INSERT INTO `banners` ( `banner_id` , `grid_width` , `grid_height` , `days_expire` , `price_per_block`, `name`, `currency`, `max_orders`, `block_width`, `block_height`, `max_blocks`, `min_blocks`, `date_updated`, `bgcolor`, `auto_publish`, `auto_approve` $image_sql_fields ) VALUES (NULL, '" . intval( $_REQUEST['grid_width'] ) . "', '" . intval( $_REQUEST['grid_height'] ) . "', '" . intval( $_REQUEST['days_expire'] ) . "', '" . floatval( $_REQUEST['price_per_block'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['name'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['currency'] ) . "', '" . intval( $_REQUEST['max_orders'] ) . "', '" . intval( $_REQUEST['block_width'] ) . "', '" . intval( $_REQUEST['block_height'] ) . "', '" . intval( $_REQUEST['max_blocks'] ) . "', '" . intval( $_REQUEST['min_blocks'] ) . "', '" . $now . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['bgcolor'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['auto_publish'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['auto_approve'] ) . "' $image_sql_values);";
		} else {
			$sql = "REPLACE INTO `banners` ( `banner_id` , `grid_width` , `grid_height` , `days_expire` , `price_per_block`, `name`, `currency`, `max_orders`, `block_width`, `block_height`, `max_blocks`, `min_blocks`, `date_updated`, `bgcolor`, `auto_publish`, `auto_approve` $image_sql_fields ) VALUES ('" . $BID . "', '" . intval( $_REQUEST['grid_width'] ) . "', '" . intval( $_REQUEST['grid_height'] ) . "', '" . intval( $_REQUEST['days_expire'] ) . "', '" . floatval( $_REQUEST['price_per_block'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['name'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['currency'] ) . "', '" . intval( $_REQUEST['max_orders'] ) . "', '" . intval( $_REQUEST['block_width'] ) . "', '" . intval( $_REQUEST['block_height'] ) . "', '" . intval( $_REQUEST['max_blocks'] ) . "', '" . intval( $_REQUEST['min_blocks'] ) . "', '" . $now . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['bgcolor'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['auto_publish'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['auto_approve'] ) . "' $image_sql_values);";
		}

		mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );

		$BID = mysqli_insert_id( $GLOBALS['connection'] );

		// TODO: Add individual order expiry dates
		$sql = "UPDATE `orders` SET days_expire=" . intval( $_REQUEST['days_expire'] ) . " WHERE banner_id=" . $BID;
		mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );

		$_REQUEST['new'] = '';
		//	$_REQUEST['action'] = '';

	}
}

?>
<?php if ( ( isset( $_REQUEST['new'] ) && $_REQUEST['new'] == '' ) && ( ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == '' ) ) ) { ?>
    Здесь вы можете управлять своей сеткой:
    <ul>
        <li>Установить срок действия пикселей</li>
        <li>Установите максимально разрешенные заказы на сетку</li>
        <li>Установите цену по умолчанию для пикселей</li>
        <li>Установите ширину сетки</li>
        <li>Создать и удалить новые сетки</li>
    </ul>

<?php } ?>
<?php //if ((isset($_REQUEST['new']) && $_REQUEST['new']=='')) { ?>
<input type="button" style="background-color:#66FF33" value="New Grid..." onclick="mds_load_page('inventory.php?action=new&new=1', true)"><br>
<?php //} ?>

<?php

if ( isset( $_REQUEST['new'] ) && $_REQUEST['new'] == '1' ) {
	echo "<h4>New Grid:</h4>";
}
if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
	echo "<h4>Edit Grid:</h4>";

	$sql = "SELECT * FROM banners WHERE `banner_id`='" . $BID . "' ";
	$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
	$row                         = mysqli_fetch_array( $result );
	$_REQUEST['BID']             = $row['banner_id'];
	$_REQUEST['grid_width']      = $row['grid_width'];
	$_REQUEST['grid_height']     = $row['grid_height'];
	$_REQUEST['days_expire']     = $row['days_expire'];
	$_REQUEST['max_orders']      = $row['max_orders'];
	$_REQUEST['price_per_block'] = $row['price_per_block'];
	$_REQUEST['name']            = $row['name'];
	$_REQUEST['currency']        = $row['currency'];
	$_REQUEST['block_width']     = $row['block_width'];
	$_REQUEST['block_height']    = $row['block_height'];
	$_REQUEST['max_blocks']      = $row['max_blocks'];
	$_REQUEST['min_blocks']      = $row['min_blocks'];
	$_REQUEST['bgcolor']         = $row['bgcolor'];
	$_REQUEST['auto_approve']    = $row['auto_approve'];
	$_REQUEST['auto_publish']    = $row['auto_publish'];
}

if ( ( isset( $_REQUEST['new'] ) && $_REQUEST['new'] != '' ) || ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) ) {

	if ( isset( $_REQUEST['block_width'] ) && ! $_REQUEST['block_width'] ) {
		$_REQUEST['block_width'] = 10;
	}

	if ( isset( $_REQUEST['block_height'] ) && ! $_REQUEST['block_height'] ) {
		$_REQUEST['block_height'] = 10;
	}

	if ( isset( $_REQUEST['max_blocks'] ) && ! $_REQUEST['max_blocks'] ) {
		$_REQUEST['max_blocks'] = '0';
	}

	if ( isset( $_REQUEST['min_blocks'] ) && ! $_REQUEST['min_blocks'] ) {
		$_REQUEST['min_blocks'] = '0';
	}

	if ( isset( $_REQUEST['days_expire'] ) && ! $_REQUEST['days_expire'] ) {
		$_REQUEST['days_expire'] = '0';
	}

	if ( isset( $_REQUEST['max_orders'] ) && ! $_REQUEST['max_orders'] ) {
		$_REQUEST['max_orders'] = '0';
	}

	?>
    <form enctype="multipart/form-data" action='inventory.php' method="post">
        <input type="hidden" value="<?php echo( isset( $_REQUEST['new'] ) ? $_REQUEST['new'] : "" ); ?>" name="new">
        <input type="hidden" value="<?php echo( isset( $_REQUEST['edit'] ) ? $_REQUEST['edit'] : "" ); ?>" name="edit">
        <input type="hidden" value="<?php echo( isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : "" ); ?>" name="action">
        <input type="hidden" value="<?php echo( isset( $_REQUEST['BID'] ) ? $BID : "" ); ?>" name="BID">
        <input type="hidden" value="<?php echo( isset( $_REQUEST['edit_anyway'] ) ? $_REQUEST['edit_anyway'] : "" ); ?>" name="edit_anyway">
        <table border="0" cellSpacing="0" cellPadding="0" width="100%" bgcolor="#ffffff">

            <tr>
                <td width="50%" valign='top'><!-- start left column -->

                    <table border='0' cellSpacing="1" cellPadding="3" bgColor="#d9d9d9">
                        <tr>
                            <td>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Сетка Name</b></font></td>
                            <td><input size="30" type="text" name="name" value="<?php echo( isset( $_REQUEST['name'] ) ? $_REQUEST['name'] : "" ); ?>"/> <font size="2"> Моя сетка миллионов пикселей</font></td>
                        </tr>
						<?php

						$sql   = "SELECT * FROM blocks where banner_id=" . $BID . " AND status <> 'nfs' limit 1 ";
						$b_res = mysqli_query( $GLOBALS['connection'], $sql );

						if ( ( $row['banner_id'] != '' ) && ( mysqli_num_rows( $b_res ) > 0 ) ) {
							$locked = true;
						} else {
							$locked = false;
						}

						if ( isset( $_REQUEST['edit_anyway'] ) && $_REQUEST['edit_anyway'] != '' ) {

							$locked = false;
						}

						?>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Ширина сетки</b></font></td>
                            <td>
								<?php
								$disabled = "";
								if ( ! $locked ) {
									?>
                                    <input <?php echo $disabled; ?> size="2" type="text" name="grid_width" value="<?php echo $_REQUEST['grid_width']; ?>"/><font size="2"> Измеряется в блоках (размер блока по умолчанию 10х10 пикселей)</font>
								<?php } else {

									echo "<b>" . $_REQUEST['grid_width'];
									echo "<input type='hidden' value='" . $row['grid_width'] . "' name='grid_width'> Блоки.</b> <font size='1'>Примечание. Невозможно изменить ширину, поскольку сетка используется рекламодателем. [<a href='inventory.php?action=edit&BID=" . $BID . "&edit_anyway=1'>Все равно редактировать</a>]</font>";
								}
								?>
                            </td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea">
                                <font size="2"><b>Высота сетки</b></font></td>
                            <td>
								<?php

								if ( ! $locked ) {
									?>
                                    <input <?php echo $disabled; ?> size="2" type="text" name="grid_height" value="<?php echo $_REQUEST['grid_height']; ?>"/><font size="2"> Измеряется в блоках (размер блока по умолчанию 10х10 пикселей)</font>
								<?php } else {

									echo "<b>" . $_REQUEST['grid_height'];
									echo "<input type='hidden' value='" . $row['grid_height'] . "' name='grid_height'> Блоки.</b> <font size='1'> Примечание. Невозможно изменить высоту, поскольку сетка используется рекламодателем.[<a href='inventory.php?action=edit&BID=" . $BID . "&edit_anyway=1'>Все равно редактировать</a>]</font>";
								}
								?>
                            </td>
                        </tr>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Цена за блок</b></font></td>
                            <td><input size="1" type="text" name="price_per_block" value="<?php echo $_REQUEST['price_per_block']; ?>"/><font size="2">(Сколько за 1 блок пикселей?)</font></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Валюта</b></font></td>
                            <td>
                                <select name="currency">
									<?php
									currency_option_list( $_REQUEST['currency'] );

									?>
                                </select>
                            </td>
                        </tr>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Дни истечения</b></font></td>
                            <td><input <?php echo $disabled; ?> size="1" type="text" name="days_expire" value="<?php echo $_REQUEST['days_expire']; ?>"/><font size="2">(Сколько дней до истечения срока действия пикселей? Введите 0 для неограниченного числа.)</font></td>
                        </tr>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Максимальное количество заказов на одного клиента</b></font></td>
                            <td><input <?php echo $disabled; ?> size="1" type="text" name="max_orders" value="<?php echo $_REQUEST['max_orders']; ?>"/><font size="2">(Сколько заказов на 1 клиента? Введите 0 для неограниченного.)</font><br>
                            </td>
                        </tr>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Максимум блоков</b></font></td>
                            <td><input size="1" type="text" name="max_blocks" value="<?php echo $_REQUEST['max_blocks']; ?>"/><font size="2">(Максимальное количество блоков, которое клиент может приобрести? Введите 0 для неограниченного количества.)</font><br>
                            </td>
                        </tr>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Минимум блоков</b></font></td>
                            <td><input size="1" type="text" name="min_blocks" value="<?php echo $_REQUEST['min_blocks']; ?>"/><font size="2">(Минимальное количество блоков, которое клиент должен приобрести за заказ? Введите 1 или 0 без ограничений.)</font><br>
                            </td>
                        </tr>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Утвердить автоматически?</b></font></td>
                            <td>
                                <font size="1" face="Verdana">
                                    <input type="radio" name="auto_approve" value="Y" <?php if ( $_REQUEST['auto_approve'] == 'Y' ) {
										echo " checked ";
									} ?> >Да. Одобрить все пиксели автоматически по мере их отправки.<br>
                                    <input type="radio" name="auto_approve" value="N" <?php if ( $_REQUEST['auto_approve'] == 'N' ) {
										echo " checked ";
									} ?> >Нет, подтвердить вручную от администратора.<br>
                                </font>
                            </td>
                        </tr>

                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font size="2"><b>Публиковать автоматически?</b></font></td>
                            <td>
                                <font size="1" face="Verdana">
                                    <input type="radio" name="auto_publish" value="Y" <?php if ( $_REQUEST['auto_publish'] == 'Y' ) {
										echo " checked ";
									} ?> >Да. Обрабатывать изображения сетки автоматически, каждый раз, когда пиксели утверждены, срок годности истек или отклонен.<br>
                                    <input type="radio" name="auto_publish" value="N" <?php if ( $_REQUEST['auto_publish'] == 'N' ) {
										echo " checked ";
									} ?> >Нет, обрабатывать вручную от админа<br>
                                </font>
                            </td>
                        </tr>
                    </table>

					<?php

					$size_error_style = "style='font-size:9px; color:#F7DAD5; border-color:#FF6600; border-style: solid'";
					$size_error_msg   = "Error: Invalid size! Must be " . $_REQUEST['block_width'] . "x" . $_REQUEST['block_height'];

					function validate_block_size( $image_name, $BID ) {

						if ( ! $BID ) {

							return true; // new grid...

						}

						$block_w = $_REQUEST['block_width'];
						$block_h = $_REQUEST['block_height'];

						$sql    = "SELECT * FROM banners where banner_id=" . intval( $BID );
						$result = mysqli_query( $GLOBALS['connection'], $sql );
						$b_row  = mysqli_fetch_array( $result );

						if ( $b_row[ $image_name ] == '' ) { // no data, assume that the default image will be loaded..

							return true;
						}

						$imagine = new Imagine\Gd\Imagine();

						$img = $imagine->load( base64_decode( $b_row[ $image_name ] ) );

						$temp_file = SERVER_PATH_TO_ADMIN . "temp/temp_block" . md5( session_id() ) . ".png";
						$img->save( $temp_file );
						$size = $img->getSize();

						unlink( $temp_file );

						if ( $size->getWidth() != $block_w ) {
							return false;
						}

						if ( $size->getHeight() != $block_h ) {
							return false;
						}

						return true;
					}

					?>

                </td>
                <td valign='top' bgcolor="#ffffff">
                    <table id="table1" border='0' cellSpacing="1" cellPadding="3" bgColor="#d9d9d9">
                        <tr bgcolor="#ffffff">
                            <td colspan="3" bgColor="#eaeaea"><b><font face="Arial" size="2">Конфигурация блока</font></b></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><b><font size="2" face="Arial">Размер блока</font></b></td>
                            <td colspan="2">

                                <p>
                                    <input type="text" name="block_width" size="2" style="font-size: 18pt" value="<?php echo $_REQUEST['block_width']; ?>">
                                    &nbsp;<font size="6">X</font>&nbsp;
                                    <input type="text" name="block_height" size="2" style="font-size: 18pt" value="<?php echo $_REQUEST['block_height']; ?>"><br>
                                    <font face="Arial" size="2">(Ширина X Высота, по умолчанию 10x10 в пикселях)</font></p>

                        </tr>
                        <tr bgcolor="#ffffff">
                            <td colspan="3" bgColor="#eaeaea"><font face="Arial" size="2"><b>Блок Графика -
                                        Отображается на общедоступной сетке</b></font></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font face="Arial" size="2"><b>Сетка блока<?php display_reset_link( $BID, 'grid_block' ); ?></b></font></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'grid_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?> ><span><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=grid_block" border="0"><?php if ( ! $valid ) {
										echo $size_error_msg;
										$valid = '';
									} ?></span></td>
                            <td><input type="file" name="grid_block" size="10"></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><b><font size="2" face="Arial">Не для продажи блоки<?php display_reset_link( $BID, 'nfs_block' ); ?></font></b></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'nfs_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?>><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=nfs_block" border="0"><?php if ( ! $valid ) {
									echo $size_error_msg;
									$valid = '';
								} ?></td>
                            <td><input type="file" name="nfs_block" size="10"></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><b><font size="2" face="Arial">Фоновая плитка<?php display_reset_link( $BID, 'tile' ); ?></font></b></td>
                            <td bgcolor='#867C6F'><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=tile" border="0"></td>
                            <td><input type="file" name="tile" size="10">(<font size="1" face="Verdana">Эта плитка используется для заполнения пространства позади изображения сетки. Плитка будет видна до загрузки изображения сетки.) <b>Фоновый цвет:</b> <input type='text' name='bgcolor' size='7' value='<?php echo $_REQUEST['bgcolor']; ?>'> eg. #ffffff</font></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td colspan="3" bgColor="#eaeaea"><b><font size="2" face="Arial">Блок Графика -
                                        Отображается в сетке заказа</font></b></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font face="Arial" size="2"><b>Сетка блока<?php display_reset_link( $BID, 'usr_grid_block' ); ?></b></font></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'usr_grid_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?>><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=usr_grid_block" border="0"><?php if ( ! $valid ) {
									echo $size_error_msg;
									$valid = '';
								} ?></td>
                            <td><input type="file" name="usr_grid_block" size="10"></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><b><font size="2" face="Arial">Не для продажи блоки<?php display_reset_link( $BID, 'usr_nfs_block' ); ?></font></b></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'usr_nfs_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?> ><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=usr_nfs_block" border="0"><?php if ( ! $valid ) {
									echo $size_error_msg;
									$valid = '';
								} ?></td>
                            <td><input type="file" name="usr_nfs_block" size="10"></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font face="Arial" size="2"><b>Упорядоченный блок<?php display_reset_link( $BID, 'usr_ord_block' ); ?></b></font></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'usr_ord_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?> ><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=usr_ord_block" border="0"><?php if ( ! $valid ) {
									echo $size_error_msg;
									$valid = '';
								} ?></td>
                            <td><input type="file" name="usr_ord_block" size="10"></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font face="Arial" size="2"><b>Зарезервированный блок<?php display_reset_link( $BID, 'usr_res_block' ); ?></b></font></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'usr_res_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?> ><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=usr_res_block" border="0"><?php if ( ! $valid ) {
									echo $size_error_msg;
									$valid = '';
								} ?></td>
                            <td><input type="file" name="usr_res_block" size="10"></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><b><font size="2" face="Arial">Выбранный блок<?php display_reset_link( $BID, 'usr_sel_block' ); ?></font></b></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'usr_sel_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?> ><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=usr_sel_block" border="0"><?php if ( ! $valid ) {
									echo $size_error_msg;
									$valid = '';
								} ?></td>
                            <td><input type="file" name="usr_sel_block" size="10"></td>
                        </tr>
                        <tr bgcolor="#ffffff">
                            <td bgColor="#eaeaea"><font face="Arial" size="2"><b>Проданный Блок<?php display_reset_link( $BID, 'usr_sol_block' ); ?></b></font></td>
                            <td bgcolor='#867C6F' <?php $valid = validate_block_size( 'usr_sol_block', $BID );
							if ( ! $valid ) {
								echo $size_error_style;
							} ?> ><img src="get_block_image.php?t=<?php echo time(); ?>&BID=<?php echo $BID; ?>&image_name=usr_sol_block" border="0"><?php if ( ! $valid ) {
									echo $size_error_msg;
									$valid = '';
								} ?></td>
                            <td><input type="file" name="usr_sol_block" size="10"></td>
                        </tr>
                        <!--
	<tr bgcolor="#ffffff">
	<td colspan="3">
<a href="inventory.php?action=edit&BID=<?php echo $BID ?>&default_all=yes" onclick="return confirmLink(this, 'Reset all blocks to default, are you sure?')"><font color='red' size=1>Reset all blocks to default</font></a></td>
	<tr>
	-->
                    </table>

                </td>

            </tr>
        </table>
        <input type="submit" name="submit" value="Сохранить настройки сетки" style="font-size: 21px;">
    </form>
    <hr>
	<?php

	if ( $locked ) {
		echo "Note: The Grid Width and Grid Height fields are locked because this image has some pixels on order / sold";
	}
}

function render_offer( $price, $currency, $max_orders, $days_expire, $package_id = 0 ) {
	?>
    <font size="2">
		<?php
		if ( $package_id != 0 ) {
			//echo "<font color='#CC0033'>#$package_id </font>";
		}
		?>
        <small>Days:</small> <b><?php if ( $days_expire > 0 ) {
				echo $days_expire;
			} else {
				echo "unlimited";
			} ?></b></font><font size="2"> <small>Max Ord</small>: <b><?php if ( $max_orders > 0 ) {
				echo $max_orders;
			} else {
				echo "unlimited";
			} ?></b></font><font size="2"> <small>Price/100</small>: <b><?php echo $price; ?></font><font size="2"> <?php echo $currency; ?></b></font><br>

	<?php
}

?>

<font size='1'>Примечание. Сетка со 100 строками и 100 столбцами составляет миллион пикселей. Установка большего значения может повлиять на память и производительность скрипта.</font><br>

<table border="0" cellSpacing="1" cellPadding="3" bgColor="#d9d9d9">
    <tr bgColor="#eaeaea">
        <td><b><font size="2">ИД сетки</b></font></td>
        <td><b><font size="2">Имя</b></font></td>
        <td><b><font size="2">Ширина сетки</b></font></td>
        <td><b><font size="2">Высота сетки</b></font></td>
        <!--
		<td><b><font size="2">Days to Exp.</b></font></td>
		<td><b><font size="2">Price /<br>Block</b></font></td>
		<td><b><font size="2">Currency</b></font></td>
		-->
        <td><b><font size="2">Предложение</b></font></td>

        <td><b><font size="2">Действие</b></font></td>
        <td><b><font size="2">Сегодня кликов</b></font></td>
        <td><b><font size="2">Всего кликов</b></font></td>
    </tr>
	<?php
	$result = mysqli_query( $GLOBALS['connection'], "select * FROM banners" ) or die ( mysqli_error( $GLOBALS['connection'] ) );
	while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {

		?>
        <tr bgcolor="#ffffff">
            <td><font size="2"><?php echo $row['banner_id']; ?></font></td>
            <td><font size="2"><?php echo $row['name']; ?></font></td>
            <td><font size="2"><?php echo $row['grid_width']; ?> блоки</font></td>
            <td><font size="2"><?php echo $row['grid_height']; ?> блоки</font></td>
            <td nowrap>

				<?php

				$banner_packages = banner_get_packages( $row['banner_id'] );

				if ( ! $banner_packages ) {
					// render the default offer
					render_offer( $row['price_per_block'], $row['currency'], $row['max_orders'], $row['days_expire'] );
				} else {

					?>

					<?php while ( $p_row = mysqli_fetch_array( $banner_packages ) ) {

						render_offer( $p_row['price'], $p_row['currency'], $p_row['max_orders'], $p_row['days_expire'], $p_row['package_id'] );

						?>

					<?php }
				} ?>
            </td>
            <td><font size="2"><a href='inventory.php?action=edit&BID=<?php echo $row['banner_id']; ?>'>Редактировать</a> / <a href="packs.php?BID=<?php echo $row['banner_id']; ?>"> Управление сетками</a><?php if ( $row['banner_id'] != '1' ) { ?> / <a href='inventory.php?action=delete&BID=<?php echo $row['banner_id']; ?>'>Удалить</a><?php } ?></font></td>
            <td><font size="2"><?php echo get_clicks_for_today( $row['banner_id'] ); ?></font></td>
            <td><font size="2"><?php echo get_clicks_for_banner( $row['banner_id'] ); ?></font></td>

        </tr>
		<?php
	}
	?>
</table>
