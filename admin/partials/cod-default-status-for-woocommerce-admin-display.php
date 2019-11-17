<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://woofx.kaizenflow.xyz
 * @since      1.0.0
 *
 * @package    Cod_Default_Status_For_Woocommerce
 * @subpackage Cod_Default_Status_For_Woocommerce/admin/partials
 */
?>

<h3 class="wc-settings-sub-title"><?php echo wp_kses_post( __( 'COD Default Status', 'cod-default-status-for-woocommerce' ) ); ?></h3>

<table class="form-table wcp-conditions-table" data-id="<?php echo $gateway->id ?>">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="woocommerce_cod_enabled">Default Order Status </label>
            </th>
            <td class="forminp">
                <select name='<?php echo $_option_name_status ?>' id='<?php echo $_option_name_status ?>'>
                <?php foreach($statuses as $id=>$title): ?>
                    <option value='<?php echo $id ?>' <?php selected($id,$_option_status) ?>>
                        <?php echo $title ?>
                    </option>
                <?php endforeach ?>
                </select>
            </td>
		</tr>
        
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo $_option_name_inventory ?>">Inventory Changes</label>
			</th>
            <td class="forminp">
                <fieldset>
                    <legend class="screen-reader-text"><span>Reduce Stock</span></legend>

                    <label for='<?php echo $_option_name_inventory ?>'>
                        <input type='checkbox' name='<?php echo $_option_name_inventory ?>' id='<?php echo $_option_name_inventory ?>' <?php checked($_option_inventory,1) ?> value="1" >
                        Do not reduce Inventory Stock
                    </label>

                    <?php if($_option_status=='wc-processing'): ?>
                    <p style='color:red'>Not applicable, if default order status is set to 'Processing'.</p>
                    <?php endif ?>

                    <p class="description">
                        By default WooCommerce reduces stock on COD orders. If you <strong>don't want to reduce stock</strong>, check the option above.
                    </p>
                    <p class="description">
                        If checked, <strong>inventory will be reduced only when it is set to 'Processing'</strong>, usually by an admin or store manager.
                    </p>
						
				</fieldset>
			</td>
		</tr>

	</tbody>
</table>