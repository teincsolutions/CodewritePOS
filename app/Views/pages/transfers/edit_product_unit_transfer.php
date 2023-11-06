<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Create Unit Transfer</h4>
            <h6>Unit Transfer your stocks to one store another store.</h6>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('transfers/units') ?>" method="post">
        <?= csrf_field() ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Date</label>
                            <div class="input-groupicon">
                                <input name="transfer_date" type="text" class="datetimepicker" value="<?= date('d-m-Y', time()) ?>" required>
                                <div class="addonset">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Store</label>
                            <select name="store_id" class="select2-store">
                                <?php
                                if (isset($stores))
                                    foreach ($stores as $row) { ?>
                                    <option value="<?= $row->id ?>">
                                        <?= $row->name; ?><?= $row->location ? "($row->location)" : ($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : ''); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Transac. ID</label>
                            <input type="text" name="invoice" class="form-control" value="<?= $invoice ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div style="max-height: 500px !important;overflow-y:scroll" class="table-responsive mb-3">
                        <table class="table tr-items">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="max-width: 35%;width: 35%;">From Product</th>
                                    <th>From QTY</th>
                                    <th style="max-width: 35%;width: 35%;">To Product</th>
                                    <th>To QTY</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>
                                        <select name="items[0][from_product_id]" class="select2-product from-product form-control">
                                            <option value=""></option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="increment-decrement">
                                            <div class="input-groups">
                                                <input type="hidden" name="items[0][to_unit_qty]" value="0" class="to_unit_qty" required>
                                                <input type="button" value="-" class="button-minus dec button">
                                                <input onblur="updateItemRow(this)" min="1" type="text" name="items[0][from_unit_qty]" value="0" class="quantity-field" required>
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select name="items[0][to_product_id]" class="select2-product to-product form-control">
                                            <option value=""></option>
                                        </select>
                                    </td>
                                    <td>0.00</td>
                                    <td>
                                        <a href="javascript:void(0);" class="add-set btn btn-info text-white"><i class="fa fa-plus"></i></a>
                                        <a href="javascript:void(0);" class="delete-set btn btn-danger text-white"><i class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button onclick="$('.post-form').submit()" type="button" class="btn btn-submit me-2">Submit Unit Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-unit-transfer.js?v=1') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>
<?= $this->endSection() ?>