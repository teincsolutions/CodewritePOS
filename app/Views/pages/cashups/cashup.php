<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Cash Up</h4>
            <h6>Load your store cash</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('cashups') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-2"></i>List Customers</a>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <select id="stores" name="store_id" required>
                                <?php
                                if (isset($stores))
                                    foreach ($stores as $row) { ?>
                                    <?php if (isset($store)) : ?>
                                        <option value="<?= $row->id ?>" <?= $row->id === $store->id ? 'selected' : '' ?>>
                                            <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                        </option>
                                    <?php else : ?>
                                        <option value="<?= $row->id ?>" <?= isset($closing) ? ($row->id === $closing->store_id ? 'selected' : '') : null ?>>
                                            <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                        </option>
                                    <?php endif ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
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
                            <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                                <div class="form-group">
                                    <button type="button" class="btn btn-filters filter ms-auto"><img src="<?= base_url('assets/icons/search-white.svg') ?>" alt="img"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3 d-flex justify-content-end">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#add-payment" class="btn btn-primary btn-sm"><i class="fa fa-plus me-2"></i>Add Cash</button>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="dt-ledger" class="table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#Code</th>
                                    <th>Date</th>
                                    <th>Cashup</th>
                                    <th>Balance</th>
                                    <th>Added By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<form action="<?= site_url('cashup') ?>" class="modal fade" id="add-payment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
    <input type="hidden" name="store_id" value="<?= isset($store) ? $store->id : '' ?>">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Cash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Date</label>
                            <div class="input-groupicon">
                                <input type="text" name="tdate" value="<?= date('d-m-Y', time()) ?>" class="datetimepicker" required>
                                <div class="addonset">
                                    <i class="fa fa-calendar fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Cash Amount</label>
                            <input type="text" name="credit" min="0" value="" placeholder="Enter Amount" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js?v=2') ?>"></script>
<script src="<?= base_url('assets/js/cashup.js?v=1') ?>"></script>
<?= $this->endSection() ?>