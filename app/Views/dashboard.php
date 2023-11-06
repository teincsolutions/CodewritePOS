<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="row">
        <div class="col-md-4 offset-md-8">
            <div class="form-group">
                <label for="store_id">Active Store</label>
                <select name="store_id" class="select2-store">
                    <option value=""></option>
                    <?php
                    if (isset($stores))
                        foreach ($stores as $row) { ?>
                        <option value="<?= $row->id ?>" <?= $activeStore ? ($activeStore->id === $row->id ? 'selected' : '') : ($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
                            <?= $row->name; ?> (<?= $row->location; ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
                <div class="dash-widgetimg">
                    <span class="text-info"><i class="fa fa-shopping-cart fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC <span class="counters" data-count="<?= model('SalesModel')->getTodayTotalAmount($activeStore->id ?? 0) ?>">0.00</span></h5>
                    <h6>Today's Sales</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
                <div class="dash-widgetimg">
                    <span class="text-info"><i class="fa fa-cart-plus fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC
                        <span class="counters" data-count="<?= model('PurchaseModel')->getTodayTotalAmount($activeStore->id ?? 0) ?>">0.00</span>
                    </h5>
                    <h6>Today's Purchases</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
                <div class="dash-widgetimg">
                    <span class="text-warning"><i class="fa fa-cart-arrow-down fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC <span class="counters" data-count="<?= model('SalesModel')->getDueAmount($activeStore->id ?? 0) ?>">0.00</span></h5>
                    <h6>Total Sales Due</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
                <div class="dash-widgetimg">
                    <span class="text-warning"><i class="fa fa-cart-plus fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC
                        <span class="counters" data-count="<?= model('PurchaseModel')->getDueAmount($activeStore->id ?? 0) ?>">0.00</span>
                    </h5>
                    <h6>Total Purchase Due</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
                <div class="dash-widgetimg">
                    <span class="text-danger"><i class="fa fa-cart-arrow-down fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC <span class="counters" data-count="<?= model('SalesReturnModel')->getTodayTotalAmount($activeStore->id ?? 0) ?>">0.00</span></h5>
                    <h6>Today's Sales Returns</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
                <div class="dash-widgetimg">
                    <span class="text-danger"><i class="fa fa-shopping-cart fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC
                        <span class="counters" data-count="<?= model('PurchaseReturnModel')->getTodayTotalAmount($activeStore->id ?? 0) ?>">0.00</span>
                    </h5>
                    <h6>Today's P. Returns</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
                <div class="dash-widgetimg">
                    <span class="text-danger"><i class="fa fa-money-bill fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC <span class="counters" data-count="<?= model('ExpenseModel')->getTodayTotalAmount($activeStore->id ?? 0) ?>">0.00</span></h5>
                    <h6>Today's Expenses</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
                <div class="dash-widgetimg">
                    <span class="text-warning"><i class="fa fa-cart-plus fa-lg"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>GHC
                        <span class="counters" data-count="<?= model('CustomerLedgerModel')->getTodayTotalCredit($activeStore->id ?? 0) ?>">0.00</span>
                    </h5>
                    <h6>Today's Debt Paymts</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count">
                <div class="dash-counts">
                    <h4><?= model('CustomerModel')->countAllResults() ?></h4>
                    <h5>Customers</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das1">
                <div class="dash-counts">
                    <h4><?= model('SupplierModel')->countAllResults() ?></h4>
                    <h5>Suppliers</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das2">
                <div class="dash-counts">
                    <h4><?= model('PurchaseModel')->where('store_id', $activeStore->id ?? 0)->countAllResults() ?></h4>
                    <h5>Purchase Invoice</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="file-text"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das3">
                <div class="dash-counts">
                    <h4><?= model('SalesModel')->where('store_id', $activeStore->id ?? 0)->countAllResults() ?></h4>
                    <h5>Sales Invoice</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="file"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Purchase & Sales</h5>
                    <div class="graph-sets">
                        <ul>
                            <li>
                                <span>Sales</span>
                            </li>
                            <li>
                                <span>Purchase</span>
                            </li>
                        </ul>
                        <div class="dropdown">
                            <button class="btn btn-white btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                2022 <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2022</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2021</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2020</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="sales_charts"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Recently Added Products</h4>
                    <div class="dropdown">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="dropset">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                                <a href="<?= site_url('products') ?>" class="dropdown-item">Product List</a>
                            </li>
                            <li>
                                <a href="<?= site_url('products/create') ?>" class="dropdown-item">Product Add</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Sn</th>
                                    <th>Products</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (model('ProductModel')->orderBy('id', 'desc')->findAll(10) as $key => $row) {
                                ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td class="productimgname">
                                            <a href="<?= site_url('products/' . $row->id) ?>" class="product-img">
                                                <img src="<?= $row->image_uri ? base_url($row->image_uri) : base_url('assets/images/noimage.png') ?>" alt="product">
                                            </a>
                                            <a href="<?= site_url('products/' . $row->id) ?>"><?= $row->name; ?> <?= $row->brand ? "(" . $row->brand->name . ")" : ''; ?></a>
                                        </td>
                                        <td>GHS <?= $row->unit_price; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (setting('App.UseExpiration') === 'yes') : ?>
        <div class="card mb-0">
            <div class="card-body">
                <h4 class="card-title">Expired Products</h4>
                <div class="table-responsive dataview">
                    <table id="dt-expired-products" class="table w-100">
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Brand Name</th>
                                <th>Category Name</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/expired-products.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>

<?= $this->endSection() ?>