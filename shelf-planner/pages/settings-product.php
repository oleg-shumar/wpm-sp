<?php
if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-product-settings'] ) ) {
		update_option( 'sp.settings.po_stock_type', sanitize_text_field( $_POST['po-stock-type'] ) );
	}
}
$po_stock_type = get_option( 'sp.settings.po_stock_type', 'ideal_stock' );
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
                    <link rel="stylesheet" href="<?= SP_PLUGIN_DIR_URL; ?>assets/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
                    <link href="<?= SP_PLUGIN_DIR_URL; ?>assets/tabulator.min.css" rel="stylesheet">
                    <link rel="stylesheet" href="<?= SP_PLUGIN_DIR_URL; ?>assets/flat-ui.css">
                    <link rel="stylesheet" href="<?= SP_PLUGIN_DIR_URL; ?>assets/common.css">
                    <script src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
                    <script src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
                    <script type="text/javascript" src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/tabulator.min.js"></script>
                    <script type="text/javascript" src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/xlsx.full.min.js"></script>
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }
                    </style>
                    <h4><?= __( 'Settings', QA_MAIN_DOMAIN ); ?></h4>
                    <div class="card">
                        <div class="card-body">
                            <h4><?= __( 'Product Settings', QA_MAIN_DOMAIN ); ?></h4>
                            <p class="mg-b-20"></p>
                            <p style="font-weight: bold; font-size: inherit; margin-bottom: 2em"><?= __( 'Shelf Planner calculates an Ideal Stock per product based on your stores\' sales forecast.', QA_MAIN_DOMAIN ); ?></p>
                            <form method="post" id="id-settings-product-form">
                                <p style="margin-bottom: 2em; font-size: inherit">
                                    <input type="radio" name="po-stock-type" value="min_stock"
										<?= $po_stock_type == 'min_stock' ? 'checked="checked"' : ''; ?>
                                    /><?= __( 'Use Min Stock threshold for my products instead of Ideal Stock when present', QA_MAIN_DOMAIN ); ?>
                                </p>
                                <p class="mg-b-20"></p>
                                <p style="margin-bottom: 2em; font-size: inherit">
                                    <input type="radio" name="po-stock-type" value="ideal_stock"
										<?= $po_stock_type == 'ideal_stock' ? 'checked="checked"' : ''; ?>
                                    /><?= __( 'Always use Ideal Stock to calculate Order
                                    Proposals', QA_MAIN_DOMAIN ); ?>
                                </p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success" value="<?= __( 'Save Settings', QA_MAIN_DOMAIN ); ?>" name="save-product-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>