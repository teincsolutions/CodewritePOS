<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Create Container Receivings</h4>
            <h6>Add/Update Container Receivings</h6>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('containers/receivings') ?>" method="post">
        <?= csrf_field() ?>
        <input id="order-status" type="hidden" name="order_status" value="completed">
        <input id="payment-status" type="hidden" name="payment_status" value="pending">
        <input id="sales-type" type="hidden" name="type" value="walk-in-customer">
        <input id="sales-total" type="hidden" name="total_amount" value="<?= isset($receiving) ? $receiving->total_amount : 0.00 ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-12" style="overflow-x: auto;">
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
                                    <option value="<?= $row->id ?>" <?= isset($receiving) ? ($row->id === $receiving->store_id ? 'selected' : '') : ($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
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
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-lg-6"></div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Settlement Type</label>
                            <select name="settlement" class="form-control select2-settlement" required>
                                <option value="container">Containers</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Paid</label>
                            <input type="number" name="paid" id="paid" value="0" class="form-control" placeholder="Paid Amount" readonly>
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
                                <li class="total-value">
                                    <h4>Grand Total </h4>
                                    <h5 class="grandTotal">GHS 0.00</h5>
                                </li>
                                <li id="acc-bal">
                                    <h4>A/c Balance </h4>
                                    <h5 class="customer-balance">GHS 0.00</h5>
                                </li>
                            </ul>
                            <button onclick="$('.post-form').submit()" type="button" class="btn btn-success d-flex justify-content-between w-100 text-left me-2 mt-3">
                                <h5>Checkout</h5>
                                <h5 class="grandTotal">GHS 0.00</h5>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-12 text-right">
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