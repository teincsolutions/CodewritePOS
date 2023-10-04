<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h1 class="text-uppercase"><?= $title ?? "Sales Receipt" ?></h1>
        </div>
        <div class="logo" style="background: url(<?= base_url('assets/images/logo.png') ?>) no-repeat;"></div>
        <div class="info">
            <h2 class="text-uppercase">Codewrite Technology Ltd</h2>
            <p class="text-uppercase"><?= $sales->store->name; ?> at <?= $sales->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1">
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
                <strong>Time : </strong><span><?= date('d/m/y', strtotime($sales->created_at)); ?></span></br>
                <strong>Reference : </strong><span>INV<?= $sales->invoice; ?></span></br>
                <strong>Sales Person : </strong><span><?= $sales->user->firstname; ?> <?= $sales->user->lastname; ?></span><br>
                <strong>Contact Store : </strong><span><?= $sales->store->phone ?? '0246092155'; ?></span>
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
                        <h2>Price</h2>
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
                foreach ($sales->items as $k => $row) : ?>
                    <?php $total_discount += $row->discount; ?>
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext"><?= $row->product->name; ?></p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext"><?= number_format($row->unit_price, 2); ?></p>
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
                        <h2>GHS <?= number_format($sales->total_amount, 2) ?></h2>
                    </td>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h3>Paid</h3>
                    </td>
                    <td class="payment">
                        <h3>GHS <?= number_format($sales->paid, 2) ?></h3>
                    </td>
                </tr>
                <?php if ($sales->type === 'customer') : ?>
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
                <?php endif ?>
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h3>Change</h3>
                    </td>
                    <td class="payment">
                        <h3>GHS <?= number_format($sales->change, 2) ?></h3>
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