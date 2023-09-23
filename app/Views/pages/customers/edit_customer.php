<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Customer Management</h4>
            <h6>Save/Update Customer</h6>
        </div>
    </div>

    <form action="<?= site_url('customers') ?>" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($customer) ? $customer->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($customer) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input name="name" type="text" value="<?= isset($customer) ? $customer->name : null ?>" placeholder="Customer Name" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="text" value="<?= isset($customer) ? $customer->email : null ?>" placeholder="Email">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Phone</label>
                        <input name="phone" type="text" value="<?= isset($customer) ? $customer->phone : null ?>" placeholder="Phone number">
                    </div>
                </div>
                <div class="col-lg-9 col-12">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" value="<?= isset($customer) ? $customer->address : null ?>" placeholder="Customer address">
                    </div>
                </div>

                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Save</button>
                    <a href="<?= site_url('customers') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>