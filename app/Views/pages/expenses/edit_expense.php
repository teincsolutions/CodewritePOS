<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Expense Panel</h4>
            <h6>Add/Update Expenses</h6>
        </div>
    </div>
    <form action="<?=site_url('expenses') ?>" class="post-form" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($expense) ? $expense->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($expense) ? 'put' : 'post' ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Store</label>
                            <select class="select" name="store_id" required>
                                <option value="1">store1</option>
                                <option value="2">store2</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Expense Category</label>
                            <select class="select" name="expense_category_id">
                                <option value="1">Petrol</option>
                                <option value="2">Diesel</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Expense Date </label>
                            <div class="input-groupicon">
                                <input type="text" placeholder="Choose Date" class="datetimepicker" value="<?= isset($expense) ? $expense->expense_date : null ?>">
                                <div class="addonset">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/calendars.svg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Amount</label>
                            <div class="input-groupicon">
                                <input type="text" value="<?= isset($expense) ? $expense->amount : null ?>" name="amount" required>
                                <div class="addonset">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/dollar.svg" alt="img">
                                </div>
                            </div>
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