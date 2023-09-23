<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= isset($title) ? $title : 'Edit Product'; ?></h4>
            <h6>Create new product</h6>
        </div>
    </div>

    <form action="<?= site_url('products') ?>" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($product) ? $product->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($product) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product Code/SKU</label>
                        <input type="text" name="sku" value="<?= isset($product) ? $product->sku : null ?>" placeholder="Product Code/SKU" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?= isset($product) ? $product->name : null ?>" placeholder="Product Name" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product Barcode</label>
                        <input type="text" name="barcode" value="<?= isset($product) ? $product->barcode : null ?>" placeholder="Barcode" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Unit Cost</label>
                        <input type="text" name="unit_cost" value="<?= isset($product) ? $product->unit_cost : null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Selling Price</label>
                        <input type="text" name="unit_price" value="<?= isset($product) ? $product->unit_price : null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Min. Quantity</label>
                        <input type="number" name="min_quantiy" class="form-control" value="<?= isset($product) ? $product->unit_price : 10 ?>" placeholder="Minimum quantity">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="select2-category" name="category_id" required>
                            <option value=""></option>
                            <?php
                            if (isset($categories) && isset($product))
                                foreach ($categories as $row) { ?>
                                <option value="<?= $row->id ?>" <?= $row->id === $product->category_id ? 'selected' : '' ?>><?= $row->name; ?></option>
                                <?php }
                            else if (isset($categories)) {
                                foreach ($categories as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Brand</label>
                        <select name="brand_id" class="select2-brand">
                            <option value=""></option>
                            <?php
                            if (isset($brands) && isset($product))
                                foreach ($brands as $row) { ?>
                                <option value="<?= $row->id ?>" <?= $row->id === $product->brand_id ? 'selected' : '' ?>><?= $row->name; ?></option>
                                <?php }
                            else if (isset($brands)) {
                                foreach ($brands as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Unit</label>
                        <select name="unit_id" class="select2-unit" required>
                            <option value=""></option>
                            <?php
                            if (isset($units) && isset($product))
                                foreach ($units as $row) { ?>
                                <option value="<?= $row->id ?>" <?= $row->id === $product->unit_id ? 'selected' : '' ?>><?= $row->label; ?></option>
                                <?php }
                            else if (isset($units)) {
                                foreach ($units as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" placeholder="Description here..."><?= isset($product) ? $product->description : null ?></textarea>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Tax</label>
                        <select name="tax_id" class="select">
                            <option value=""></option>
                            <?php
                            if (isset($taxes) && isset($product))
                                foreach ($taxes as $row) { ?>
                                <option taxes="<?= $row->id ?>" <?= $row->id === $product->tax_id ? 'selected' : '' ?>><?= $row->label; ?></option>
                                <?php }
                            else if (isset($taxes)) {
                                foreach ($taxes as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Discount Type</label>
                        <input type="number" name="discount" class="form-control" value="<?= isset($product) ? $product->discount : 0.00 ?>" placeholder="Discount amount">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label> Product Image</label>
                        <div class="image-upload">
                            <input type="file">
                            <div class="image-uploads">
                                <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/upload.svg" alt="img">
                                <h4>Drag and drop a file to upload</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Save</button>
                    <a href="<?= site_url('products') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>