<?php

/**
 * XLSX Reader Class
 */
if ( ! defined( 'WP_USE_THEMES' ) ) {
	define( 'WP_USE_THEMES', false );
}
require_once __DIR__ . '/../../../wp-load.php';
require_once __DIR__ . '/includes/simple_xlsx.class.php';
require_once __DIR__ . '/includes/core.php';

sp_deny_if_not_logged_in();

/**
 * Parse XLSX
 */
if ( $_FILES && isset( $_FILES['excel'] ) ) {
	if ( $_FILES['excel']['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) {
		wp_die( 'Error: unsupported file format. Try again please' );
	}

	$new_dataset_data = [];
	$xlsx_file        = $_FILES['excel']['tmp_name'];

	if ( $xlsx = SimpleXLSX::parse( $xlsx_file ) ) {
		foreach ( $xlsx->rows() as $line => $each_row ) {
			if ( 0 == $line ) {
				foreach ( $each_row as $k => $cell ) {
					if ( ! trim( $cell ) ) {
						continue;
					}
					$new_dataset_data['title'][ $k ] = esc_sql( $cell );
				}
			} else {
				foreach ( $each_row as $k => $cell ) {
					if ( $new_dataset_data['title'][ $k ] ) {
						$new_dataset_data['items'][ $line ][ $new_dataset_data['title'][ $k ] ] = esc_sql( $cell );
					}
				}
			}
		}
	} else {
		$error_str = SimpleXLSX::parseError();
	}

	if ( $new_dataset_data['items'] ) {
		foreach ( $new_dataset_data['items'] as $item ) {
			if ( ! $item['product_id'] ) {
				continue;
			}
			$wpdb->replace( $wpdb->product_settings, $item );
		}
	}

	echo "<center>Succesfully imported " . count( $new_dataset_data['items'] ) . " items. You can close this window.</center>";

	?>
    <script>
        // Reload the parent page to see the changes in dataset
        setTimeout(function () {
            parent.location.reload();
        }, 2000);
    </script>
	<?php
}

?>
<div style="width:600px; height: 4em; padding-top: 5px; margin: auto; text-align: center;">
    <form action="" method="POST" enctype="multipart/form-data">
        <p>
            <strong>Upload .XLSX file</strong> <select name="import_mode" style="display: none">
                <option value="append" style="color: darkgreen">Append</option>
                <option value="overwrite" style="color: darkred">Overwrite</option>
            </select>
        </p>
        <input type="file" name="excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/> <input type="submit" onclick="this.innerHTML = 'Loading...';">
    </form>
</div>