<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= isset($title) ? $title : 'Edit Container'; ?></h4>
            <h6>Create new container</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('containers') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-2"></i>List Container</a>
        </div>
    </div>

    <form action="<?= site_url('containers') ?>" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($container) ? $container->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($container) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Container Type</label>
                        <select class="select2-ctype" name="type" required>
                            <option value="product" <?= isset($container) ? ('product' === $container->type ? 'selected' : '') : null ?>>Product Container</option>
                            <option value="brand" <?= isset($container) ? ('brand' === $container->type ? 'selected' : '') : null ?>>Brand Container</option>

                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Container Code/SKU</label>
                        <input type="text" name="sku" value="<?= isset($container) ? $container->sku : null ?>" placeholder="Container Code/SKU" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Container Name</label>
                        <input type="text" name="name" value="<?= isset($container) ? $container->name : null ?>" placeholder="Container Name" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Container Barcode</label>
                        <input type="text" name="barcode" value="<?= isset($container) ? $container->barcode : null ?>" placeholder="Barcode">
                    </div>
                </div>
                <?php if (setting('App.ContainerDiffForStore') !== 'yes') : ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Unit Cost</label>
                            <input type="text" name="unit_cost" value="<?= isset($container) ? $container->unit_cost : null ?>" placeholder="Unit Cost">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Selling Price</label>
                            <input type="text" name="unit_price" value="<?= isset($container) ? $container->unit_price : null ?>" placeholder="Unit Price" required>
                        </div>
                    </div>
                    <?php if (setting('App.AllowWholeSalePrices') === 'yes') : ?>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Wholesale Price</label>
                                <input type="text" name="unit_ws_price" value="<?= isset($container) ? $container->unit_ws_price : null ?>" placeholder="Wholesale Price">
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Min. Quantity</label>
                            <input type="number" name="min_qty" class="form-control" value="<?= isset($container) ? $container->min_qty : 10 ?>" placeholder="Minimum quantity">
                        </div>
                    </div>
                <?php endif ?>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="select2-category" name="category_id" required>
                            <option value=""></option>
                            <?php
                            if (isset($categories) && isset($container))
                                foreach ($categories as $row) { ?>
                                <option value="<?= $row->id ?>" <?= $row->id === $container->category_id ? 'selected' : '' ?>><?= $row->name; ?></option>
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
                            if (isset($brands) && isset($container))
                                foreach ($brands as $row) { ?>
                                <option value="<?= $row->id ?>" <?= $row->id === $container->brand_id ? 'selected' : '' ?>><?= $row->name; ?></option>
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
                            if (isset($units) && isset($container))
                                foreach ($units as $row) { ?>
                                <option value="<?= $row->id ?>" <?= $row->id === $container->unit_id ? 'selected' : '' ?>><?= $row->label; ?></option>
                                <?php }
                            else if (isset($units)) {
                                foreach ($units as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Unit Quantity</label>
                        <input type="number" name="unit_qty" class="form-control" value="<?= isset($container) ? $container->unit_qty : 1 ?>" placeholder="Unit quantity">
                    </div>
                </div>
                <?php if (setting('App.UseExpiration') === 'yes') : ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input type="date" class="form-control" name="expiration" value="<?= isset($container) ? $container->expiration : null ?>">
                        </div>
                    </div>
                <?php endif ?>
                <?php if (setting('App.UsePurchaseDiscount') === 'yes') : ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Purchase Discount</label>
                            <input type="number" name="pdiscount" step="any" class="form-control" value="<?= isset($container) ? $container->pdiscount : "0.00" ?>" placeholder="Purchase Discount">
                        </div>
                    </div>
                <?php endif ?>
                <?php if (setting('App.ContainerDiffForStore') === 'yes') : ?>
                    <div class="col-lg-12">
                        <?php foreach ($stores as $key => $row) : ?>
                            <input type="hidden" name="items[<?= $key ?>][store_id]" value="<?= $row->id; ?>">
                            <div class="row mb-3 p-2 border border-bottom">
                                <h6 class="mb-1">Set <span class="text-warning"><?= $row->name; ?></span> Details</h6>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Unit Cost</label>
                                        <input type="text" name="items[<?= $key ?>][unit_cost]" value="<?= isset($container) ? model('StoreContainerModel')->getCost($container->id, $row->id) : null ?>" placeholder="Unit Cost">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Selling Price</label>
                                        <input type="text" name="items[<?= $key ?>][unit_price]" value="<?= isset($container) ? model('StoreContainerModel')->getPrice($container->id, $row->id) : null ?>" placeholder="Unit Price" required>
                                    </div>
                                </div>
                                <?php if (setting('App.AllowWholeSalePrices') === 'yes') : ?>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Wholesale Price</label>
                                            <input type="text" name="items[<?= $key ?>][unit_ws_price]" value="<?= isset($container) ? model('StoreContainerModel')->getWSPrice($container->id, $row->id) : null ?>" placeholder="Wholesale Price">
                                        </div>
                                    </div>
                                <?php endif ?>

                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Min. Quantity</label>
                                        <input type="number" name="items[<?= $key ?>][min_qty]" class="form-control" value="<?= isset($container) ? model('StoreContainerModel')->getMinQty($container->id, $row->id) : 10 ?>" placeholder="Minimum quantity">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Sales Discount</label>
                                        <input type="number" name="items[<?= $key ?>][discount]" step="any" class="form-control" value="<?= isset($container) ? model('StoreContainerModel')->getDiscount($container->id, $row->id) : "0.00" ?>" placeholder="Discount amount">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Discontinued</label>
                                        <div class="d-flex gap-5">
                                            <label class="inputcheck text-capitalize">Yes
                                                <input type="radio" name="items[<?= $key ?>][discontinued]" value="1" <?= isset($container) ? (model('StoreContainerModel')->getDiscontinued($container->id, $row->id) == 1 ? 'checked' : '') : '' ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="inputcheck text-capitalize">No
                                                <input type="radio" name="items[<?= $key ?>][discontinued]" value="0" <?= isset($container) ? (model('StoreContainerModel')->getDiscontinued($container->id, $row->id) == 0 ? 'checked' : '') : 'checked' ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" placeholder="Description here..."><?= isset($container) ? $container->description : null ?></textarea>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Tax</label>
                        <select name="tax_id" class="select2-tax">
                            <option value=""></option>
                            <?php
                            if (isset($taxes) && isset($container))
                                foreach ($taxes as $row) { ?>
                                <option value="<?= $row->id ?>" <?= $row->id == $container->tax_id ? 'selected' : '' ?>><?= $row->label; ?></option>
                                <?php }
                            else if (isset($taxes)) {
                                foreach ($taxes as $row) { ?>
                                    <option value="<?= $row->id ?>"><?= $row->label; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <?php if (setting('App.ContainerDiffForStore') !== 'yes') : ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Sales Discount</label>
                            <input type="number" name="discount" step="any" class="form-control" value="<?= isset($container) ? $container->discount : "0.00" ?>" placeholder="Discount amount">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Purchase Discount</label>
                            <input type="number" name="pdiscount" step="any" class="form-control" value="<?= isset($container) ? $container->pdiscount : "0.00" ?>" placeholder="Discount amount">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Discontinued</label>
                            <div class="d-flex gap-5">
                                <label class="inputcheck text-capitalize">Yes
                                    <input type="radio" name="discontinued" value="1" <?= isset($container) ? ($container->discontinued == 1 ? 'checked' : '') : '' ?>>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="inputcheck text-capitalize">No
                                    <input type="radio" name="discontinued" value="0" <?= isset($container) ? ($container->discontinued == 0 ? 'checked' : '') : 'checked' ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label> Container Image</label>
                        <div class="image-upload">
                            <input name="images[]" type="file">
                            <div class="image-uploads">
                                <img src="<?= base_url('assets/icons/upload.svg') ?>" alt="img">
                                <h4>Drag and drop a file to upload</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Save</button>
                    <a href="<?= site_url('containers') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>