<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?=isset($title)?$title:'Edit Product'; ?></h4>
            <h6>Create new product</h6>
        </div>
    </div>

   <form action="<?=site_url('products') ?>" class="card post-form" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($product) ? $customer->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($product) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product SKU</label>
                        <input type="text" name="sku" value="<?=isset($product)?$product->sku:null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?=isset($product)?$product->name:null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Product Barcode</label>
                        <input type="text" name="barcode" value="<?=isset($product)?$product->barcode:null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Unit Cost</label>
                        <input type="text" name="unit_cost" value="<?=isset($product)?$product->unit_cost:null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Selling Price</label>
                        <input type="text" name="unit_price" value="<?=isset($product)?$product->unit_price:null ?>" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Min. Quantity</label>
                        <input type="number" name="min_quantiy" value="<?=isset($product)?$product->unit_price:null ?>" >
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="select" name="category_id">
                            <option>Choose Category</option>
                            <option value="1">Computers</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Brand</label>
                        <select class="select">
                            <option>Choose Brand</option>
                            <option value="1">Brand</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Unit</label>
                        <select class="select">
                            <option>Choose Unit</option>
                            <option value="1">Unit</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"><?=isset($product)?$product->description:null ?></textarea>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Tax</label>
                        <select class="select">
                            <option>Choose Tax</option>
                            <option>2%</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Discount Type</label>
                        <select class="select">
                            <option>Percentage</option>
                            <option>10%</option>
                            <option>20%</option>
                        </select>
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
                    <a href="<?=site_url('products') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>