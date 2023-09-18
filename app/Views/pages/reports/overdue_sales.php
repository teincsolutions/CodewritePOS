<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?=$title??'Overdue Sales Report' ?></h4>
            <h6>Overdue sales report</h6>
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
                       
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="sales.store_id" class="select2-store">
                                    <option value=""></option>
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
                        <div class="col-lg-1 col-sm-6 col-12">
                            <div class="form-group">
                                <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="dt-overdue-report" class="table w-100">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Last Payment Date</th>
                            <th>Overdue Amount</th>
                            <th>A/c Type</th>
                            <th>A/c Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/overdue-sales-report.js?v=0') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js?v=1') ?>"></script>
<?= $this->endSection() ?>