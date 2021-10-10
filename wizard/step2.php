<?php
$step = 2;
?>
<h2 class="sphd-category"><?= __( 'Business Model', QA_MAIN_DOMAIN ); ?></h2>
<h4><?= __( 'Please specify your business model and how you sell your products.', QA_MAIN_DOMAIN ); ?></h4>

<p><?= SPHD_Wizard::get_radio( 2, 'business-model', 'A', 'Retail - my site sells directly to consumers' ); ?></p>
<p><?= SPHD_Wizard::get_radio( 2, 'business-model', 'B', 'Wholesale – my site sells business to business' ); ?></p>
<p><?= SPHD_Wizard::get_radio( 2, 'business-model', 'C', 'Multichannel – my site sells to both end consumers as well as business to business' ); ?></p>
