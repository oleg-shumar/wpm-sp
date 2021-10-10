<?php
$step = 3;
?>
<h2 class="sphd-category"><?= __( 'Assortment Size', QA_MAIN_DOMAIN ); ?></h2>
<h4><?= __( 'Please specify the breath of your store.', QA_MAIN_DOMAIN ); ?></h4>

<p class="sphd-p"><?= __( 'This information is used to define the current performance of your store and to allow us to create different scenarios.', QA_MAIN_DOMAIN ); ?></p>

<p><?= SPHD_Wizard::get_radio( 3, 'assortment-size', 'A', 'my store has less than 250 products' ); ?></p>
<p><?= SPHD_Wizard::get_radio( 3, 'assortment-size', 'B', 'my store has between 250 and 1000 products' ); ?></p>
<p><?= SPHD_Wizard::get_radio( 3, 'assortment-size', 'C', 'my store has more than 1000 products' ); ?></p>
<p>
    <input type="checkbox" id="id-force-zero-price-products" name="force_zero_price_products"<?= $checked = isset( $_SESSION['wizard_answers'][ $step ]['force_zero_price_products'] ) ? ' checked="checked"' : '' ?>>
    <label for="id-force-zero-price-products" style="font-weight: normal"> <?= __( 'Add Force include products with zero cost price?', QA_MAIN_DOMAIN ) ?></label>
</p>

