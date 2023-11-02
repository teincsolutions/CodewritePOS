<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h2 class="text-uppercase"><?= $title ?? "Sales Receipt" ?></h2>
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
            <p class="text-uppercase">Branch: <?= $sales->store->name; ?> at <?= $sales->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1" style="margin-top:3px">
        <div class="info">
            <?php if ($sales->customer) : ?>
                <p class="sec">
                    <strong>Customer : </strong><span><?= $sales->customer->name; ?></span></br>
                    <strong>Address : </strong><span><?= $sales->customer->address; ?></span></br>
                    <strong>Phone Number : </strong><span><?= $sales->customer->phone; ?></span>
                </p>
            <?php else : ?>
                <p class="sec">
                    <strong>Customer : </strong><span>walk-in-customer</span>
                </p>
            <?php endif ?>
        </div>
        <div class="info">
            <p class="sec">
                <strong>Time : </strong><span><?= date('d/m/y  h:i a', strtotime($sales->created_at)); ?></span></br>
                <strong>Reference : </strong><span>INV<?= $sales->invoice; ?></span></br>
                <strong>Sales Person : </strong><span><?= $sales->user->firstname; ?> <?= $sales->user->lastname; ?></span><br>
                <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
                    <strong>Contact Store : </strong><span><?= $sales->store->phone ?? '0246092155'; ?></span>
                <?php endif ?>
            </p>
        </div>
    </div>
    <div id="bot">
        <div id="table">
            <table>
                <tr class="tabletitle">
                    <th style="width:50%">
                        <strong>Item</strong>
                    </th>
                    <th style="width:10%">
                        <strong>Price</strong>
                    </th>
                    <th style="width:15%">
                        <strong>Qty
                            <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                                | (Rtn)
                            <?php endif ?>
                        </strong>
                    </th>
                    <th style="width:25%">
                        <strong>Sub Ttl
                            <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                                | (Rtn Ttl)
                            <?php endif ?>
                        </strong>
                    </th>
                </tr>
                <?php
                $total_discount = ($sales->discount ?? 0);
                $rtntotalDiscount = $rtnTotalAmount = 0;
                if (model('SalesModel')->hasReturns($sales->id))
                    $items = model('SalesModel')->getItemsWithReturnItems($sales->id);
                else $items = $sales->items;

                foreach ($items as $k => $row) : ?>
                    <?php
                    $total_discount += $row->discount;
                    if (model('SalesModel')->hasReturns($sales->id)) {
                        $rtntotalDiscount += $row->rtn_discount;
                        $rtnTotalAmount += $row->rtn_subtotal;
                    }
                    ?>
                    <tr class="service">
                        <td class="tableitem text-left">
                            <p class="itemtext"><?= $row->product->name; ?>
                                (<?= $row->product->unit->label; ?>)</p>
                        </td>
                        <td class="tableitem border-end">
                            <p class="itemtext"><?= number_format($row->unit_price, 2); ?></p>
                        </td>
                        <td class="tableitem border-end">
                            <p class="itemtext"><?= floatval($row->qty); ?>
                                <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                                    (<?= floatval($row->rtn_qty) ?>)
                                <?php endif ?></p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= number_format($row->subtotal, 2); ?>
                                <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                                    (<?= $row->rtn_subtotal ?>)
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
                        GHS <?= number_format($sales->total_amount, 2) ?>
                    </td>
                </tr>
                <tr class="foottitle">
                    <td>Paid
                        <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                            + Total Returns
                        <?php endif ?></p>
                    </td>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($sales->paid, 2) ?>
                    </td>
                </tr>
                <?php if ($sales->type === 'customer') : ?>
                    <tr class="foottitle">
                        <td>
                            Due<?php if (model('SalesModel')->hasReturns($sales->id)) : ?>/Change<?php endif ?>
                        </td>
                        <td></td>
                        <td colspan="2">
                            GHS <?= $sales->total_amount - $sales->paid < 0 ? "(" . number_format(abs($sales->total_amount - $sales->paid), 2) . ")" : number_format($sales->total_amount - $sales->paid, 2) ?>
                        </td>
                    </tr>
                <?php endif ?>
                <?php if (!model('SalesModel')->hasReturns($sales->id)) : ?>
                    <tr class="foottitle">
                        <td>Change</td>
                        <td></td>
                        <td colspan="2">
                            GHS <?= number_format($sales->change, 2) ?>
                        </td>
                    </tr>
                <?php endif ?>
                <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
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