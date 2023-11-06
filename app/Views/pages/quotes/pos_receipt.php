<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h2 class="text-uppercase"><?= $title ?? "Quotation Receipt" ?></h2>
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
            <p class="text-uppercase">Branch: <?= $quote->store->name; ?> at <?= $quote->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1" style="margin-top:3px">
        <div class="info">
            <?php if ($quote->customer) : ?>
                <p class="sec">
                    <strong>Customer : </strong><span><?= $quote->customer->name; ?></span></br>
                    <strong>Address : </strong><span><?= $quote->customer->address; ?></span></br>
                    <strong>Phone Number : </strong><span><?= $quote->customer->phone; ?></span>
                </p>
            <?php else : ?>
                <p class="sec">
                    <strong>Customer : </strong><span>walk-in-customer</span>
                </p>
            <?php endif ?>
        </div>
        <div class="info">
            <p class="sec">
                <strong>Time : </strong><span><?= date('d/m/y  h:i a', strtotime($quote->created_at)); ?></span></br>
                <strong>Reference : </strong><span>INV<?= $quote->invoice; ?></span></br>
                <strong>Sales Person : </strong><span><?= $quote->user->firstname; ?> <?= $quote->user->lastname; ?></span><br>
                <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
                    <strong>Contact Store : </strong><span><?= $quote->store->phone ?? '0246092155'; ?></span>
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
                        </strong>
                    </th>
                    <th style="width:25%">
                        <strong>Sub Ttl
                        </strong>
                    </th>
                </tr>
                <?php
                $total_discount = ($quote->discount ?? 0);
                $items = $quote->items;

                foreach ($items as $k => $row) : ?>
                    <?php
                    $total_discount += $row->discount;
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
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= number_format($row->subtotal, 2); ?>
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
                        GHS <?= number_format($quote->total_amount, 2) ?>
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