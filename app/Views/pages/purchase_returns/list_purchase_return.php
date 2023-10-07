<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Purchases Return List</h4>
            <h6>Manage your Returns</h6>
        </div>
        <div class="page-btn">
            <a href="<?=site_url('returns/purchases/create') ?>" class="btn btn-added"><i class="fa fa-plus" class="me-1"></i>Add New Purchases Return</a>
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
                        <a class="btn btn-searchset"><img src="<?= base_url('assets/icons/search-white.svg') ?>" alt="img"></a>
                    </div>
                </div>
                <div class="wordset">
                </div>
            </div>

            <div class="card" id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="supplier_id" class="select2-supplier">
                                    <option value=""></option>
                                    <?php
                                    if (isset($suppliers))
                                        foreach ($suppliers as $row) { ?>
                                        <option value="<?= $row->id ?>">
                                            <?= $row->name; ?><?= $row->address ? "($row->address)" : "($row->phone)"; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="payment_status" class="select">
                                    <option value="">Select a status</option>
                                    <option value="due">Due</option>
                                    <option value="paid">Paid</option>
                                </select>
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
                <table id="dt-returns" class="table">
                    <thead>
                        <tr>
                            <th>
                            </th>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Biller</th>
                            <th class="text-center">Action</th>
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
<script src="<?= base_url('assets/js/datatables/purchase-returns.js') ?>"></script>
<?= $this->endSection() ?>