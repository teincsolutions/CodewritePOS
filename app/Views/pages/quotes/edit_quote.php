<?= $this->extend('template/blank') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= $title ?? "Add Quote" ?></h4>
            <h6>Manage your quote</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('sales/pos') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-1"></i> POS</a>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('quotes') ?>" method="post">
        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= isset($quote) ? $quote->id : null ?>">
                <input type="hidden" name="invoice" value="<?= isset($quote) ? $quote->invoice : $invoice ?>">
                <input id="quote-type" type="hidden" name="type" value="<?= isset($quote) ? $quote->type : null ?>">
                <input id="quote-total" type="hidden" name="total_amount" value="<?= isset($quote) ? $quote->total_amount : 0.00 ?>">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?php if (isset($error)) : ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $error ?>
                                    <a href="<?= site_url('quote/pos') ?>" type="button" class="btn-close" aria-label="Close"></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <div class="row">
                                        <div class="col-lg-10 col-sm-10 col-10">
                                            <select name="customer_id" class="select2-customer">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                            <div class="add-icon">
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add-customer" class="btn btn-icon"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store_id" class="select2-store">
                                        <option value=""></option>
                                        <?php
                                        if (isset($stores))
                                            foreach ($stores as $row) { ?>
                                            <option value="<?= $row->id ?>" <?= isset($quote) ? ($row->id === $quote->store_id ? 'selected' : '') : ($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
                                                <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="input-groupicon">
                                        <input name="quote_date" type="text" class="datetimepicker" value="<?= date('d-m-Y', time()) ?>" required>
                                        <div class="addonset">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12 mb-3">
                                <div class="form-group">
                                    <div class="form-outline autocomplete">
                                        <label class="form-label" for="form1">Search</label>
                                        <input autocomplete="off" id="search-products" type="search" class="form-control" placeholder="Enter product name, barcode, sku..." />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div style="max-height: 500px !important;overflow-y:scroll" class="table-responsive mb-3">
                                <table class="table tr-items">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>QTY</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Tax</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <script>
                                    let prodIndex = <?= isset($quote) ? sizeof($quote->items) : 0 ?>;
                                </script>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Order Tax</label>
                                    <div class="input-group">
                                        <input type="text" name="tax" value="<?= isset($quote) ? $quote->tax :  setting("App.SalesTax") ?>" class="form-control" placeholder="Order taxes" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer Discount</label>
                                    <div class="input-group">
                                        <input onkeyup="updateTotals()" type="number" name="discount" value="<?= isset($quote) ? $quote->discount : null ?>" class="form-control addon-inline" placeholder="Order discount" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Shipping</label>
                                    <input onkeyup="updateTotals()" type="number" name="shipping" value="<?= isset($quote) ? $quote->shipping : null ?>" class="form-control" placeholder="Shipping amount">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Order Tax</h4>
                                                <h5 class="orderTaxes">0.00 (0.00%)</h5>
                                            </li>
                                            <li>
                                                <h4>Discount </h4>
                                                <h5 class="discountTotal"> 0.00(0.00%)</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Shipping</h4>
                                                <h5 class="shippingTotal">0.00</h5>
                                            </li>
                                            <li class="total">
                                                <h4>Grand Total</h4>
                                                <h5 class="grandTotal">0.00</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 ">
                <div class="order-list">
                    <div class="orderid">
                        <h5>Transaction id : INV<span id="order-id"><?= isset($quote) ? $quote->invoice : $invoice; ?></span></h5>
                    </div>
                    <div class="actionproducts">
                        <ul>
                            <?php if (isset($quote)) { ?>
                                <li>
                                    <a onclick="deleteRecord(<?= $quote->id ?>,'<?= site_url('quotes') ?>', '<?= site_url('quotes') ?>')" href="javascript:void(0);" class="deletebg confirm-text"><img src="<?= base_url('assets/icons/delete-2.svg') ?>" alt="img"></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="card card-order">
                    <div class="card-body pb-2">
                        <div class="setvalue">
                            <ul>
                                <li>
                                    <h5>Customer </h5>
                                    <h6 class="customer"><?= isset($quote) ? ($quote->customer ? $quote->customer->name : '') : 'walk-in-customer' ?></h6>
                                </li>
                                <li id="acc-bal" class="<?= isset($quote) ? ($quote->customer ? $quote->customer->name : 'd-none') : 'd-none' ?>">
                                    <h5>A/c Balance</h5>
                                    <?php
                                    if (isset($quote) && $quote->customer) : ?>
                                        <h6 class="customer-balance" data-balance="<?= $quote->customer->balance ?>">GHS
                                            <?= $quote->customer->balance < 0 ? "(" . number_format(abs($quote->customer->balance), 2, '.', '') . ")" : $quote->customer->balance ?>
                                        </h6>
                                    <?php else : ?>
                                        <h6 class="customer-balance" data-balance="0.00">GHS 0.00</h6>
                                    <?php endif ?>
                                </li>
                                <li>
                                    <h6>Total Quote </h6>
                                    <h6 class="subTotal">GHS 0.00</h6>
                                </li>
                                <li>
                                    <h6>Total Shipping </h6>
                                    <h6 class="shippingTotal">GHS 0.00</h6>
                                </li>
                                <li>
                                    <h6>Total Tax</h6>
                                    <h6 class="orderTaxes">GHS 0.0</h6>
                                </li>
                                <li>
                                    <h6>Total Discount</h6>
                                    <h6 class="discountTotal">GHS 0.00</h6>
                                </li>
                                <li class="total-value">
                                    <h5>Grand Total </h5>
                                    <h6 class="grandTotal">GHS 0.00</h6>
                                </li>
                                <li hidden class="text-danger">
                                    <h5>Due </h5>
                                    <h6 class="dueTotal">GHS 0.00</h6>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:void(0);" onclick="$('.post-form').submit()" class="btn btn-success mb-5 d-flex justify-content-between">
                            <h5>Checkout</h5>
                            <h6 class="grandTotal">0.00</h6>
                        </a>
                        <div class="btn-pos">
                            <ul>
                                <li>
                                    <a class="btn" data-bs-toggle="modal" data-bs-target="#recents"><i class="fa fa-list me-1"></i> Transaction</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('modal') ?>
<form action="<?= site_url('customers') ?>" class="modal fade" id="add-customer" tabindex="-1" aria-labelledby="create" aria-hidden="true" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="post">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Customer</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" name="name" placeholder="Customer Name">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" placeholder="Address">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" name="discount" class="form-control addon-inline" placeholder="Customer discount">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-12">
                    <button class="btn btn-submit me-2">Submit</button>
                    <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="recents" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recent Transactions</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tabs-sets">
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="quote-tab" data-bs-toggle="tab" data-bs-target="#quote" type="button" aria-controls="quote" aria-selected="true" role="tab">Quote</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="quote" role="tabpanel" aria-labelledby="quote-tab">
                            <div class="table-top">

                                <div class="quote-wordset">
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table id="dt-quotes" class="table w-100">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Date</th>
                                            <th>Invoice No.</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th class="text-end">Action</th>
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

<div class="modal fade" id="edit-product" tabindex="-1" aria-labelledby="editproduct" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?php if (setting('App.AllowPriceChange') === 'yes' || setting('App.AllowCustomerDiscountChange') === 'yes') : ?>
                    <div class="row">
                        <div <?= setting('App.AllowPriceChange') === 'yes' ? '' : 'hidden' ?> class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Unit Price</label>
                                <input id="unit-price" min="0" type="number" class="form-control" placeholder="Unit Price">
                            </div>
                        </div>
                        <div <?= setting('App.AllowCustomerDiscountChange') === 'yes' ? '' : 'hidden' ?> class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Discount</label>
                                <input id="discount" type="number" placeholder="Discount Amount" class="form-control">
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        You don't have permission to Edit Products. Please Contact Admin
                    </div>
                <?php endif ?>
            </div>

            <div class="modal-footer">
                <?php if (auth()->user()->can('sales.edit-price')) : ?>
                    <button onclick="updateProduct()" type="submit" class="btn btn-submit">Update</button>
                <?php endif ?>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-quote.js?v=18') ?>"></script>
<script src="<?= base_url('assets/js/datatables/quote.modal.js?v=1') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>
<?= $this->endSection() ?>