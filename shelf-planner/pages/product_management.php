<?php

/**
 * Product Management page, like /wp-admin/admin.php?page=shelf_planner_product_management
 * Here are debug, import, export functions are located
 */

if ( $_POST && isset( $_POST['action'] ) ) {
	if ( $_POST['action'] == 'download_sample' ) {
		/**
		 * Make sample file to prepare import
		 */
		$sample_data = [
			[ 'product_id' ] + array_keys( QAMain_Core::get_products_settings_list() ),
		];
		foreach ( QAMain_Core::get_all_product_ids() as $row ) {
			$product_id = $row['product_id'];
			$tmp        = [ 'product_id' => $product_id ];
			foreach ( QAMain_Core::get_products_settings_list() as $key => $description ) {
				$tmp[ $key ] = '';
				if ( $key == 'sp_primary_category' ) {
					$tmp[ $key ] = (string) QAMain_Core::get_product_primary_category_id( $product_id );
				}
			}
			$sample_data[] = $tmp;
		}

		$writer = new XLSXWriter();
		$writer->writeSheet( $sample_data );

		$file_name = $_SERVER['HTTP_HOST'] . '_Products_Settings_Sample_' . time() . '.xlsx';
		$file_path = __DIR__ . DIRECTORY_SEPARATOR . $file_name;
		$writer->writeToFile( $file_path );
		header( "Location: " . plugin_dir_url( __FILE__ ) . $file_name );
		exit;
	} elseif ( $_POST['action'] == 'export' ) {
		/**
		 * Make export of all the current settings
		 */
		$data = QAMain_Core::get_all_product_settings();

		foreach ( $data as $key => $item ) {
			unset( $data[ $key ]['setting_id'] );
		}

		/**
		 * If no settings yet
		 */
		if ( ! $data ) {
			$sample_data = [
				[ 'product_id' ] + array_keys( QAMain_Core::get_products_settings_list() ),
			];
		} else {
			$sample_data = [
				array_keys( $data[0] ),
			];
			foreach ( $data as $key => $item ) {
				$sample_data[] = $item;
			}
		}

		$writer = new XLSXWriter();
		$writer->writeSheet( $sample_data );

		$file_name = $_SERVER['HTTP_HOST'] . '_Products_Settings_Export_' . date( 'd.m.Y_H:i:s' ) . '.xlsx';
		$file_path = SP_ROOT_DIR . DIRECTORY_SEPARATOR . $file_name;
		$writer->writeToFile( $file_path );
		header( "Location: " . plugin_dir_url( SP_FILE_INDEX ) . $file_name );
		exit;
	}
}

require_once SP_ROOT_DIR . '/pages/admin_page_header.php';

?><?php require_once SP_ROOT_DIR . '/header.php'; ?>
    <div class="sp-admin-overlay">
        <div class="sp-admin-container">
			<?php include SP_ROOT_DIR . "/left_sidebar.php"; ?>
            <!-- main-content opened -->
            <div class="main-content horizontal-content">
                <div class="page">
                    <!-- container opened -->
                    <div class="container">
                        <h4><?= __( 'Product Management', QA_MAIN_DOMAIN ); ?></h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?= __( 'Settings Import/Export', QA_MAIN_DOMAIN ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <div style="width: 100%;clear: both; float: left;">
                                            <form action="" method="POST" style="max-width: 200px; float: left; margin-right: 10px;">
                                                <p>
                                                    <input type="hidden" required name="action" value="download_sample"/> <input type="hidden" required name="redirect" value="<?= esc_attr( $_SERVER['REQUEST_URI'] ); ?>"/> <input type="submit" class="button button-primary" value="<?= __( 'Download Sample', QA_MAIN_DOMAIN ); ?>"/>
                                                </p>
                                            </form>
                                            <div style="max-width: 200px; float: left; margin-right: 10px;">
                                                <p>
                                                    <a href="<?= plugin_dir_url( SP_FILE_INDEX ); ?>upload_settings_xlsx.php?TB_iframe=true&width=600&height=100" target="_blank"> <input type="button" class="button button-primary" value="<?= __( 'Import', QA_MAIN_DOMAIN ); ?>"/> </a>
                                                </p>
                                            </div>
                                            <form action="" method="POST" style="max-width: 200px; float: left; margin-right: 10px;">
                                                <p>
                                                    <input type="hidden" required name="action" value="export"/> <input type="hidden" required name="redirect" value="<?= esc_attr( $_SERVER['REQUEST_URI'] ); ?>"/> <input type="submit" class="button button-primary" value="<?= __( 'Export', QA_MAIN_DOMAIN ); ?>"/>
                                                </p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?= __( 'Get Product Info', QA_MAIN_DOMAIN ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <p><?= __( 'Use this tool to view settings and real-time Forecast API data per product.', QA_MAIN_DOMAIN ); ?></p>
                                        <form action="" method="get">
                                            <p>
                                                <input type="hidden" required name="page" value="<?= esc_attr( $_GET['page'] ) ?>"/> <input type="text" required name="product_id" placeholder="Product ID/SKU" value="<?= isset( $_GET['product_id'] ) ? (int) $_GET['product_id'] : ''; ?>"/> <input type="submit" class="button button-primary" value="<?= __( 'Search', QA_MAIN_DOMAIN ); ?>"/>
                                            </p>
                                        </form>
										<?php if ( isset( $_GET['product_id'] ) && $product_id = (int) $_GET['product_id'] && $product_settings = QAMain_Core::get_product_settings( $_GET['product_id'] ) ) {
											$primary_category_id = QAMain_Core::get_product_primary_category_id( $product_id );
											$forecast            = QAMain_Core::get_sales_forecast_by_product_id( $product_id );
											$forecast_by_weeks   = $forecast['WeeklySlesArray'];
											?>
                                            <table class="wp-list-table widefat striped">
                                                <tr>
                                                    <th>
                                                        <b><?= __( 'General Info', QA_MAIN_DOMAIN ); ?>
                                                        </b></th>
                                                    <th><b><?= __( 'Value', QA_MAIN_DOMAIN ); ?></b>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?= __( 'Product Title', QA_MAIN_DOMAIN ); ?></td>
                                                    <td>
                                                        <span><?= esc_html( get_post( $product_id )->post_title ); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?= __( 'Product Link', QA_MAIN_DOMAIN ); ?></td>
                                                    <td><a target="_blank" href="<?= esc_attr( get_post_permalink( $product_id ) ); ?>"><?= esc_html( get_post_permalink( $product_id ) ); ?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?= __( 'Primary Category', QA_MAIN_DOMAIN ); ?></td>
                                                    <td>
                                                        <span><?= esc_html( get_term_by( 'id', $primary_category_id, 'product_cat' )->name ); ?> (ID: <?= (int) $primary_category_id; ?>)</span>
                                                    </td>
                                                </tr>
                                            </table><br>
                                            <table class="wp-list-table widefat striped">
                                                <tr>
                                                    <th>
                                                        <b><?= __( 'Real Time Data', QA_MAIN_DOMAIN ); ?></b>
                                                    </th>
                                                    <th>
                                                        <b><?= __( 'Value', QA_MAIN_DOMAIN ); ?></b>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;">
														<?= __( 'Variation (Child) Product?', QA_MAIN_DOMAIN ); ?>
                                                    </td>
                                                    <td>
														<?php if ( $product_settings['parent_id'] ) { ?><?= __( 'This is a variation (child) product.', QA_MAIN_DOMAIN ); ?>
                                                            <a href="<?= esc_attr( admin_url( 'admin.php?page=shelf_planner_product_management&product_id=' . $product_settings['parent_id'] ) ); ?>" target="_blank"><?= __( 'Parent product info', QA_MAIN_DOMAIN ); ?></a>
														<?php } else { ?><?= __( 'No.', QA_MAIN_DOMAIN ); ?><?php

															$product          = wc_get_product( $product_settings['product_id'] );
															$current_products = $product->get_children();

															if ( $current_products ) {
																echo __( 'Found ' . count( $current_products ) . ' variations. Variations info: ', QA_MAIN_DOMAIN );
																foreach ( $current_products as $variation_id ) {
																	?>
                                                                    [ <a href="<?= esc_attr( admin_url( 'admin.php?page=shelf_planner_product_management&product_id=' . $variation_id ) ); ?>" target="_blank"><?= (int) $variation_id ?></a> ]
																	<?php
																}
															}

															?><?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;">
														<?= __( 'Ideal Stock', QA_MAIN_DOMAIN ); ?>
                                                        <span style="color: red"><?= __( '(DEBUG)', QA_MAIN_DOMAIN ); ?></span>
                                                    </td>
                                                    <td>
														<?php

														$stock_weeks_total = $product_settings['sp_weeks_of_stock'] + $product_settings['sp_lead_time'];
														$ideal_stock       = 0;
														foreach ( range( 0, $stock_weeks_total - 1 ) as $week_id ) {
															$ideal_stock += $forecast_by_weeks[ $week_id ];
														}

														?>
                                                        <code>Based on [Cover (<?= (int) $product_settings['sp_weeks_of_stock'] ?>) + Leadtime (<?= (int) $product_settings['sp_lead_time'] ?>) =
															<?= (int) $stock_weeks_total; ?> Weeks] = SUM(<?= esc_html( implode( ', ', array_slice( $forecast_by_weeks, 0, $stock_weeks_total ) ) ); ?>) = <?= (int) $ideal_stock; ?> </code></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?= __( 'Sales Forecast by Weeks', QA_MAIN_DOMAIN ); ?>
                                                    </td>
                                                    <td><textarea readonly style="width: 100%" rows="6"><?php foreach ( $forecast_by_weeks as $week => $week_value ) { ?>Week <?= (int) $week + 1; ?>: <?= (int) $week_value . PHP_EOL; ?><?php } ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?= __( 'Raw Forecast Data', QA_MAIN_DOMAIN ); ?>
                                                        <span style="color: red"><?= __( '(DEBUG)', QA_MAIN_DOMAIN ); ?></span></td>
                                                    <td><textarea readonly style="width: 100%" rows="6"><?= json_encode( $forecast ); ?></textarea>
                                                    </td>
                                                </tr>
                                            </table><br>
                                            <table class="wp-list-table widefat striped">
                                                <tr>
                                                    <th><b><?= __( 'Setting', QA_MAIN_DOMAIN ); ?></b></th>
                                                    <th><b><?= __( 'Value', QA_MAIN_DOMAIN ); ?></b>
                                                    <th><b><?= __( 'Setting', QA_MAIN_DOMAIN ); ?></b></th>
                                                    <th><b><?= __( 'Value', QA_MAIN_DOMAIN ); ?></b>
                                                    </th>
                                                </tr>
												<?php $i = 0; ?>
												<?php foreach ( QAMain_Core::get_products_settings_list() as $key => $description ) { ?><?php if ( $i == 0 ) { ?><tr><?php } ?>
                                                    <td style="width: 400px;"><?= esc_html( $description ); ?></td>
                                                    <td>
                                                        <span class="badge badge-success"><?= ( trim( $product_settings[ $key ] ) ? esc_html( $product_settings[ $key ] ) : 'null' ); ?></span>
                                                    </td>
													<?php $i ++;
													if ( $i == 2 || $key == 'sp_margin_tax' ) { ?></tr><?php $i = 0;
													} ?><?php } ?>
                                            </table>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?= __( 'Settings Reference', QA_MAIN_DOMAIN ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mg-b-0 text-md-nowrap">
                                                <thead>
                                                <tr>
                                                    <th><b><?= __( 'Setting', QA_MAIN_DOMAIN ); ?></b></th>
                                                    <th><b><?= __( 'Description', QA_MAIN_DOMAIN ); ?></b></th>
                                                    <th><b><?= __( 'Setting', QA_MAIN_DOMAIN ); ?></b></th>
                                                    <th><b><?= __( 'Description', QA_MAIN_DOMAIN ); ?></b></th>
                                                </tr>
                                                </thead>
												<?php $i = 0; ?>
												<?php foreach ( QAMain_Core::get_products_settings_list() as $key => $description ) { ?><?php if ( $i == 0 ) { ?><tr><?php } ?>
                                                    <td style="width: 100px;"><span class="badge badge-primary"><?= esc_html( $key ); ?></span>
                                                    </td>
                                                    <td><?= esc_html( $description ); ?></td>
													<?php $i ++;
													if ( $i == 2 || $key == 'sp_margin_tax' ) { ?></tr><?php $i = 0;
													} ?><?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once SP_ROOT_DIR . '/footer.php';