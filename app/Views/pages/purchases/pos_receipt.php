<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h2 class="text-uppercase"><?= $title ?? "Purchase Receipt" ?></h2>
        </div>
        <?php if (setting('App.Logo')) : ?>
            <div class="logo" style="background: url(<?= base_url(setting('App.Logo')) ?>) no-repeat;"></div>
        <?php endif ?>
        <div class="info">
            <?php if (setting('App.companyName')) : ?>
                <h3 class="text-uppercase"><?= setting('App.companyName') ?></h3>
            <?php endif ?>
            <?php if (setting('App.ShowMainBranchAddress') === 'yes') : ?>
                <small><?= setting('App.companyAddress') ? 'Main Branch: ' : '' ?> <?= setting('App.companyAddress') ?> | tel:<?= setting('App.companyContacts') ?></small>
            <?php endif ?>
            <p class="text-uppercase">Branch: <?= $purchases->store->name; ?> at <?= $purchases->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1" style="margin-top:3px">
        <div class="info">
            <?php if ($purchases->supplier) : ?>
                <p class="sec">
                    <strong>Customer : </strong><span><?= $purchases->supplier->name; ?></span></br>
                    <strong>Address : </strong><span><?= $purchases->supplier->address; ?></span></br>
                    <strong>Phone Number : </strong><span><?= $purchases->supplier->phone; ?></span>
                </p>
            <?php else : ?>
                <p class="sec">
                    <strong>Customer : </strong><span>walk-in-supplier</span>
                </p>
            <?php endif ?>
        </div>
        <div class="info">
            <p class="sec">
                <strong>Time : </strong><span><?= date('d/m/y  h:i a', strtotime($purchases->created_at)); ?></span></br>
                <strong>Reference : </strong><span>INV<?= $purchases->invoice; ?></span></br>
                <strong>Sales Person : </strong><span><?= $purchases->user->firstname; ?> <?= $purchases->user->lastname; ?></span><br>
                <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
                    <strong>Contact Store : </strong><span><?= $purchases->store->phone ?? '0246092155'; ?></span>
                <?php endif ?>
            </p>
        </div>
    </div>
    <div id="bot">
        <div id="table">
            <table>
                <tr class="tabletitle">
                    <th style="width:50%">
                        Item
                    </th>
                    <th style="width:15%">
                        Qty
                        <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                            | (Rtn)
                        <?php endif ?>
                    </th>
                    <th style="width:10%">
                        Cost
                    </th>
                    <th style="width:25%">
                        Sub Ttl
                        <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                            | (Rtn Ttl)
                        <?php endif ?>
                    </th>
                </tr>
                <?php
                $total_discount = ($purchases->discount ?? 0);
                $rtntotalDiscount = $rtnTotalAmount = 0;
                if (model('PurchaseModel')->hasReturns($purchases->id))
                    $items = model('PurchaseModel')->getItemsWithReturnItems($purchases->id);
                else $items = $purchases->items;

                $purchases->total_amount2 = 0.00;

                foreach ($items as $k => $row) : ?>
                    <?php
                    $total_discount += $row->discount;
                    $row->subtotal2 = $row->qty * $row->unit_price - $row->discount;
                    $purchases->total_amount2 += $row->subtotal2;

                    if (model('PurchaseModel')->hasReturns($purchases->id)) {
                        $rtntotalDiscount += $row->rtn_discount;
                        $row->rtn_subtotal2 = $row->rtn_qty * $row->unit_price;

                        if (setting('App.UseSalesPriceOnReceipt') === 'yes')
                            $rtnTotalAmount += $row->rtn_subtotal2;
                        else   $rtnTotalAmount += $row->rtn_subtotal;
                    }
                    ?>
                    <tr class="service">
                        <td class="tableitem text-left">
                            <p class="itemtext"><?= $row->product->name; ?>
                                (<?= $row->product->unit->label; ?>)</p>
                        </td>
                        <td class="tableitem border-end">
                            <p class="itemtext"><?= floatval($row->qty); ?>
                                <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                                    (<?= floatval($row->rtn_qty) ?>)
                                <?php endif ?></p>
                        </td>
                        <td class="tableitem border-end">
                            <p class="itemtext">
                                <?php if (setting('App.UseSalesPriceOnReceipt') === 'yes') : ?>
                                    <?= number_format($row->unit_price, 2); ?>
                                <?php else : ?>
                                    <?= number_format($row->unit_cost, 2); ?>
                                <?php endif ?>
                            </p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">
                                <?php if (setting('App.UseSalesPriceOnReceipt') === 'yes') : ?>
                                    <?= number_format($row->subtotal2, 2); ?>
                                <?php else : ?>
                                    <?= number_format($row->subtotal, 2); ?>
                                <?php endif ?>
                                <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                                    (<?php if (setting('App.UseSalesPriceOnReceipt') === 'yes') : ?>
                                    <?= number_format($row->rtn_subtotal2, 2); ?>
                                <?php else : ?>
                                    <?= number_format($row->rtn_subtotal, 2); ?>
                                    <?php endif ?>)
                                <?php endif ?></p>
                        </td>
                    </tr>
                <?php endforeach ?>

                <tr class="foottitle">
                    <td>Total Discount</td>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($total_discount, 2) ?>
                    </td>
                </tr>
                <tr class="foottitle">
                    <td>Grand Total</td>
                    <td></td>
                    <td colspan="2">
                        GHS <?php if (setting('App.UseSalesPriceOnReceipt') === 'yes') : ?>
                            <?= number_format($purchases->total_amount2, 2) ?>
                        <?php else : ?>
                            <?= number_format($purchases->total_amount, 2) ?>
                        <?php endif ?>
                    </td>
                </tr>
                <tr class="foottitle">
                    <td>Paid
                        <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                            + Total Returns
                        <?php endif ?></p>
                    </td>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($purchases->paid, 2) ?>
                    </td>
                </tr>
                <tr class="foottitle">
                    <td>
                        Due<?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>/Change<?php endif ?>
                    </td>
                    <td></td>
                    <td colspan="2">
                        GHS <?= $purchases->total_amount - $purchases->paid < 0 ? "(" . number_format(abs($purchases->total_amount - $purchases->paid), 2) . ")" : number_format($purchases->total_amount - $purchases->paid, 2) ?>
                    </td>
                </tr>
                <?php if (!model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                    <tr class="foottitle">
                        <td>Change</td>
                        <td></td>
                        <td colspan="2">
                            GHS <?= number_format($purchases->change, 2) ?>
                        </td>
                    </tr>
                <?php endif ?>
                <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                    <tr class="foottitle footspace">
                        <td>Total Rtn Discount</td>
                        <td></td>
                        <td colspan="2">
                            GHS <?= number_format($rtntotalDiscount, 2) ?>
                        </td>
                    </tr>
                    <tr class="foottitle">
                        <td>Total Returns</td>
                        <td></td>
                        <td colspan="2">
                            GHS <?= number_format($rtnTotalAmount, 2) ?>
                        </td>
                    </tr>
                <?php endif ?>
            </table>
        </div><!--End Table-->

        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your business!</strong></p>
            <p class="developer"><small>Codewrite Technology Ltd. Copyright &copy; 2023 version <?= env('app.version') ?> Mobile: 0246092155/0553035684</small></p>
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
<?= $this->endSection() ?>