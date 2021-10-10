<?php

/**
 * Plugin tables
 */
$sp_db_tables = array(
	'api_log',
	'purchase_orders',
	'purchase_orders_products',
	'suppliers',
	'product_settings',
	'warehouses'
);

/**
 * Prefix
 */
$db_prefix = 'sp';

/**
 * Make mapping
 */
sp_map_tables( $sp_db_tables, $db_prefix );

/**
 * Migrations
 */
$sp_db_version        = get_option( 'sp.settings.db_version', 0 );
$sp_migration_applied = false;
if ( $sp_db_version < 10 ) {
	$wpdb->query( "
ALTER TABLE `{$wpdb->product_settings}` ADD `sp_supplier_product_id` INT(11) NULL
    AFTER `sp_sku_pack_size`
" );
	$wpdb->query( "
ALTER TABLE `{$wpdb->product_settings}` ADD `sp_supplier_product_reference` VARCHAR(255) NULL
    AFTER `sp_supplier_product_id`
" );

	$sp_db_version        = 10;
	$sp_migration_applied = true;
}

if ( $sp_migration_applied ) {
	update_option( 'sp.settings.db_version', $sp_db_version );
}

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->api_log}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `message` text NOT NULL,
    `type` varchar(20) NOT NULL,
    `date_added` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->purchase_orders}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `supplier_id` int(11) NOT NULL,
    `warehouse_id` int(11) NOT NULL,
    `deliver_to` varchar(255) NOT NULL,
    `order_prefix` varchar(255) NOT NULL,
    `order_number` varchar(32) UNIQUE NOT NULL,
    `reference_number` varchar(255) NOT NULL,
    `order_date` date NOT NULL,
    `expected_delivery_date` date DEFAULT NULL,
    `shipping_address` varchar(255) DEFAULT NULL,
    `status` varchar(32) NOT NULL DEFAULT 'On Order',
    `description` text,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->purchase_orders_products}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `qty` int(11) NOT NULL DEFAULT '1',
    `price` decimal(10,2) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->suppliers}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `supplier_name` varchar(255) DEFAULT NULL,
    `supplier_code` varchar(45) DEFAULT NULL,
    `tax_vat_number` varchar(45) DEFAULT NULL,
    `phone_number` varchar(45) DEFAULT NULL,
    `website` varchar(255) DEFAULT NULL,
    `email_for_ordering` varchar(255) DEFAULT NULL,
    `general_email_address` varchar(255) DEFAULT NULL,
    `description` text,
    `currency` char(3) DEFAULT NULL,
    `address` varchar(255) DEFAULT NULL,
    `city` varchar(45) DEFAULT NULL,
    `country` varchar(45) DEFAULT NULL,
    `state` varchar(45) DEFAULT NULL,
    `zip_code` varchar(45) DEFAULT NULL,
    `assigned_to` varchar(45) DEFAULT NULL,
    `ship_to_location` varchar(45) DEFAULT NULL,
    `discount` float DEFAULT NULL,
    `tax_rate` float DEFAULT NULL,
    `lead_times` int(11) DEFAULT NULL,
    `dt_added` timestamp NULL DEFAULT NULL,
    `weeks_of_stock` int(11) DEFAULT NULL,
    `payment_terms` varchar(255) DEFAULT NULL,
    `delivery_terms` varchar(255) DEFAULT NULL,
    `account_no` varchar(255) DEFAULT NULL,
    `account_id` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uid_supplier_code` (`supplier_code`),
    UNIQUE KEY `uid_supplier_name` (`supplier_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->product_settings}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `sp_supplier_id` int(11) DEFAULT NULL,
    `sp_activate_replenishment` tinyint(1) unsigned DEFAULT NULL,
    `sp_weeks_of_stock` tinyint(3) unsigned DEFAULT NULL,
    `sp_lead_time` mediumint(5) unsigned DEFAULT NULL,
    `sp_product_launch_date` date DEFAULT NULL,
    `sp_product_replenishment_date` date DEFAULT NULL,
    `sp_inbound_stock_limit` mediumint(5) unsigned DEFAULT NULL,
    `sp_on_hold` tinyint(1) unsigned DEFAULT NULL,
    `sp_primary_category` bigint(20) unsigned NOT NULL,
    `sp_size_packs` tinyint(1) unsigned DEFAULT NULL,
    `sp_size_pack_threshold` mediumint(5) unsigned DEFAULT NULL,
    `sp_sku_pack_size` mediumint(5) unsigned DEFAULT NULL,
    `sp_supplier_product_id` int(11) DEFAULT NULL,
    `sp_supplier_product_reference` int(11) DEFAULT NULL,
    `sp_cost` decimal(10,2) unsigned DEFAULT NULL,
    `sp_stock_value` mediumint(5) unsigned DEFAULT NULL,
    `sp_mark_up` mediumint(5) unsigned DEFAULT NULL,
    `sp_margin` mediumint(5) unsigned DEFAULT NULL,
    `sp_margin_tax` mediumint(5) unsigned DEFAULT NULL,
    `dt_updated` timestamp ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uid_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->warehouses}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `warehouse_name` varchar(255) NOT NULL,
    `warehouse_address` varchar(255) DEFAULT NULL,
    `warehouse_postal_code` varchar(255) DEFAULT NULL,
    `warehouse_city` varchar(255) DEFAULT NULL,
    `warehouse_country` varchar(255) DEFAULT NULL,
    `warehouse_phone` varchar(255) DEFAULT NULL,
    `warehouse_website` varchar(255) DEFAULT NULL,
    `warehouse_email` varchar(255) DEFAULT NULL,
    `warehouse_use_same` int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
" );
