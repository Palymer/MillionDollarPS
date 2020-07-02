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

@set_time_limit( 180 );
require_once __DIR__ . "/../include/init.php";
require( 'admin_common.php' );

$oid = 0;
if ( $_REQUEST['mass_complete'] != '' ) {

	foreach ( $_REQUEST['orders'] as $oid ) {

		$sql = "SELECT * from orders where order_id=" . intval( $oid );
		$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
		$order_row = mysqli_fetch_array( $result );

		if ( $order_row['status'] != 'completed' ) {
			complete_order( $order_row['user_id'], $oid );
			debit_transaction( $order_row['user_id'], $order_row['price'], $order_row['currency'], $order_row['order_id'], 'complete', 'Admin' );
		}
	}
}

if ( $_REQUEST['action'] == 'complete' ) {

	$sql = "SELECT * from orders where order_id=" . intval( $_REQUEST['order_id'] );
	$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
	$order_row = mysqli_fetch_array( $result );

	complete_order( $_REQUEST['user_id'], $_REQUEST['order_id'] );
	debit_transaction( $_REQUEST['order_id'], $order_row['price'], $order_row['currency'], $order_row['order_id'], 'complete', 'Admin' );
	echo "Order completed.";
}

if ( $_REQUEST['action'] == 'cancel' ) {
	cancel_order( $_REQUEST['order_id'] );
	echo "Order cancelled.";
}

if ( $_REQUEST['mass_cancel'] != '' ) {

	echo "cancelling...";

	foreach ( $_REQUEST['orders'] as $oid ) {

		//echo "$order_id ";
		cancel_order( $oid );
	}
}

if ( $_REQUEST['action'] == 'delete' ) {

	delete_order( $_REQUEST['order_id'] );

	echo "Order deleted.";
}

if ( $_REQUEST['mass_delete'] != '' ) {

	foreach ( $_REQUEST['orders'] as $oid ) {
		delete_order( $oid );
	}
}

$q_aday     = $_REQUEST['q_aday'];
$q_amon     = $_REQUEST['q_amon'];
$q_ayear    = $_REQUEST['q_ayear'];
$q_name     = $_REQUEST['q_name'];
$q_username = $_REQUEST['q_username'];
$q_resumes  = $_REQUEST['q_resumes'];
$q_news     = $_REQUEST['q_news'];
$q_email    = $_REQUEST['q_email'];
$q_company  = $_REQUEST['q_company'];
$search     = $_REQUEST['search'];
$q_string   = urlencode( "&q_name=$q_name&q_username=$q_username&q_email=$q_email&q_aday=$q_aday&q_amon=$q_amon&q_ayear=$q_ayear&search=$search" );
?>

<form style="margin: 0" action="orders.php?search=search" method="post">
    <input type="hidden" value="<?php echo $_REQUEST['show']; ?>" name="show">
    <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse" id="AutoNumber2" width="100%">
        <tr>
            <td width="63" bgcolor="#EDF8FC" valign="top">
                <p><b>Имя</b></p>
            </td>
            <td width="286" bgcolor="#EDF8FC" valign="top">
                <input type="text" name="q_name" size="39" value="<?php echo $q_name; ?>"/></td>
            <td width="71" bgcolor="#EDF8FC" valign="top">
                <p align="right"><b>Имя</b></td>
            <td width="299" bgcolor="#EDF8FC" valign="top">
                <input type="text" name="q_username" size="28" value="<?php echo $q_username; ?>"/></td>
        </tr>
        <tr>
            <td width="63" bgcolor="#EDF8FC" valign="top">
                <p align="right"><b>Дата от:</b></td>
            <td width="286" bgcolor="#EDF8FC" valign="top">
                <select name="q_aday">
                    <option></option>
                    <option <?php if ( $q_aday == '01' ) {
						echo 'выбран';
					} ?> >1
                    </option>
                    <option <?php if ( $q_aday == '02' ) {
						echo 'выбран';
					} ?> >2
                    </option>
                    <option <?php if ( $q_aday == '03' ) {
						echo 'выбран';
					} ?> >3
                    </option>
                    <option <?php if ( $q_aday == '04' ) {
						echo 'выбран';
					} ?> >4
                    </option>
                    <option <?php if ( $q_aday == '05' ) {
						echo 'выбран';
					} ?> >5
                    </option>
                    <option <?php if ( $q_aday == '06' ) {
						echo 'выбран';
					} ?> >6
                    </option>
                    <option <?php if ( $q_aday == '07' ) {
						echo 'выбран';
					} ?>>7
                    </option>
                    <option <?php if ( $q_aday == '08' ) {
						echo 'выбран';
					} ?>>8
                    </option>
                    <option <?php if ( $q_aday == '09' ) {
						echo 'выбран';
					} ?> >9
                    </option>
                    <option <?php if ( $q_aday == '25' ) {
						echo 'выбран';
					} ?> >25
                    </option>
                    <option <?php if ( $q_aday == '26' ) {
						echo 'выбран';
					} ?> >26
                    </option>
                    <option <?php if ( $q_aday == '10' ) {
						echo 'выбран';
					} ?> >10
                    </option>
                    <option <?php if ( $q_aday == '11' ) {
						echo 'выбран';
					} ?> > 11
                    </option>
                    <option <?php if ( $q_aday == '12' ) {
						echo 'выбран';
					} ?> >12
                    </option>
                    <option <?php if ( $q_aday == '13' ) {
						echo 'выбран';
					} ?> >13
                    </option>
                    <option <?php if ( $q_aday == '14' ) {
						echo 'выбран';
					} ?> >14
                    </option>
                    <option <?php if ( $q_aday == '15' ) {
						echo 'выбран';
					} ?> >15
                    </option>
                    <option <?php if ( $q_aday == '16' ) {
						echo 'выбран';
					} ?> >16
                    </option>
                    <option <?php if ( $q_aday == '17' ) {
						echo 'выбран';
					} ?> >17
                    </option>
                    <option <?php if ( $q_aday == '18' ) {
						echo 'выбран';
					} ?> >18
                    </option>
                    <option <?php if ( $q_aday == '19' ) {
						echo 'выбран';
					} ?> >19
                    </option>
                    <option <?php if ( $q_aday == '20' ) {
						echo 'выбран';
					} ?> >20
                    </option>
                    <option <?php if ( $q_aday == '21' ) {
						echo 'выбран';
					} ?> >21
                    </option>
                    <option <?php if ( $q_aday == '22' ) {
						echo 'выбран';
					} ?> >22
                    </option>
                    <option <?php if ( $q_aday == '23' ) {
						echo 'выбран';
					} ?> >23
                    </option>
                    <option <?php if ( $q_aday == '24' ) {
						echo 'выбран';
					} ?> >24
                    </option>
                    <option <?php if ( $q_aday == '27' ) {
						echo 'выбран';
					} ?> >27
                    </option>
                    <option <?php if ( $q_aday == '28' ) {
						echo 'выбран';
					} ?> >28
                    </option>
                    <option <?php if ( $q_aday == '29' ) {
						echo 'выбран';
					} ?> >29
                    </option>
                    <option <?php if ( $q_aday == '30' ) {
						echo 'выбран';
					} ?> >30
                    </option>
                    <option <?php if ( $q_aday == '31' ) {
						echo 'выбран';
					} ?> >31
                    </option>
                </select>
                <select name="q_amon">
                    <option></option>
                    <option <?php if ( $q_amon == '01' ) {
						echo 'выбран';
					} ?> value="1">Январь
                    </option>
                    <option <?php if ( $q_amon == '02' ) {
						echo 'выбран';
					} ?> value="2">Февраль
                    </option>
                    <option <?php if ( $q_amon == '03' ) {
						echo 'выбран';
					} ?> value="3">Март
                    </option>
                    <option <?php if ( $q_amon == '04' ) {
						echo 'выбран';
					} ?> value="4">Апрель
                    </option>
                    <option <?php if ( $q_amon == '05' ) {
						echo 'выбран';
					} ?> value="5">Май
                    </option>
                    <option <?php if ( $q_amon == '06' ) {
						echo 'выбран';
					} ?> value="6">Июнь
                    </option>
                    <option <?php if ( $q_amon == '07' ) {
						echo 'выбран';
					} ?> value="7">Июль
                    </option>
                    <option <?php if ( $q_amon == '08' ) {
						echo 'выбран';
					} ?> value="8">Август
                    </option>
                    <option <?php if ( $q_amon == '09' ) {
						echo 'выбран';
					} ?> value="9">Сентябрь
                    </option>
                    <option <?php if ( $q_amon == '10' ) {
						echo 'выбран';
					} ?> value="10">Октябрь
                    </option>
                    <option <?php if ( $q_amon == '11' ) {
						echo 'выбран';
					} ?> value="11">Ноябрь
                    </option>
                    <option <?php if ( $q_amon == '12' ) {
						echo 'выбран';
					} ?> value="12">Декабрь
                    </option>
                </select>
                <input type="text" name="q_ayear" size="4" value="<?php echo $q_ayear; ?>"/>
            </td>
            <td width="71" bgcolor="#EDF8FC" valign="top"></td>
            <td width="299" bgcolor="#EDF8FC" valign="top"></td>
        </tr>
        <tr>
            <td width="731" bgcolor="#EDF8FC" colspan="4">
                <b><input type="submit" value="Find" name="B1" style="float: left"><?php if ( $_REQUEST['search'] == 'search' ) { ?>&nbsp; </b><b>[<a href="<?php echo $_SERVER['PHP_SELF'] ?>?show=<?php echo $_REQUEST['show']; ?>">Новый поиск</a>]</b><?php } ?>
            </td>
        </tr>
    </table>
</form>

<?php
if ( $_REQUEST['show'] == 'WA' ) {
	$where_sql = " AND (status ='confirmed' OR status='pending') ";
	$date_link = "&show=WA";
} else if ( $_REQUEST['show'] == 'CA' ) {
	$where_sql = " AND (status ='cancelled') ";
	$date_link = "&show=CA";
} else if ( $_REQUEST['show'] == 'EX' ) {
	$where_sql = " AND (status ='expired') ";
	$date_link = "&show=EX";
} else if ( $_REQUEST['show'] == 'DE' ) {
	$where_sql = " AND (status ='deleted') ";
	$date_link = "&show=DE";
} else if ( $_REQUEST['show'] == 'CO' ) {
	$where_sql = " AND status ='completed' ";
}

switch ( $_REQUEST['show'] ) {
	case 'WA':
		echo '<p>Показаны заказы в ожидании</p>';
		break;
	case 'CO':
		echo '<p>Показаны завершенные заказы</p>';
		break;
	case 'EX':
		echo '<p>Показаны истекшие заказы.</p>';
		break;
	case 'CA':
		echo '<p>Показаны отмененные заказы. Примечание. Блоки зарезервированы для отмененных заказов. Удалить заказ, чтобы освободить блоки.</p>';
		break;
	case 'DE':
		echo '<p>Показаны удаленные заказы.</p>';
		break;
}

$q_aday     = $_REQUEST['q_aday'];
$q_amon     = $_REQUEST['q_amon'];
$q_ayear    = $_REQUEST['q_ayear'];
$q_name     = $_REQUEST['q_name'];
$q_username = $_REQUEST['q_username'];

$q_email = $_REQUEST['q_email'];

if ( $q_name != '' ) {
	$list = preg_split( "/[\s,]+/", $q_name );
	for ( $i = 1; $i < sizeof( $list ); $i ++ ) {
		$or1 .= " OR (`FirstName` like '%" . $list[ $i ] . "%')";
		$or2 .= " OR (`LastName` like '%" . $list[ $i ] . "%')";
	}
	$where_sql .= " AND (((`FirstName` like '%$list[0]%') $or1) OR ((`LastName` like '%$list[0]%') $or2))";
}

if ( $q_username != '' ) {
	$q_username = trim( $q_username );
	$list       = preg_split( "/[\s,]+/", $q_username );
	for ( $i = 1; $i < sizeof( $list ); $i ++ ) {
		$or .= " OR (`Username` like '%" . mysqli_real_escape_string( $GLOBALS['connection'], $list[ $i ] ) . "%')";
	}
	$where_sql .= " AND ((`Username` like '%" . mysqli_real_escape_string( $GLOBALS['connection'], $list[0] ) . "%') $or)";
}

if ( $_REQUEST['user_id'] != '' ) {

	$where_sql .= " AND t1.user_id=" . intval( $_REQUEST['user_id'] );
}

if ( $_REQUEST['order_id'] != '' ) {

	echo '<h3>*** Highlighting order #' . $_REQUEST['order_id'] . '.</h3> ';
}

$sql = "SELECT * FROM orders as t1, users as t2 where t1.user_id=t2.ID $where_sql ORDER BY t1.order_date DESC  ";

//echo $sql;

$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
$count = mysqli_num_rows( $result );

$records_per_page = 40;

if ( $count > $records_per_page ) {

	mysqli_data_seek( $result, $_REQUEST['offset'] );
}

$pages    = ceil( $count / $records_per_page );
$cur_page = $offset / $records_per_page;
$cur_page ++;

?>

<form style="margin: 0;" method="post" action="orders.php?offset=<?php echo intval( $_REQUEST['offset'] ) . $q_string; ?>" name="form1">
    <input type="hidden" name="show" value="<?php echo $_REQUEST['show']; ?>">
    <input type="hidden" name="offset" value="<?php echo $_REQUEST['offset']; ?>">
    <div style="text-align: center;"><b><?php echo mysqli_num_rows( $result ); ?> Orders Returned (<?php echo $pages; ?> pages) </b></div>
	<?php
	if ( $count > $records_per_page ) {
		// calculate number of pages & current page
		$q_string .= "&show=" . urlencode( $_REQUEST['show'] );

		$label["navigation_page"] = str_replace( "%CUR_PAGE%", $cur_page, $label["navigation_page"] );
		$label["navigation_page"] = str_replace( "%PAGES%", $pages, $label["navigation_page"] );
		//	echo "<span > ".$label["navigation_page"]."</span> ";
		$nav   = nav_pages_struct( $q_string, $count, $records_per_page );
		$LINKS = 40;
		render_nav_pages( $nav, $LINKS, $q_string );
	}
	?>
    <table width="100%" cellSpacing="1" cellPadding="3" align="center" bgColor="#d9d9d9" border="0">
        <tr>
            <td colspan="12"> <?php if ( $_REQUEST['show'] != 'DE' ) { ?>
                    С выбранным:
                    <input type="submit" value='Complete' onclick="if (!confirmLink(this, 'Complete for all selected, are you sure?')) return false" name='mass_complete'>
					<?php
					if ( $_REQUEST['show'] != 'CA' ) {
						?>
                        | <input type="submit" value='Cancel' name='mass_cancel' onclick="if (!confirmLink(this, 'Cancel for all selected, are you sure?')) return false">
						<?php
					}
					if ( $_REQUEST['show'] == 'CA' ) {
						?>
                        | <input type="submit" value='Delete' name='mass_delete' onclick="if (!confirmLink(this, 'Delete for all selected, are you sure?')) return false">
						<?php
					}
				} ?></td>
        </tr>
        <tr bgcolor="#eaeaea">
            <td><input type="checkbox" onClick="checkBoxes('orders');"></td>
            <td><b>Дата заказа</b></td>
            <td><b>Имя покупателя</b></td>
            <td><b>Имя пользователя и ИД</b></td>
            <td><b>ИД заказа</b></td>
            <td><b>ИД рекламы</b></td>
            <td><b>Сетка</b></td>
            <td><b>Количество:</b></td>
            <td><b>Количество</b></td>
            <td><b>Статус</b></td>
        </tr>
		<?php
		$i = 0;
		while ( ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) && ( $i < $records_per_page ) ) {
			$i ++;

			?>
            <tr onmouseover="old_bg=this.getAttribute('bgcolor');this.setAttribute('bgcolor', '#FBFDDB', 0);" onmouseout="this.setAttribute('bgcolor', old_bg, 0);" bgColor="<?php if ( $_REQUEST['order_id'] == $row['order_id'] ) {
				echo '#FFFF99';
			} else {
				echo '#ffffff';
			} ?>">
                <td><input type="checkbox" name="orders[]" value="<?php echo $row['order_id']; ?>"></td>
                <td><?php echo get_local_time( $row['order_date'] ); ?></td>
                <td><?php echo escape_html( $row['FirstName'] . " " . $row['LastName'] ); ?></td>
                <td><?php echo $row['Username']; ?> (<a href='edit.php?user_id=<?php echo $row['ID']; ?>'>#<?php echo $row['ID']; ?></a>)</td>
                <td>#<?php echo $row['order_id']; ?></td>
                <td><a href='ads.php?ad_id=<?php echo $row['ad_id']; ?>&order_id=<?php echo $row['order_id']; ?>&BID=<?php echo $row['banner_id']; ?>'>#<?php echo $row['ad_id']; ?></a></td>
                <td><?php

					$sql = "select * from banners where banner_id=" . $row['banner_id'];
					$b_result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) . $sql );
					$b_row = mysqli_fetch_array( $b_result );

					echo $b_row['name'];

					?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo convert_to_default_currency_formatted( $row['currency'], $row['price'] ) ?></td>
                <td><?php echo $row['status']; ?><br>
					<?php
					if ( $row['status'] == 'cancelled' ) {
						$sql = "select * from transactions where type='CREDIT' and order_id=" . intval( $row['order_id'] );
						$r1 = mysqli_query( $GLOBALS['connection'], $sql ) or die( mysqli_error( $GLOBALS['connection'] ) );
						if ( mysqli_num_rows( $r1 ) > 0 ) {
							$refunded = true;
							echo "(Refunded)";
						} else {

							$refunded = false;
						}
					}
					?>
					<?php if ( ( $row['status'] != 'completed' ) && ( $row['status'] != 'deleted' ) && ! $refunded ) { ?>
                        <input type="button" style="font-size: 9px;" value="Complete" onclick="if (!confirmLink(this, 'Payment from <?php echo str_replace( "'", "\\'", escape_html( $row['LastName'] ) ) . ", " . str_replace( "'", "\\'", escape_html( $row['FirstName'] ) ); ?> to be completed. Order for <?php echo $row['price']; ?> will be credited to their account.\n ** Are you sure? **')) return false;" data-link="orders.php?action=complete&user_id=<?php echo $row['ID'] ?>&order_id=<?php echo $row['order_id'] . $date_link . $q_string . "&show=" . $_REQUEST['show']; ?>"> / <?php }
					if ( $row['status'] == 'cancelled' ) { ?>
                        <input type="button" style="font-size: 9px;" value="Delete" onclick="if (!confirmLink(this, 'Delete the order from <?php echo str_replace( "'", "\\'", escape_html( $row['LastName'] ) ) . ", " . str_replace( "'", "\\'", escape_html( $row['FirstName'] ) ); ?>, are you sure?')) return false;" data-link="orders.php?action=delete&order_id=<?php echo $row['order_id'] . $date_link . $q_string . "&show=" . $_REQUEST['show']; ?>">
						<?php
					} else if ( $row['status'] == 'deleted' ) {

					} else { ?>
                        <input type="button" style="font-size: 9px;" value="Cancel" onclick="if (!confirmLink(this, 'Cancel the order from <?php echo str_replace( "'", "\\'", escape_html( $row['LastName'] ) ) . ", " . str_replace( "'", "\\'", escape_html( $row['FirstName'] ) ); ?>, are you sure?')) return false;" data-link="orders.php?action=cancel&user_id=<?php echo $row['ID'] ?>&order_id=<?php echo $row['order_id'] . $date_link . $q_string . "&show=" . $_REQUEST['show']; ?>">
					<?php } ?>
                </td>
            </tr>
			<?php
		}
		?>
    </table>
</form>
