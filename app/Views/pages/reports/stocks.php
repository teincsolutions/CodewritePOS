<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= $title ?? 'Sale Report' ?></h4>
            <h6>Inventory report on products</h6>
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
                <div class="col-lg-3 col-sm-6 pt-3 col-12">
                    <div class="form-group">
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
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="barcode" type="text" placeholder="Enter Barcode">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="sku" type="text" placeholder="Enter sku">
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
                <table class="table w-100" id="dt-stock-report">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Product</th>
                            <th>Qty Instock</th>
                            <th>Qty Ordered</th>
                            <th>Qty P. Returns</th>
                            <th>Qty Sold</th>
                            <th>Qty S. Returns</th>
                            <th>Quotations</th>
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
<script src="<?= base_url('assets/js/datatables/stocks-report.js?v=11') ?>"></script>
<?= $this->endSection() ?>