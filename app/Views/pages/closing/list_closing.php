<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Closing List </h4>
            <h6>Manage your Closings</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url("closing/store"); ?>" class="btn btn-added"><i class="fa fa-plus me-2"></i> Add New Closing</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset">
                            <i class="fa fa-search"></i>
                        </a>
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
                        <div class="col-lg-3 col-sm-6 col-12" style="overflow-x: auto;">
                            <div class="form-group">
                                <select name="store_id" class="select2-store">
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
                                <input name="cash_in_hand" type="text" placeholder="Enter Cash in Hand">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="closing_balance" type="text" placeholder="Enter Closing Balance">
                            </div>
                        </div>

                        <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                            <div class="form-group">
                                <button type="button" class="btn btn-filters filter ms-auto"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="dt-closing">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Closing Time</th>
                            <th>Store</th>
                            <th>Status</th>
                            <th>Opening Bal</th>
                            <th>Cash up</th>
                            <th>Cust. Dbt Paymt</th>
                            <th>Sup. Paymt</th>
                            <th>Tlt Sales</th>
                            <th>Sales Rtn</th>
                            <th>Purchase Rtn</th>
                            <th>Transfers Bal</th>
                            <th>Expenses</th>
                            <th>Cash in Hand</th>
                            <th>Avaliable Bal</th>
                            <th>Closing Bal</th>
                            <th>Closed By</th>
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
<script src="<?= base_url('assets/js/datatables/closings.js?v=9') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js?v=1') ?>"></script>
<?= $this->endSection() ?>