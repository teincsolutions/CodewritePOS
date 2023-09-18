<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Container Receivings List</h4>
            <h6>Manage your Receivings</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('container/receivings/create') ?>" class="btn btn-added"><i class="fa fa-plus me-1"></i> New Container Receivings</a>
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
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="type" class="select">
                                    <option value="">Select type</option>
                                    <option value="walk-in-customer">walk-in-customer</option>
                                    <option value="customer">regular customer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="container_receivings.store_id" class="select2-store" style="overflow-x: auto;">
                                    <?php
                                    if (isset($stores))
                                        foreach ($stores as $row) { ?>
                                        <option value="<?= $row->id ?>" <?=($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
                                            <?= $row->name; ?> (<?= $row->location; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="settlement" class="select">
                                    <option value="">Select a settlement</option>
                                    <option value="cash">Cash</option>
                                    <option value="container">Container</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="customer_id" class="select2-customer" style="overflow-x: auto;">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" name="container_receivings.invoice" placeholder="Enter Returns Ref. No" value="">
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
                            <th>Customer Name</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Settlement</th>
                            <th>Total</th>
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
<script src="<?= base_url('assets/js/datatables/container-receivings.js?v=1') ?>"></script>
<?= $this->endSection() ?>