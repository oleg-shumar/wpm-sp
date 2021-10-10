<?php
$filters = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( ! in_array( 'woocommerce/woocommerce.php', $filters ) ) {
	echo '<h1 style="color:red;text-align:center">' . __( 'WooCommerce still not installed yet!', QA_MAIN_DOMAIN ) . '</h1>';
	$step = - 1;
} else {
	$step = 1;
	?>
    <h2 class="sphd-category"><?= __( 'Industry', QA_MAIN_DOMAIN ); ?></h2><h4><?= __( 'Please specify which industry your company belongs to.', QA_MAIN_DOMAIN ); ?></h4>
    <table class="sphd-answers-1">
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Fashion & Apparel', 'industry' ); ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Beauty & Personal Care', 'industry' ); ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Footwear', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Home & Household', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Bags & Suitcases', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Furniture & Decoration', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Jewellery & Watches', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Consumer Electronics', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Babywear', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Health', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Optical', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Toys & Games', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Sportswear & Sporting goods', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Bookshop', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Outdoor Life', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Gardening', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Equestrian', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'DIY', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Drinks & Beverages', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Pet Store', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Food', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Car Parts & Car Care', 'industry' ) ?>
            </td>
        </tr>
        <tr>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Kitchen & Dining', 'industry' ) ?>
            </td>
            <td>
				<?= SPHD_Wizard::get_checkbox( 1, 'Other', 'industry' ) ?>
            </td>
        </tr>
    </table>
<?php } ?>