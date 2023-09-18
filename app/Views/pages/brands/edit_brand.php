<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= isset($title) ? $title : "Add New Brand" ?></h4>
            <h6><?= isset($title) ? $title : "Create a new brand" ?></h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('brands') ?>" class="btn btn-added"><i  class="fa fa-arrow-left me-2"></i>List Brands</a>
        </div>
    </div>
    <form action="<?= site_url('brands') ?>" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($brand) ? $brand->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($brand) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Brand Name</label>
                        <input type="text" name="name" autocomplete="off" placeholder="Enter brand name" value="<?= isset($brand) ? $brand->name : null ?>" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Brief description here..."><?= isset($brand) ? $brand->description : null ?></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label> Brand Image</label>
                        <div class="image-upload">
                            <input type="file" name="image">
                            <div class="image-uploads">
                                <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/upload.svg" alt="img">
                                <h4>Drag and drop a file to upload</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="product-list">
                        <ul class="row">
                            <?php if (isset($brand) && isset($brand->image_uri)) { ?>
                                <li>
                                    <div class="productviews">
                                        <div class="productviewsimg">
                                            <img src="<?= site_url($brand->image_uri); ?>" alt="img">
                                        </div>
                                        <div class="productviewscontent">
                                            <div class="productviewsname">
                                                <h2></h2>
                                                <h3>581kb</h3>
                                            </div>
                                            <a class="remove" href="javascript:void(0);">x</a>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="<?= site_url('brands') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>