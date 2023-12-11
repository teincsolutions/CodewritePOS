<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Purchases List</h4>
            <h6>Manage your purchases</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('purchases/create') ?>" class="btn btn-added"><i class="fa fa-plus" class="me-1"></i>Add Purchases</a>
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
                                    <input type="text" class="datetimepicker" placeholder="From date" id="date-from">
                                    <div class="addonset">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="input-groupicon">
                                    <input type="text" class="datetimepicker" placeholder="To date" id="date-to">
                                    <div class="addonset">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="store_id" class="select2-store">
                                    <?php
                                    if (isset($stores))
                                        foreach ($stores as $row) { ?>
                                        <option value="<?= $row->id ?>" <?=($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
                                            <?= $row->name; ?> (<?= $row->location; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12"  style="overflow-x: auto;">
                            <div class="form-group">
                                <select name="supplier_id" class="select2-supplier" >
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
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
                <table id="dt-purchases" class="table">
                    <thead>
                        <tr>
                            <th>
                            </th>
                            <th>Date</th>
                            <th>Supplier Name</th>
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
<form action="<?= site_url('suppliers/ledgers') ?>" class="modal fade" id="add-payment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
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
                            <select name="purchase_id" class="select2-invoices" required>
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
                            <input type="text" onkeyup="$('#inv-due').val(($('#inv-bal').val()- $(this).val()).toFixed(2))" name="debit" min="0" value="" placeholder="Enter Amount" required>
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
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables/purchases.js?v=19') ?>"></script>
<?= $this->endSection() ?>