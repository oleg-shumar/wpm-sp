<?php require_once SP_ROOT_DIR . '/header.php'; ?>
    <style>
        .card {
            max-width: 520px;
        }
    </style>
    <div class="sp-admin-overlay">
        <div class="sp-admin-container">
			<?php include SP_ROOT_DIR . "/left_sidebar.php"; ?>
            <!-- main-content opened -->
            <div class="main-content horizontal-content">
                <div class="page">
                    <!-- container opened -->
                    <div class="container">
                        <div class="breadcrumb-header justify-content-between" style="max-width: 520px;">
                            <div class="my-auto">
                                <div class="d-flex"><h4 class="content-title mb-0 my-auto"><?= __( 'Integrations', QA_MAIN_DOMAIN ); ?></h4></div>
                            </div>
                        </div>
                        <div class="row row-sm">
                            <div class="col-xl-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="main-content-label mg-b-5">
											<?= __( 'Settings', QA_MAIN_DOMAIN ); ?>
                                        </div>
                                        <p class="mg-b-20"></p>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="ckbox"><input checked="<?= esc_attr( get_option( 'sp.in_background', 'checked' ) ); ?>" type="checkbox" onchange="jQuery.get('<?= plugin_dir_url( __FILE__ ); ?>ajax.php?bg='+jQuery(this).prop('checked'));"><span style="display: inline-block;padding-top: 3px;"><?= __( 'Work in Background', QA_MAIN_DOMAIN ); ?></span></label>
                                            </div>
                                            <div class="col-lg-6 mg-t-20 mg-lg-t-0" style="white-space: nowrap !important; text-wrap: avoid">
                                                <label class="ckbox"><input <?= esc_attr( get_option( 'sp.log', 'checked' ) ); ?> type="checkbox" onchange="jQuery.get('<?= plugin_dir_url( __FILE__ ); ?>ajax.php?log='+jQuery(this).prop('checked'));"><span style="display: inline-block;padding-top: 3px;"><?= __( 'Debug Log', QA_MAIN_DOMAIN ); ?></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h3 class="card-title mb-2"><?= __( 'Orders Analytics', QA_MAIN_DOMAIN ); ?></h3>
                                        <p class="tx-12 mb-0 text-muted">
											<?= __( 'Shelf Planner collects and analyze your store historical data in order to build forecasts. This diagram shows the orders import progress.', QA_MAIN_DOMAIN ); ?>
                                            <br><br>
                                        </p>
                                    </div>
                                    <div class="card-body sales-info ot-0 pt-0 pb-0">
                                        <div id="chart-sp" class="ht-150" style="margin-bottom: 2em !important;"></div>
                                        <div class="row sales-infomation pb-0 mb-0 mx-auto wd-100p">
                                            <div class="col-md-6 col">
                                                <p class="mb-0 d-flex"><span class="legend bg-primary brround"></span>Analyzed </p>
                                                <h3 class="mb-1" id="sp-analyzed-orders-count"><?= ShelfPlannerCore::getAnalyzedOrdersCount(); ?></h3>
                                                <div class="d-flex">
                                                    <p class="text-muted "><?= __( 'All Time Data', QA_MAIN_DOMAIN ); ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col">
                                                <p class="mb-0 d-flex"><span class="legend bg-info brround"></span><?= __( 'Total', QA_MAIN_DOMAIN ); ?></p>
                                                <h3 class="mb-1" id="sp-total-orders-count"><?= esc_html( ShelfPlannerCore::getOrdersCount() ); ?></h3>
                                                <div class="d-flex">
                                                    <p class="text-muted"><?= __( 'All Time Data', QA_MAIN_DOMAIN ); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="main-content-label mg-b-5">
											<?= __( 'Debug Log', QA_MAIN_DOMAIN ); ?>
                                        </div>
                                        <p class="mg-b-20"></p>
                                        <div class="row">
                                            <div class="col-md-12 col">
                                                <div class="d-flex">
                                                    <p class="">
                                                        <a href="<?= plugin_dir_url( SP_FILE_INDEX ) ?>api.log?<?= time(); ?>" target="_blank" class="btn btn-info"><i class="fa fa-eye"></i> <?= __( 'View Debug Log', QA_MAIN_DOMAIN ); ?></a>
                                                        <a href="javascript:void(0);" onclick="jQuery.get('<?= get_admin_url( null, '?sp_purge_api_log' ); ?>');
                                                        alert('<?= __( 'Log was successfully purged', QA_MAIN_DOMAIN ); ?>');" class="btn btn-danger"><i class="fa fa-trash"></i> <?= __( 'Purge Debug Log', QA_MAIN_DOMAIN ); ?></a>
                                                        <br><br><a href="javascript:void(0);" onclick="jQuery.get('<?= get_admin_url( null, '?sp_clear_api_sent_entries' ); ?>'); alert('<?= __( 'Entries was successfully cleared', QA_MAIN_DOMAIN ); ?>');" class="btn btn-danger"><i class="fa fa-trash"></i>
															<?= __( 'Clear API Sent Entries', QA_MAIN_DOMAIN ); ?></a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once SP_ROOT_DIR . '/footer.php';