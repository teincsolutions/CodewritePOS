<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Supplier Management</h4>
            <h6>Save/Update Supplier</h6>
        </div> 
    </div>

    <form action="<?=site_url('suppliers') ?>" class="card post-form" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($supplier) ? $supplier->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($supplier) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Supplier Name</label>
                        <input name="name" type="text" value="<?=isset($supplier)?$supplier->name:null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="text" value="<?=isset($supplier)?$supplier->email:null ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Phone</label>
                        <input name="phone" type="text" value="<?=isset($supplier)?$supplier->phone:null ?> ">
                    </div>
                </div>
                <div class="col-lg-9 col-12">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" value="<?=isset($supplier)?$supplier->address:null ?>">
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Save</button>
                    <a href="<?=site_url('suppliers') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>