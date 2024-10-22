<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sale Details</h4>
            <h6>View sale details</h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-sales-split">
                <h2>Sale Detail : <?= $sales->invoice; ?></h2>
                <ul>
                    <li>
                        <a href="javascript:void(0);"></a>
                    </li>
                </ul>
            </div>
            <div class="invoice-box table-height" style="max-width: 1600px;width:100%;overflow: auto;margin:15px auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                <table style="width: 100%;line-height: inherit;text-align: left; display:table !important;">
                    <thead>
                        <td class="w-50"></td>
                        <td class="w-50"></td>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px;width:100%; text-align:center">

                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                        <h1><?= $sales->store ? $sales->store->name : ''; ?></h1>
                                    </font>
                                </font><br>
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                        <?= $sales->store ? $sales->store->location : ''; ?></font>
                                </font><br>
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                        <?= $sales->store ? $sales->store->description : ''; ?></font>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px;width:100%;">
                                <div>
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            Customer Info</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        Name: <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $sales->customer ? $sales->customer->name : 'walk-in-customer' ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        Email: <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <a href="<?= $sales->customer ? $sales->customer->email : null ?>" class="__cf_email__"><?= $sales->customer ? $sales->customer->email : null ?></a>
                                        </font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        Phone: <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $sales->customer ? $sales->customer->phone : null ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"><?= $sales->customer ? $sales->customer->address : null ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        Biller: <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $sales->user->firstname; ?> <?= $sales->user->lastname; ?></font>
                                    </font>
                                </div>
                            </td>
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;min-width: 200px !important;">
                                <div style="width: 200px;">
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
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            Payment Method:</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            Time:</font>
                                    </font>
                                </div>
                            </td>
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;width:calc(40%)">
                                <div style="width: 150px;">
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            &nbsp;</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $sales->invoice; ?> </font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font class="text-capitalize" style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                            <?= $sales->payment_status; ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font class="text-capitalize" style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                            <?= $sales->order_status; ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font class="text-capitalize" style="vertical-align: inherit;font-size: 14px;color:#FE7D32;font-weight: 400;">
                                            <?= $sales->payment_type; ?></font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font class="text-capitalize" style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= date('d/m/y h:i a', strtotime($sales->created_at)); ?></font>
                                    </font>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;display:table !important;">
                    <?php
                    $salesItems = $sales->items;
                    $return = model('SalesReturnModel')->where('sale_id', $sales->id)->first();

                    if ($return) $salesItems = model('SalesModel')->getItemsWithReturnItems($sales->id);;
                    ?>
                    <tbody>
                        <tr class="heading" style="background: #F3F2F7;">
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                #
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Product Name
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                QTY
                            </td>
                            <?php if ($return) : ?>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    Rtn QTY
                                </td>
                            <?php endif ?>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Price
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Discount
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                TAX
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                Subtotal
                            </td>
                            <?php if ($return) : ?>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    Rtn subTl
                                </td>
                            <?php endif ?>
                        </tr>
                        <?php
                        if (isset($sales)) {
                        ?>
                            <?php
                            $totalReturns = 0;
                            $totalReturnDiscount = 0;
                            foreach ($salesItems as $key => $row) :
                            ?>
                                <tr class="details" style="border-bottom:1px solid #E9ECEF;">
                                    <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $key + 1 ?></td>
                                    <td class="productimgname" style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                        <?= $row->product->image_uri
                                            ? '<a class="product-img"><img src="' . base_url($row->product->image_uri) . '" alt="product"></a>'
                                            : '<a class="p-3"></a>' ?>
                                        <a target="_blank" href="<?= site_url('products/' . $row->product_id) ?>">
                                            <?= setting('App.ShowProductSKU') === 'yes' ? $row->product->sku : '' ?> <?= $row->product->name ?>(<?= $row->product->unit->label ?>)
                                    </td>
                                    <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->qty ?></td>
                                    <?php if ($return) : ?>
                                        <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->rtn_qty ?></td>
                                    <?php endif ?>
                                    <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->unit_price ?></td>
                                    <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->discount ?></td>
                                    <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= number_format(0, 2) ?></td>
                                    <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= number_format($row->unit_price * $row->qty - $row->discount, 2) ?></td>
                                    <?php if ($return) :
                                        $totalReturns += $row->rtn_subtotal;
                                        $totalReturnDiscount += $row->rtn_discount;
                                    ?>
                                        <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= number_format($row->rtn_subtotal, 2) ?></td>
                                    <?php endif ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php } ?>
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
                                    <h5 class="orderTaxes"><?= 0; ?>%</h5>
                                </li>
                                <li>
                                    <h4>Discount </h4>
                                    <h5 class="discountTotal"> <?= $sales->discount; ?></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="total-order w-100 max-widthauto m-auto mb-4">
                            <ul>
                                <li>
                                    <h4>Total Discount</h4>
                                    <h5 class="shippingTotal"><?= number_format($sales->discount, 2); ?></h5>
                                </li>
                                <li class="total">
                                    <h4>Checkout Paid</h4>
                                    <h5 class="grandTotal"><?= number_format($sales->paid, 2) ?></h5>
                                </li>
                                <li class="total">
                                    <h4>Sales Total</h4>
                                    <h5 class="grandTotal"><?= number_format($sales->total_amount, 2) ?></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php if ($return) : ?>
                        <div class="col-lg-6 offset-lg-6">
                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                <ul>
                                    <li>
                                        <h4>Total Return Discount</h4>
                                        <h5 class="shippingTotal"><?= number_format($totalReturnDiscount, 2); ?></h5>
                                    </li>
                                    <li class="total">
                                        <h4>Returns Total</h4>
                                        <h5 class="grandTotal"><?= number_format($totalReturns, 2) ?></h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="col-lg-12">
                    <a onclick="rePrintInvoice('<?= site_url('print/sales/' . $sales->id) ?>')" href="javascript:void(0);" class="btn btn-submit me-2">Print Invoice</a>
                    <a href="<?= site_url('sales/returns/create?invoice=' . $sales->invoice) ?>" class="btn btn-submit me-2">Return</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>