<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h1 class="text-uppercase"><?= $title ?? "Purchase Return Receipt" ?></h1>
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
            <p class="text-uppercase">Branch: <?= $return->purchase->store->name; ?> at <?= $return->purchase->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1">
        <div class="info">
            <?php if ($return->purchase->supplier) : ?>
                <p class="sec">
                    <strong>Customer : </strong><span><?= $return->purchase->supplier->name; ?></span></br>
                    <strong>Address : </strong><span><?= $return->purchase->supplier->address; ?></span></br>
                    <strong>Phone Number : </strong><span><?= $return->purchase->supplier->phone; ?></span>
                </p>
            <?php else : ?>
                <p class="sec">
                    <strong>Customer : </strong><span>walk-in-supplier</span>
                </p>
            <?php endif ?>
        </div>
        <div class="info">
            <p class="sec">
                <strong>Time : </strong><span><?= date('d/m/y  h:i a', strtotime($return->created_at)); ?></span></br>
                <strong>Reference : </strong><span>INV<?= $return->invoice; ?></span></br>
                <strong>Manger : </strong><span><?= $return->user->firstname; ?> <?= $return->user->lastname; ?></span><br>
                <strong>Contact Store : </strong><span><?= $return->purchase->store->phone ?? '0246092155'; ?></span>
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
                    </th>
                    <th style="width:15%">
                        Cost
                    </th>
                    <th style="width:20%">
                        Sub Total
                    </th>
                </tr>
                <?php
                $total_discount = 0;
                $return->total_amount2 = 0.00;
                foreach ($return->items as $k => $row) : ?>
                    <?php
                    $total_discount += $row->discount;
                    $row->subtotal2 = $row->qty * $row->unit_price;
                    $return->total_amount2 += $row->subtotal2;
                    ?>
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext"><?= $row->product->name; ?>
                                (<?= $row->product->unit->label; ?>)</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= $row->qty; ?></p>
                        </td>
                        <td class="tableitem">
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
                            </p>
                        </td>
                    </tr>
                <?php endforeach ?>

                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h3>Total Discount</h3>
                    </td>
                    <td class="payment">
                        <h3>GHS <?= number_format($row->discount + $total_discount, 2) ?></h3>
                    </td>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h2>Total</h2>
                    </td>
                    <td class="payment">
                        <h2>GHS <?php if (setting('App.UseSalesPriceOnReceipt') === 'yes') : ?>
                                <?= number_format($return->total_amount2, 2) ?>
                            <?php else : ?>
                                <?= number_format($return->total_amount, 2) ?>
                            <?php endif ?>
                        </h2>
                    </td>
                </tr>
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h3>Change</h3>
                    </td>
                    <td class="payment">
                        <h3>GHS <?= number_format($return->paid, 2) ?></h3>
                    </td>
                </tr>
            </table>
        </div><!--End Table-->

        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your business!</strong></p>
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
<?= $this->endSection() ?>