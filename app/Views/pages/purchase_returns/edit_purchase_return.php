<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Create Purchase Return</h4>
            <h6>Add/Update Purchase Return</h6>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('purchases/returns') ?>" method="post">
        <?= csrf_field() ?>
        <input id="order-status" type="hidden" name="order_status" value="completed">
        <input id="payment-status" type="hidden" name="payment_status" value="paid">
        <input type="hidden" name="payment_type" value="cash">
        <input type="hidden" name="store_id" value="<?= isset($purchase) ? $purchase->store_id : null ?>">
        <input id="purchases-total" type="hidden" name="total_amount" value="<?= isset($purchase) ? $purchase->total_amount : 0.00 ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $error ?>
                            <a href="<?= site_url('purchases/pos') ?>" type="button" class="btn-close" aria-label="Close"></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">

                    <div class="col-lg-6 col-sm-6 col-12" style="overflow-x: auto;">
                        <div class="form-group">
                            <label>Reference</label>
                            <select name="purchase_id" class="select2-invoices" required>
                                <option value=""></option>
                                <?php if (isset($purchase)) : ?>
                                    <option value="<?= $purchase->id ?>" selected><?= $purchase->invoice ?> (<?= isset($purchase->supplier) ? $purchase->supplier->name : 'walk-in-supplier' ?> - GHS <?= $purchase->total_amount; ?>)</option>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12" style="overflow-x: auto;">
                        <div class="form-group">
                            <label>Supplier</label>
                            <select name="supplier_id" class="select2-suppliers" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Date</label>
                            <div class="input-groupicon">
                                <input name="return_date" type="text" class="datetimepicker" value="<?= date('d-m-Y', time()) ?>" required>
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

                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <div class="form-outline autocomplete">
                                <label class="form-label" for="form1">Search</label>
                                <input autocomplete="off" id="search-products" type="search" class="form-control" placeholder="Enter product name, barcode, sku..." />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div style="max-height: 500px !important;overflow-y:scroll" class="table-responsive mb-3">
                        <table class="table tr-items">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>QTY</th>
                                    <th>Cost</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($purchase)) :
                                    $ids = array_map(function ($item) {
                                        return $item->id;
                                    }, $purchase->items);

                                    foreach ($purchase->items as $key => $row) :
                                        $row->max_qty  = $row->qty - $row->return_qty;
                                        if ($row->max_qty <= 0) continue;
                                ?>
                                        <tr>
                                            <td>
                                            </td>
                                            <td class="productimgname">
                                                <?= $row->product->image_uri
                                                    ? '<a class="product-img"><img src="' . base_url($row->product->image_uri) . '" alt="product"></a>'
                                                    : '<a class="p-3"></a>' ?>
                                                <a target="_blank" href="<?= site_url('products/' . $row->product_id) ?>">
                                                    <?= setting('App.ShowProductSKU') === 'yes' ? $row->product->sku : '' ?> <?= $row->product->name ?>
                                                    (<?= $row->product->unit->label; ?>)</a>
                                            </td>
                                            <td>
                                                <div class="increment-decrement">
                                                    <div class="input-groups">
                                                        <input type='hidden' name="items[<?= $key ?>][product_id]" value="<?= $row->product_id ?>">
                                                        <input type='hidden' name="items[<?= $key ?>][purchase_item_id]" value="<?= $row->id ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][unit_cost]" value="<?= $row->unit_cost; ?>" class="runit_cost">
                                                        <input type="hidden" name="items[<?= $key ?>][unit_price]" value="<?= $row->unit_price; ?>" class="runit_price">
                                                        <input type="hidden" name="items[<?= $key ?>][store_id]" value="<?= $row->store_id; ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][subtotal]" class="rsubtotal" value="<?= $row->max_qty * $row->unit_cost ?>">
                                                        <input type="button" value="-" class="button-minus dec button">
                                                        <input onkeyup="updateItemRow(this)" min="0.1" type="text" name="items[<?= $key ?>][qty]" max="<?= $row->max_qty ?>" value="<?= $row->max_qty ?>" class="quantity-field rqty" required>
                                                        <input type="button" value="+" class="button-plus inc button">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $row->unit_cost ?></td>
                                            <td><?= number_format($row->max_qty * $row->unit_cost, 2, '.', '') ?></td>
                                            <td><a href="javascript:void(0);" class="delete-set" data-item-id="<?= $row->id ?>"><i class="fa text-danger fa-trash"></i></a></td>

                                        </tr>
                                <?php endforeach;
                                endif ?>
                            </tbody>
                        </table>
                        <script>
                            let prodIndex = <?= isset($purchase) ? sizeof($purchase->items) : 0 ?>;
                        </script>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Order Tax</label>
                            <div class="input-group">
                                <input type="text" name="tax" value="<?= isset($purchase) ? $purchase->tax : null ?>" class="form-control" placeholder="Purchase taxes" readonly>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Supplier Discount</label>
                            <div class="input-group">
                                <input onkeyup="updateTotals()" type="number" name="discount" value="<?= isset($purchase) ? $purchase->discount : null ?>" class="form-control addon-inline" placeholder="Purchase discount" readonly>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shipping</label>
                            <input onkeyup="updateTotals()" type="number" name="shipping" value="<?= isset($purchase) ? $purchase->shipping : null ?>" class="form-control" placeholder="Shipping amount">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Change</label>
                            <input type="number" name="paid" id="paid" class="form-control" placeholder="Change Amount">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 float-md-right">
                        <div class="total-order">
                            <ul>
                                <li>
                                    <h4>SubTotal </h4>
                                    <h5 class="subTotal">GHS 0.00</h5>
                                </li>
                                <li>
                                    <h4>Total Shipping </h4>
                                    <h5 class="shippingTotal">GHS 0.00</h5>
                                </li>
                                <li>
                                    <h4>Total Tax</h4>
                                    <h5 class="orderTaxes">GHS 0.0</h5>
                                </li>
                                <li>
                                    <h4>Total Discount</h4>
                                    <h5 class="discountTotal">GHS 0.00</h5>
                                </li>
                                <li class="total-value">
                                    <h4>Grand Total </h4>
                                    <h5 class="grandTotal">GHS 0.00</h5>
                                </li>
                                <li id="acc-bal">
                                    <h4>A/c Balance </h4>
                                    <h5 class="supplier-balance">GHS 0.00</h5>
                                </li>
                                <li class="total-value">
                                    <h4>Change/Due</h4>
                                    <h5 class="dueTotal">GHS 0.00</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button id="submit" onclick="$('.post-form').submit()" type="button" class="btn btn-submit me-2">Submit Return</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-purchase-return.js?v=26') ?>"></script>
<?php if (isset($purchase) && $purchase->supplier) {
    $supplier = $purchase->supplier;
    $supplier->text = $supplier->name . " (" . ($supplier->address ? $supplier->address : $supplier->phone) . ")";
?>
    <script>
        $(() => {
            let supplierData = <?= json_encode($supplier) ?>;
            var option = new Option(supplierData.text, supplierData.id, true, true);
            select2Supplier.append(option).trigger('change');
            select2Supplier.trigger({
                type: 'select2:select',
                params: {
                    data: supplierData
                }
            });
        });
        purchaseItemIds = <?= json_encode($ids) ?>;
    </script>
<?php } else if (isset($purchase)) { ?>
    <script>
        purchaseItemIds = <?= json_encode($ids) ?>;
    </script>
<?php } ?>
<?= $this->endSection() ?>