<?php

global $wpdb;

if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-forecast-settings'] ) ) {
		update_option( 'sp.settings.force_zero_price_products', intval( isset( $_POST['force_zero_price_products'] ) && strtolower( $_POST['force_zero_price_products'] ) === 'on' ) );

		$default_weeks_of_stock = sanitize_text_field( $_POST['default-weeks-of-stock'] );

		if ( is_numeric( $default_weeks_of_stock ) && $default_weeks_of_stock > 0 ) {
			update_option( 'sp.settings.default_weeks_of_stock', $default_weeks_of_stock = (int) $default_weeks_of_stock );
			$wpdb->query( "UPDATE `{$wpdb->product_settings}`
                SET `sp_weeks_of_stock` = {$default_weeks_of_stock}
                WHERE `sp_weeks_of_stock` = 0" );
		}

		$default_lead_time = sanitize_text_field( $_POST['default-lead-time'] );
		if ( is_numeric( $default_lead_time ) && $default_lead_time > 0 ) {
			update_option( 'sp.settings.default_lead_time', $default_lead_time = (int) $default_lead_time );
			$wpdb->query( "UPDATE `{$wpdb->product_settings}`
                SET `sp_lead_time` = {$default_lead_time}
                WHERE `sp_lead_time` = 0" );
		}
	}
}

require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

?>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
		<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
                <!-- container opened -->
                <div class="container">
	                <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sp-settings-forecast-table {
                            width: 60%;
                        }

                        .sp-settings-forecast-table td {
                            padding-bottom: 2em;
                        }
                    </style>
                    <h4><?php echo  __( 'Settings', QA_MAIN_DOMAIN ); ?></h4>
                    <div class="card">
                        <div class="card-body">
                            <h4><?php echo  __( 'Forecast Settings', QA_MAIN_DOMAIN ); ?></h4>
                            <p class="mg-b-20"></p>
                            <form method="post">
                                <table class="sp-settings-forecast-table">
                                    <tr>
                                        <td style="width: 60%;"><?php echo  __( 'Default Weeks of Stock', QA_MAIN_DOMAIN ); ?>
                                        </td>
                                        <td>
                                            <input type="number" name="default-weeks-of-stock" value="<?php echo  esc_attr( get_option( 'sp.settings.default_weeks_of_stock', 6 ) ) ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo  __( 'Default Lead Time', QA_MAIN_DOMAIN ); ?></td>
                                        <td>
                                            <input type="number" name="default-lead-time" value="<?php echo  esc_attr( get_option( 'sp.settings.default_lead_time', 1 ) ) ?>"/>
                                        </td>
                                    </tr>
                                </table>
                                <p class="mg-b-20"></p>
                                <p style="font-size: inherit"><input type="checkbox" id="id-force-zero-price-products" name="force_zero_price_products"
										<?php echo  ( get_option( 'sp.settings.force_zero_price_products', true ) ? ' checked="checked"' : '' ) ?>> <label for="id-force-zero-price-products" style="font-weight: normal"> <?php echo  __( 'Add Force include products with zero cost price?', QA_MAIN_DOMAIN ) ?></label></p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success" value="<?php echo  __( 'Save Settings', QA_MAIN_DOMAIN ); ?>" name="save-forecast-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>