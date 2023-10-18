<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= $title ?? "Add Adjustment" ?></h4>
            <h6>Manage your adjustment</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('adjustments') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-3"></i> List Adjustment</a>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('adjustments') ?>" method="post">

        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <?= csrf_field() ?>
                <input type="hidden" name="invoice" value="<?= $invoice ?>">
                <input id="adjustment-total" type="hidden" name="total_amount">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store_id" class="select2-store">
                                        <?php
                                        if (isset($stores))
                                            foreach ($stores as $row) { ?>
                                            <option value="<?= $row->id ?>" <?= isset($adjustment) ? ($row->id === $adjustment->store_id ? 'selected' : '') : null ?>>
                                                <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="input-groupicon">
                                        <input name="adj_date" type="text" class="datetimepicker" value="<?= date('d-m-Y', time()) ?>" required>
                                        <div class="addonset">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Transac. ID</label>
                                    <input type="text" name="invoice" class="form-control" value="<?= $invoice ?>" readonly>
                                </div>
                            </div>

                            <div class="col-lg-12 col-sm-6 col-12 mb-3">
                                <div class="form-group">
                                    <div class="form-outline autocomplete">
                                        <label class="form-label" for="form1">Search</label>
                                        <input autocomplete="off" id="search-products" type="search" class="form-control" placeholder="Enter product name, barcode, sku..." />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive mb-3">
                                <table class="table tr-items">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Cost</th>
                                            <th>Instock Qty</th>
                                            <th>Phys Qty</th>
                                            <th>Diff Qty</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 offset-lg-6">
                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                    <ul>
                                        <li class="total">
                                            <h4>Grand Total</h4>
                                            <h5 class="grandTotal">0.00</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success text-uppercase">Submit Adjustments</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('modal') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-adjustment.js?v=9') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>
<?= $this->endSection() ?>