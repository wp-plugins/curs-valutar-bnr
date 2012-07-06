<?php
/**
 * @package Curs Valutar BNR
 * @author Petre Anghel - email: petre@zing.ro
 * @version 1.1
 */
/*
Plugin Name: Curs Valutar BNR
Plugin URI: http://cursul-valutar.net
Description: Plugin that displays the Romanian exchange rates. Preluare si afisare curs valutar BNR zilnic.
Author: Petre Anghel
Version: 1.0
Author URI: http://cursul-valutar.net
*/


function meniu_curs_valutar()
{
    $file = __file__;

    add_submenu_page('plugins.php', 'Curs Valutar BNR', 'Curs Valutar BNR', 10, $file,
        'generate_curs_valutar_admin');
}


function generate_curs_valutar_admin()
{
    global $write_keywords_to_db_file;
    echo "<h3>Optiuni afisare curs valutar BNR</h3>";


?>

    



<div class="wrap">
<h2>Curs Valutar BNR</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table>

<tr>
<td><b>Titlu Preferat: &nbsp;  </b></td>
<td><input type="text" name="titlu_preferat_curs_valutar" value="<?php

    $value_titlu_preferat_curs_valutar = get_option('titlu_preferat_curs_valutar');
    if ($value_titlu_preferat_curs_valutar == "") {
        $value_titlu_preferat_curs_valutar = "Curs Valutar BNR";
    }

    echo $value_titlu_preferat_curs_valutar;
?>" /></td>
</tr>
</table>

<style>
/*------------------------------------------ style tabel valute*/
table.valute {
   border-collapse: collapse;
   font-size:12px;
   text-align:center;	
   color: #073e5a;
   width: 350px;
}
table.valute td {
   border: 1px solid #ccc;
   padding:2px;
}
table.valute tr a{
   color: #073e5a;
}
table.valute tr:link, table.navbar tr:visited {
   background-color: #fff;
}
table.valute tr:hover, table.navbar tr:active {
	background-color: #9FEFFF;
}
/*-----------------------------------style tabel  valute sfarsit*/

</style>

<table width="700"><tr><td valign="top">

<p>Selectati valutele dorite pentru afisare:</p>

<table class="valute">

<?
    $valute_presc = array(0 => 'EUR', 1 => 'USD', 2 => 'XAU', 3 => 'AUD', 4 => 'BGN',
        5 => 'CAD', 6 => 'CHF', 7 => 'CZK', 8 => 'DKK', 9 => 'EGP', 10 => 'GBP', 11 =>
        'HUF', 12 => 'JPY', 13 => 'MDL', 14 => 'NOK', 15 => 'PLN', 16 => 'RUB', 17 =>
        'SEK', 18 => 'TRY', 19 => 'XDR');
    $valute_text = array(0 => 'Euro', 1 => 'Dolar SUA', 2 => 'gramul de aur', 3 =>
        'Dolar australian', 4 => 'Leva bulgareasca', 5 => 'Dolar canadian', 6 =>
        'Franc elvetian', 7 => 'Coroana ceha', 8 => 'Coroana daneza', 9 =>
        'Lira egipteana', 10 => 'Lira sterlina', 11 => '100 Florinti maghiari', 12 =>
        '100 Yeni Japonezi', 13 => 'Leu moldovenesc', 14 => 'Coroana norvegiana', 15 =>
        'Zlot polonez', 16 => 'Rubla ruseasca', 17 => 'Coroana suedeza', 18 =>
        'Lira turceasca', 19 => 'DST');
    $valute_alese = get_option('valute_alese');
    if ($valute_alese == "") {
        $valute_alese = array("EUR", "USD", "XAU");
    }
    $i = 0;
    while ($i < 20) {
        $valuta_presc = $valute_presc[$i];
        $text_valuta = $valute_text[$i];

        if (in_array($valuta_presc, $valute_alese)) {
            $checked = "checked='checked'";
        } else {
            $checked = "";
        }

        echo "<tr><td><img src=\"http://cursul-valutar.net/drapele/" . strtolower($valuta_presc) .
            ".gif\" /></td><td>$valuta_presc</td><td>$text_valuta</td><td><input type='checkbox' name='valute_alese[]' value='$valuta_presc' $checked /></td></tr>";

        $i++;
    }


?>

</table>
<br /><br />

</td><td>

<p><b>Culoarea textului:</b></p>

<?
    $culoare_curs_valutar = get_option('culoare_curs_valutar');
    if ($culoare_curs_valutar == "") {
        $culoare_curs_valutar = "06367E";
    }
?>

<br />
<script src="http://cursul-valutar.net/cp/script.js" type="text/javascript"></script>
<input type="text" class="color" name="culoare_curs_valutar" value="<?= $culoare_curs_valutar; ?>" /> 

<table><tr><td>
<b>Latime modul:</b> 
</td><td>
<select name="latime_curs_valutar" onchange="this.form.submit();">

<?
    $latime_curs_valutar = get_option('latime_curs_valutar');
    if ($latime_curs_valutar == "") {
        $latime_curs_valutar = "160";
    }
    if ($latime_curs_valutar == 200) {
        $selected_200 = "selected";
    }
    if ($latime_curs_valutar == 160) {
        $selected_160 = "selected";
    }
    if ($latime_curs_valutar == 120) {
        $selected_120 = "selected";
    }


?>

<option value='200' <?= $selected_200 ?>>&nbsp; 200 px &nbsp;</option>
<option value='160' <?= $selected_160 ?>>&nbsp; 160 px &nbsp;</option>
<option value='120' <?= $selected_120 ?>>&nbsp; 120 px &nbsp;</option>
</select>
</td></tr></table>

<br />

<h3>Preview Modul Curs Valutar:</h3>
<?
    foreach ($valute_alese as $valuta_presc) {
        if ($string_valute == "") {
            $string_valute = $valuta_presc;
        } else {

            $string_valute .= "-" . $valuta_presc;
        }
    }

?>
<!-- START cod - cursul-valutar.net -->
<a href="http://cursul-valutar.net/" title="Curs Valutar" style="text-decoration: none; font-size:18px; font-family: Tahoma; color:#<?= str_replace("#",
    "", $culoare_curs_valutar); ?>;"><?= $value_titlu_preferat_curs_valutar; ?></a>
<script language="JavaScript" src="http://cursul-valutar.net/f1.php?cul=<?= str_replace("#",
    "", $culoare_curs_valutar); ?>&val=<?= $string_valute ?>&w=<?= $latime_curs_valutar ?>" type="text/javascript">
</script><noscript><a href="http://cursul-valutar.net" title="curs valutar">Curs Valutar BNR</a></noscript>
<!-- END cod - cursul-valutar.net -->

</td></tr></table>


<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="titlu_preferat_curs_valutar,valute_alese,latime_curs_valutar,culoare_curs_valutar" />

<table width="400"><tr><td align="center">
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</td></tr></table>

</form>
<br /><br />
</div>

<?

}


function afisare_curs_valutar()
{
    $culoare_curs_valutar = get_option('culoare_curs_valutar');
    if ($culoare_curs_valutar == "") {
        $culoare_curs_valutar = "06367E";
    }
    $value_titlu_preferat_curs_valutar = get_option('titlu_preferat_curs_valutar');
    if ($value_titlu_preferat_curs_valutar == "") {
        $value_titlu_preferat_curs_valutar = "Curs Valutar BNR";
    }
    $valute_alese = get_option('valute_alese');
    if ($valute_alese == "") {
        $valute_alese = array("EUR", "USD", "XAU");
    }
    foreach ($valute_alese as $valuta_presc) {
        if ($string_valute == "") {
            $string_valute = $valuta_presc;
        } else {

            $string_valute .= "-" . $valuta_presc;
        }
    }
    $latime_curs_valutar = get_option('latime_curs_valutar');
    if ($latime_curs_valutar == "") {
        $latime_curs_valutar = "160";
    }
    if ($latime_curs_valutar == 200) {
        $selected_200 = "selected";
    }
    if ($latime_curs_valutar == 160) {
        $selected_160 = "selected";
    }
    if ($latime_curs_valutar == 120) {
        $selected_120 = "selected";
    }
?>
<!-- START cod - cursul-valutar.net -->
<a href="http://cursul-valutar.net/" title="Curs Valutar" style="text-decoration: none; font-size:18px; font-family: Tahoma; color:#<?= str_replace("#",
    "", $culoare_curs_valutar); ?>;"><?= $value_titlu_preferat_curs_valutar; ?></a>
<script language="JavaScript" src="http://cursul-valutar.net/f1.php?cul=<?= str_replace("#",
    "", $culoare_curs_valutar); ?>&val=<?= $string_valute ?>&w=<?= $latime_curs_valutar ?>" type="text/javascript">
</script><noscript><a href="http://cursul-valutar.net" title="curs valutar">Curs Valutar BNR</a></noscript>
<!-- END cod - cursul-valutar.net -->
<?
}

// Now we set that function up to execute when the admin_footer action is called
//add_action('wp_footer', 'afisare_curs_valutar');

add_action('admin_menu', 'meniu_curs_valutar');


function widget_cursvalutarbnr_register()
{

    function widget_cursvalutarbnr($args)
    {
        extract($args);
?>
              <?php echo $before_widget; ?>
                  <?php //echo $before_title . 'Curs Valutar BNR' . $after_title; ?>
                  <? afisare_curs_valutar(); ?>
              <?php echo $after_widget; ?>
      <?php
    }
    function widget_cursvalutarbnr_control()
    {
        /*
        $options = $newoptions = get_option('widget_akismet');
        if ($_POST["akismet-submit"]) {
        $newoptions['title'] = strip_tags(stripslashes($_POST["akismet-title"]));
        if (empty($newoptions['title']))
        $newoptions['title'] = 'Spam Blocked';
        }
        if ($options != $newoptions) {
        $options = $newoptions;
        update_option('widget_akismet', $options);
        }
        $title = htmlspecialchars($options['title'], ENT_QUOTES);
        */
		?>
		<br />
		<a href="plugins.php?page=curs_valutar_bnr.php" >Editare Optiuni Afisare Curs Valutar BNR</a>
		<br />
        <br />
        <br />
        <?php

    }
    register_sidebar_widget('Curs Valutar BNR', 'widget_cursvalutarbnr');
    register_widget_control('Curs Valutar BNR', 'widget_cursvalutarbnr_control', null, 75,
        'curs_valutar_bnr');

}

function cursvalutarbnr_filter_plugin_actions($links, $file)
{
    //Static so we don't call plugin_basename on every plugin row.
    static $this_plugin;
    if (!$this_plugin)
        $this_plugin = plugin_basename(__file__);

    if ($file == $this_plugin) {
        $settings_link = '<a href="plugins.php?page=curs_valutar_bnr.php">' . __('Settings') .
            '</a>';
        array_unshift($links, $settings_link); // before other links
    }
    return $links;
}
add_filter('plugin_action_links', 'cursvalutarbnr_filter_plugin_actions', 10, 2);
add_action('init', widget_cursvalutarbnr_register);


?>
