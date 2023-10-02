<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Expense Panel</h4>
            <h6>Add/Update Expenses</h6>
        </div>
    </div>
    <form action="<?= site_url('expenses') ?>" class="post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($expense) ? $expense->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($expense) ? 'put' : 'post' ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>Expense Date </label>
                            <div class="input-groupicon">
                                <input type="text" placeholder="Choose Date" class="datetimepicker" value="<?= isset($expense) ? $expense->expense_date : null ?>">
                                <div class="addonset">
                                    <i class="fa fa-calendar fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>Store</label>
                            <select class="select2-store" name="store_id" required>
                                <option value=""></option>
                                <?php
                                if (isset($stores))
                                    foreach ($stores as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($sales) ? ($row->id === $sales->store_id ? 'selected' : '') : null ?>>
                                        <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>Expense Category</label>
                            <select class="select2-category" name="expense_category_id">
                                <option value=""></option>
                                <?php
                                if (isset($categories) && isset($expense))
                                    foreach ($categories as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= $row->id === $expense->category_id ? 'selected' : '' ?>><?= $row->label; ?></option>
                                    <?php }
                                else if (isset($categories)) {
                                    foreach ($categories as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>Amount</label>
                            <div class="input-groupicon">
                                <input type="text" value="<?= isset($expense) ? $expense->amount : null ?>" name="amount" placeholder="Enter Amount" required>
                                <div class="addonset">
                                    <i class="fa fa-money-bill fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description"><?= isset($expense) ? $expense->description : null ?></textarea>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Save</button>
                        <a href="" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>