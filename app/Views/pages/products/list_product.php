<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Product List</h4>
            <h6>Manage your products</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('products/create') ?>" class="btn btn-added"><i class="fa fa-plus" class="me-1"></i> New Product</a>
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
                <?php if (setting('App.ProductDiffForStore') === 'yes') : ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="store_id">Store</label>
                            <select name="store_id" class="select2-store filter">
                                <?php
                                if (isset($stores))
                                    foreach ($stores as $row) { ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->name; ?> (<?= $row->location; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                <?php endif ?>
                <div class="wordset"></div>
            </div>

            <div class="card mb-0" id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">

                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="name" type="text" placeholder="Enter Product Name">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="barcode" type="text" placeholder="Enter Barcode">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="sku" type="text" placeholder="Enter sku">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select class="select2-category" name="category_id" required>
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
                                <select name="brand_id" class="select2-brand">
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
                                <select name="unit_id" class="select2-unit" required>
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
                        <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                            <div class="form-group">
                                <a class="btn btn-filters filter ms-auto"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="productstable">
                    <thead>
                        <tr>
                            <th>
                            </th>
                            <th>Product Name</th>
                            <th>Barcode</th>
                            <th>SKU</th>
                            <th>Brand</th>
                            <th>Category </th>
                            <th>Cost</th>
                            <th>price</th>
                            <th>Instock</th>
                            <th>Discontinued</th>
                            <th>Added By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables/products.js?v=2') ?>"></script>
<?= $this->endSection() ?>