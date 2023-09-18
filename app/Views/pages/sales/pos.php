<?= $this->extend('template/blank') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Ponit of Sales</h4>
            <h6>Manage your sales</h6>
        </div>
        <div class="page-btn">
            <div class="d-flex gap-3">
                <a href="<?= site_url('sales/pos') ?>" target="_blank" class="btn btn-added"><i class="fa fa-plus me-1"></i>POS</a>
                <a href="<?= site_url('sales/returns/create') ?>" class="btn btn-danger"><i class="fa fa-plus me-1"></i> Sales Return</a>
            </div>
        </div>

    </div>
    <form class="post-form <?= isset($sales) ? 'refresh-page' : null ?>" action="<?= site_url('sales') ?>" method="post" data-refresh-url="<?= site_url('sales/pos') ?>">
        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= isset($sales) ? $sales->id : null ?>">
                <input type="hidden" name="invoice" value="<?= isset($sales) ? $sales->invoice : $invoice ?>">
                <input id="sales-type" type="hidden" name="type" value="<?= isset($sales) ? $sales->type : null ?>">
                <input id="order-status" type="hidden" name="order_status" value="<?= isset($sales) ? $sales->order_status : null ?>">
                <input id="payment-status" type="hidden" name="payment_status" value="<?= isset($sales) ? $sales->payment_status : null ?>">
                <input id="sales-total" type="hidden" name="total_amount" value="<?= isset($sales) ? $sales->total_amount : 0.00 ?>">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if (isset($error)) : ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $error ?>
                                    <a href="<?= site_url('sales/pos') ?>" type="button" class="btn-close" aria-label="Close"></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <div class="row">
                                        <div class="col-lg-10 col-sm-10 col-10" style="overflow-x: auto;">
                                            <select name="customer_id" class="select2-customer">
                                                <option value="">walk-in-customer</option>
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
                            <div class="col-lg-5 col-sm-6 col-12" style="overflow-x: auto;">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store_id" class="select2-store">
                                        <?php
                                        if (isset($stores))
                                            foreach ($stores as $row) { ?>
                                            <option value="<?= $row->id ?>" <?= isset($sales) ? ($row->id === $sales->store_id ? 'selected' : '') : ($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
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
                                        <input name="sales_date" type="text" class="datetimepicker" value="<?= date('d-m-Y', time()) ?>" required>
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
                                            <th>#SN</th>
                                            <th>Product Name</th>
                                            <th>QTY</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Tax</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($sales))
                                            foreach ($sales->items as $key => $row) : ?>
                                            <tr>
                                                <td>
                                                </td>
                                                <td class="productimgname">
                                                    <?= $row->product->image_uri
                                                        ? '<a class="product-img"><img src="' . base_url($row->product->image_uri) . '" alt="product"></a>'
                                                        : '<a class="p-3"></a>' ?>
                                                    <a target="_blank" href="<?= site_url('products/' . $row->product_id) ?>">
                                                        <?= setting('App.ShowProductSKU') === 'yes' ? $row->product->sku : '' ?> <?= $row->product->name ?>
                                                        (<?= $row->product->unit->label; ?>)
                                                </td>
                                                <td>
                                                    <div class="increment-decrement">
                                                        <div class="input-groups">
                                                            <input type='hidden' name="items[<?= $key ?>][sale_id]" value="<?= $row->sale_id ?>">
                                                            <input type='hidden' name="items[<?= $key ?>][product_id]" value="<?= $row->product_id ?>">
                                                            <input type="hidden" name="items[<?= $key ?>][unit_price]" value="<?= $row->unit_price; ?>" class="runit_price">
                                                            <input type="hidden" name="items[<?= $key ?>][unit_cost]" value="<?= $row->unit_cost; ?>" class="runit_cost">
                                                            <input type="hidden" name="items[<?= $key ?>][taxes]" value="<?= $row->taxes ?>">
                                                            <input type="hidden" name="items[<?= $key ?>][store_id]" value="<?= $row->store_id; ?>">
                                                            <input type="hidden" name="items[<?= $key ?>][tax_amounts]" value="<?= $row->tax_amounts ?>" class="rtax">
                                                            <input type="hidden" name="items[<?= $key ?>][discount]" value="<?= $row->discount ?>" class="rdiscount">
                                                            <input type="hidden" name="items[<?= $key ?>][subtotal]" value="<?= $row->subtotal ?>" class="rsubtotal">
                                                            <input type="button" value="-" class="button-minus dec button">
                                                            <input onkeyup="updateItemRow(this)" min=".1" type="text" name="items[<?= $key ?>][qty]" value="<?= $row->qty ?>" class="rqty quantity-field" required>
                                                            <input type="button" value="+" class="button-plus inc button">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= $row->unit_price ?></td>
                                                <td data-discount="<?= $row->discount ?>"><?= number_format($row->discount, 2) ?></td>
                                                <td data-tax_amounts="<?= $row->tax_amounts ?>" class="suffix-percent"><?= number_format($row->tax_amounts, 2) ?></td>
                                                <td><?= number_format($row->subtotal, 2) ?></td>
                                                <td> <?= setting("App.AllowPriceChange") === "yes" || setting("App.AllowCustomerDiscountChange") === "yes"
                                                            ? '<span class="edit-price btn btn-icon"><i class="fa fa-edit"></i></span>'
                                                            : "" ?>
                                                    <a href="javascript:void(0);" class="delete-set"><i class="fa text-danger fa-trash"></i></a>
                                                </td>

                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                    <script>
                                        let prodIndex = <?= isset($sales) ? sizeof($sales->items) : 0 ?>;
                                    </script>
                                </table>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Order Tax</label>
                                    <div class="input-group">
                                        <input type="text" name="tax_amounts" value="<?= isset($sales) ? $sales->tax_amounts : setting("App.SalesTax") ?>" class="form-control" placeholder="Sales taxes" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer Discount</label>
                                    <div class="input-group">
                                        <input onkeyup="updateTotals()" type="number" name="discount" value="<?= isset($sales) ? $sales->discount : null ?>" class="form-control addon-inline" placeholder="Sales discount" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Shipping</label>
                                    <input onkeyup="updateTotals()" type="number" name="shipping" value="<?= isset($sales) ? $sales->shipping : null ?>" class="form-control" placeholder="Shipping amount">
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
                        <h5>Transaction id : <span id="order-id"><?= isset($sales) ? $sales->invoice : $invoice; ?></span></h5>
                    </div>
                    <div class="actionproducts">
                        <ul>
                            <?php if (isset($sales)) { ?>
                                <li>
                                    <a onclick="deleteRecord(<?= $sales->id ?>,'<?= site_url('sales') ?>', '<?= site_url('sales') ?>')" href="javascript:void(0);" class="deletebg confirm-text"><img src="<?= base_url('assets/icons/delete-2.svg') ?>" alt="img"></a>
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
                                    <h6 class="customer"><?= isset($sales) ? ($sales->customer ? $sales->customer->name : 'walk-in-customer') : 'walk-in-customer' ?></h6>
                                </li>
                                <li id="acc-bal" class="<?= isset($sales) ? ($sales->customer ? '' : 'd-none') : 'd-none' ?>">
                                    <h5>A/c Balance</h5>
                                    <?php
                                    if (isset($sales) && $sales->customer) : ?>
                                        <h6 class="customer-balance" data-balance="<?= $sales->customer->balance ?>">GHS
                                            <?= $sales->customer->balance < 0 ? "(" . number_format(abs($sales->customer->balance), 2, '.', '') . ")" : $sales->customer->balance ?>
                                        </h6>
                                    <?php else : ?>
                                        <h6 class="customer-balance" data-balance="0.00">GHS 0.00</h6>
                                    <?php endif ?>
                                </li>
                                <li id="acc-limit" class="<?= isset($sales) ? ($sales->customer ? '' : 'd-none') : 'd-none' ?>">
                                    <h5>A/c Credit Limit</h5>
                                    <?php
                                    if (isset($sales) && $sales->customer) : ?>
                                        <h6 class="customer-limit" data-credit-limit="<?= $sales->customer->balance ?>">GHS
                                            <?= $sales->customer->credit_limit < 0 ? "(" . number_format(abs($sales->customer->credit_limit), 2, '.', '') . ")" : $sales->customer->credit_limit ?>
                                        </h6>
                                    <?php else : ?>
                                        <h6 class="customer-limit" data-balance="0.00">GHS 0.00</h6>
                                    <?php endif ?>
                                </li>
                                <li>
                                    <h6>Total Sales </h6>
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
                                <li class="text-danger">
                                    <h5>Due/Change </h5>
                                    <h6 class="dueTotal">GHS 0.00</h6>
                                </li>
                            </ul>
                        </div>
                        <div class="setvalue">
                            <input onkeyup="updateTotals()" onchange="updateTotals()" type="number" name="paid" value="" step="any" min="0" class="form-control" placeholder="Enter paid amount">
                        </div>
                        <div class="setvaluecash">
                            <ul>
                                <li>
                                    <input type="radio" class="btn-check" name="payment_type" id="cash" autocomplete="off" value="cash" <?= isset($sales) ? ($sales->payment_type === 'cash' ? 'checked' : null) : 'checked' ?>>
                                    <label style="height: 90px;" class="btn-outline-primary border d-flex flex-column align-items-center justify-content-center rounded" for="cash">
                                        <img src="<?= base_url('assets/icons/cash.svg') ?>" alt="img" class="me-2">
                                        <span>Cash</span>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" class="btn-check" name="payment_type" id="momo" autocomplete="off" value="momo">
                                    <label style="height: 90px;" class="btn-outline-primary border d-flex flex-column align-items-center justify-content-center rounded" for="momo" <?= isset($sales) ? ($sales->payment_type === 'momo' ? 'checked' : null) : null ?>>
                                        <img src="<?= base_url('assets/icons/scan.svg') ?>" alt="img" class="me-2">
                                        <span>MoMo</span>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" class="btn-check" name="payment_type" id="debit" autocomplete="off" value="debit" disabled <?= isset($sales) ? ($sales->payment_type === 'debit' ? 'checked' : null) : null ?>>
                                    <label style="height: 90px;" class="btn-outline-primary border d-flex flex-column align-items-center justify-content-center rounded" for="debit">
                                        <img src="<?= base_url('assets/icons/debitcard.svg') ?>" alt="img" class="me-2">
                                        <span class="text-muted">Debit Card</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <a id="submit" href="javascript:void(0);" onclick="$('.post-form').submit()" class="btn btn-success mb-5 d-flex justify-content-between">
                            <h5>Checkout</h5>
                            <h6 class="grandTotal">0.00</h6>
                        </a>
                        <div class="btn-pos">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" onclick="hold(this)" action="<?= site_url('sales/hold') ?>" class="btn"><i class="fa fa-pause me-1"></i> Hold</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="qoute(this)" action="<?= site_url('quotes/save') ?>" class="btn"><i class="fa fa-print me-1"></i> Quotation</a>
                                </li>
                                <li>
                                    <a class="btn" data-bs-toggle="modal" data-bs-target="#recents"><i class="fa fa-list me-1"></i> Transaction</a>
                                </li>
                                <li>
                                    <a class="btn" data-bs-toggle="modal" data-bs-target="#add-payment"><i class="fa fa-plus me-1"></i> Add Payment</a>
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
                <input type="hidden" name="user_id" value="<?= user_id() ?>">
                <div class="tabs-sets">
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sales" type="button" aria-controls="sales" aria-selected="true" role="tab">Sales</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ledger-tab" type="button" aria-controls="ledger-tab" aria-selected="false" role="tab">Payment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#returns-tab" type="button" aria-controls="return" aria-selected="false" role="tab">Return</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                            <div class="table-top">
                                <div class="sales-wordset">
                                </div>
                            </div>
                            <div id="input-filter" class="row">

                            </div>
                            <div class="table-responsive">
                                <table id="dt-sales" class="table w-100">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Date</th>
                                            <th>Invoice No.</th>
                                            <th>Customer</th>
                                            <th>Order Status</th>
                                            <th>Amount</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ledger-tab" role="tabpanel">
                            <div class="table-top">
                                <div class="wordset">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dt-ledger" class="table w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Invoice No.</th>
                                            <th>Customer</th>
                                            <th>Due</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="returns-tab" role="tabpanel">
                            <div class="table-top">
                                <div class="wordset">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dt-returns" class="table w-100">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice No.</th>
                                            <th>Customer</th>
                                            <th>Return Status</th>
                                            <th>Return Amount </th>
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
                            <input id="inv-bal" type="text" value="0.00" placeholder="Enter Amount" disabled>
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
<script src="<?= base_url('assets/js/handle-pos.js?v=33') ?>"></script>
<script src="<?= base_url('assets/js/datatables/pos.modal.js?v=5') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>
<?php if (isset($sales) && $sales->customer) {
    $customer = $sales->customer;
    $customer->text = $customer->name . " (" . ($customer->address ? $customer->address : $customer->phone) . ")";
?>
    <script>
        $(() => {
            let customerData = <?= json_encode($customer) ?>;
            var option = new Option(customerData.text, customerData.id, true, true);
            select2Customer.append(option).trigger('change');
            select2Customer.trigger({
                type: 'select2:select',
                params: {
                    data: customerData
                }
            });
        });
    </script>
<?php } ?>
<?= $this->endSection() ?>