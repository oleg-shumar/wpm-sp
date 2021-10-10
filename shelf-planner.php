<?php
/**
 * Shelf Planner
 *
 * Shelf Planner helps you reduce waste and minimize spillage, all whilst improving your business and bottom line.
 *
 * Plugin Name: Shelf Planner
 * Plugin URI: https://shelfplanner.com/
 * Version: 0.1.0
 * Author: Quick Assortments AB
 * Description: Shelf Planner helps you reduce waste and minimize spillage, all whilst improving your business and bottom line.
 * Text Domain: shelf-planner
 *
 * @author      Quick Assortments AB
 * @version     v.0.1.0 (28/09/21)
 * @copyright   Copyright (c) 2021
 */

const QA_MAIN_DOMAIN = 'shelf_planner';
const SP_TEXT_DOMAIN = 'shelf_planner';

/**
 * Industries list for mapping
 */
$categories_industry = array(
	1  => 'Fashion & Apparel',
	2  => 'Footwear',
	3  => 'Bags & Suitcases',
	4  => 'Jewellery & Watches',
	5  => 'Babywear',
	6  => 'Optical',
	7  => 'Sportswear & Sporting goods',
	8  => 'Outdoor Life',
	9  => 'Equestrian',
	10 => 'Drinks & Beverages',
	11 => 'Food',
	12 => 'Kitchen & Dining',
	13 => 'Beauty & Personal Care',
	14 => 'Home & Household',
	15 => 'Furniture & Decoration',
	16 => 'Consumer Electronics',
	17 => 'Health',
	18 => 'Toys & Games',
	19 => 'Bookshop',
	20 => 'Gardening',
	21 => 'DIY',
	22 => 'Pet Store',
	23 => 'Car Parts & Car Care',
	24 => 'Other',
);

/**
 * Setup Wizard
 */
register_activation_hook( __FILE__, function () {
	$was_installed = get_option( 'sp.wizard_in_progress', null );
	if ( ! isset( $was_installed ) ) {
		update_option( 'sp.wizard_in_progress', 1 );
	}
} );

register_deactivation_hook( __FILE__, function () {
	global $wpdb;
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}api_log`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}purchase_orders`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}purchase_orders_bundle`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}suppliers`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}product_settings`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}warehouses`");
	delete_option( 'sp.wizard_in_progress' );
} );

function sphd_start_wizard( $plugin ) {
	global $wpdb;

	if ( $plugin == plugin_basename( __FILE__ ) ) {
		update_option( 'sp.in_background', 'checked' );
		update_option( 'sp.log', 'checked' );

		update_option( 'sp.settings.db_version', 1 );

		exit( wp_redirect( admin_url( 'admin.php?page=shelf_planner&wizard_step=1' ) ) );
	}
}

add_action( 'activated_plugin', 'sphd_start_wizard' );

if ( get_option( 'sp.wizard_in_progress', 1 ) ) {
	define( 'SPDH_ROOT_DIR', __DIR__ );
	define( 'SPDH_ROOT', __FILE__ );

	add_filter( 'show_admin_bar', '__return_false' );
	add_action( 'admin_head', 'sphd_full_screen_patch' );

	require_once __DIR__ . '/sphd_wizard.class.php';

	return SPHD_Wizard::init();
}

// End of Wizard: plugin is ready for use
function ajax_sphd_purge_data() {
	global $wpdb;

	$options = array(
		'sp.in_background',
		'sp.log',
		'sp.last_forecast_success',

		'sp.wizard_in_progress',
		'sp.settings.db_version',

		'sp.settings.business_model',
		'sp.settings.assortment_size',
		'sp.settings.industry',
		'sp.settings.default_weeks_of_stock',
		'sp.settings.default_lead_time',
		'sp.settings.po_auto-generate_orders',
		'sp.settings.po_prefix',
		'sp.settings.po_next_number',
		'sp.settings.po_stock_type',

	);
	foreach ( $options as $each_option ) {
		delete_option( $each_option );
	}

	$db_tables = array(
		// 'purchase_orders',
		// 'suppliers',
		// 'qa_main_products_settings',
		// 'warehouses',
	);

	foreach ( $db_tables as $each_table ) {
		$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}{$each_table}`" );
	}

	wp_die( json_encode( array( 'message' => 'purged all data' ) ) );
}

add_action( 'wp_ajax_sphd_purge_data', 'ajax_sphd_purge_data' );

/**
 * SP API
 */
const SP_API_ENDPOINT           = 'https://apifc.shelfplanner.com';

/**
 * Meta key for processing marks
 */
const SP_META_KEY_PROCESSED     = 'imported_to_shelf_planner_2021_test14';

/**
 * How much orders to push per call (while import process)
 */
const SP_ORDERS_IMPORT_PER_CALL = 50;

/**
 * Shelf Planner - Historical Data
 * Admin Settings
 */
require_once __DIR__ . '/includes/core.php';
require_once __DIR__ . '/admin_init.php';

/**
 * Shelf Planner - All the data pages
 * Domain
 */
define( 'SP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

const SP_FILE_INDEX = __FILE__;
const SP_ROOT_DIR   = __DIR__;
const SP_FORECAST_FILE = __DIR__ . '/forecast.json';

require_once __DIR__ . '/includes/functions_new.php';
require_once __DIR__ . '/includes/database.php';

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/includes/xlsxwriter.class.php';
require_once __DIR__ . '/quick-assortments-main.core.class.php';

/**
 * Redirects user to plugin after activation
 *
 * @param $plugin
 */
if ( ! isset( $wpdb ) ) {
	return;
} elseif ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

/**
 * Init Admin Page
 */
SPHD_Admin::init();

/**
 * BACKGROUND IMPORT
 */
$bg_import_status = get_option( 'sp.in_background', 'checked' );

/**
 * Get Forecast
 */
if ( is_admin() ) {
	if ( isset( $_GET['sp_purge_api_log'] ) ) {
		purgeApiLog();
	}

	if ( ! file_exists( __DIR__ . '/forecast.json' ) || isset( $_GET['sp_forecast_push'] ) || ( get_option( 'sp.last_forecast_success' ) && ( time() - get_option( 'sp.last_forecast_success' ) > 24 * 60 * 60 ) ) ) {
		set_time_limit( 0 );
		$affiliate_id = parse_url( home_url(), PHP_URL_HOST );
		$forecast_url = SP_API_ENDPOINT . '/FullForecast.aspx?affiliate_id=' . $affiliate_id;
		try {
			spApiLog( 'Trying to download the JSON forecast: ' . $forecast_url );

			$sp_json_data = wp_remote_retrieve_body ( wp_remote_get( $forecast_url ) );
			if ( $sp_json_data !== false ) {
				spApiLog( 'Download Success, JSON length is: ' . mb_strlen( $sp_json_data ), 'success' );

				file_put_contents( __DIR__ . '/forecast.json', $sp_json_data );
				update_option( 'sp.last_forecast_success', time() );
			} else {
				spApiLog( 'Download Failed', 'error' );
			}
		} catch ( Exception $e ) {
			spApiLog( 'Failed to download the JSON forecast: ' . $e->getMessage(), 'error' );
		}
	}
}

/**
 * @param $order_ids
 *
 * @internal param $order_id
 */
function pushOrder( $order_ids ) {
	if ( ! $order_ids ) {
		return;
	}

	$sales_row = [];

	foreach ( $order_ids as $order_id ) {
		// Allow code execution only once
		if ( ! get_post_meta( $order_id, SP_META_KEY_PROCESSED, true ) ) {
			// Get an instance of the WC_Order object
			$order = wc_get_order( $order_id );

			$order->update_meta_data( SP_META_KEY_PROCESSED, date( 'd.m.Y H:i:s' ) );
			$order->save();

			if ( ! $order ) {
				spApiLog( "Request denied: wrong order_id {$order_id}, not found", 'error' );
				header( 'HTTP/1.0 404 Not Found' );
				exit;
			}

			spApiLog( "Start Processing: order_id {$order_id} with " . count( $order->get_items() ) . ' item(s)' );

			// Loop through order items
			foreach ( $order->get_items() as $item_id => $item ) {
				if ( ! method_exists( $order, 'get_date_paid' ) ) {
					spApiLog( "Skip Non Order Item: {$order_id}" );
					exit;
				}

				spApiLog( "Start Processing Order Item: order_id {$order_id}, product_id " . $item->get_product_id() );

				$variation_id = $item->get_variation_id();
				if ( ! empty( $variation_id ) ) {
					spApiLog( "VARIATION FOUND: {$variation_id}, PARENT PRODUCT: " . $item->get_product_id() );
				}

				$product = new WC_Product( $item->get_product_id() );

				if ( $product->get_status() != 'publish' ) {
					continue;
				}

				/**
				 * Fill all the data in
				 */
				$tmp                    = [];
				$tmp['creation_date']   = date( "Y-m-d", strtotime( $order->get_date_created() ) );
				$tmp['affiliate_id']    = $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : AFFILIATE_ID;
				$tmp['order_id']        = $order_id;
				$tmp['segment_id']      = 3;
				$primary_category_id    = QAMain_Core::get_product_primary_category_id( $item->get_product_id() );
				$tmp['raw_category_id'] = empty( $primary_category_id ) ? 0 : $primary_category_id;

				$date_payed = $order->get_date_paid();
				if ( $date_payed ) {
					$date_payed = $date_payed->getTimestamp();
				}
				if ( ! $date_payed ) {
					$date_payed = $order->get_date_created()->getTimestamp();
				}
				$tmp['order_date'] = $tmp['shipping_date'] = date( "Y-m-d", $date_payed );

				$tmp['product_stock'] = $product->get_stock_quantity();
				if ( ! empty( $variation_id ) ) {
					$tmp['product_id'] = $variation_id;
				} else {
					$tmp['product_id'] = $item->get_product_id();
				}
				$tmp['product_creation_date'] = date( "Y-m-d", strtotime( $product->get_date_created() ) );
				$tmp['product_sku']           = $product->get_sku();

				if ( $item->get_variation_id() ) {
					$tmp['product_options'] = [ $item->get_variation_id() ];
				} else {
					$tmp['product_options'] = [];
				}
				$tmp['product_options']          = "";
				$tmp['product_strong_option1']   = 0;
				$tmp['product_strong_option2']   = 0;
				$tmp['product_strong_option3']   = 0;
				$tmp['product_strong_option4']   = 0;
				$tmp['product_quantity_ordered'] = $item->get_quantity();

				$tmp['product_cost_price']     = sp_get_cost_price( $tmp['product_id'] );
				$tmp['product_original_price'] = (string) floatval( $product->get_regular_price() );
				$tmp['product_final_price']    = (string) floatval( $product->get_price() );

				$with_tax    = wc_get_price_including_tax( $product );
				$without_tax = wc_get_price_excluding_tax( $product );

				if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
					$with_tax    = $product->get_price_including_tax();
					$without_tax = $product->get_price_excluding_tax();
				}

				if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
					$with_tax    = 0;
					$without_tax = 0;
				}

				$tax_amount         = $with_tax - $without_tax;
				$tmp['product_vat'] = round( ( $tax_amount / max( $without_tax, 0.01 ) ) * 100, 1 );

				$shipping_class_id = $product->get_shipping_class_id();
				$shipping_class    = $product->get_shipping_class();
				$fee               = 0;
				if ( $shipping_class_id ) {
					$flat_rates = get_option( "woocommerce_flat_rates" );
					$fee        = $flat_rates[ $shipping_class ]['cost'];
				}
				$flat_rate_settings = get_option( "woocommerce_flat_rate_settings" );

				$tmp['product_shipping_price'] = $flat_rate_settings['cost_per_order'] + $fee;
				$tmp['order_grandtotal']       = $order->get_total();
				$tmp['order_discount']         = $order->get_total_discount();

				$tmp['shipping_country'] = addslashes( $order->get_shipping_country() );
				$tmp['shipping_town']    = addslashes( $order->get_shipping_city() );
				$tmp['billing_country']  = addslashes( $order->get_billing_country() );
				$tmp['billing_town']     = addslashes( $order->get_billing_city() );

				// We need string here, not array! API expects string with commas inside!
				$tmp['industry_id']            = sp_get_industry_id();
				$tmp['normalized_category_id'] = sp_get_normalized_category_id( $tmp['industry_id'] );

				$product_id             = $item->get_product_id();
				$primary_category_id    = \QAMain_Core::get_product_primary_category_id( $product_id );
				$tmp['raw_category_id'] = empty( $primary_category_id ) ? 0 : $primary_category_id;

				$tmp['industry_id'] = \QAMain_Core::get_industry_by_category( $tmp['raw_category_id'] );

				$tmp['normalized_category_id'] = sp_get_normalized_category_id( $tmp['industry_id'] );

				spApiLog( "[IMPORTANT] Product #{$product_id} - normalized category ID is {$tmp['normalized_category_id']}" );

				if ( $order->get_total() <= 0 || $order->get_item_count() <= 0 ) {
					continue;
				}

				$sales_row[] = $tmp;
			}
		} else {
			spApiLog( "Order Ignored: order_id {$order_id}, it already has " . SP_META_KEY_PROCESSED . " meta key", 'notice' );
			header( "HTTP/1.0 208 Already Reported" );
			exit;
		}

		spApiLog( "API Data Prepared: order_id {$order_id}, data: " . json_encode( $sales_row ) );
	}

	$sp_json_data = json_encode( [ 'SalesRow' => $sales_row ] );

	$url = SP_API_ENDPOINT . '/Api/Sales';
	$args = array(
		'method' => 'POST',
		'headers' => array(
			'content-type' => 'application/json', // Set content type to multipart/form-data
		),
        'body' => "'" . $sp_json_data . "'",
        'timeout' => 60 * 60 * 10,
	);
	$response = wp_remote_request( $url, $args );

	spApiLog( PHP_EOL . PHP_EOL . "NEW API Call at [" . SP_API_ENDPOINT . '/Api/Sales' . "]" . PHP_EOL . PHP_EOL . $sp_json_data . PHP_EOL . PHP_EOL, 'notice' );

	if ( $sales_row ) {
		if ( $response['body'] != 'result= ok' ) {
			spApiLog( "API Call: order_ids " . implode( ', ', $order_ids ) . ", error " . $response['body'], 'notice' );

			spApiLog( "Response: {$response['body']}" );
		} else {
			spApiLog( "API Call: order_ids " . implode( ', ', $order_ids ) . ", success " . $response['body'], 'success' );

			spApiLog( "Orders Imported: order_ids " . implode( ', ', $order_ids ) . ", added " . SP_META_KEY_PROCESSED . " status to meta data" );

			spApiLog( "Response: {$response['body']}" );
		}
	} else {
		spApiLog( PHP_EOL . PHP_EOL . "Skipped - no products in order", 'notice' );
	}
}

if ( $bg_import_status == 'checked' && $_SERVER['REQUEST_URI'] == '/' ) {
	/**
	 * Add action for page load
	 */
	function sp_action_wp_woocommerce_loaded() {
		$orders = wc_get_orders( array(
			'orderby'    => 'date',
			'order'      => 'DESC',
			'post_type'  => 'shop_order',
			'limit'      => SP_ORDERS_IMPORT_PER_CALL,
			'meta_query' => [
				[
					'key'     => SP_META_KEY_PROCESSED,
					'value'   => 0,
					'compare' => 'NOT EXISTS',
				],
			],
		) );

		if ( $orders ) {
			$order_ids = [];

			foreach ( $orders as $order ) {
				$order_ids[] = $order->get_id();
			}

			sp_payment_complete( $order_ids );
		}
	}

	add_action( 'wp_loaded', 'sp_action_wp_woocommerce_loaded', 10, 1 );

	/**
	 * @param $order_ids
	 *
	 * @internal param $order_id
	 */
	function sp_payment_complete( $order_ids ) {
		spApiLog( "New Orders Processing: " . implode( ', ', $order_ids ) );
		try {
			pushOrder( $order_ids );
		} catch ( Exception $e ) {
			spApiLog( "Error while pushing orders: " . implode( ', ', $order_ids ) );
		}
	}
}

if ( isset( $_GET['sp_clear_api_sent_entries'] ) ) {
	add_action( 'wp_loaded', 'sp_unpush_orders', 9, 1 );
}

/**
 * Make meta queries work
 */
add_filter( 'woocommerce_get_wp_query_args', function ( $wp_query_args, $query_vars ) {
	if ( isset( $query_vars['meta_query'] ) ) {
		$meta_query                  = isset( $wp_query_args['meta_query'] ) ? $wp_query_args['meta_query'] : [];
		$wp_query_args['meta_query'] = array_merge( $meta_query, $query_vars['meta_query'] );
	}

	return $wp_query_args;
}, 10, 2 );

/**
 * WooCommerce meta. Displaying product edit form - additional fields
 */
add_action( 'woocommerce_product_options_general_product_data', 'qa_main_adv_product_options' );
function qa_main_adv_product_options() {
	global $wpdb;

	$product = new WC_Product( get_the_ID() );

	$settings = \QAMain_Core::get_products_settings_list();

	echo '<header><h4 style="padding-bottom: 0px !important; color:#000; margin-bottom: 0px; padding-left: 10px;">Shelf Planner Product Settings</h4></header>';
	echo '<div class="options_group">';
	?>
    <input type="hidden" name="tmp_qa_stock" value="<?= $product->get_stock_quantity(); ?>"/>
	<?php

	$data = $wpdb->get_results( "SELECT * FROM {$wpdb->product_settings} WHERE product_id = " . get_the_ID(), ARRAY_A );
	if ( isset( $data[0] ) ) {
		$data = $data[0];
	} else {
		$data = array(
			'product_id'                    => get_the_ID(),
			'sp_supplier_id'                => 0,
			'sp_activate_replenishment'     => 0,
			'sp_weeks_of_stock'             => 0,
			'sp_lead_time'                  => 0,
			'sp_product_launch_date'        => 0,
			'sp_product_replenishment_date' => 0,
			'sp_inbound_stock_limit'        => 0,
			'sp_on_hold'                    => 0,
			'sp_primary_category'           => 0,
			'sp_size_packs'                 => 0,
			'sp_size_pack_threshold'        => 0,
			'sp_sku_pack_size'              => 0,
			'sp_supplier_product_id'        => 0,
			'sp_supplier_product_reference' => 0,
			'sp_cost'                       => 0,
			'sp_stock_value'                => 0,
			'sp_mark_up'                    => 0,
			'sp_margin'                     => 0,
			'sp_margin_tax'                 => 0
		);
		$wpdb->insert( $wpdb->product_settings, $data );
	}

	/**
	 * Set product creation date by default, if it was not set
	 */
	if ( ! $data['sp_product_launch_date'] || $data['sp_product_launch_date'] == '0000-00-00' ) {
		if ( method_exists( $product, 'get_date_created' ) ) {
			$data['sp_product_launch_date'] = $product->get_date_created()->format( 'Y-m-d' );
		}
	}

	/**
	 * Set product primary category by default, if it was not set
	 */
	if ( ! $data['sp_primary_category'] ) {
		$data['sp_primary_category'] = QAMain_Core::get_product_primary_category_id( get_the_ID() );
	}

	$data['sp_stock_value'] = $product->get_stock_quantity() * $data['sp_cost'];

	$profit = $product->get_price() - $data['sp_cost'];

	$data['sp_mark_up'] = round( $profit / max( $data['sp_cost'], 0.01 ), 2 );

	$with_tax    = wc_get_price_including_tax( $product );
	$without_tax = wc_get_price_excluding_tax( $product );

	if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
		$with_tax    = $product->get_price_including_tax();
		$without_tax = $product->get_price_excluding_tax();
	}

	if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
		$with_tax    = 0;
		$without_tax = 0;
	}

	$tax_amount = $with_tax - $without_tax;
	$percent    = ( $tax_amount / max( $without_tax, 0.01 ) ) * 100;

	$data['sp_margin']     = round( $profit / max( $with_tax, 0.01 ) * 100, 2 ) . '%';
	$data['sp_margin_tax'] = round( ( $product->get_price() - $tax_amount - $data['sp_cost'] ) / max( $without_tax, 0.01 ) * 100, 2 ) . '%';

	woocommerce_wp_checkbox( array(
		'id'    => 'sp_activate_replenishment',
		'value' => (bool) $data['sp_activate_replenishment'],
		'label' => $settings['sp_activate_replenishment'],
	) );
	array_shift( $settings );

	$suppliers = [];
	foreach ( QAMain_Core::get_suppliers() as $row ) {
		$suppliers[ $row['id'] ] = $row['supplier_name'];
	}

	woocommerce_wp_select( array(
		'id'      => 'sp_supplier_id',
		'value'   => $data['sp_supplier_id'],
		'label'   => $settings['sp_supplier_id'],
		'options' => $suppliers,
	) );
	array_shift( $settings );

	foreach ( $settings as $key => $setting ) {
		if ( $key == 'sp_on_hold' ) {
			woocommerce_wp_select( array(
				'id'      => $key,
				'value'   => $data[ $key ],
				'label'   => $settings[ $key ],
				'options' => [ 'No', 'Yes' ],
			) );
			continue;
		}

		if ( $key == 'sp_primary_category' ) {
			woocommerce_wp_select( array(
				'id'      => $key,
				'value'   => $data[ $key ],
				'label'   => $settings[ $key ],
				'options' => QAMain_Core::get_all_categories(),
			) );
			continue;
		}

		woocommerce_wp_text_input( array(
			'id'    => $key,
			'value' => $data[ $key ],
			'label' => $setting,
			'type'  => ( strpos( $key, '_date' ) !== false ) ? 'date' : 'text',
		) );
	}

	echo '</div>';
}

/**
 * Save fields from product edit form
 */
add_action( 'woocommerce_process_product_meta', 'sp_main_save_product_settings', 10, 2 );
function sp_main_save_product_settings( $id, $post ) {
	global $wpdb;

	$_POST['sp_margin']     = (float) $_POST['sp_margin'];
	$_POST['sp_margin_tax'] = (float) $_POST['sp_margin_tax'];
	$_POST['sp_cost']       = (float) $_POST['sp_cost'];

	$clean = [];
	foreach ( $_POST as $key => $value ) {
		if ( strpos( $key, 'sp_' ) !== false ) {
			$clean[ $key ] = esc_sql( $value );
		}
	}
	$clean['product_id'] = $id;

	$wpdb->replace( $wpdb->product_settings, $clean );
}

/**
 * Custom Cost price for variations - display field
 */
add_action( 'woocommerce_variation_options_pricing', 'sp_add_custom_field_to_variations', 10, 3 );
function sp_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	woocommerce_wp_text_input( array(
		'id'    => 'variation_cost_price[' . $loop . ']',
		'class' => 'short wc_input_price',
		'style' => 'width: 100% !important',
		'label' => __( 'Variation Cost Price', SP_TEXT_DOMAIN ) . ' (' . get_woocommerce_currency_symbol() . ')',
		'value' => get_post_meta( $variation->ID, 'variation_cost_price', true )
	) );
}

/**
 * Custom Cost price for variations - save it
 */
add_action( 'woocommerce_save_product_variation', 'sp_save_custom_field_variations', 10, 2 );
function sp_save_custom_field_variations( $variation_id, $i ) {
	global $wpdb;

	if ( isset( $_POST['variation_cost_price'][ $i ] ) ) {
		$variation_id = (int)$variation_id;
	    $variation_cost_price = (float) $_POST['variation_cost_price'][ $i ];
		update_post_meta( $variation_id, 'variation_cost_price', $variation_cost_price );
		\QAMain_Core::get_product_settings( $variation_id );

		$wpdb->query( "UPDATE `{$wpdb->product_settings}` SET `sp_cost` = {$variation_cost_price} WHERE `product_id` = {$variation_id} limit 1" );
	}
}

/**
 * Custom Routing for Logs
 */
function sp_rewrites_init() {
	global $wp_rewrite;
	$file_logger = ltrim( str_replace( $_SERVER['DOCUMENT_ROOT'], '', plugin_dir_path( __FILE__ ) . 'sp-logger.php' ), '/' );
	add_rewrite_rule( 'sp-logs', $file_logger, 'top' );
	$wp_rewrite->flush_rules();
}

add_action( 'init', 'sp_rewrites_init' );