<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h1 class="text-uppercase"><?= $title ?? "Purchase Receipt" ?></h1>
        </div>
        <div class="logo" style="background: url(<?= base_url('assets/images/logo.png') ?>) no-repeat;"></div>
        <div class="info">
        <h2 class="text-uppercase"><?= setting('App.companyName') ?></h2>
            <small> <?= setting('App.companyAddress') ?> | tel:<?= setting('App.companyContacts') ?></small>
            <p class="text-uppercase">Branch: <?= $purchases->store->name; ?> at <?= $purchases->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1">
        <div class="info">
            <?php if ($purchases->supplier) : ?>
                <p class="sec">
                    <strong>Supplier : </strong><span><?= $purchases->supplier->name; ?></span></br>
                    <strong>Address : </strong><span><?= $purchases->supplier->address; ?></span></br>
                    <strong>Phone Number : </strong><span><?= $purchases->supplier->phone; ?></span>
                </p>
            <?php endif ?>
        </div>
        <div class="info">
            <p class="sec">
                <strong>Time : </strong><span><?= date('d/m/y h:i a', strtotime($purchases->created_at)); ?></span></br>
                <strong>Reference : </strong><span><?= $purchases->invoice; ?></span></br>
                <strong>Manager : </strong><span><?= $purchases->user->firstname; ?> <?= $purchases->user->lastname; ?></span><br>
                <strong>Contact Store : </strong><span><?= $purchases->store->phone ?? '0246092155'; ?></span>
            </p>
        </div>
    </div>
    <div id="bot">
        <div id="table">
            <table>
                <tr class="tabletitle">
                    <td class="item">
                        <h2>Item</h2>
                    </td>
                    <td class="Hours">
                        <h2>Cost</h2>
                    </td>
                    <td class="Hours">
                        <h2>Qty</h2>
                    </td>
                    <td class="Rate">
                        <h2>Sub Total</h2>
                    </td>
                </tr>
                <?php
                $total_discount = 0;
                foreach ($purchases->items as $k => $row) : ?>
                    <?php $total_discount += $row->discount; ?>
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext"><?= $row->product->name; ?>
                            (<?= $row->product->unit->label; ?>)</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= number_format($row->unit_cost, 2); ?></p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= $row->qty; ?> <?= $row->product->unit->label; ?></p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= number_format($row->subtotal, 2); ?></p>
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
                        <h3>GHS <?= number_format($total_discount, 2) ?></h3>
                    </td>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h2>Total</h2>
                    </td>
                    <td class="payment">
                        <h2>GHS <?= number_format($purchases->total_amount, 2) ?></h2>
                    </td>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h3>Paid</h3>
                    </td>
                    <td class="payment">
                        <h3>GHS <?= number_format($purchases->paid, 2) ?></h3>
                    </td>
                </tr>
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h2>Due</h2>
                    </td>
                    <td class="payment">
                        <h2>GHS <?= number_format($purchases->total_amount - $purchases->paid, 2) ?></h2>
                    </td>
                </tr>
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h3>Change</h3>
                    </td>
                    <td class="payment">
                        <h3>GHS <?= number_format($purchases->change, 2) ?></h3>
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