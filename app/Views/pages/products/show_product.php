<?= $this->extend('template/default') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Product Details</h4>
            <h6>Full details of a product</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('products') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-1"></i> List Product</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <?php if ($product->barcode) : ?>
                        <div class="bar-code-view">
                            <img src="" alt="barcode">
                            <a class="printimg">
                                <img src="" alt="print">
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h4>Product</h4>
                                <h6><?= $product->name ?></h6>
                            </li>
                            <li>
                                <h4>Category</h4>
                                <h6><?= $product->category->name; ?></h6>
                            </li>
                            <li>
                                <h4>Brand</h4>
                                <h6><?= $product->brand ? $product->brand->name : 'None' ?></h6>
                            </li>
                            <li>
                                <h4>Unit</h4>
                                <h6><?= $product->unit->label ?></h6>
                            </li>
                            <li>
                                <h4>SKU</h4>
                                <h6><?= $product->sku ?></h6>
                            </li>
                            <li>
                                <h4>Minimum Qty</h4>
                                <h6><?= $product->min_qty; ?></h6>
                            </li>
                            <li>
                                <h4>Unit Qty</h4>
                                <h6><?= $product->unit_qty; ?></h6>
                            </li>
                            <li>
                                <h4>Quantity In Stock</h4>
                                <h6><?= $product->instock ?></h6>
                            </li>
                            <li>
                                <h4>Tax</h4>
                                <h6><?= $product->tax ? $product->tax->rate : 0.00 ?>%</h6>
                            </li>
                            <li>
                                <h4>Discount</h4>
                                <h6><?= $product->discount ?>%</h6>
                            </li>
                            <li>
                                <h4>Price</h4>
                                <h6>GHS <?= number_format($product->unit_price, 2) ?></h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6><?= ['Active', 'Discontinued'][$product->discontinued] ?></h6>
                            </li>
                            <li>
                                <h4>Description</h4>
                                <h6><?= $product->description; ?></h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="slider-product-details">
                        <div class="owl-carousel owl-theme product-slide">
                            <div class="slider-product">
                                <img src="<?= $product->image_uri ? base_url($product->image_uri) : base_url('assets/images/noimage.png') ?>" alt="img">
                                <h4></h4>
                                <h6></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dt-inventory">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Store</th>
                                    <th>Instock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (model('StockModel')->where('product_id',$product->id)->findAll() as $key => $row): ?>
                                <tr>
                                    <th><?=$key + 1 ?></th>
                                    <td><?=$row->store->name ?></td>
                                    <td><?=$row->instock ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>