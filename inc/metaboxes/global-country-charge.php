<?php
add_action('admin_menu', 'country_menu');

function country_menu()
{
    add_menu_page('Country options', 'Country List', 'administrator', 'country', 'country_options_page');
}
function country_options_page()
{
    $updated = 0;
    if (isset($_REQUEST['submit'])) {
        $allCountries = WC()->countries->get_shipping_countries();
        foreach ($allCountries as $country_code => $country) {
            update_option($country_code . '_five', $_REQUEST[$country_code . '_five']);
            update_option($country_code . '_more', $_REQUEST[$country_code . '_more']);
        }
        $updated = 1;
    }
?>
    <?php if ($updated == 1) { ?>
        <div class="updated" style="margin-top: 10px;">
            <p>Details Updated Successfully</p>
        </div>
    <?php } ?>
    <div id="usual2" class="usual">
        <form name="options" id="options" action="" method="post">
            <table>
                <h1>Country options</h1>
                <th>Country</th>
                <th>Price of First 500g</th>
                <th>Price of Additional 500g</th>
                <?php
                $allCountries = WC()->countries->get_shipping_countries();
                foreach ($allCountries as $country_code => $country) { ?>
                    <tr>
                        <td style="width:25%;text-align: left;"><?php echo $country; ?> :</td>
                        <td><input type="text" name="<?php echo $country_code; ?>_five" id="<?php echo $country_code; ?>_five" value="<?php echo get_option($country_code . '_five'); ?>" /></td>
                        <td><input type="text" name="<?php echo $country_code; ?>_more" id="<?php echo $country_code; ?>_more" value="<?php echo get_option($country_code . '_more'); ?>" /></td>
                    </tr>
                <?php } ?>
            </table>
            <br style="clear:both;" />
            <input type="submit" class="btn" name="submit" value="Save Options" style="margin-top:20px;" />
        </form>
    </div>
<?php } ?>
