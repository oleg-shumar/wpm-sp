<?php

global $wpdb;

require_once __DIR__ . '/admin_page_header.php';

if ( $_POST && isset( $_POST['save-warehouse'] ) ) {
	unset( $_POST['save-warehouse'] );
	$_POST['warehouse_use_same'] = intval( 'same' === $_POST['warehouse_use_same'] );

	$data = $_POST;

	if ( isset( $_GET['warehouse_id'] ) ) {
		$result = $wpdb->update( $wpdb->warehouses, $data, [
			'id' => (int) $_GET['warehouse_id']
		] );
		$msg    = 'Warehouse was updated successfully';
	} else {
		$result = $wpdb->insert( $wpdb->warehouses, $data );
		$msg    = 'Warehouse was added successfully';
	}
	if ( $result ) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
		<?= $msg; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div><?php
	} else { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= __( 'Error occured, please try again', QA_MAIN_DOMAIN ); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div><?php }
}

// TODO: replace using Methods class
$warehouses = [];
$tmp        = $wpdb->get_results( "select * from {$wpdb->warehouses}", ARRAY_A );
if ( $tmp ) {
	foreach ( $tmp as &$row ) {
		$row['warehouse_edit_link']   = "/wp-admin/admin.php?page=shelf_planner_warehouses&warehouse_id={$row['id']}";
		$warehouses[ $row['id'] ]     = $row;
		$row['warehouse_name']        = stripslashes( (string) $row['warehouse_name'] );
		$row['warehouse_address']     = stripslashes( (string) $row['warehouse_address'] );
		$row['warehouse_postal_code'] = stripslashes( (string) $row['warehouse_postal_code'] );
		$row['warehouse_city']        = stripslashes( (string) $row['warehouse_city'] );
		$row['warehouse_country']     = stripslashes( (string) $row['warehouse_country'] );
		$row['warehouse_phone']       = stripslashes( (string) $row['warehouse_phone'] );
		$row['warehouse_website']     = stripslashes( (string) $row['warehouse_website'] );
		$row['warehouse_email']       = stripslashes( (string) $row['warehouse_email'] );
	}
}

?>
<?php require_once __DIR__ . '/../' . 'header.php'; ?>

    <div class="sp-admin-overlay">

        <div class="sp-admin-container">

			<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>

            <!-- main-content opened -->
            <div class="main-content horizontal-content">

                <div class="page">
                    <!-- container opened -->
                    <div class="container">
                        <link rel="stylesheet"
                              href="<?= SP_PLUGIN_DIR_URL; ?>assets/bootstrap.min.css">
                        <link href="<?= SP_PLUGIN_DIR_URL; ?>assets/tabulator.min.css"
                              rel="stylesheet">

                        <link rel="stylesheet" href="<?= SP_PLUGIN_DIR_URL; ?>assets/flat-ui.css">
                        <link rel="stylesheet" href="<?= SP_PLUGIN_DIR_URL; ?>assets/common.css">

                        <script src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/bootstrap.min.js"></script>
                        <script src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/moment.min.js"></script>

                        <script type="text/javascript"
                                src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/tabulator.min.js"></script>
                        <script type="text/javascript"
                                src="<?= SP_PLUGIN_DIR_URL; ?>assets/js/xlsx.full.min.js"></script>

                        <h4><?= __( 'Warehouses', QA_MAIN_DOMAIN ); ?></h4>

                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?= __( 'Add New', QA_MAIN_DOMAIN ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">

                                        <div style="text-align: left; margin-bottom:
										5px">
                                            <button id="js-add-new"
                                                    onclick="window.location = '/wp-admin/admin.php?page=shelf_planner_warehouses&new';"
                                                    class="btn btn-sm btn-success">Add New
                                            </button>
                                        </div>
                                        <div id="table_1"></div>

                                        <script>

                                            //custom max min header filter
                                            var minMaxFilterEditor = function (cell, onRendered, success, cancel, editorParams) {

                                                var end;
                                                var container = document.createElement("span");
                                                //create and style inputs
                                                var start = document.createElement("input");
                                                start.setAttribute("type", "number");
                                                start.setAttribute("placeholder", "Min");
                                                start.style.padding = "4px";
                                                start.style.width = "50%";
                                                start.style.boxSizing = "border-box";
                                                start.value = cell.getValue();

                                                function buildValues() {
                                                    success({
                                                        start: start.value,
                                                        end: end.value,
                                                    });
                                                }

                                                function keypress(e) {
                                                    if (e.keyCode == 13) {
                                                        buildValues();
                                                    }

                                                    if (e.keyCode == 27) {
                                                        cancel();
                                                    }
                                                }

                                                end = start.cloneNode();
                                                end.setAttribute("placeholder", "Max");

                                                start.addEventListener("change", buildValues);
                                                start.addEventListener("blur", buildValues);
                                                start.addEventListener("keydown", keypress);

                                                end.addEventListener("change", buildValues);
                                                end.addEventListener("blur", buildValues);
                                                end.addEventListener("keydown", keypress);

                                                container.appendChild(start);
                                                container.appendChild(end);

                                                return container;
                                            }

                                            //custom max min filter function
                                            function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams) {
                                                //headerValue - the value of the header filter element
                                                //rowValue - the value of the column in this row
                                                //rowData - the data for the row being filtered
                                                //filterParams - params object passed to the headerFilterFuncParams property

                                                if (rowValue) {
                                                    if (headerValue.start != "") {
                                                        if (headerValue.end != "") {
                                                            return rowValue >= headerValue.start && rowValue <= headerValue.end;
                                                        } else {
                                                            return rowValue >= headerValue.start;
                                                        }
                                                    } else {
                                                        if (headerValue.end != "") {
                                                            return rowValue <= headerValue.end;
                                                        }
                                                    }
                                                }

                                                return true; //must return a boolean, true if it passes the filter.
                                            }

                                            var tabledata = <?=json_encode( $tmp );?>;
                                            var table = new Tabulator("#table_1", {
                                                // height:"311px",
                                                layout: "fitColumns",
                                                responsiveLayout: "collapse",
                                                data: tabledata,
                                                columns: [
                                                    {
                                                        title: "<?=__( 'Name', QA_MAIN_DOMAIN );?>",
                                                        field: "warehouse_edit_link",
                                                        formatter: "link",
                                                        formatterParams: {
                                                            labelField: "warehouse_name",
                                                            urlPrefix: "",
                                                            target: "",
                                                        }
                                                    },
                                                    {
                                                        title: "<?=__( 'Email', QA_MAIN_DOMAIN );?>",
                                                        field: "warehouse_email",
                                                        headerFilter: "input",
                                                        formatter: "link",
                                                        formatterParams: {
                                                            labelField: "email_for_ordering",
                                                            urlPrefix: "mailto://",
                                                            target: "_blank",
                                                        }
                                                    },
                                                    {
                                                        title: "<?=__( 'Country / Region', QA_MAIN_DOMAIN );?>",
                                                        field: "warehouse_country",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: true
                                                    },
                                                    {
                                                        title: "<?=__( 'City', QA_MAIN_DOMAIN );?>",
                                                        field: "warehouse_city",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: true
                                                    },
                                                    {
                                                        title: "<?=__( 'Address', QA_MAIN_DOMAIN );?>",
                                                        field: "warehouse_address",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: true
                                                    },
                                                ],
                                            });
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card"
						     <?php if ( ! isset( $_GET['warehouse_id'] ) && ! isset( $_GET['new'] ) ) { ?>style="display: none"<?php } ?>>
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?php if ( ! isset( $_GET['warehouse_id'] ) ) { ?>New <?php }
									?><?= __( 'Warehouse Details', QA_MAIN_DOMAIN ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">

										<?php
										if ( isset( $_GET['warehouse_id'] ) || isset( $_GET['new'] ) ) {
											if ( isset( $_GET['warehouse_id'] ) ) {
												$warehouse = $warehouses[ $_GET['warehouse_id'] ];
											} else {
												$warehouse = [
													'warehouse_use_same'    => false,
													'warehouse_name'        => '',
													'warehouse_address'     => '',
													'warehouse_postal_code' => '',
													'warehouse_city'        => '',
													'warehouse_country'     => '',
													'warehouse_phone'       => '',
													'warehouse_website'     => '',
													'warehouse_email'       => '',
												];
											}
											?>
                                            <form method="post">

                                                <p style="font-weight: bold; font-size: inherit"
                                                ><?= __( 'Here you can define the information about your warehouses and shipping addresses.', QA_MAIN_DOMAIN ); ?></p>

                                                <div class="row" id="js-add-new-supplier">
                                                    <div class="col-md-4 ">
                                                        <p>
                                                            <input type="radio"
                                                                   name="warehouse_use_same"
                                                                   id="wh-alternate-delivery-1"
                                                                   value="same" <?= $warehouse['warehouse_use_same'] ? '' : 'checked="checked"'; ?> /><label for="wh-alternate-delivery-1" style="font-weight: normal"><?= __( 'Delivery Address is the same as company address.', QA_MAIN_DOMAIN ); ?></label>
                                                        </p>
                                                        <p>
                                                            <input type="radio"
                                                                   name="warehouse_use_same"
                                                                   id="wh-alternate-delivery-2"
                                                                   value="alter" <?= $warehouse['warehouse_use_same'] ? '' : 'checked="checked"'; ?> /><label for="wh-alternate-delivery-2" style="font-weight: normal"><?= __( 'Deliver my order to this address:', QA_MAIN_DOMAIN ); ?></label>
                                                        </p>
                                                    </div>
                                                </div>


                                                <!-- begin new form -->
                                                <div class="row" id="js-add-new-supplier">
                                                    <div class="col-md-4 ">
                                                        <label><?= __( 'Warehouse Name', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_name"
                                                               required="required"
                                                               value="<?= esc_attr( $warehouse['warehouse_name'] ); ?>" placeholder="Warehouse Name*"/>
                                                        <label><?= __( 'Warehouse Address', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_address"
                                                               required="required"
                                                               value="<?= esc_attr( $warehouse['warehouse_address'] ); ?>" placeholder="Warehouse Address*"/>
                                                        <label><?= __( 'Postal Code', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_postal_code"
                                                               required="required"
                                                               value="<?= esc_attr( $warehouse['warehouse_postal_code'] ); ?>" placeholder="Postal Code*"/>
                                                        <label><?= __( 'City', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_city"
                                                               required="required"
                                                               value="<?= esc_attr( $warehouse['warehouse_city'] ); ?>" placeholder="City*"/>
                                                        <label><?= __( 'Country', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_country"
                                                               required="required"
                                                               value="<?= esc_attr( $warehouse['warehouse_country'] ); ?>" placeholder="Country*"/>
                                                        <br>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><?= __( 'Phone', QA_MAIN_DOMAIN ); ?>
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_phone"
                                                               value="<?= esc_attr( $warehouse['warehouse_phone'] ); ?>"
                                                               placeholder="Phone"/>
                                                        <label><?= __( 'Website', QA_MAIN_DOMAIN ); ?>
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_website"
                                                               value="<?= esc_attr( $warehouse['warehouse_website'] ); ?>"
                                                               placeholder="Website"/>
                                                        <label><?= __( 'Email', QA_MAIN_DOMAIN ); ?>
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               name="warehouse_email"
                                                               value="<?= esc_attr( $warehouse['warehouse_email'] ); ?>"
                                                               placeholder="Email"/>
                                                    </div>
                                                </div>
                                                <!-- end new form -->
                                                <p class="mg-b-20"></p>
                                                <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success"
                                                       value="<?= __( 'Save', QA_MAIN_DOMAIN ); ?>"
                                                       name="save-warehouse"/>
                                            </form>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once __DIR__ . '/../' . 'footer.php';