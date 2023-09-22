<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Product Add Category</h4>
            <h6>Create new product Category</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('categories') ?>" class="btn btn-added"><i  class="fa fa-arrow-left me-2"></i>List Brands</a>
        </div>
    </div>

    <form action="<?= site_url('categories') ?>" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($category) ? $category->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($category) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input name="name" type="text" placeholder="Category Name" value="<?= isset($category) ? $category->name : null ?>" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Brief description..."><?= isset($category) ? $category->description : null ?></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="<?= site_url('categories') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>