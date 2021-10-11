<?php

class SPHD_Admin {
	/**
	 * Init
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array(
			__CLASS__,
			'include_scripts_styles',
		) );

		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
	}

	/**
	 * Plugin Deactivation Event
	 */
	public static function include_scripts_styles() {
		wp_enqueue_script( 'sp-wp-deactivation-message', plugin_dir_url( __FILE__ ) . 'assets/js/sp_deactivate.js', array(), time(), true );
		wp_enqueue_script( 'sp-moment', plugin_dir_url( __FILE__ ) . 'assets/js/moment.min.js', array( 'jquery' ), time(), true );
		wp_enqueue_script( 'sp-tabulator', plugin_dir_url( __FILE__ ) . 'assets/js/tabulator.min.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'sp-xlsx', plugin_dir_url( __FILE__ ) . 'assets/js/xlsx.full.min.js', array( 'jquery' ), time(), true );
		wp_enqueue_script( 'sp-apexcharts', plugin_dir_url( __FILE__ ) . 'assets/js/apexcharts.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'sp-custom', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js', array( 'jquery' ), time(), false );
		wp_enqueue_script( 'sp-drag-n-drop-new', plugin_dir_url( __FILE__ ) . 'assets/js/drag-n-drop-new.js', array( 'jquery' ), time(), false );
		wp_enqueue_style( 'sp-tabulator-css', plugin_dir_url( __FILE__ ) . 'assets/tabulator.min.css' );
		wp_enqueue_style( 'sp-icons-css', plugin_dir_url( __FILE__ ) . 'assets/css/icons.css' );
		wp_enqueue_style( 'sp-sidebar-css', plugin_dir_url( __FILE__ ) . 'assets/plugins/sidebar/sidebar.css' );
		wp_enqueue_style( 'sp-style-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
		wp_enqueue_style( 'sp-style-dark-css', plugin_dir_url( __FILE__ ) . 'assets/css/style-dark.css' );
		wp_enqueue_style( 'sp-skin-modes-css', plugin_dir_url( __FILE__ ) . 'assets/css/skin-modes.css' );
		wp_enqueue_style( 'sp-animate-css', plugin_dir_url( __FILE__ ) . 'assets/css/animate.css' );
		wp_enqueue_style( 'sp-closed-sidemenu-css', plugin_dir_url( __FILE__ ) . 'assets/css/closed-sidemenu.css' );
	}

	/**
	 * Add Menu Items
	 */
	public static function register_menu() {
		add_menu_page( __( 'Shelf Planner', QA_MAIN_DOMAIN ), __( 'Shelf Planner', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner', array( __CLASS__, 'stock_analyses_page' ), plugin_dir_url( __FILE__ ) . 'assets/img/menu-icon.png', 2 );

		add_submenu_page( null, __( 'Integrations', QA_MAIN_DOMAIN ), __( 'Integrations', QA_MAIN_DOMAIN ), 'edit_others_posts', 'sp_integrations', array( __CLASS__, 'integrations_page' ), null );

		add_submenu_page( null, __( 'Product Management', QA_MAIN_DOMAIN ), __( 'Product Management', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_product_management', array( __CLASS__, 'product_management_page' ), null );

		add_submenu_page( null, __( 'Purchase Orders', QA_MAIN_DOMAIN ), __( 'Purchase Orders', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_purchase_orders', array( __CLASS__, 'purchase_orders_page' ), null );
		add_submenu_page( null, __( 'Purchase Orders', QA_MAIN_DOMAIN ), __( 'Purchase Orders', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_po_create_po', array( __CLASS__, 'shelf_planner_po_create_po_page' ), null );
		add_submenu_page( null, __( 'Purchase Orders', QA_MAIN_DOMAIN ), __( 'Purchase Orders', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_po_orders', array( __CLASS__, 'shelf_planner_po_orders_page' ), null );

		add_submenu_page( null, __( 'Suppliers', QA_MAIN_DOMAIN ), __( 'Suppliers', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_suppliers', array( __CLASS__, 'suppliers_page' ), null );

		add_submenu_page( null, __( 'Warehouses', QA_MAIN_DOMAIN ), __( 'Warehouses', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_warehouses', array( __CLASS__, 'warehouses_page' ), null );

		add_submenu_page( null, __( 'Suppliers', QA_MAIN_DOMAIN ), __( 'Suppliers', QA_MAIN_DOMAIN ), 'edit_others_posts', 'quick_assortments_suppliers_page', array( __CLASS__, 'suppliers_page' ), null );

		add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_forecast', array( __CLASS__, 'shelf_planner_settings_forecast_page' ), null );
		add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_po', array( __CLASS__, 'shelf_planner_settings_po_page' ), null );
		add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_product', array( __CLASS__, 'shelf_planner_settings_product_page' ), null );
		add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_store', array( __CLASS__, 'shelf_planner_settings_store_page' ), null );

		add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_category_mapping', array( __CLASS__, 'shelf_planner_settings_category_mapping_page' ), null );
	}

	/**
	 * Integrations Page
	 */
	public static function integrations_page() {
		global $wpdb;
		require_once __DIR__ . '/pages/integrations.php';
	}

	/**
	 * Admin Page
	 */
	public static function product_management_page() {
		global $wpdb;
		require_once __DIR__ . '/pages/product_management.php';
	}

	/**
	 * Stock Analyses Page
	 */
	public static function stock_analyses_page() {
		global $wpdb;
		require_once __DIR__ . '/pages/stock_analyses.php';
	}

	/**
	 * Purchase Orders Page
	 */
	public static function purchase_orders_page() {
		global $wpdb;
		require_once __DIR__ . '/pages/purchase_orders.php';
	}

	/**
	 * Suppliers Page
	 */
	public static function suppliers_page() {
		global $wpdb;
		require_once __DIR__ . '/pages/suppliers.php';
	}

	/**
	 * Warehouses Page
	 */
	public static function warehouses_page() {
		require_once __DIR__ . '/pages/warehouses.php';
	}

	/**
	 * Forecast Settings page
	 */
	public static function shelf_planner_settings_forecast_page() {
		require_once __DIR__ . '/pages/settings-forecast.php';
	}

	/**
	 * PO Settings page
	 */
	public static function shelf_planner_settings_po_page() {
		require_once __DIR__ . '/pages/settings-po.php';
	}

	/**
	 * Product Settings page
	 */
	public static function shelf_planner_settings_product_page() {
		require_once __DIR__ . '/pages/settings-product.php';
	}

	/**
	 * Store Settings page
	 */
	public static function shelf_planner_settings_store_page() {
		require_once __DIR__ . '/pages/settings-store.php';
	}

	/**
	 * Category Mapping page
	 */
	public static function shelf_planner_settings_category_mapping_page() {
		require_once __DIR__ . '/pages/settings-category-mapping.php';
	}

	/**
	 * Purchase Orders Orders page
	 */
	public static function shelf_planner_po_orders_page() {
		require_once __DIR__ . '/pages/po_orders.php';
	}

	/**
	 * Purchase Orders Create PO page
	 */
	public static function shelf_planner_po_create_po_page() {
		require_once __DIR__ . '/pages/po_create_po.php';
	}

}