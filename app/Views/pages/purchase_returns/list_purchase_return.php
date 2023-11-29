<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Purchases Return List</h4>
            <h6>Manage your Returns</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('purchases/returns/create') ?>" class="btn btn-added"><i class="fa fa-plus" class="me-3"></i>New Purchase Return</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                    </div>
                </div>
                <div class="wordset">
                </div>
            </div>
            <div class="card" id="filter_inputs9">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="input-groupicon">
                                    <input type="text" class="datetimepicker" placeholder="From date" id="date-from">
                                    <div class="addonset">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="input-groupicon">
                                    <input type="text" class="datetimepicker" placeholder="To date" id="date-to">
                                    <div class="addonset">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="purchase_returns.store_id" class="select2-store" style="overflow-x: auto;">
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
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="supplier_id" class="select2-supplier" style="overflow-x: auto;">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" name="purchases.invoice" placeholder="Enter Purchase Reference No" value="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" name="purchase_returns.invoice" placeholder="Enter Return Reference No" value="">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="payment_status" class="select">
                                    <option value="">Select a status</option>
                                    <option value="due">Due</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12">
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
                            <th></th>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Received</th>
                            <th>Due</th>
                            <th>Biller</th>
                            <th class="text-center">Action</th>
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
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables/purchase-returns.js?v=7') ?>"></script>
<?= $this->endSection() ?>