<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Create Container Receivings</h4>
            <h6>Add/Update Container Receivings</h6>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('sales/returns') ?>" method="post">
        <?= csrf_field() ?>
        <input id="order-status" type="hidden" name="order_status" value="completed">
        <input id="payment-status" type="hidden" name="payment_status" value="paid">
        <input type="hidden" name="store_id" value="<?= isset($sales) ? $sales->store_id : '' ?>">
        <input id="sales-total" type="hidden" name="total_amount" value="<?= isset($sales) ? $sales->total_amount : 0.00 ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $error ?>
                            <a href="<?= site_url('sales/pos') ?>" type="button" class="btn-close" aria-label="Close"></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-12"  style="overflow-x: auto;">
                        <div class="form-group">
                            <label>Customer</label>
                            <select name="customer_id" class="select2-customer">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12" style="overflow-x: auto;">
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
                                <input name="return_date" type="text" class="datetimepicker" value="<?= date('d-m-Y', time()) ?>" required>
                                <div class="addonset">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Transac. ID</label>
                            <input type="text" name="invoice" class="form-control" value="<?= $invoice ?>" readonly>
                        </div>
                    </div>

                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <div class="form-outline autocomplete">
                                <label class="form-label" for="form1">Search</label>
                                <input autocomplete="off" id="search-containers" type="search" class="form-control" placeholder="Enter container name, barcode, sku..." />
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Order Tax</label>
                            <div class="input-group">
                                <input type="text" name="tax" value="<?= isset($sales) ? $sales->tax : null ?>" class="form-control" placeholder="Sales taxes" readonly>
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
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Change</label>
                            <input type="number" name="paid" id="paid" class="form-control" placeholder="Change Amount">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 float-md-right">
                        <div class="total-order">
                            <ul>
                                <li>
                                    <h4>SubTotal </h4>
                                    <h5 class="subTotal">GHS 0.00</h5>
                                </li>
                                <li>
                                    <h4>Total Shipping </h4>
                                    <h5 class="shippingTotal">GHS 0.00</h5>
                                </li>
                                <li>
                                    <h4>Total Tax</h4>
                                    <h5 class="orderTaxes">GHS 0.0</h5>
                                </li>
                                <li>
                                    <h4>Total Discount</h4>
                                    <h5 class="discountTotal">GHS 0.00</h5>
                                </li>
                                <li class="total-value">
                                    <h4>Grand Total </h4>
                                    <h5 class="grandTotal">GHS 0.00</h5>
                                </li>
                                <li id="acc-bal">
                                    <h4>A/c Balance </h4>
                                    <h5 class="customer-balance">GHS 0.00</h5>
                                </li>
                                <li class="total-value">
                                    <h4>Change/Due</h4>
                                    <h5 class="dueTotal">GHS 0.00</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button onclick="$('.post-form').submit()" type="button" class="btn btn-submit me-2">Submit Return</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-container-receivings.js?v=1') ?>"></script>
<?= $this->endSection() ?>