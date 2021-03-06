<?php
$step = 4;
?>
<h1 class="sphd-category"><?= __( 'Success!', QA_MAIN_DOMAIN ); ?></h1>
<div style="width: 48%; text-align: left; position: relative; display: inline-block;
vertical-align: top; margin-top: 45px">
    <img style="position: relative; width: 90%" src="<?= plugin_dir_url( __FILE__ ) ?>assets/shelf_planner_by_quick_assortments.png">
</div>
<div style="width: 51%; text-align: left; position: relative; display: inline-block"
     class="sphd-final-text">
    <p style="color: #486A82; font-weight: bold;"><?= __( 'That’s it!', QA_MAIN_DOMAIN ); ?></p>
    <p><?= __( 'Shelf Planner will now create advanced scenarios for your business, based on your performance, stock, and hundreds of other data points.', QA_MAIN_DOMAIN ); ?></p>
    <p><?= __( 'For tutorials or instructions, have a look at our dedicated <a href="https://www.youtube.com/channel/UCZxCYDp2ToyWcAxaqnNDBPg" target="_blank">Youtube channel</a> for Shelf Planner.', QA_MAIN_DOMAIN ); ?></p>
    <p><?= __( 'You will also find tips and tricks on our user guides on <a href="https://merchants.shelfplanner.com" target="_blank">merchants.shelfplanner.com</a>', QA_MAIN_DOMAIN ); ?></p>

    <p><?= __( 'All you need to do now is complete the installation by pressing the button below. This will run a few processes in the background to perform an initial analyses.', QA_MAIN_DOMAIN ); ?></p>
    <p><?= __( 'Once that’s done, you’re good to go!', QA_MAIN_DOMAIN ) ?></p>
    <p><?= __( 'By installing this plugin, you agree to Shelf Planner’s', QA_MAIN_DOMAIN ) ?> <a href="https://shelfplanner.com/terms-conditions/" target="_blank"><?= htmlspecialchars( __( 'Terms & Conditions', QA_MAIN_DOMAIN ) ) ?></a></p>
    <button class="button-primary sphd-finish-button" onclick="complete_wizard(); return false"><?= __( 'Complete Installation', QA_MAIN_DOMAIN ); ?></button>
</div>
