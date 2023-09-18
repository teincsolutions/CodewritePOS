<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Add Expense SubCategory</h4>
            <h6>Create new Expense SubCategory</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('expenses/subcategories') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-1"></i> List Expense SubCategory</a>
        </div>
    </div>

    <form action="<?= site_url('expenses/subcategories') ?>" class="post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($expense_subcategory) ? $expense_subcategory->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($expense_subcategory) ? 'put' : 'post' ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>SubCategory Name</label>
                            <input type="text" name="label" required placeholder="Expense SubCategory" value="<?= isset($expense_subcategory) ? $expense_subcategory->label : null ?>">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label>Expense Category</label>
                            <select class="select2-category" name="expense_category_id">
                                <option value=""></option>
                                <?php
                                if (isset($categories) && isset($expense_subcategory))
                                    foreach ($categories as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= $row->id === $expense_subcategory->expense_category_id ? 'selected' : '' ?>><?= $row->label; ?></option>
                                    <?php }
                                else if (isset($categories)) {
                                    foreach ($categories as $row) { ?>
                                        <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description"><?= isset($expense_subcategory) ? $expense_subcategory->description : null ?></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="<?= site_url('expenses/categories') ?>" class="btn btn-cancel">Cancel</a>
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