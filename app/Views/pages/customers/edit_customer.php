<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Edit Customer Management</h4>
            <h6>Edit/Update Customer</h6>
        </div>
    </div>

    <form action="<?=site_url('customers') ?>" class="card post-form" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($category) ? $category->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($category) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input name="name" type="text" value="Thomas" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="text" value="Thomas@example.com">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Phone</label>
                        <input name="phone" type="text" value="+12163547758 ">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Choose Country</label>
                        <select class="select">
                            <option>United States</option>
                            <option>India</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>City</label>
                        <select class="select">
                            <option>Newyork</option>
                            <option>City</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-9 col-12">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" value="132, My Street, Kingston, New York ">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text </textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Update</button>
                    <a class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>