<?php
/**
 * Show AJAX Progress Info
 */
if ( ! defined( 'WP_USE_THEMES' ) ) {
	define( 'WP_USE_THEMES', false );
}

require_once __DIR__ . '/../../../wp-load.php';
require_once __DIR__ . '/includes/core.php';

sp_deny_if_not_logged_in();

if ( isset( $_GET['bg'] ) ) {
	if ( $_GET['bg'] == 'true' ) {
		update_option( 'sp.in_background', 'checked' );
	} else {
		update_option( 'sp.in_background', 'false' );
	}
} elseif ( isset( $_GET['log'] ) ) {
	if ( $_GET['log'] == 'true' ) {
		update_option( 'sp.log', 'checked' );
	} else {
		update_option( 'sp.log', 'false' );
	}
} elseif ( isset( $_GET['sp-analyzed-orders-count'] ) ) {
	echo ShelfPlannerCore::getAnalyzedOrdersCount();
} elseif ( isset( $_GET['sp-total-orders-count'] ) ) {
	echo ShelfPlannerCore::getOrdersCount();
} elseif ( isset( $_GET['sp-chart'] ) ) {
	echo (int) ( min( 100, ( ShelfPlannerCore::getAnalyzedOrdersCount() / max( 1, ShelfPlannerCore::getOrdersCount() ) * 100 ) ) );
} else {
	echo json_encode( [
		'total'    => ShelfPlannerCore::getOrdersCount(),
		'analyzed' => ShelfPlannerCore::getAnalyzedOrdersCount(),
		'progress' => ShelfPlannerCore::getAnalyzedProgress(),
	] );
}

exit;