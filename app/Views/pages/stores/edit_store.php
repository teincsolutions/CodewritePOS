<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Store Management</h4>
            <h6><?=isset($subtitle)?$subtitle:'Add a new Store' ?></h6>
        </div>
    </div>

    <form action="<?=site_url('stores') ?>" class="post-form" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($store) ? $store->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($store) ? 'put' : 'post' ?>">
        <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Store Name</label>
                        <input type="text" name="name" required placeholder="Store name" value="<?=isset($store)?$store->name:null ?>" required>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" required placeholder="Store location" value="<?=isset($store)?$store->location:null ?>">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" required placeholder="Store phone number" value="<?=isset($store)?$store->phone:null ?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" placeholder="Description here"><?=isset($store)?$store->description:null ?></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="<?=site_url('stores') ?>" class="btn btn-cancel">Cancel</a>
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