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
    <b>Ценовые зоны:</b> Здесь вы можете добавить различные ценовые зоны в сетку. Эта функция позволяет сделать некоторые регионы сетки более дорогими, чем другие. <i>Осторожно: пакеты игнорируют ценовые зоны, т.е. если в сетке есть пакеты, ценовые зоны будут игнорироваться для этой сетки.</i></p>
<hr>
<?php
$sql = "Select * from banners ";
$res = mysqli_query( $GLOBALS['connection'], $sql );
?>
<form name="bidselect" method="post" action="price.php">
    <label>
        Выберите сетку:
        <select name="BID" onchange="mds_submit(this)">
            <option></option>
			<?php
			while ( $row = mysqli_fetch_array( $res ) ) {

				if ( ( $row['banner_id'] == $BID ) && ( $BID != 'all' ) ) {
					$sel = 'selected';
				} else {
					$sel = '';
				}
				echo '
            <option
            ' . $sel . ' value=' . $row['banner_id'] . '>' . $row['name'] . '</option>';
			}
			?>
        </select>
    </label>
</form>
<?php

if ( $BID != '' ) {
	$banner_data = load_banner_constants( $BID );
	?>
    <hr>
    <b>ИД сетки:</b> <?php echo $BID; ?><br>
    <b>Имя сетки:</b> <?php echo $banner_data['G_NAME']; ?><br>
    <b>Цена по умолчанию за блок:</b> <?php echo $banner_data['G_PRICE']; ?><br>

    <input type="button" style="background-color:#66FF33" value="New Price Zone..." onclick="mds_load_page('price.php?new=1&BID=<?php echo $BID; ?>', true)"><br>

    Вывод списка строк, помеченных как пользовательские цены.<br>
	<?php
	function validate_input() {

		global $BID;

		$banner_data = load_banner_constants( $BID );

		$error = "";
		if ( trim( $_REQUEST['row_from'] ) == '' ) {
			$error .= "<b>- Код «Начать с строки» пуст</b><br>";
		}
		if ( trim( $_REQUEST['row_to'] ) == '' ) {
			$error .= "<b>- «Конец в строке» пусто</b><br>";
		}

		if ( trim( $_REQUEST['col_from'] ) == '' ) {
			$error .= "<b>- Код «Начать с Кол» пуст</b><br>";
		}
		if ( trim( $_REQUEST['col_to'] ) == '' ) {
			$error .= "<b>- «Конец в седле» пусто</b><br>";
		}

		if ( trim( $_REQUEST['color'] ) == '' ) {
			$error .= "<b>- «Цвет» не выбран</b><br>";
		}

		if ( $error == '' ) {
			if ( ! is_numeric( $_REQUEST['row_from'] ) ) {
				$error .= "<b>- «Старт с строки» должен быть числом</b><br>";
			}

			if ( ! is_numeric( $_REQUEST['row_to'] ) ) {
				$error .= "<b>- «Конец в строке» должен быть числом</b><br>";
			}

			if ( $error == '' ) {
				if ( $_REQUEST['row_from'] > $_REQUEST['row_to'] ) {
					$error .= "<b>- «Начать с строки» больше, чем «Конец с строки»</b><br>";
				} else if ( ( $_REQUEST['row_from'] < 1 ) || ( $_REQUEST['row_to'] > $banner_data['G_HEIGHT'] ) ) {
					$error .= "<b>- Указанные строки находятся вне диапазона! (Текущая сетка имеет " . $banner_data['G_HEIGHT'] . " строк)</b><br>";
				} else {
					// check database..
					if ( $_REQUEST['submit'] != '' ) {
						$and_price = "";
						if ( $_REQUEST['price_id'] != '' ) {
							$and_price = "and price_id <>" . intval( $_REQUEST['price_id'] );
						}

						$sql = "SELECT * FROM prices where row_from <= " . intval( $_REQUEST['row_to'] ) . " AND row_to >=" . intval( $_REQUEST['row_from'] ) . " AND col_from <= " . intval( $_REQUEST['col_to'] ) . " AND col_to >=" . intval( $_REQUEST['col_from'] ) . " $and_price AND banner_id=" . intval( $BID );
						$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );

						if ( mysqli_num_rows( $result ) > 0 ) {
							$error .= "<b> - Невозможно создать: ценовые зоны не могут перекрывать другие ценовые зоны!</b><br>";
						}
					}
				}

				if ( $_REQUEST['col_from'] > $_REQUEST['col_to'] ) {
					$error .= "<b>- «Начало из столбца» больше, чем «Конец в столбце»</b><br>";
				} else if ( ( $_REQUEST['col_from'] < 1 ) || ( $_REQUEST['col_to'] > $banner_data['G_WIDTH'] ) ) {
					$error .= "<b>- Указанные столбцы находятся вне диапазона! (Текущая сетка имеет " . $banner_data['G_WIDTH'] . " колонок)</b><br>";
				}
			}
		}

		if ( trim( $_REQUEST['price'] ) == '' ) {
			$error .= "<b>- Цена не указана</b><br>";
		}

		if ( trim( $_REQUEST['currency'] ) == '' ) {
			$error .= "<b>- Валюта пуста</b><br>";
		}

		return $error;
	}

	if ( $_REQUEST['action'] == 'delete' ) {
		$sql = "DELETE FROM prices WHERE price_id='" . intval( $_REQUEST['price_id'] ) . "' ";
		mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) . $sql );
	}

	if ( $_REQUEST['submit'] != '' ) {
		$error = validate_input();

		if ( $error != '' ) {
			echo "<p>";
			echo "<font color='red'>Ошибка: невозможно сохранить из-за следующих ошибок:</font><br>";
			echo $error;
			echo "</p>";
		} else {
			// calculate block id..
			$_REQUEST['block_id_from'] = ( $_REQUEST['row_from'] - 1 ) * $banner_data['G_WIDTH'];
			$_REQUEST['block_id_to']   = ( ( ( $_REQUEST['row_to'] ) * $banner_data['G_WIDTH'] ) - 1 );

			$sql = "REPLACE INTO prices(price_id, banner_id, row_from, row_to, col_from, col_to, block_id_from, block_id_to, price, currency, color) VALUES ('" . intval( $_REQUEST['price_id'] ) . "', '" . intval( $BID ) . "', '" . intval( $_REQUEST['row_from'] ) . "', '" . intval( $_REQUEST['row_to'] ) . "', '" . intval( $_REQUEST['col_from'] ) . "', '" . intval( $_REQUEST['col_to'] ) . "', '" . intval( $_REQUEST['block_id_from'] ) . "', '" . intval( $_REQUEST['block_id_to'] ) . "', '" . floatval( $_REQUEST['price'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['currency'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['color'] ) . "') ";
			mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );

			$_REQUEST['new']    = '';
			$_REQUEST['action'] = '';
		}
	}

	$result = mysqli_query( $GLOBALS['connection'], "select * FROM prices  where banner_id=" . intval( $BID ) ) or die ( mysqli_error( $GLOBALS['connection'] ) );
	if ( mysqli_num_rows( $result ) > 0 ) {
		?>

        <table width="800" cellSpacing="1" cellPadding="3" bgColor="#d9d9d9" border="0">
            <tr>
                <td><b><font face="Arial" size="2">ИД сетки</font></b></td>
                <td><b><font face="Arial" size="2">Цвет</font></b></td>
                <td><b><font face="Arial" size="2">Строка<br>- от</font></b></td>
                <td><b><font face="Arial" size="2">Строка<br>- к</font></b></td>
                <td><b><font face="Arial" size="2">Колонка<br>- от</font></b></td>
                <td><b><font face="Arial" size="2">Колонка<br>- к</font></b></td>
                <td><b><font face="Arial" size="2">Цена<br>за блок</font></b></td>
                <td><b><font face="Arial" size="2">Валюта</font></b></td>
                <td><b><font face="Arial" size="2">Действие</font></b></td>
            </tr>

			<?php
			while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
				?>

                <tr bgcolor="#ffffff">
                    <td><font face="Arial" size="2"><?php echo $row['banner_id']; ?></font></td>
                    <td bgcolor="<?php if ( $row['color'] == 'yellow' ) {
						echo '#FFFF00';
					} else if ( $row['color'] == 'cyan' ) {
						echo '#00FFFF';
					} else if ( $row['color'] == 'magenta' ) {
						echo '#FF00FF';
					} ?>"><font face="Arial" size="2"><?php

							echo $row['color'];

							?>
                        </font></td>
                    <td><font face="Arial" size="2"><?php echo $row['row_from']; ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['row_to']; ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['col_from']; ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['col_to']; ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['price']; ?></font></td>
                    <td><font face="Arial" size="2"><?php echo $row['currency']; ?></font></td>
                    <td nowrap><font face="Arial" size="2"><a href="price.php?price_id=<?php echo $row['price_id']; ?>&BID=<?php echo $BID; ?>&action=edit">Редактировать</a> | <a href="price.php?price_id=<?php echo $row['price_id']; ?>&BID=<?php echo $BID; ?>&action=delete" onclick="return confirmLink(this, 'Delete, are you sure?');">Удалить</a></font></td>
                </tr>
				<?php
			}
			?>
        </table>
		<?php
	} else {
		echo "Для этой сетки нет пользовательских ценовых зон.<br>";
	}

	if ( $_REQUEST['new'] == '1' ) {
		echo "<h4>Добавить ценовую зону:</h4>";
	}

	if ( $_REQUEST['action'] == 'edit' ) {
		echo "<h4>Изменить ценовую зону:</h4>";

		$sql = "SELECT * FROM prices WHERE `price_id`='" . intval( $_REQUEST['price_id'] ) . "' ";
		$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
		$row = mysqli_fetch_array( $result );

		if ( isset( $error ) && $error == '' ) {
			$_REQUEST['color']    = $row['color'];
			$_REQUEST['price_id'] = $row['price_id'];
			$_REQUEST['row_from'] = $row['row_from'];
			$_REQUEST['row_to']   = $row['row_to'];
			$_REQUEST['col_from'] = $row['col_from'];
			$_REQUEST['col_to']   = $row['col_to'];
			$_REQUEST['price']    = $row['price'];
			$_REQUEST['currency'] = $row['currency'];
		}
	}

	if ( ( $_REQUEST['new'] != '' ) || ( $_REQUEST['action'] == 'edit' ) ) {
		if ( $_REQUEST['col_from'] == '' ) {
			$_REQUEST['col_from'] = 1;
		}

		if ( $_REQUEST['col_to'] == '' ) {
			$_REQUEST['col_to'] = $banner_data['G_HEIGHT'];
		}
		?>
        <form action='price.php' method="post">
            <input type="hidden" value="<?php echo intval( $row['price_id'] ); ?>" name="price_id">
            <input type="hidden" value="<?php echo intval( $_REQUEST['new'] ); ?>" name="new">
            <input type="hidden" value="<?php echo $f2->filter( $_REQUEST['action'] ); ?>" name="action">
            <input type="hidden" value="<?php echo $BID; ?>" name="BID">
            <table border="0" cellSpacing="1" cellPadding="3" bgColor="#d9d9d9">
                <tr bgcolor="#ffffff">
                    <td><font size="2">Цвет:</font></td>
                    <td>
                        <select name="color">
                            <option value="">[Выбрать]</option>
                            <option value="yellow" <?php if ( $_REQUEST['color'] == 'yellow' ) {
								echo 'выбран';
							} ?> style="background-color: #FFFF00">Желтый
                            </option>
                            <option value="cyan" <?php if ( $_REQUEST['color'] == 'cyan' ) {
								echo 'выбран';
							} ?> style="background-color: #00FFFF">Голубой
                            </option>
                            <option value="magenta" <?php if ( $_REQUEST['color'] == 'magenta' ) {
								echo 'выбран';
							} ?> style="background-color: #FF00FF">Розовый
                            </option>
                            <option value="white" <?php if ( $_REQUEST['color'] == 'white' ) {
								echo 'выбран';
							} ?> style="background-color: #FFffFF">Белый
                            </option>
                        </select>

                    </td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Начало с строки:</font></td>
                    <td><input size="2" type="text" name="row_from" value="<?php echo intval( $_REQUEST['row_from'] ); ?>"> eg. 1</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Конец строки:</font></td>
                    <td><input size="2" type="text" name="row_to" value="<?php echo intval( $_REQUEST['row_to'] ); ?>"> eg. 25</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Начало с колонки:</font></td>
                    <td><input size="2" type="text" name="col_from" value="<?php echo intval( $_REQUEST['col_from'] ); ?>"> eg. 1</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Конец колонки:</font></td>
                    <td><input size="2" type="text" name="col_to" value="<?php echo intval( $_REQUEST['col_to'] ); ?>"> eg. 25</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Цена за блок:</font></td>
                    <td><input size="5" type="text" name="price" value="<?php echo floatval( $_REQUEST['price'] ); ?>">Price per block (<?php echo $banner_data['BLK_WIDTH'] * $banner_data['BLK_HEIGHT']; ?> pixels). Enter a decimal</td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td><font size="2">Валюта:</font></td>
                    <td><select size="1" name="currency"><?php currency_option_list( $_REQUEST['currency'] ); ?>The price's currency</td>
                </tr>

            </table>
            <input type="submit" name="submit" value="ОК">
        </form>
		<?php
	}
	?>
    <br/>
    <img usemap="#prices" src="show_price_zone.php?BID=<?php echo $BID; ?>&time=<?php echo( time() ); ?>" width="<?php echo( $banner_data['G_WIDTH'] * $banner_data['BLK_WIDTH'] ); ?>" height="<?php echo( $banner_data['G_HEIGHT'] * $banner_data['BLK_HEIGHT'] ); ?>" border="0" usemap="#main"/>
	<?php
	show_price_area( $BID );
}
?>
