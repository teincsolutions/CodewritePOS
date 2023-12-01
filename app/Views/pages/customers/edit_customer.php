<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Customer Management</h4>
            <h6>Save/Update Customer</h6>
        </div>
        <div class="page-btn">
            <a href="<?=site_url('customers') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-1"></i> List Customers</a>
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
                        <label>Type</label>
                        <select name="type" class="select">
                            <option value="retailer" <?= isset($customer) ? ($customer->type === 'retailer' ? 'selected' : '') : null  ?>>Retailer</option>
                            <option value="wholeseller" <?= isset($customer) ? ($customer->type === 'wholeseller' ? 'selected' : '') : null  ?>>Wholeseller</option>
                        </select>
                    </div>
                </div>
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
                <div class="col-lg-6 col-12">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" value="<?= isset($customer) ? $customer->address : null ?>" placeholder="Customer address">
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="form-group">
                        <label>Discount</label>
                        <div class="input-group">
                            <input type="number" name="discount" class="form-control addon-inline" value="<?= isset($customer) ? $customer->discount : null ?>" placeholder="Customer discount">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-12">
                    <?php if (setting('App.AllowCustomerLimit') === 'yes') : ?>
                        <div class="row p-2 mb-3 border">
                            <div class="col-lg-6 col-12">
                                <div class="form-group">
                                    <label>Credit Limit</label>
                                    <div class="input-group">
                                    <span class="input-group-text">GHS</span>
                                        <input type="number" name="credit_limit" class="form-control w-100" value="<?= isset($customer) ? $customer->credit_limit : null ?>" placeholder="Customer credit limit">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="form-group">
                                    <label>Credit Limit Days</label>
                                    <div class="input-group">
                                        <input type="number" name="credit_limit_days" class="form-control w-100" value="<?= isset($customer) ? $customer->credit_limit_days : setting('App.LimitSalesDebitDays') ?>" placeholder="Customer credit limit days" required>
                                        <span class="input-group-text">days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
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