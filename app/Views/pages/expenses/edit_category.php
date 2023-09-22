<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Add Expense Category</h4>
            <h6>Create new Expense Category</h6>
        </div>
    </div>

    <form action="<?=site_url('expense-categories') ?>" class="post-form" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($expense_category) ? $expense_category->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($expense_category) ? 'put' : 'post' ?>">
        <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="label" required placeholder="Expense Category">
                    </div>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="<?=site_url('expense-categories') ?>" class="btn btn-cancel">Cancel</a>
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