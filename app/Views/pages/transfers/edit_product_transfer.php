<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Create Transfer</h4>
            <h6>Transfer your stocks to one store another store.</h6>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('transfers/products') ?>" method="post">
        <?= csrf_field() ?>
        <input id="order-status" type="hidden" name="order_status" value="completed">
        <input id="payment-status" type="hidden" name="payment_status" value="paid">
        <input id="transfers-total" type="hidden" name="total_amount">
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
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>From Store</label>
                            <select name="from_store_id" class="select2-store">
                                <?php
                                if (isset($stores))
                                    foreach ($stores as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($sales) ? ($row->id === $sales->store_id ? 'selected' : '') : null ?>>
                                        <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>To Store</label>
                            <select name="to_store_id" class="select2-store">
                                <option value=""></option>
                                <?php
                                if (isset($stores))
                                    foreach ($stores as $row) { ?>
                                    <option value="<?= $row->id ?>" <?= isset($sales) ? ($row->id === $sales->store_id ? 'selected' : '') : null ?>>
                                        <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
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
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($transfer)) :
                                    $ids = array_map(function ($item) {
                                        return $item->id;
                                    }, $transfer->items);

                                    foreach ($transfer->items as $key => $row) :
                                        if ($row->qty <= 0) continue;
                                ?>
                                        <tr>
                                            <td>
                                            </td>
                                            <td class="productimgname">
                                                <?= $row->product->image_uri
                                                    ? '<a class="product-img"><img src="' . base_url($row->product->image_uri) . '" alt="product"></a>'
                                                    : '<a class="p-3"></a>' ?>
                                                <a target="_blank" href="<?= site_url('products/' . $row->product_id) ?>">
                                                    <?= $row->product->name ?>
                                                    (<?= $row->store->name; ?><?= $row->store->location ? "(" . $row->store->location . ")" : null; ?>)</a>
                                            </td>
                                            <td>
                                                <div class="increment-decrement">
                                                    <div class="input-groups">
                                                        <input type="button" value="-" class="button-minus dec button">
                                                        <input type='hidden' name="items[<?= $key ?>][product_id]" value="<?= $row->product_id ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][unit_price]" value="<?= $row->unit_price; ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][unit_cost]" value="<?= $row->unit_cost; ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][tax_id]" value="<?= $row->tax_id ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][store_id]" value="<?= $row->store_id; ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][tax]" class="rtax" value="<?= $row->tax ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][discount]" class="rdiscount" value="<?= $row->discount ?>">
                                                        <input type="hidden" name="items[<?= $key ?>][subtotal]" class="rsubtotal" value="<?= $row->subtotal ?>">
                                                        <input onblur="updateItemRow(this)" min="1" type="text" name="items[<?= $key ?>][qty]" max="<?= $row->qty ?>" value="<?= $row->qty ?>" class="quantity-field" required>
                                                        <input type="button" value="+" class="button-plus inc button">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $row->unit_price ?></td>
                                            <td data-discount="<?= $row->discount ?>"><?= $row->discount ?></td>
                                            <td data-tax="<?= $row->tax ?>" class="suffix-percent"><?= number_format($row->tax, 2) ?></td>
                                            <td><?= number_format($row->subtotal, 2) ?></td>
                                            <td><a href="javascript:void(0);" class="delete-set" data-item-id="<?= $row->id ?>"><i class="fa text-danger fa-trash"></i></a></td>

                                        </tr>
                                <?php endforeach;
                                endif ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <input onkeyup="updateTotals()" type="number" name="discount" class="form-control addon-inline" placeholder="Transfer discount">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shipping</label>
                            <input onkeyup="updateTotals()" type="number" name="shipping" class="form-control" placeholder="Shipping amount">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Paid</label>
                            <input onkeyup="updateTotals()" onchange="updateTotals()" type="number" name="paid" step="any" min="0" class="form-control" placeholder="Enter paid amount">
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
                                <li class="total-value">
                                    <h4>Change/Due</h4>
                                    <h5 class="dueTotal">GHS 0.00</h5>
                                    <input type="hidden" name="paid" id="paid">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-product-transfer.js?v=1') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>
<?= $this->endSection() ?>