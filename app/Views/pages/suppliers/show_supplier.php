<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Supplier Details</h4>
            <h6>Full details of a supplier</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('suppliers') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-2"></i>List Suppliers</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-header">
                    <h5 class="card-title">Details of <?= $supplier->name; ?></h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item"><a class="nav-link active" href="#supplier-tab" data-bs-toggle="tab">Supplier</a></li>
                        <li class="nav-item"><a class="nav-link" href="#ledger-tab" data-bs-toggle="tab">Account Book</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bills-tab" data-bs-toggle="tab">Purchases</a></li>
                        <li class="nav-item"><a class="nav-link" href="#returns-tab" data-bs-toggle="tab">Returns</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="supplier-tab">
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <h3>Personal Information</h3>
                                    <div class="productdetails">
                                        <ul class="product-bar">
                                            <li>
                                                <h4>Supplier Name</h4>
                                                <h6><?= $supplier->name ?></h6>
                                            </li>
                                            <li>
                                                <h4>Address</h4>
                                                <h6><?= $supplier->address ?></h6>
                                            </li>
                                            <li>
                                                <h4>Email</h4>
                                                <h6><?= $supplier->email ?></h6>
                                            </li>
                                            <li>
                                                <h4>Phone Number</h4>
                                                <h6><?= $supplier->phone ?></h6>
                                            </li>
                                            <li>
                                                <h4>Note</h4>
                                                <h6><?= $supplier->note ?></h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3>Account Information</h3>
                                    <div class="productdetails">
                                        <ul class="product-bar">
                                            <li>
                                                <h4>Account Balance</h4>
                                                <h6>GHS <?= $supplier->balance < 0 ? "(" . number_format(abs($supplier->balance), 2) . ")" : number_format($supplier->balance, 2) ?></h6>
                                            </li>
                                            <li>
                                                <h4>Status</h4>
                                                <h6 class="text-capitalize <?= ['text-danger', 'text-success'][$supplier->status === 'opened' ? 1 : 0] ?>"><?= $supplier->status ?></h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="ledger-tab">
                            <div class="row mt-5">
                                <div class="col-md-12 mb-3 d-flex justify-content-end">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#add-payment" class="btn btn-primary btn-sm"><i class="fa fa-plus me-2"></i>Add Payment</button>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="dt-ledger" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>#Code</th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Credit</th>
                                                    <th>Debit</th>
                                                    <th>A/c Balance</th>
                                                    <th>Added By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bills-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search">
                                                    <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                                                    <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><img src="<?= base_url('assets/icons/search-white.svg') ?>" alt="img"></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                        </div>
                                    </div>

                                    <div class="card" id="filter_inputs">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <input type="hidden" name="supplier_id" value="<?= $supplier->id; ?>">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <select name="payment_status" class="select">
                                                            <option value="">Select a status</option>
                                                            <option value="due">Due</option>
                                                            <option value="paid">Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="dt-purchases" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                    </th>
                                                    <th>Date</th>
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
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="returns-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search1">
                                                    <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                                                    <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><img src="<?= base_url('assets/icons/search-white.svg') ?>" alt="img"></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                        </div>
                                    </div>

                                    <div class="card" id="filter_inputs1">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <input type="hidden" name="purchases.supplier_id" value="<?= $supplier->id; ?>">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <select name="payment_status" class="select">
                                                            <option value="">Select a status</option>
                                                            <option value="due">Due</option>
                                                            <option value="paid">Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="dt-returns" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                    </th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th>Total</th>
                                                    <th>Paid</th>
                                                    <th>Biller</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
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
                    <input type="hidden" name="supplier_id">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Invoice Balance</label>
                            <input id="inv-bal" type="text" value="0.00" placeholder="Enter Amount" disabled>
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
                            <select name="payment_type" class="select" required>
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

<form action="<?= site_url('suppliers/ledgers') ?>" class="modal fade" id="edit-payment" tabindex="-1" aria-labelledby="editpayment" aria-hidden="true">
    <input type="hidden" name="id">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Payment</h5>
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
                            <select name="purchase_id" class="select2-invoices" disabled required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="text" name="debit" min="0" value="" placeholder="Enter Amount" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment type</label>
                            <select name="payment_type" class="select" required>
                                <option value="cash">Cash</option>
                                <option value="momo">MoMo</option>
                                <option value="credit">Credit Card</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-submit">Save Changes</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js?v=1') ?>"></script>
<script src="<?= base_url('assets/js/supplier-details.js?v=4') ?>"></script>
<?= $this->endSection() ?>