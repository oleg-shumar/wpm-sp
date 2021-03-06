<?php

global $wpdb;

if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-po-settings'] ) ) {
		update_option( 'sp.settings.po_auto-generate_orders', sanitize_text_field( $_POST['po-auto-generate'] ) );

		$po_prefix_new = trim( sanitize_text_field( $_POST['po-prefix'] ) );
		if ( strlen( $po_prefix_new ) > 0 ) {
			$po_prefix = $po_prefix_new;
			update_option( 'sp.settings.po_prefix', sanitize_text_field( $po_prefix ) );
		}

		$po_next_number = intval( trim( sanitize_text_field( $_POST['po-next-number'] ) ) );
		update_option( 'sp.settings.po_next_number', $po_next_number );

		$po_company_name = trim( sanitize_text_field( $_POST['po-company-name'] ) );
		update_option( 'sp.settings.po_company_name', $po_company_name );

		$po_company_address = trim( sanitize_text_field( $_POST['po-company-address'] ) );
		update_option( 'sp.settings.po_company_address', $po_company_address );

		$po_postal_code = trim( sanitize_text_field( $_POST['po-postal-code'] ) );
		update_option( 'sp.settings.po_postal_code', $po_postal_code );

		$po_city = trim( sanitize_text_field( $_POST['po-city'] ) );
		update_option( 'sp.settings.po_city', $po_city );

		$po_country = trim( sanitize_text_field( $_POST['po-country'] ) );
		update_option( 'sp.settings.po_country', $po_country );

		$po_description = trim( sanitize_text_field( $_POST['po-description'] ) );
		update_option( 'sp.settings.po_description', $po_description );

		$po_phone = trim( sanitize_text_field( $_POST['po-phone'] ) );
		update_option( 'sp.settings.po_phone', $po_phone );

		$po_website = trim( sanitize_text_field( $_POST['po-website'] ) );
		update_option( 'sp.settings.po_website', $po_website );

		$po_email = trim( sanitize_text_field( $_POST['po-email'] ) );
		update_option( 'sp.settings.po_email', $po_email );

		$po_vat_number = trim( sanitize_text_field( $_POST['po-vat-number'] ) );
		update_option( 'sp.settings.po_vat_number', $po_vat_number );

		$po_bank = trim( sanitize_text_field( $_POST['po-bank'] ) );
		update_option( 'sp.settings.po_bank', $po_bank );

		$po_branch = trim( sanitize_text_field( $_POST['po-branch'] ) );
		update_option( 'sp.settings.po_branch', $po_branch );

		$po_account_number = trim( sanitize_text_field( $_POST['po-account-number'] ) );
		update_option( 'sp.settings.po_account_number', $po_account_number );

		$po_swift_code = trim( sanitize_text_field( $_POST['po-swift-code'] ) );
		update_option( 'sp.settings.po_swift_code', $po_swift_code );

		$po_iban = trim( sanitize_text_field( $_POST['po-iban'] ) );
		update_option( 'sp.settings.po_iban', $po_iban );

		if ( isset( $_FILES['po-company-logo'] ) && 'image/png' == $_FILES['po-company-logo']['type'] ) {
			update_option( 'sp.settings.po_company_logo', 'data:image/png;base64,' . base64_encode( file_get_contents( $_FILES['po-company-logo']['tmp_name'] ) ) );
		}
	}
}

$po_autogenerate_orders_type = get_option( 'sp.settings.po_auto-generate_orders', 'auto' );
$po_prefix                   = get_option( 'sp.settings.po_prefix', 'PO-' );
$po_next_number              = sp_get_next_po();

$po_company_name    = get_option( 'sp.settings.po_company_name', '' );
$po_company_address = get_option( 'sp.settings.po_company_address', '' );
$po_postal_code     = get_option( 'sp.settings.po_postal_code', '' );
$po_city            = get_option( 'sp.settings.po_city', '' );
$po_country         = get_option( 'sp.settings.po_country', '' );
$po_description     = get_option( 'sp.settings.po_description', '' );
$po_phone           = get_option( 'sp.settings.po_phone', '' );
$po_website         = get_option( 'sp.settings.po_website', '' );
$po_email           = get_option( 'sp.settings.po_email', '' );
$po_vat_number      = get_option( 'sp.settings.po_vat_number', '' );
$po_bank            = get_option( 'sp.settings.po_bank', '' );
$po_branch          = get_option( 'sp.settings.po_branch', '' );
$po_account_number  = get_option( 'sp.settings.po_account_number', '' );
$po_swift_code      = get_option( 'sp.settings.po_swift_code', '' );
$po_iban            = get_option( 'sp.settings.po_iban', '' );

$po_company_logo = get_option( 'sp.settings.po_company_logo', '' );
$po_company_logo = strlen( $po_company_logo ) > 0 ? '<img onclick="jQuery(\'#company-logo-upload\').show()" style="cursor:pointer" src="' . esc_attr( $po_company_logo ) . '">' : '';

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

                        .sp-settings-po-table {
                            margin-top: 2em;
                            width: 80%;
                        }

                        .sp-settings-po-table th {
                            text-align: center;
                        }

                        .sp-settings-po-table td {
                            padding-bottom: 2em;
                            text-align: center;
                        }
                    </style>
                    <h4><?= __( 'Settings', QA_MAIN_DOMAIN ); ?></h4>
                    <div class="card">
                        <div class="card-body">
                            <h4><?= __( 'Purchase Order Settings', QA_MAIN_DOMAIN ); ?></h4>
                            <p class="mg-b-20"></p>
                            <form method="post" enctype="multipart/form-data">
                                <p style="font-weight: bold; font-size: inherit"><?= __( 'Here you can define the content and layout of your Purchase Orders.This information is included in the Purchase Order. If you do not wish to share this information, please leave blank.', QA_MAIN_DOMAIN ); ?></p>
                                <!-- begin new form -->
                                <div class="row" id="js-add-new-supplier">
                                    <div class="col-md-4 ">
                                        <label><?= __( 'Company Name', QA_MAIN_DOMAIN ); ?>*</label> <input type="text" class="form-control" name="po-company-name" required="required" value="<?= esc_attr( $po_company_name ); ?>" placeholder="Company Name*"/> <label><?= __( 'Company Address', QA_MAIN_DOMAIN ); ?>*</label> <input type="text" class="form-control" name="po-company-address" required="required" value="<?= esc_attr( $po_company_address ); ?>" placeholder="Company Address*"/> <label><?= __( 'Postal Code', QA_MAIN_DOMAIN ); ?>*</label> <input type="text" class="form-control" name="po-postal-code" required="required" value="<?= esc_attr( $po_postal_code ); ?>" placeholder="Postal Code*"/> <label><?= __( 'City', QA_MAIN_DOMAIN ); ?>*</label> <input type="text" class="form-control" name="po-city" required="required" value="<?= esc_attr( $po_city ); ?>" placeholder="City*"/> <label><?= __( 'Country', QA_MAIN_DOMAIN ); ?>*</label> <input type="text" placeholder="Country*"/>
                                        <label><?= __( 'Additional Information', QA_MAIN_DOMAIN ); ?></label> <textarea class="form-control" name="po-description" placeholder="Additional Information"><?= esc_textarea( $po_description ); ?></textarea> <br>
                                    </div>
                                    <div class="col-md-4">
                                        <label><?= __( 'Phone', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-phone" value="<?= esc_attr( $po_phone ); ?>" placeholder="Phone"/> <label><?= __( 'Website', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-website" value="<?= esc_attr( $po_website ); ?>" placeholder="Website"/> <label><?= __( 'Email', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-email" value="<?= esc_attr( $po_email ); ?>" placeholder="Email"/> <label><?= __( 'VAT Registration Number', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-vat-number" value="<?= esc_attr( $po_vat_number ); ?>" placeholder="VAT Registration Number"/> <label><?= __( 'Your company logo', QA_MAIN_DOMAIN ); ?></label>
										<?php
										// Already escaped
										echo $po_company_logo;
										?>
                                        <div id="company-logo-upload" style="<?= strlen( $po_company_logo ) > 0 ? 'display:none' : ''; ?>">
                                            <input type="file" class="form-control" name="po-company-logo"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label><?= __( 'Bank', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-bank" value="<?= esc_attr( $po_bank ); ?>" placeholder="Bank"/> <label><?= __( 'Branch', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-branch" value="<?= esc_attr( $po_branch ); ?>" placeholder="Branch"/> <label><?= __( 'Account Number', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-account-number" value="<?= esc_attr( $po_account_number ); ?>" placeholder="Account Number"/> <label><?= __( 'Swift Code', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-swift-code" value="<?= esc_attr( $po_swift_code ); ?>" placeholder="Swift Code"/> <label><?= __( 'IBAN', QA_MAIN_DOMAIN ); ?></label> <input type="text" class="form-control" name="po-iban" value="<?= esc_attr( $po_iban ); ?>" placeholder="IBAN"/>
                                    </div>
                                </div>
                                <!-- end new form -->
                                <p style="font-weight: bold; font-size: inherit"><?= __( 'Your purchase order numbers are set on auto-generate mode to save you time.', QA_MAIN_DOMAIN ); ?></p>
                                <p style="font-weight: bold; font-size: inherit"><?= __( 'Do you want to change settings?', QA_MAIN_DOMAIN ); ?></p>
                                <table class="sp-settings-po-table">
                                    <tr>
                                        <th></th>
                                        <th><?= __( 'Prefix', QA_MAIN_DOMAIN ); ?></th>
                                        <th><?= __( 'Next Number', QA_MAIN_DOMAIN ); ?></th>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%; text-align: left; font-weight: bold"><input type="radio" name="po-auto-generate" value="auto"
												<?= $po_autogenerate_orders_type == 'auto' ? 'checked="checked"' : ''; ?>
                                            /><?= __( 'Continue auto-generating purchase order numbers', QA_MAIN_DOMAIN ); ?>
                                        </td>
                                        <td>
                                            <input type="text" name="po-prefix" value="<?= esc_attr( $po_prefix ) ?>"/>
                                        </td>
                                        <td>
                                            <input type="text" name="po-next-number" value="<?= esc_attr( $po_next_number ) ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: left; font-weight: bold"><input type="radio" name="po-auto-generate" value="manual"
												<?= $po_autogenerate_orders_type == 'manual' ? 'checked="checked"' : ''; ?>
                                            /><?= __( 'I will add them manually each time', QA_MAIN_DOMAIN ); ?>
                                        </td>
                                    </tr>
                                </table>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn btn-sm
                                    btn-success" value="<?= __( 'Save Settings', QA_MAIN_DOMAIN ); ?>" name="save-po-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>