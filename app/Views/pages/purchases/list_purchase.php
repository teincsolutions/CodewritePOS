<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Purchases List</h4>
            <h6>Manage your purchases</h6>
        </div>
        <div class="page-btn">
            <a href="<?=site_url('purchases/create') ?>" class="btn btn-added"><i class="fa fa-plus" class="me-1"></i>Add Purchases</a>
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
                                    <option value="pending">Due</option>
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
                <table id="dt-purchases" class="table">
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

<?= $this->section('modal') ?>
<div class="modal fade" id="showpayment" tabindex="-1" aria-labelledby="showpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Show Payments</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Amount </th>
                                <th>Paid By </th>
                                <th>Paid By </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bor-b1">
                                <td>2022-03-07 </td>
                                <td>INV/SL0101</td>
                                <td>$ 0.00 </td>
                                <td>Cash</td>
                                <td>
                                    <a class="me-2" href="javascript:void(0);">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/printer.svg" alt="img">
                                    </a>
                                    <a class="me-2" href="javascript:void(0);" data-bs-target="#editpayment" data-bs-toggle="modal" data-bs-dismiss="modal">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                    </a>
                                    <a class="me-2 confirm-text" href="javascript:void(0);">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createpayment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Supplier</label>
                            <div class="input-groupicon">
                                <input type="text" value="2022-03-07" class="datetimepicker">
                                <div class="addonset">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/calendars.svg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" value="INV/SL0101">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Received Amount</label>
                            <input type="text" value="0.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="text" value="0.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment type</label>
                            <select class="select">
                                <option>Cash</option>
                                <option>Online</option>
                                <option>Inprogress</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-0">
                            <label>Note</label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editpayment" tabindex="-1" aria-labelledby="editpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Payment</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Supplier</label>
                            <div class="input-groupicon">
                                <input type="text" value="2022-03-07" class="datetimepicker">
                                <div class="addonset">
                                    <i class="fa fa-calendar fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" value="INV/SL0101">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Received Amount</label>
                            <input type="text" value="0.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="text" value="0.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment type</label>
                            <select class="select">
                                <option>Cash</option>
                                <option>Online</option>
                                <option>Inprogress</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-0">
                            <label>Note</label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables/purchases.js') ?>"></script>
<?= $this->endSection() ?>