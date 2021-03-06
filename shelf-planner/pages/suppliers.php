<?php

require_once __DIR__ . '/admin_page_header.php';

if ( $_POST ) {
	$data             = $_POST;
	$data['dt_added'] = current_time( 'mysql', 1 );

	if ( isset( $_GET['supplier_id'] ) ) {
		$result = $wpdb->update( $wpdb->suppliers, $data, [ 'id' => (int) $_GET['supplier_id'] ] );
		$msg    = 'Supplier was updated successfully';
	} else {
		$result = $wpdb->insert( $wpdb->suppliers, $data );
		$msg    = 'Supplier was added successfully';
	}
	if ( $result ) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
		<?= esc_html( $msg ) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><?php
	} else { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= __( 'Error occurred, please try again', QA_MAIN_DOMAIN ); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div><?php }
}

// TODO: replace using Methods class
$suppliers = [];
$tmp       = $wpdb->get_results( "
    select a.*,
        count(distinct po1.id) as orders,
        count(distinct po2.id) as total_orders,
        concat('/wp-admin/admin.php?page=shelf_planner_suppliers&supplier_id=', a.id)
            as supplier_edit_link
    from {$wpdb->suppliers} a
    
    left join `{$wpdb->purchase_orders}` po1 on po1.supplier_id = a.id and po1.status != 'Completed'
    left join `{$wpdb->purchase_orders}` po2 on po2.supplier_id = a.id and po2.status = 'Completed'
    
    group by a.id, a.supplier_name, a.supplier_code, a.tax_vat_number, a.phone_number, a.website, a.email_for_ordering, a.general_email_address, a.`description`, a.currency, a.address, a.city, a.country, a.state, a.zip_code, a.assigned_to, a.ship_to_location, a.discount, a.tax_rate, a.lead_times, a.dt_added, supplier_edit_link
", ARRAY_A );
if ( $tmp ) {
	foreach ( $tmp as $row ) {
		$suppliers[ $row['id'] ] = $row;
	}
}

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
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
                        <h4><?= __( 'Suppliers', QA_MAIN_DOMAIN ); ?></h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?= __( 'Add New', QA_MAIN_DOMAIN ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <div style="float:left;text-align: left;margin-bottom: 5px; width:30%">
                                            <button id="js-add-new" onclick="window.location = '/wp-admin/admin.php?page=shelf_planner_suppliers&new';" class="btn btn-sm btn-success">Add New
                                            </button>
                                        </div>
                                        <div style="float:right;text-align: right;margin-bottom: 5px;width:70%">
                                            <button id="download-csv" class="btn btn-sm btn-info"><?= __( 'Download CSV', QA_MAIN_DOMAIN ); ?>
                                            </button>
                                            <button id="download-json" class="btn btn-sm btn-info"><?= __( 'Download JSON', QA_MAIN_DOMAIN ); ?>
                                            </button>
                                            <button id="download-xlsx" class="btn btn-sm btn-info"><?= __( 'Download XLSX', QA_MAIN_DOMAIN ); ?>
                                            </button>
                                            <button id="download-html" class="btn btn-sm btn-info"><?= __( 'Download HTML', QA_MAIN_DOMAIN ); ?>
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
                                                        field: "supplier_edit_link",
                                                        formatter: "link", /*headerFilter: "input", headerFilterLiveFilter: true,*/
                                                        formatterParams: {
                                                            labelField: "supplier_name",
                                                            urlPrefix: "",
                                                            target: "",
                                                        }
                                                    },
                                                    {
                                                        title: "<?=__( 'Created', QA_MAIN_DOMAIN );?>",
                                                        field: "dt_added",
                                                        hozAlign: "left",
                                                        sorter: "date",
                                                        headerFilter: "input",
                                                        formatter: "datetime",
                                                        formatterParams: {
                                                            inputFormat: "YYYY-MM-DD H:m:s",
                                                            outputFormat: "LL",
                                                            invalidPlaceholder: "(invalid date)",
                                                        }
                                                    },
                                                    {
                                                        title: "<?=__( 'Email', QA_MAIN_DOMAIN );?>",
                                                        field: "email_for_ordering",
                                                        headerFilter: "input",
                                                        formatter: "link",
                                                        formatterParams: {
                                                            labelField: "email_for_ordering",
                                                            urlPrefix: "mailto://",
                                                            target: "_blank",
                                                        }
                                                    },
                                                    {
                                                        title: "<?=__( 'Orders', QA_MAIN_DOMAIN );?>",
                                                        field: "orders",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?=__( 'Completed Orders', QA_MAIN_DOMAIN );?>",
                                                        field: "total_orders",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?=__( 'Country / Region', QA_MAIN_DOMAIN );?>",
                                                        field: "country",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: true
                                                    },
                                                    {
                                                        title: "<?=__( 'City', QA_MAIN_DOMAIN );?>",
                                                        field: "city",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: true
                                                    },

                                                ],
                                            });

                                            //trigger download of data.csv file
                                            document.getElementById("download-csv").addEventListener("click", function () {
                                                table.download("csv", "data.csv");
                                            });

                                            //trigger download of data.json file
                                            document.getElementById("download-json").addEventListener("click", function () {
                                                table.download("json", "data.json");
                                            });

                                            //trigger download of data.xlsx file
                                            document.getElementById("download-xlsx").addEventListener("click", function () {
                                                table.download("xlsx", "data.xlsx", {sheetName: "My Data"});
                                            });

                                            //trigger download of data.html file
                                            document.getElementById("download-html").addEventListener("click", function () {
                                                table.download("html", "data.html", {style: true});
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" <?php if ( ! isset( $_GET['supplier_id'] ) && ! isset( $_GET['new'] ) ) { ?>style="display: none"<?php } ?>>
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?php if ( ! isset( $_GET['supplier_id'] ) ) { ?>New<?php } ?><?= __( 'Supplier Details', QA_MAIN_DOMAIN ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
										<?php
										if ( isset( $_GET['supplier_id'] ) || isset( $_GET['new'] ) ) {
											if ( isset( $_GET['supplier_id'] ) ) {
												$supplier = $suppliers[ $_GET['supplier_id'] ];
											} else {
												$supplier = [
													'supplier_name'         => '',
													'supplier_code'         => '',
													'tax_vat_number'        => '',
													'phone_number'          => '',
													'website'               => '',
													'email_for_ordering'    => '',
													'general_email_address' => '',
													'supplier_id'           => '',
													'currency'              => '',
													'address'               => '',
													'city'                  => '',
													'country'               => '',
													'state'                 => '',
													'zip_code'              => '',
													'account_no'            => '',
													'assigned_to'           => '',
													'ship_to_location'      => '',
													'discount'              => '',
													'tax_rate'              => '',
													'lead_times'            => '',
													'weeks_of_stock'        => '',
													'description'           => '',
													'account_id'            => '',
													'payment_terms'         => '',
													'delivery_terms'        => '',
												];
											}
											?>
                                            <form method="post" action="">
                                                <div class="row" id="js-add-new-supplier">
                                                    <div class="col-md-4 ">
                                                        <label><?= __( 'Supplier Name', QA_MAIN_DOMAIN ); ?>*</label> <input type="text" class="form-control" name="supplier_name" required="required" value="<?= esc_attr( $supplier['supplier_name'] ); ?>" placeholder="Supplier Name*"/> <label><?= __( 'Supplier Code', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control" name="supplier_code" required="required" value="<?= esc_attr( $supplier['supplier_code'] ); ?>" placeholder="Supplier Code*"/>
                                                        <label><?= __( 'TAX / VAT Number', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="tax_vat_number" value="<?= esc_attr( $supplier['tax_vat_number'] ); ?>" placeholder="TAX / VAT Number"/>
                                                        <label><?= __( 'Phone Number', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="phone_number" value="<?= esc_attr( $supplier['phone_number'] ); ?>" placeholder="Phone Number"/>
                                                        <label><?= __( 'Website', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="website" value="<?= esc_attr( $supplier['website'] ); ?>" placeholder="Website"/>
                                                        <label><?= __( 'Email for Ordering', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control" name="email_for_ordering" value="<?= esc_attr( $supplier['email_for_ordering'] ); ?>" placeholder="Email for Ordering*"/>
                                                        <label><?= __( 'General Email Address', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="general_email_address" value="<?= esc_attr( $supplier['general_email_address'] ); ?>" placeholder="General Email Address"/> <label><?= __( 'Description', QA_MAIN_DOMAIN ); ?></label>
                                                        <textarea class="form-control" name="description" placeholder="Description"><?= esc_textarea( $supplier['description'] ); ?></textarea> <br>
                                                        <input type="submit" class="btn btn-success" value="<?php if ( ! isset( $_GET['supplier_id'] ) ) { ?>Add New Supplier<?php } else { ?>Save<?php } ?>"/>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><?= __( 'Currency', QA_MAIN_DOMAIN ); ?>
                                                        </label> <input type="text" class="form-control" name="currency" required="required" value="<?= esc_attr( $supplier['currency'] ); ?>" placeholder="Currency"/>
                                                        <label><?= __( 'Address', QA_MAIN_DOMAIN ); ?>
                                                        </label> <input type="text" class="form-control" name="address" value="<?= esc_attr( $supplier['address'] ); ?>" placeholder="Address"/>
                                                        <label><?= __( 'City', QA_MAIN_DOMAIN ); ?>*</label> <input type="text" class="form-control" name="city" value="<?= esc_attr( $supplier['city'] ); ?>" placeholder="City*"/>
                                                        <label><?= __( 'Country', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control" name="country" value="<?= esc_attr( $supplier['country'] ); ?>" placeholder="Country*"/>
                                                        <label>State</label> <input type="text" class="form-control" name="state" value="<?= esc_attr( $supplier['state'] ); ?>" placeholder="State"/>
                                                        <label><?= __( 'Zip Code', QA_MAIN_DOMAIN ); ?>
                                                        </label> <input type="text" class="form-control" name="zip_code" value="<?= esc_attr( $supplier['zip_code'] ); ?>" placeholder="Zip Code"/>
                                                        <label><?= __( 'Account Number', QA_MAIN_DOMAIN ); ?>
                                                        </label> <input type="text" class="form-control" name="account_no" value="<?= esc_attr( $supplier['account_no'] ); ?>" placeholder="Account Number"/>
                                                        <label><?= __( 'Account ID', QA_MAIN_DOMAIN ); ?>
                                                        </label> <input type="text" class="form-control" name="account_id" value="<?= esc_attr( $supplier['account_id'] ); ?>" placeholder="Account ID"/>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><?= __( 'Assigned To', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="assigned_to" required="required" value="<?= esc_attr( $supplier['assigned_to'] ); ?>" placeholder="Assigned To"/>
                                                        <label><?= __( 'Ship To Location', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="ship_to_location" value="<?= esc_attr( $supplier['ship_to_location'] ); ?>" placeholder="Ship To Location"/>
                                                        <label><?= __( 'Discount', QA_MAIN_DOMAIN ); ?>
                                                        </label> <input type="text" class="form-control" name="discount" value="<?= esc_attr( $supplier['discount'] ); ?>" placeholder="Discount"/>
                                                        <label><?= __( 'Tax Rate', QA_MAIN_DOMAIN ); ?>
                                                        </label> <input type="text" class="form-control" name="tax_rate" value="<?= esc_attr( $supplier['tax_rate'] ); ?>" placeholder="Tax Rate"/>
                                                        <label><?= __( 'Lead Times (in weeks)', QA_MAIN_DOMAIN ); ?>*</label>
                                                        <input type="text" class="form-control" name="lead_times" required="required" value="<?= esc_attr( $supplier['lead_times'] ); ?>" placeholder="Lead Times (in weeks) *"/> <label><?= __( 'Payment Terms', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="payment_terms" value="<?= esc_attr( $supplier['payment_terms'] ); ?>" placeholder="Payment Terms"/>
                                                        <label><?= __( 'Delivery Terms', QA_MAIN_DOMAIN ); ?></label>
                                                        <input type="text" class="form-control" name="delivery_terms" value="<?= esc_attr( $supplier['delivery_terms'] ); ?>" placeholder="Delivery Terms"/>
                                                    </div>
                                                </div>
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