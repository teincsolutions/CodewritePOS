<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sales List</h4>
            <h6>Manage your sales</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('sales/pos') ?>" class="btn btn-added"><i class="fa fa-plus" class="me-1"></i>Add Sales</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                    </div>
                </div>
                <div class="wordset">
                </div>
            </div>
            <div class="card" id="filter_inputs9">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="input-groupicon">
                                    <input type="text" class="datetimepicker" placeholder="From date" id="date-from" value="<?= date('d-m-Y', strtotime('first day of this month')) ?>">
                                    <div class="addonset">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="input-groupicon">
                                    <input type="text" class="datetimepicker" placeholder="To date" id="date-to" value="<?= date('d-m-Y', strtotime('last day of this month')) ?>">
                                    <div class="addonset">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="type" class="select">
                                    <option value="">Select type</option>
                                    <option value="walk-in-customer">walk-in-customer</option>
                                    <option value="customer">regular customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12" style="overflow-x: auto;">
                            <div class="form-group">
                                <select name="store_id" class="select2-store">
                                    <?php
                                    if (isset($stores))
                                        foreach ($stores as $row) { ?>
                                        <option value="<?= $row->id ?>" <?= ($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
                                            <?= $row->name; ?> (<?= $row->location; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="payment_type" class="select">
                                    <option value="">Select method</option>
                                    <option value="cash">Cash</option>
                                    <option value="momo">MoMo</option>
                                    <option value="credit">Credit Card</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12" style="overflow-x: auto;">
                            <div class="form-group">
                                <select name="customer_id" class="select2-customer">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="payment_status" class="select">
                                    <option value="">Select a status</option>
                                    <option value="due">Due</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12">
                            <div class="form-group">
                                <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="dt-sales" class="table">
                    <thead>
                        <tr>
                            <th>
                            </th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Biller</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('modal') ?>
<form action="<?= site_url('customers/ledgers') ?>" class="modal fade" id="add-payment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment Date</label>
                            <div class="input-groupicon">
                                <input type="text" name="tdate" value="<?= date('d-m-Y', time()) ?>" class="datetimepicker" required>
                                <div class="addonset">
                                    <i class="fa fa-calendar fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Reference</label>
                            <select name="sale_id" class="select2-invoices" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="customer_id">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Invoice Balance</label>
                            <input id="inv-bal" type="text" name="invoice_balance" value="0.00" placeholder="Enter Amount" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="text" onkeyup="$('#inv-due').val(($('#inv-bal').val()- $(this).val()).toFixed(2))" name="credit" min="0" value="" placeholder="Enter Amount" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Amount Due</label>
                            <input id="inv-due" type="text" value="0.00" placeholder="Enter Amount" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment type</label>
                            <select class="select" required>
                                <option value="cash">Cash</option>
                                <option value="momo">MoMo</option>
                                <option value="credit">Credit Card</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</form>
<form action="<?= site_url('sales/items/save') ?>" class="modal fade" id="editItemsForm" tabindex="-1" aria-labelledby="editItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemsModalLabel">Edit Sales Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->include('pages/sales/edit_items') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables/sales.js?v=22') ?>"></script>
<script>
$(function () {
    // Handle Edit Items button click for sales
    $(document).on("click", ".edit-sales-items", function () {
        var id = $(this).data("id");
        var invoice = $(this).data("invoice");
        
        // Show loading state
        Swal.fire({
            title: "Loading items...",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // AJAX load items for this sale
        $.ajax({
            url: baseUrl + "/sales/items/datatable",
            type: "GET",
            data: { sale_id: id },
            dataType: "json",
            success: function (response) {
                if (response.status && response.data) {
                    var items = response.data;
                    var form = $("#editItemsForm");
                    
                    // Set sale_id hidden field
                    form.find("input[name='sale_id']").val(id);
                    
                    // Clear and populate item table
                    var tbody = form.find("tbody");
                    tbody.empty();
                    
                    var idx = 1;
                    $.each(items, function (k, row) {
                        var subtotal = row.subtotal ? parseFloat(row.subtotal) : 0;
                        var unit_price = row.unit_price ? parseFloat(row.unit_price) : 0;
                        var qty = row.qty ? parseFloat(row.qty) : 0;
                        var discount = row.discount ? parseFloat(row.discount) : 0;
                        var tax = row.tax ? parseFloat(row.tax) : 0;
                        
                        var rowHtml = `
                            <tr>
                                <td>${idx}</td>
                                <td>
                                    ${row.product_name ? row.product_name : 'N/A'}
                                    ${row.product_sku ? ' (' + row.product_sku + ')' : ''}
                                </td>
                                <td>
                                    <input type="number" name="items[${row.id}][qty]" value="${qty}" min="0" class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <input type="number" name="items[${row.id}][unit_price]" value="${unit_price}" min="0" step="0.01" class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <input type="number" name="items[${row.id}][discount]" value="${discount}" min="0" step="0.01" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="number" name="items[${row.id}][tax]" value="${tax}" min="0" step="0.01" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="text" name="items[${row.id}][subtotal]" value="${subtotal.toFixed(2)}" readonly class="form-control form-control-sm">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm delete-item-row" data-id="${row.id}"><i class="fa fa-trash"></i> Remove</button>
                                </td>
                            </tr>`;
                        tbody.append(rowHtml);
                        idx++;
                    });
                    
                    // Show modal
                    $("#editItemsModal").find(".modal-title").text("Edit Sales Items - " + invoice);
                    $("#editItemsModal").modal("show");
                } else {
                    Swal.fire({
                        icon: "error",
                        text: response.message || "Failed to load items!"
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    text: "Unable to load items. Please try again."
                });
            }
        });
    });
    
    // Handle form submit for editing items
    $(document).on("submit", "#editItemsForm", function (e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = new FormData(form[0]);
        
        Swal.fire({
            title: "Saving changes...",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        icon: "success",
                        text: response.message
                    });
                    $("#editItemsModal").modal("hide");
                    // Reload the sales datatable
                    table = $("#dt-sales").DataTable();
                    table.ajax.reload();
                } else {
                    Swal.fire({
                        icon: "error",
                        text: response.message
                    });
                }
            },
            error: function () {
              Swal.fire({
                icon: "error",
                text: "Unable to save changes. Please try again."
              });
            }
        });
    });
    
    // Handle remove row in edit items form
    $(document).on("click", ".delete-item-row", function () {
        $(this).closest("tr").remove();
        recalculateEditItemsTotal();
    });
    
    function recalculateEditItemsTotal() {
        var total = 0;
        $("#editItemsForm tbody tr").each(function () {
            var subtotal = parseFloat($(this).find("input[name*='subtotal']").val()) || 0;
            total += subtotal;
        });
        $("#editItemsTotal").html(total.toFixed(2));
    }
});
</script>
<?= $this->endSection() ?>