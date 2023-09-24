<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Add/Update Unit</h4>
            <h6>Create new Unit</h6>
        </div>
    </div>

    <form action="<?=site_url('units') ?>" class="post-form" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($unit) ? $unit->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($unit) ? 'put' : 'post' ?>">
        <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Unit Name</label>
                        <input type="text" name="label" required placeholder="Unit label" value="<?=isset($unit)?$unit->label:null ?>">
                    </div>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"><?=isset($unit)?$unit->description:null ?></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="<?=site_url('units') ?>" class="btn btn-cancel">Cancel</a>
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