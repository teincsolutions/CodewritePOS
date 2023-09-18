<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Expenses LIST </h4>
            <h6>Manage your Expenses</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url("expenses/create"); ?>" class="btn btn-added"><i class="fa fa-plus fa-lg me-2"></i>Add New Expense</a>
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
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select class="select2-category" name="expense_category_id">
                                    <option value=""></option>
                                    <?php
                                    if (isset($categories)) : ?>
                                        <?php foreach ($categories as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select class="select2-subcategory" name="expense_subcategory_id" required>
                                    <option value=""></option>
                                    <?php
                                    if (isset($subcategories)) : ?>
                                        <?php foreach ($subcategories as $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>

                         <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="store_id" class="select2-store">
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

                        <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                            <div class="form-group">
                                <button type="button" class="btn btn-filters filter ms-auto"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="expensestable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>#SN</th>
                            <th>Expenses Date</th>
                            <th>Store</th>
                            <th>Category</th>
                            <th>SubCategory</th>
                            <th>Amount</th>
                            <th>Description</th>
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
<script src="<?= base_url('assets/js/datatables/expenses.js?v=1') ?>"></script>
<?= $this->endSection() ?>