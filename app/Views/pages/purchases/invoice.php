<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Purchase Details</h4>
            <h6>View purchase details</h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-purchases-split">
                <h2>Purchase Detail : INV<?= $purchase->invoice; ?></h2>
                <ul>
                    <li>
                    </li>
                </ul>
            </div>
            <div class="invoice-box table-height" style="max-width: 1600px;width:100%;overflow: auto;margin:15px auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                <table style="width: 100%;line-height: inherit;text-align: left; display:table !important;">
                    <tbody>
                        <tr>
                            <td colspan="6" style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px;width:100%; text-align:center">

                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                        <h1><?= $purchase->store->name; ?></h1>
                                    </font>
                                </font><br>
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                        <?= $purchase->store->location; ?></font>
                                </font><br>
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                        <?= $purchase->store->description; ?></font>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px;width:100%;">
                                <div>
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            Supplier Info</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $purchase->supplier ? $purchase->supplier->name : 'walk-in-supplier' ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <a href="<?= $purchase->supplier ? $purchase->supplier->email : null ?>" class="__cf_email__"><?= $purchase->supplier ? $purchase->supplier->email : null ?></a>
                                        </font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $purchase->supplier ? $purchase->supplier->phone : null ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"><?= $purchase->supplier ? $purchase->supplier->address : null ?></font>
                                    </font>
                                </div>
                            </td>
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;min-width: 150px !important;">
                                <div>
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            Invoice Info</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            Reference:</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            Payment Status:</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            Order Status:</font>
                                    </font>
                                </div>
                            </td>
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;width:calc(25%)">
                                <div>
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            &nbsp;</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            INV<?= $purchase->invoice; ?> </font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                            <?= $purchase->payment_status; ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                            <?= $purchase->order_status; ?></font>
                                    </font>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;display:table !important;">
                    <tbody>
                        <tr class="heading" style="background: #F3F2F7;">
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Product Name
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                QTY
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Cost
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Subtotal
                            </td>
                        </tr>
                        <?php
                        if (isset($purchase))
                            foreach ($purchase->items as $key => $row) : ?>
                            <tr class="details" style="border-bottom:1px solid #E9ECEF;">
                                <td class="productimgname" style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    <?= $row->product->image_uri
                                        ? '<a class="product-img"><img src="' . base_url($row->product->image_uri) . '" alt="product"></a>'
                                        : '<a class="p-3"></a>' ?>
                                    <a target="_blank" href="<?= site_url('products/' . $row->product_id) ?>">
                                        <?= $row->product->name ?>
                                        <?php if ($row->store) { ?>
                                            <?= $row->store->name; ?><?= $row->store->location ? "(" . $row->store->location . ")" : null; ?></a>
                                <?php } ?>
                                </td>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->qty ?></td>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->unit_cost ?></td>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= number_format($row->subtotal, 2) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-lg-6 ">
                        <div class="total-order w-100 max-widthauto m-auto mb-4">
                            <ul>
                                <li>
                                    <h4>Order Tax</h4>
                                    <h5 class="orderTaxes"><?= $purchase->tax; ?>%</h5>
                                </li>
                                <li>
                                    <h4>Discount </h4>
                                    <h5 class="discountTotal"> <?= $purchase->discount; ?></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="total-order w-100 max-widthauto m-auto mb-4">
                            <ul>
                                <li>
                                    <h4>Shipping</h4>
                                    <h5 class="shippingTotal"><?= number_format($purchase->shipping, 2); ?></h5>
                                </li>
                                <li class="total">
                                    <h4>Grand Total</h4>
                                    <h5 class="grandTotal">GHS <?= number_format($purchase->total_amount, 2) ?></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <a href="javascript:void(0);" class="btn btn-submit me-2">Print Invoice</a>
                    <a href="<?= site_url('purchases/returns/create?invoice=' . $purchase->invoice) ?>" class="btn btn-submit me-2">Return</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>