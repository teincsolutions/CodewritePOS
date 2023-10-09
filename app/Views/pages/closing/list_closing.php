<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Closing List </h4>
            <h6>Manage your Closings</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url("closings/create"); ?>" class="btn btn-added"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" class="me-2" alt="img">Add New Closing</a>
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
                        <a class="btn btn-searchset">
                            <img src="<?= base_url('assets/icons/search-white.svg') ?>" alt="img">
                        </a>
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
                                <div class="input-groupicon">
                                    <input type="text" name="created_at" placeholder="Choose Date" class="datetimepicker">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="store_id" class="select2-store">
                                    <option value=""></option>
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
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="cash_in_hand" type="text" placeholder="Enter Cash in Hand">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="closing_balance" type="text" placeholder="Enter Closing Balance">
                            </div>
                        </div>

                        <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                            <div class="form-group">
                                <button type="button" class="btn btn-filters filter ms-auto"><img src="<?= base_url('assets/icons/search-white.svg') ?>" alt="img"></button>
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
                            <th>Opening Bal</th>
                            <th>Cash up</th>
                            <th>Cust. Paymt</th>
                            <th>Sup. Paymt</th>
                            <th>Tlt Walk-in Sales</th>
                            <th>Sales Rtn</th>
                            <th>Purchase Rtn</th>
                            <th>Stock Adj. Bal</th>
                            <th>Transfers Bal</th>
                            <th>Expenses</th>
                            <th>Cash in Hand</th>
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
<script src="<?= base_url('assets/js/datatables/closings.js?v=1') ?>"></script>
<?= $this->endSection() ?>