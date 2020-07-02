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

session_start( [
	'name' => 'MDSADMIN_PHPSESSID',
] );

require_once __DIR__ . "/../include/init.php";

require( "admin_common.php" );
require_once( '../include/ads.inc.php' );
require_once( '../include/dynamic_forms.php' );
error_reporting( E_ALL & ~E_NOTICE );

$mode = $_REQUEST['mode'];

?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000; "></div>
<b>[Список объявлений]</b>
<span style="background-color: <?php if ( ( $_REQUEST['mode'] != 'edit' ) ) {
	echo "#F2F2F2";
} ?>; border-style:outset; padding: 5px;"><a href="adform.php?mode=view">Посмотреть форму</a>
</span> <span style="background-color:  <?php if ( ( $_REQUEST['mode'] == 'edit' ) && ( $_REQUEST['NEW_FIELD'] == '' ) ) {
	echo "#FFFFCC";
} ?>; border-style:outset; padding: 5px;"><a href="adform.php?mode=edit">Редактировать поля</a></span>
<span style="background-color: <?php if ( ( $_REQUEST['mode'] == 'edit' ) && ( $_REQUEST['NEW_FIELD'] != '' ) ) {
	echo "#FFFFCC";
} else {
	echo "#F2F2F2";
} ?> ; border-style:outset; padding: 5px;"><a href="adform.php?NEW_FIELD=YES&mode=edit">Новое поле</a></span> &nbsp; &nbsp;
<span style="background-color: <?php echo "#FFFFCC"; ?> ; border-style:outset; padding: 5px;"><a href="adslist.php">Список объявлений</a></span>

<hr>

<?php

if ( $_REQUEST['action'] == 'del' ) {

	$sql    = "DELETE FROM form_lists WHERE column_id='" . intval( $_REQUEST['column_id'] ) . "' ";
	$result = mysqli_query( $GLOBALS['connection'], $sql );
}

if ( $_REQUEST['column_id'] != '' ) {
	$sql     = "SELECT * FROM form_lists WHERE column_id='" . intval( $_REQUEST['column_id'] ) . "' ";
	$result  = mysqli_query( $GLOBALS['connection'], $sql );
	$col_row = mysqli_fetch_array( $result );
}

if ( $_REQUEST['save_col'] != '' ) {

	if ( $_REQUEST['field_id'] == '' ) {
		$error = "Did not select a field ";
	}

	if ( ! is_numeric( $_REQUEST['sort_order'] ) ) {
		$error .= "'Sort order' должен быть числом. <br>";
	}

	if ( ! is_numeric( $_REQUEST['truncate_length'] ) ) {
		$error .= "'Truncate' должен быть числом. <br>";
	}

	if ( is_numeric( $_REQUEST['field_id'] ) ) {

		$sql       = "SELECT * from form_fields WHERE form_id=1 AND field_id='" . intval( $_REQUEST['field_id'] ) . "'  ";
		$result    = mysqli_query( $GLOBALS['connection'], $sql );
		$field_row = mysqli_fetch_array( $result );
	} else {

		$field_row['field_type'] = 'TEXT'; // стандарт сохранения бд.
		$field_row['field_id']   = $_REQUEST['field_id'];

		switch ( $_REQUEST['field_id'] ) {

			case 'ad_date':
				$field_row['template_tag'] = 'DATE';
				$field_row['field_type']   = 'TIME';
				break;
			case 'ad_id':
				$field_row['template_tag'] = 'AD_ID';
				break;
			case 'user_id':
				$field_row['template_tag'] = 'USER_ID';
				break;
			case 'order_id':
				$field_row['template_tag'] = 'ORDER_ID';
				break;
			case 'banner_id':
				$field_row['template_tag'] = 'BID';
				break;
		}
	}

	if ( $field_row['template_tag'] == '' ) { // need to fix the template tag!

		$field_row['template_tag'] = generate_template_tag( 1 );

		// update form field

		$sql = "UPDATE form_fields SET `template_tag`='" . mysqli_real_escape_string( $GLOBALS['connection'], $field_row['template_tag'] ) . "' WHERE form_id=1 AND field_id='" . intval( $_REQUEST['field_id'] ) . "'";
		mysqli_query( $GLOBALS['connection'], $sql );
	}

	if ( $_REQUEST['admin_only'] == '' ) {
		$_REQUEST['admin_only'] = 'N';
	}

	if ( $_REQUEST['linked'] == '' ) {
		$_REQUEST['linked'] = 'N';
	}

	$sql = "REPLACE INTO form_lists (`column_id`, `template_tag`, `field_id`, `sort_order`, `field_type`, `form_id`, `admin`, `truncate_length`, `linked`, `clean_format`, `is_bold`, `no_wrap`, `is_sortable`) VALUES ('" . intval( $_REQUEST['column_id'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $field_row['template_tag'] ) . "', '" . intval( $field_row['field_id'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['sort_order'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $field_row['field_type'] ) . "', '1', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['admin_only'] ) . "', '" . intval( $_REQUEST['truncate_length'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['linked'] ) . "',  '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['clean_format'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['is_bold'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['no_wrap'] ) . "', '" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['is_sortable'] ) . "')";

	if ( $error == '' ) {
		$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) . $sql );
		echo "Столбец обновлен.<br>";
	} else {
		echo "<span style=\"color: red; \">Не удается сохранить из-за следующих ошибок:</span><br>";
		echo $error;
	}

	// load new values

	$sql     = "SELECT * FROM form_lists WHERE column_id='" . intval( $_REQUEST['column_id'] ) . "' ";
	$result  = mysqli_query( $GLOBALS['connection'], $sql );
	$col_row = mysqli_fetch_array( $result );
}

?>
<?php
if ( $col_row['column_id'] != '' ) {

	echo '<a href="adslist.php">+ Добавить новый столбец</a>';
}

?>
<form method="POST" action="adslist.php">

    <input type="hidden" name="form_id" value="1">
    <input type="hidden" name="column_id" value="<?php echo $col_row['column_id']; ?>">
    <table border=1>
        <tr>
            <td colspan="2">
				<?php
				if ( $col_row['column_id'] == '' ) {
					?>
                    <b>Добавить новый столбец в список</b>
					<?php
				} else {
					?>
                    <b>Редактировать столбец</b>

					<?php
				}

				?>
            </td>
        </tr>
        <tr>
            <td>Столбец</td>
            <td><select name="field_id" size=4>

					<?php

					field_type_option_list( 1, $col_row['field_id'] );

					?>
                </select></td>
        </tr>

		<?php

		if ( $_REQUEST['column_id'] == '' ) { // получить последний порядок сортировки

			$sql = "SELECT max(sort_order) FROM form_lists WHERE field_id=1 GROUP BY column_id ";
			$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) . $sql );
			$row        = mysqli_fetch_row( $result );
			$sort_order = $row[0];
		}

		?>

        <tr>
            <td>Порядок</td>
            <td><input type="text" name="sort_order" size="1" value="<?php echo $col_row['sort_order']; ?>">(1=first, 2=2nd, etc.)</td>
        </tr>
        <tr>
            <td>Связанный?</td>
            <td><input <?php if ( $col_row['linked'] != 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="linked" value='N'>Нет / <input <?php if ( $col_row['linked'] == 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="linked" value='Y'> Да - ссылка для просмотра полной записи

        </tr>
        <tr>
            <td>Только администратор?</td>
            <td><input <?php if ( $col_row['admin'] != 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="admin_only" value='N'>Нет / <input <?php if ( $col_row['admin'] == 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="admin_only" value='Y'> Да

        </tr>
        <tr>
            <td>Чистый формат?</td>
            <td><input <?php if ( $col_row['clean_format'] != 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="clean_format" value='N'>Нет / <input <?php if ( $col_row['clean_format'] == 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="clean_format" value='Y'> Да - Чистая пунктуация. Например. если кто-то пишет A,B,C, система изменится на A, B, C

        </tr>
        <tr>
            <td>Сортируется?</td>
            <td><input <?php if ( $col_row['is_sortable'] != 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="is_sortable" value='N'>Нет / <input <?php if ( $col_row['is_sortable'] == 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="is_sortable" value='Y'> Да - пользователи могут сортировать записи по этому столбцу при нажатии.

        </tr>
        <tr>
            <td>Выделено жирным шрифтом?</td>
            <td><input <?php if ( $col_row['is_bold'] != 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="is_bold" value='N'>Нет / <input <?php if ( $col_row['is_bold'] == 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="is_bold" value='Y'> Да

        </tr>
        <tr>
            <td>Без выделения?</td>
            <td><input <?php if ( $col_row['no_wrap'] != 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="no_wrap" value='N'>Нет / <input <?php if ( $col_row['no_wrap'] == 'Y' ) {
					echo ' checked ';
				} ?> type="radio" name="no_wrap" value='Y'> Да

        </tr>
        <tr>
            <td>Обрезать (разрезать) до:</td>
            <td><input type="text" name="truncate_length" size="2" value='<?php if ( $col_row['truncate_length'] == '' ) {
					$col_row['truncate_length'] = '0';
				}
				echo $col_row['truncate_length']; ?>' size=''> символов. (0 = не усекать)

        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="save_col" value="Сохранить"></td>
        </tr>

    </table>

</form>

<hr>
Вот столбцы, которые появятся на списоке объявлений:
<table border='0' width="99%" id='resumelist' cellspacing="1" cellpadding="5" align="center">
	<?php
	global $tag_to_field_id;
	//$tag_to_field_id = ad_tag_to_field_id_init();
	echo_list_head_data_admin( 1 );

	?>
</table>

