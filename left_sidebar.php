<!-- main-sidebar -->
<style>
    .sub-slide {
        margin: 0;
        border-radius: 0;
        padding: 10px 20px 10px 22px;
    }

    .sub-slide > ul > li {
        margin-top: 20px;
    }

    .sub-slide_active::before {
        background: #3093BA !important;
        content: '';
        width: 3px;
        height: 31px;
        position: absolute;
        left: 0;
    }
</style>
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="<?php echo  admin_url( 'admin.php?page=shelf_planner' ); ?>"><img src="<?php echo  plugin_dir_url( __FILE__ ); ?>assets/img/brand/logo.png?1" class="main-logo" style="width: 199px; height: 28px;" alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <ul class="side-menu">
            <li class="slide">
                <a class="side-menu__item <?php echo  ( $_GET['page'] == 'shelf_planner' ) ? 'active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner' ); ?>"><i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo  __( 'Stock Analyses', QA_MAIN_DOMAIN ); ?></span></a>
            </li>
            <li class="sub-slide">
                <a class="side-menu__item" href="#" onclick="jQuery('#id-menu-po-root').slideToggle(); return false;"><i class="fa
                    fa-tasks"></i>&nbsp; &nbsp;<span class="side-menu__label"><?php echo  __( 'Purchase Orders', QA_MAIN_DOMAIN ); ?></span></a>
                <ul style="<?php echo  in_array( $_GET['page'], array(
					'shelf_planner_purchase_orders',
					'shelf_planner_po_create_po',
					'shelf_planner_po_orders',
				) ) ? '' : 'display: none'; ?>" id="id-menu-po-root" class="side-menu">
                    <li class="slide">
                        <a class="side-menu__item <?php echo  $_GET['page'] == 'shelf_planner_po_create_po' ? 'sub-slide_active active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_po_create_po' ); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo  __( 'New Order', QA_MAIN_DOMAIN ); ?></span></a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo  $_GET['page'] == 'shelf_planner_po_orders' ? 'sub-slide_active active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_po_orders' ); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo  __( 'Orders History', QA_MAIN_DOMAIN ); ?></span></a>
                    </li>
                </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item <?php echo  ( $_GET['page'] == 'shelf_planner_suppliers' ) ? 'active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_suppliers' ); ?>"><i class="fa fa-truck"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo  __( 'Suppliers', QA_MAIN_DOMAIN ); ?></span></a>
            </li>
            <li class="slide">
                <a class="side-menu__item <?php echo  ( $_GET['page'] == 'shelf_planner_warehouses' ) ? 'active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_warehouses' ); ?>"><i class="fas fa-warehouse"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo  __( 'Warehouses', QA_MAIN_DOMAIN ); ?></span></a>
            </li>
            <li class="slide">
                <a class="side-menu__item <?php echo  ( $_GET['page'] == 'shelf_planner_product_management' ) ? 'active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_product_management' ); ?>"><i class="fa fa-database"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo  __( 'Product Management', QA_MAIN_DOMAIN ); ?></span></a>
            </li>
            <li class="sub-slide">
                <a class="side-menu__item" href="#" onclick="jQuery('#id-menu-settings-root').slideToggle(); return false"><i class="fa fa-wrench"></i>&nbsp; &nbsp;<span class="side-menu__label"><?php echo  __( 'Settings', QA_MAIN_DOMAIN ); ?></span></a>
                <ul style="<?php echo  in_array( $_GET['page'], array(
					'shelf_planner_settings_forecast',
					'shelf_planner_settings_po',
					'shelf_planner_settings_product',
					'shelf_planner_settings_store',
					'shelf_planner_settings_category_mapping',
				) ) ? '' : 'display: none'; ?>" id="id-menu-settings-root" class="side-menu">
                    <li class="slide">
                        <a class="side-menu__item <?php echo  $_GET['page'] == 'shelf_planner_settings_forecast' ? 'sub-slide_active active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_settings_forecast' ); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo  __( 'Forecast Settings', QA_MAIN_DOMAIN ); ?></span></a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo  $_GET['page'] == 'shelf_planner_settings_po' ? 'sub-slide_active active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_settings_po' ); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo  __( 'PO Settings', QA_MAIN_DOMAIN ); ?></span></a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo  $_GET['page'] == 'shelf_planner_settings_product' ? 'sub-slide_active active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_settings_product' ); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo  __( 'Product Settings', QA_MAIN_DOMAIN ); ?></span></a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo  $_GET['page'] == 'shelf_planner_settings_store' ? 'sub-slide_active active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_settings_store' ); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo  __( 'Store Settings', QA_MAIN_DOMAIN ); ?></span></a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo  $_GET['page'] == 'shelf_planner_settings_category_mapping' ? 'sub-slide_active active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=shelf_planner_settings_category_mapping' ); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo  __( 'Category Mapping', QA_MAIN_DOMAIN ); ?></span></a>
                    </li>
                </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item <?php echo  ( $_GET['page'] == 'sp_integrations' ) ? 'active' : ''; ?>" href="<?php echo  admin_url( 'admin.php?page=sp_integrations' ); ?>"><i class="fas fa-cloud-upload-alt"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo  __( 'Integrations', QA_MAIN_DOMAIN ); ?></span></a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="<?php echo  admin_url( '' ); ?>"><i class="fa fa-reply-all"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo  __( 'Exit to WP Admin', QA_MAIN_DOMAIN ); ?></span></a>
            </li>
        </ul>
    </div>
</aside><!-- main-sidebar -->
<script>
    function slideSettings() {
        jQuery("#id-menu-settings-root").slideToggle();
    }
</script>