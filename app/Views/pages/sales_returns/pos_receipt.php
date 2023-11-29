<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h1 class="text-uppercase"><?= $title ?? "Sales Return Receipt" ?></h1>
        </div>
        <?php if (setting('App.Logo')) : ?>
            <div class="logo" style="background: url(<?= base_url(setting('App.Logo')) ?>) no-repeat;"></div>
        <?php endif ?>
        <div class="info">
            <h2 class="text-uppercase"><?= setting('App.companyName') ?></h2>
            <small> <?= setting('App.companyAddress') ?> | tel:<?= setting('App.companyContacts') ?></small>
            <p class="text-uppercase">Branch: <?= $returns->sale->store->name; ?> at <?= $returns->sale->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1" style="margin-top: 5px;">
        <div class="info">
            <?php if ($returns->sale->customer) : ?>
                <p class="sec">
                    <strong>Customer : </strong><span><?= $returns->sale->customer->name; ?></span></br>
                    <strong>Address : </strong><span><?= $returns->sale->customer->address; ?></span></br>
                    <strong>Phone Number : </strong><span><?= $returns->sale->customer->phone; ?></span>
                </p>
            <?php else : ?>
                <p class="sec">
                    <strong>Customer : </strong><span>walk-in-customer</span>
                </p>
            <?php endif ?>
        </div>
        <div class="info">
            <p class="sec">
                <strong>Time : </strong><span><?= date('d/m/y  h:i a', strtotime($returns->created_at)); ?></span></br>
                <strong>Reference : </strong><span>INV<?= $returns->invoice; ?></span></br>
                <strong>Sales Person : </strong><span><?= $returns->user->firstname; ?> <?= $returns->user->lastname; ?></span><br>
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
                        Item
                    </th>
                    <th style="width:15%">
                        Qty
                    </th>
                    <th style="width:15%">
                        Price
                    </th>
                    <th style="width:20%">
                        Sub Total
                    </th>
                </tr>
                <?php
                $total_discount = 0;
                foreach ($returns->items as $k => $row) : ?>
                    <?php $total_discount += $row->discount; ?>
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext"><?= $row->product->name; ?>(<?= $row->product->unit->label; ?>)</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= $row->qty; ?></p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= number_format($row->unit_price, 2); ?></p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= number_format($row->subtotal, 2); ?></p>
                        </td>
                    </tr>
                <?php endforeach ?>

                <tr class="foottitle">
                    <td>Total Discount</td>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($row->discount + $total_discount, 2) ?>
                    </td>
                </tr>

                <tr class="foottitle">
                    <td>Grand Total</td>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($returns->total_amount, 2) ?>
                    </td>
                </tr>
                <tr class="foottitle">
                    <td>Change</td>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($returns->paid, 2) ?>
                    </td>
                </tr>
            </table>
        </div><!--End Table-->

        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your business!</strong></p>
            <p class="developer"><small>Codewrite Technology Ltd. Copyright &copy; 2023 version <?= env('app.version') ?> Mobile: 0246092155/0553035684</small></p>
        </div>
    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
<?= $this->endSection() ?>