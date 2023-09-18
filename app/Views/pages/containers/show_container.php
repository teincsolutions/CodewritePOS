<?= $this->extend('template/default') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Container Details</h4>
            <h6>Full details of a container</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('containers') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-1"></i> List Container</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    Details of <?= $container->name; ?>
                    <a href="<?= site_url('containers/edit/' . $container->id) ?>"><i class="fa fa-edit"></i> </a>
                </div>
                <div class="card-body">
                    <?php if ($container->barcode) : ?>
                        <div class="bar-code-view">
                            <div id="barcode"><svg id="code128"></svg></div>
                            <a class="printimg">
                                <i class="fa fa-print fa-lg"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="productdetails">
                        <ul class="container-bar">
                            <li>
                                <h4>Container</h4>
                                <h6><?= $container->name ?></h6>
                            </li>
                            <li>
                                <h4>Category</h4>
                                <h6><?= $container->category->name; ?></h6>
                            </li>
                            <li>
                                <h4>Brand</h4>
                                <h6><?= $container->brand ? $container->brand->name : 'None' ?></h6>
                            </li>
                            <li>
                                <h4>Unit</h4>
                                <h6><?= $container->unit->label ?></h6>
                            </li>
                            <li>
                                <h4>SKU</h4>
                                <h6><?= $container->sku ?></h6>
                            </li>
                            <li>
                                <h4>Unit Qty</h4>
                                <h6><?= $container->unit_qty; ?></h6>
                            </li>
                            <li>
                                <h4>Quantity In Stock</h4>
                                <h6><?= $container->instock ?></h6>
                            </li>
                            <?php if (setting('App.ContainerDiffForStore') !== 'yes') : ?>
                                <li>
                                    <h4>Unit Price</h4>
                                    <h6>GHS <?= number_format($container->unit_price, 2) ?></h6>
                                </li>
                                <li>
                                    <h4>Unit Cost</h4>
                                    <h6>GHS <?= number_format($container->unit_cost, 2) ?></h6>
                                </li>
                                <li>
                                    <h4>Wholesale Price</h4>
                                    <h6>GHS <?= number_format($container->unit_ws_price, 2) ?></h6>
                                </li>
                              
                                <li>
                                    <h4>Status</h4>
                                    <h6><?= ['Active', 'Discontinued'][$container->discontinued] ?></h6>
                                </li>
                                <li>
                                    <h4>Minimum Qty</h4>
                                    <h6><?= $container->min_qty; ?></h6>
                                </li>
                            <?php endif ?>
                            <li>
                                <h4>Description</h4>
                                <h6><?= $container->description; ?></h6>
                            </li>
                            <?php if (setting('App.UseExpiration') === 'yes') : ?>
                                <li>
                                    <h4>Expiration Date</h4>
                                    <h6><?= date('d/m/Y', strtotime($container->expiration)); ?></h6>
                                </li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="slider-product-details">
                        <div class="owl-carousel owl-theme container-slide">
                            <div class="slider-product">
                                <img src="<?= $container->image_uri ? base_url($container->image_uri) : base_url('assets/images/noimage.png') ?>" alt="img">
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
                        <table class="table w-100" id="dt-stocks">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store</th>
                                    <th>Instock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (model('ContainerStockModel')->where('container_id', $container->id)->findAll() as $key => $row) : ?>
                                    <tr>
                                        <th><?= $key + 1 ?></th>
                                        <td><?= $row->store->name ?></td>
                                        <td><?= $row->instock ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (setting('App.ContainerDiffForStore') === 'yes') : ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table w-100" id="dt-store-containers">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Store</th>
                                        <th>Unit Price</th>
                                        <th>Wholesale Price</th>
                                        <th>Unit Cost</th>
                                        <th>Min Qty</th>
                                        <th>Sales Discount(GHS)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (model('StoreContainerModel')->where('container_id', $container->id)->findAll() as $key => $row) : ?>
                                        <tr>
                                            <th><?= $key + 1 ?></th>
                                            <td><?= $row->store->name ?></td>
                                            <td><?= $row->unit_price ?></td>
                                            <td><?= $row->unit_ws_price ?></td>
                                            <td><?= $row->unit_cost ?></td>
                                            <td><?= $row->min_qty ?></td>
                                            <td><?= $row->discount ?></td>
                                            <td><?= ['Active', 'Discontinued'][$row->discontinued] ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/container-details.js?v=1') ?>"></script>
<?php if ($container->barcode && !empty($container->barcode)) : ?>
    <script src="<?= base_url('assets/js/plugins/barcode.js') ?>"></script>
    <script>
        JsBarcode("#code128", "<?= $container->barcode ?>");

        $(() => {
            $('.printimg').on('click', () => {
                const newWin = window.open("", "<?= $container->barcode ?> <?= $container->name ?>",
                    "left=0,top=0,toolbar=0,scrollbars=0,status=0");
                newWin.document.write($('#barcode').html());
                newWin.focus();
                setTimeout(() => {
                    newWin.print();
                    newWin.close();
                }, 300);
            });
        })
    </script>
<?php endif ?>
<?= $this->endSection() ?>