<?php
/**
 * @package       mds
 * @copyright     (C) Copyright 2020 Ryan Rhode, All rights reserved.
 * @author        Ryan Rhode, ryan@milliondollarscript.com
 * @version       2020.05.13 12:41:16 EDT
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

ini_set( "session.use_trans_sid", false );

require_once __DIR__ . "/../include/init.php";

require( 'admin_common.php' );

$label = array();

$sql = "SELECT * FROM lang WHERE lang_code='" . mysqli_real_escape_string( $GLOBALS['connection'], $_REQUEST['target_lang'] ) . "' ";
$result = mysqli_query( $GLOBALS['connection'], $sql ) or die ( mysqli_error( $GLOBALS['connection'] ) );
$row = mysqli_fetch_array( $result );

$lang_filename = $row['lang_filename'];
$lang_name     = $row['name'];
echo "lang filename: $lang_filename ";

require( BASE_PATH . "/lang/english_default.php" );
$source_label = $label; // default english labels

if ( file_exists( BASE_PATH . "/lang/" . $lang_filename ) ) {
    require( BASE_PATH . "/lang/" . $lang_filename );
    $dest_label = $label; // dest labels
}

//print_r($dest_label);
// preload the source code, preg the hash key and use it as a key for the line 
//$source_code = array();
//$handle = fopen("../lang/english_default.php", "r");
//while ($buffer= fgets($handle, 4096)) {
//	if (preg_match ('/ *\$label\[.([A-z0-9]+).\].*/', $buffer, $m)) {
//		$source_code[$m[1]] = $buffer;
//	}
//}

if ( $_REQUEST['save'] != '' ) {
	$out = "<?php\n";
	$out .= 'global $label;' . "\n";
	foreach ( $source_label as $key => $val ) {
		$_REQUEST[ $key ]   = str_replace( '\\"', '"', $_REQUEST[ $key ] );
		$value              = addslashes( $_REQUEST[ $key ] );
		$out                .= "\$label['$key']='" . $value . "'; \n";
		$dest_label[ $key ] = $value;
	}
	$out     .= "?>\n";
	$handler = fopen( "../lang/" . $lang_filename, "w" );
	fputs( $handler, $out );
	// load the new labels
	//require( "../lang/" . $lang_filename );
	//$dest_label = $label; // dest labels
}

?>

<h3>
    Инструмент языкового перевода.</h3>
<b>ВАЖНЫЙ:</b> Сделайте резервную копию ваших языковых файлов перед использованием этого инструмента! Этот инструмент перезапишет любой код в целевом файле с помощью машинного кода.<br>
<pre>
ИНСТРУКЦИИ

1. Строки слева - это оригинальные английские строки.
Строки справа предназначены для редактирования.
2. Нажатие любой из кнопок Сохранить сохраняет все поля в поле from.
Вы можете нажать на них в любое время, чтобы сохранить всю форму.
3. В некоторых полях есть переменные, такие как% SITE_NAME%. Эти переменные подставляются.
Проверьте исходную строку слева, чтобы увидеть, какие переменные доступны.
4. HTML разрешен.
5. Если вы хотите использовать такие символы, как &gt; &lt; или &amp;,
обязательно запишите их как HTML-сущности: &amp;gt; &amp;lt; и &amp;amp;
</pre>
<?php

if ( ! is_writeable( "../lang/" . $lang_filename ) ) {
	print ( "<span style='color:red'><b>Ошибка:</b></span> Файл ../lang/" . $lang_filename . " не доступно для записи Вы должны дать ему разрешение на запись, чтобы изменения вступили в силу. Вы можете установить права доступа только для чтения после сохранения изменений.<br>" );
}

?>
<form method="POST" name="form1" action="translation_tool.php">

    <input type="hidden" name="target_lang" value="<?php echo $_REQUEST['target_lang'] ?>">

    <table style="margin:0 auto;width:calc(100% - 205px);border:none;padding:3px;background:#d9d9d9;">
        <tr style="background:#eaeaea">
            <td><b>Исходный язык: английский (заводской стандарт english_default.php)</b><br><br></td>
            <td><b>Язык перевода: <?php echo $lang_name; ?> (<?php echo $lang_filename; ?>)</b><br><br></td>
        </tr>

		<?php

		$i        = 0;
		$bg_color = "";
		foreach ( $source_label as $key => $val ) {
			$i ++;

			$val = stripslashes( $val );
			if ( $bg_color == "#ffffff" ) {
				$bg_color = "#FFFFff";
			} else {
				$bg_color = "#ffffff";
			}

			?>
            <tr style="background:#E8E8E8">
                <td colspan="2"><small><b><?php echo $key; ?></b></small><br>
                    <span style="font-size: 10px; white-space: normal;"><?php $str = highlight_string( "<?php " . ( $val ) . " ?>", true ); ?><span>
                </td>

            </tr>
            <tr style="background:<?php echo $bg_color; ?>">
                <td style="vertical-align: top;max-width:500px;"><?php
					if ( strpos( $key, 'email_temp' ) ) {
						echo "<pre>" . htmlentities( $val ) . "</pre><br>";
					} else {
						echo "" . htmlentities( $val ) . "<br>";
					}
					?></td>
                <td style="vertical-align: top;max-width:500px;">
	                <textarea
                            style="font-family: Arial,sans-serif; font-size: 12px;"
                            cols="90"
                            rows="15"
                            name='<?php echo $key ?>'
                    ><?php
		                $text = ( stripslashes( $dest_label[ $key ] ) );
		                echo $text;
		                ?></textarea>
                </td>
            </tr>
			<?php
			if ( $i > 5 ) {

				echo "<tr style='background:#BDD5E6'><td></td><td><input type='submit' name='save' value='Сохранить'></td></tr>";
				$i = 0;
			}
		}

		if ( $i > 0 ) {
			?>
            <tr style='background:#BDD5E6'>
                <td></td>
                <td><input type='submit' name='save' value='Сохранить'></td>
            </tr>
			<?php
		}
		?>
    </table>
</form>
