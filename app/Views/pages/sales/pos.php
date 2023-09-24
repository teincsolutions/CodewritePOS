<?= $this->extend('template/blank') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Ponit of Sales</h4>
            <h6>Manage your sales</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('sales-returns/create') ?>" class="btn btn-added"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" alt="img" class="me-1">Sales Return</a>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('sales') ?>" method="post">

        <div class="row">
            <div class="col-sm-12 col-md-8">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= isset($sales) ? $sales->id : null ?>">
                <input type="hidden" name="invoice" value="<?= isset($invoice) ? $invoice : null ?>">
                <input type="hidden" name="_method" value="<?= isset($sales) ? 'put' : 'post' ?>">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <div class="row">
                                        <div class="col-lg-10 col-sm-10 col-10">
                                            <select name="customer_id" class="select2-customer">
                                                <?php if (isset($sales) && $sales->type === 'walk-in') { ?>
                                                    <option value="" selected>walk-in-customer</option>
                                                    <?php
                                                    if (isset($customers))
                                                        foreach ($customers as $row) { ?>
                                                        <option value="<?= $row->id ?>" <?= $row->id === $sales->customer_id ? 'selected' : null ?>>
                                                            <?= $row->name; ?><?= $row->address ? "($row->address)" : "($row->phone)"; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <option value="">walk-in-customer</option>
                                                    <?php
                                                    if (isset($customers))
                                                        foreach ($customers as $row) { ?>
                                                        <option value="<?= $row->id ?>">
                                                            <?= $row->name; ?><?= $row->address ? "($row->address)" : "($row->phone)"; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                            <div class="add-icon">
                                                <a target="_blank" href="<?= site_url('customers/create') ?>" class="btn btn-icon"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store_id" class="select2-store" required>
                                    <option value="" selected></option>
                                        <?php
                                        if (isset($stores))
                                            foreach ($stores as $row) { ?>
                                            <option value="<?= $row->id ?>" <?= isset($sales) ? ($row->id === $sales->store_id ? 'selected' : '') : null ?>>
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
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <div class="form-outline autocomplete">
                                        <label class="form-label" for="form1">Search</label>
                                        <input autocomplete="off" id="search-products" type="search" class="form-control" placeholder="Enter product name, barcode, sku..." />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive mb-3">
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
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Order Tax</label>
                                    <input type="text" name="tax" value="0.00" class="form-control" placeholder="Sales taxes" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Discount</label>
                                    <input onkeyup="updateTotals()" type="number" name="discount" value="0.00" class="form-control" placeholder="Sales discount" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Shipping</label>
                                    <input onkeyup="updateTotals()" type="number" name="shipping" class="form-control" placeholder="Shipping amount">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="order_status" class="select">
                                        <option value="">Choose Status</option>
                                        <option value="completed" selected>Completed</option>
                                        <option value="pending">Inprogress</option>
                                    </select>
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
                                                <h5 class="discountTotal"> 0.00</h5>
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
                        <h5>Transaction id : INV<?= $invoice; ?></h5>
                    </div>
                    <div class="actionproducts">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="deletebg confirm-text"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete-2.svg" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card card-order">
                    <div class="card-body pb-2">
                        <div class="setvalue">
                            <ul>
                                <li>
                                    <h5>Customer </h5>
                                    <h6></h6>
                                </li>
                                <li>
                                    <h6>Total Shipping </h6>
                                    <h6 class="shippingTotal">0.00</h6>
                                </li>
                                <li>
                                    <h6>Total Tax</h6>
                                    <h6 class="orderTaxes">0.0</h6>
                                </li>
                                <li>
                                    <h6>Total Discount</h6>
                                    <h6 class="discountTotal">0.00</h6>
                                </li>
                                <li class="total-value">
                                    <h5>Total </h5>
                                    <h6 class="grandTotal">0.00</h6>
                                </li>
                                <li class="text-danger">
                                    <h5>Due </h5>
                                    <h6 class="dueTotal">0.00</h6>
                                </li>
                            </ul>
                        </div>
                        <div class="setvalue">
                            <input onkeyup="updateTotals()" onchange="updateTotals()" type="number" name="paid" step="any" min="0" class="form-control" placeholder="Enter paid amount" required>
                        </div>
                        <div class="setvaluecash">
                            <ul>
                                <li class="active">
                                    <a href="javascript:void(0);" class="paymentmethod">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/cash.svg" alt="img" class="me-2">
                                        Cash
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="paymentmethod">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/debitcard.svg" alt="img" class="me-2">
                                        Debit
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="paymentmethod">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/scan.svg" alt="img" class="me-2">
                                        MoMo
                                    </a>
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
                                    <a class="btn"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/pause1.svg" alt="img" class="me-1">Hold</a>
                                </li>
                                <li>
                                    <a class="btn"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit-6.svg" alt="img" class="me-1">Quotation</a>
                                </li>
                                <li>
                                    <a class="btn" data-bs-toggle="modal" data-bs-target="#recents"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/transcation.svg" alt="img" class="me-1"> Transaction</a>
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

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-pos.js') ?>"></script>
<?= $this->endSection() ?>