<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Product Sales Report</h4>
            <h6>List products and quantity solid</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-path">
                        <a class="btn btn-filter" id="filter_search">
                            <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                            <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                        </a>
                    </div>
                    <div class="search-input">
                        <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                    </div>
                </div>
                <div class="wordset"></div>
            </div>

            <div class="card mb-0" id="filter_inputs0">
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
                                <select name="sales.type" class="select">
                                    <option value="">Select type</option>
                                    <option value="walk-in-customer">walk-in-customer</option>
                                    <option value="customer">regular customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12" style="overflow-x: auto;">
                            <div class="form-group">
                                <select name="sales.store_id" class="select2-store">
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
                                <select name="sales.payment_type" class="select">
                                    <option value="">Select method</option>
                                    <option value="cash">Cash</option>
                                    <option value="momo">MoMo</option>
                                    <option value="credit">Credit Card</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12" style="overflow-x: auto;">
                            <div class="form-group">
                                <select name="sales.customer_id" class="select2-customer">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" name="sales.invoice" placeholder="Enter Reference No" value="">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="sales.payment_status" class="select">
                                    <option value="">Select a status</option>
                                    <option value="due">Due</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select class="select2-category" name="products.category_id" required>
                                    <option value=""></option>
                                    <?php
                                    if (isset($categories)) : ?>
                                        <?php foreach ($categories as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="products.brand_id" class="select2-brand">
                                    <option value=""></option>
                                    <?php
                                    if (isset($brands)) : ?>
                                        <?php foreach ($brands as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="products.unit_id" class="select2-unit" required>
                                    <option value=""></option>
                                    <?php
                                    if (isset($units)) : ?>
                                        <?php foreach ($units as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <a class="btn btn-primary w-100 filter">Search <i class="fa fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table w-100" id="dt-product-sales">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty Sold</th>
                            <th>Discount</th>
                            <th>Subtotal</th>
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
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/product-sales-report.js?v=0') ?>"></script>
<?= $this->endSection() ?>