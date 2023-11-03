<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= $title ?? "Stock Report" ?></h4>
            <h6>Full report of <?= $product->name; ?></h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('reports/stocks') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-2"></i>List Stock Report</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item"><a class="nav-link active" href="#product-tab" data-bs-toggle="tab">Product</a></li>
                        <li class="nav-item"><a class="nav-link" href="#purchases-tab" data-bs-toggle="tab">Purchases</a></li>
                        <li class="nav-item"><a class="nav-link" href="#purchase-returns-tab" data-bs-toggle="tab">Purchase Returns</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sales-tab" data-bs-toggle="tab">Sales</a></li>
                        <li class="nav-item"><a class="nav-link" href="#sales-returns-tab" data-bs-toggle="tab">Sales Returns</a></li>
                        <li class="nav-item"><a class="nav-link" href="#transfers-tab" data-bs-toggle="tab">Product Transfers</a></li>
                        <li class="nav-item"><a class="nav-link" href="#unit-transfers-tab" data-bs-toggle="tab">Unit Transfers</a></li>
                        <li class="nav-item"><a class="nav-link" href="#adjustments-tab" data-bs-toggle="tab">Adjustments</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="product-tab">
                            <div class="row mt-5">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="productdetails">
                                        <ul class="product-bar">
                                            <li>
                                                <h4>Product</h4>
                                                <h6><?= $product->name ?></h6>
                                            </li>
                                            <li>
                                                <h4>Category</h4>
                                                <h6><?= $product->category->name; ?></h6>
                                            </li>
                                            <li>
                                                <h4>Brand</h4>
                                                <h6><?= $product->brand ? $product->brand->name : 'None' ?></h6>
                                            </li>
                                            <li>
                                                <h4>Unit</h4>
                                                <h6><?= $product->unit->label ?></h6>
                                            </li>
                                            <li>
                                                <h4>SKU</h4>
                                                <h6><?= $product->sku ?></h6>
                                            </li>
                                            <li>
                                                <h4>Unit Qty</h4>
                                                <h6><?= $product->unit_qty; ?></h6>
                                            </li>
                                            <li>
                                                <h4>Quantity In Stock</h4>
                                                <h6><?= $product->instock ?></h6>
                                            </li>
                                            <li>
                                                <h4>Tax</h4>
                                                <h6><?= $product->tax ? $product->tax->rate : 0.00 ?>%</h6>
                                            </li>
                                            <?php if (setting('App.ProductDiffForStore') !== 'yes') : ?>
                                                <li>
                                                    <h4>Unit Price</h4>
                                                    <h6>GHS <?= number_format($product->unit_price, 2) ?></h6>
                                                </li>
                                                <li>
                                                    <h4>Unit Cost</h4>
                                                    <h6>GHS <?= number_format($product->unit_cost, 2) ?></h6>
                                                </li>
                                                <li>
                                                    <h4>Wholesale Price</h4>
                                                    <h6>GHS <?= number_format($product->unit_ws_price, 2) ?></h6>
                                                </li>
                                                <li>
                                                    <h4>Discount</h4>
                                                    <h6>GHS <?= $product->discount ?></h6>
                                                </li>
                                                <li>
                                                    <h4>Status</h4>
                                                    <h6><?= ['Active', 'Discontinued'][$product->discontinued] ?></h6>
                                                </li>
                                                <li>
                                                    <h4>Minimum Qty</h4>
                                                    <h6><?= $product->min_qty; ?></h6>
                                                </li>
                                            <?php endif ?>
                                            <li>
                                                <h4>Description</h4>
                                                <h6><?= $product->description; ?></h6>
                                            </li>
                                            <?php if (setting('App.UseExpiration') === 'yes') : ?>
                                                <li>
                                                    <h4>Expiration Date</h4>
                                                    <h6><?= date('d/m/Y', strtotime($product->expiration)); ?></h6>
                                                </li>
                                            <?php endif ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <table class="table w-100" id="dt-stocks">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Store</th>
                                                <th>Instock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (model('StockModel')->where('product_id', $product->id)->findAll() as $key => $row) : ?>
                                                <tr>
                                                    <th><?= $key + 1 ?></th>
                                                    <td><?= $row->store->name ?></td>
                                                    <td><?= $row->instock ?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="<?= $product->id; ?>">
                        <div class="tab-pane" id="sales-tab">
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
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
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
                                        <table id="dt-sales" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Customer</th>
                                                    <th>Store</th>
                                                    <th>Qty Sold</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="purchases-tab">
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
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
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
                                        <table id="dt-purchases" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Supplier</th>
                                                    <th>Store</th>
                                                    <th>Qty Ordered</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="purchase-returns-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search2">
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

                                    <div class="card" id="filter_inputs2">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
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
                                        <table id="dt-purchase-returns" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Supplier</th>
                                                    <th>Store</th>
                                                    <th>Qty Returned</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="sales-returns-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search3">
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

                                    <div class="card" id="filter_inputs3">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
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
                                        <table id="dt-sales-returns" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Customer</th>
                                                    <th>Store</th>
                                                    <th>Qty Returned</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="transfers-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search5">
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

                                    <div class="card" id="filter_inputs5">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
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
                                        <table id="dt-transfers" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>From Store</th>
                                                    <th>To Store</th>
                                                    <th>Qty</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="adjustments-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search6">
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

                                    <div class="card" id="filter_inputs6">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
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
                                        <table id="dt-adjustments" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Store</th>
                                                    <th>Instock Qty</th>
                                                    <th>Pystical Qty</th>
                                                    <th>Qty Diff</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="unit-transfers-tab">
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
                                    <div class="card" id="filter_inputs6">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
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
                                        <table id="dt-unit-transfers" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Store</th>
                                                    <th>Qty</th>
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

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/product-details.js?v=1') ?>"></script>
<script src="<?= base_url('assets/js/datatables/product-stock-report.js?v=3') ?>"></script>
<?= $this->endSection() ?>