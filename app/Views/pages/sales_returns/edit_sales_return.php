<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Create Sales Return</h4>
            <h6>Add/Update Sales Return</h6>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('sales/returns') ?>" method="post">
        <?= csrf_field() ?>
        <input id="order-status" type="hidden" name="order_status" value="completed">
        <input id="payment-status" type="hidden" name="payment_status" value="paid">
        <input id="sales-total" type="hidden" name="total_amount" value="<?= isset($sales) ? $sales->total_amount : 0.00 ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $error ?>
                            <a href="<?= site_url('sales/pos') ?>" type="button" class="btn-close" aria-label="Close"></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">

                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Reference</label>
                            <select name="sale_id" class="select2-invoices" required>
                                <option value=""></option>
                                <?php if (isset($sales)) : ?>
                                    <option value="<?= $sales->id ?>" selected><?= $sales->invoice ?> (<?= isset($sales->customer) ? $sales->customer->name : 'walk-in-customer' ?> - GHS <?= $sales->total_amount; ?>)</option>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Customer</label>
                            <select name="customer_id" class="select">
                                <?php if (isset($sales) && $sales->customer_id) : ?>
                                    <option value="<?= $sales->customer_id ?>" selected><?= $sales->customer->name ?></option>
                                <?php else : ?>
                                    <option value="" selected>walk-in-customer</option>
                                <?php endif ?>
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
                    <div class="table-responsive mb-3">
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
                                if (isset($sales)) :
                                    $ids = array_map(function ($item) {
                                        return $item->id;
                                    }, $sales->items);

                                    foreach ($sales->items as $key => $row) :
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
                            <label>Order Tax</label>
                            <div class="input-group">
                                <input type="text" name="tax" value="<?= isset($sales) ? $sales->tax : null ?>" class="form-control" placeholder="Sales taxes" readonly>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Customer Discount</label>
                            <div class="input-group">
                                <input onkeyup="updateTotals()" type="number" name="discount" value="<?= isset($sales) ? $sales->discount : null ?>" class="form-control addon-inline" placeholder="Sales discount" readonly>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shipping</label>
                            <input onkeyup="updateTotals()" type="number" name="shipping" value="<?= isset($sales) ? $sales->shipping : null ?>" class="form-control" placeholder="Shipping amount">
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
                        <button type="submit" class="btn btn-submit me-2">Submit Return</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-sale-return.js') ?>"></script>
<?php if (isset($sales) && $sales->customer) {
    $customer = $sales->customer;
    $customer->text = $customer->name . " (" . ($customer->address ? $customer->address : $customer->phone) . ")";
?>
    <script>
        $(() => {
            let customerData = <?= json_encode($customer) ?>;
            var option = new Option(customerData.text, customerData.id, true, true);
            select2Customer.append(option).trigger('change');
            select2Customer.trigger({
                type: 'select2:select',
                params: {
                    data: customerData
                }
            });
        });
        saleItemIds = <?= json_encode($ids) ?>;
    </script>
<?php } else if (isset($sales)) { ?>
    <script>
        saleItemIds = <?= json_encode($ids) ?>;
    </script>
<?php } ?>
<?= $this->endSection() ?>