<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Supplier Management</h4>
            <h6>Save/Update Supplier</h6>
        </div>
        <div class="page-btn">
            <a href="<?=site_url('suppliers') ?>" class="btn btn-added"><i class="fa fa-arrow-left fa-lg me-1"></i>List Suppliers</a>
        </div>
    </div>

    <form action="<?= site_url('suppliers') ?>" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($supplier) ? $supplier->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($supplier) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Supplier Name</label>
                        <input name="name" type="text" value="<?= isset($supplier) ? $supplier->name : null ?>" placeholder="Supplier Name" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="text" value="<?= isset($supplier) ? $supplier->email : null ?>" placeholder="Email">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Phone</label>
                        <input name="phone" type="text" value="<?= isset($supplier) ? $supplier->phone : null ?>" placeholder="Phone number">
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" value="<?= isset($supplier) ? $supplier->address : null ?>" placeholder="Supplier address">
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="form-group">
                        <label>Discount</label>
                        <div class="input-group">
                            <input type="number" name="discount" class="form-control addon-inline" value="<?= isset($supplier) ? $supplier->discount : null ?>" placeholder="Customer discount">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Save</button>
                    <a href="<?= site_url('suppliers') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>