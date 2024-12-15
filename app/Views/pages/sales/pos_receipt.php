<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>

<header>
    <h2><?= $title ?? "Sales Receipt" ?></h2>
    <?php if (setting('App.Logo')) : ?>
        <p style="background: url(<?= base_url(setting('App.Logo')) ?>) no-repeat;"></p>
    <?php endif ?>
    <?php if (setting('App.companyName')) : ?>
        <h3><?= setting('App.companyName') ?></h3>
    <?php endif ?>

    <?php if (setting('App.ShowMainBranchAddress') === 'yes') : ?>
        <p class="text-center">
            <?= setting('App.companyAddress') ?>
            <br> Tel:<?= setting('App.companyContacts') ?>
        </p>
    <?php else: ?>
        <h4>Branch: <?= $sales->store->name; ?></h4>
        <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
            <h5>Mobile: <?= $sales->store->phone ?? ''; ?></h5>
        <?php endif ?>
    <?php endif ?>

    <?php if (setting('App.ShowOrderNum')) : ?>
        <h2>Order# <?= substr($sales->invoice, -4) ?></h2>
    <?php endif ?>
</header>
<main>
    <table>
        <tbody>
            <td class="no-border">
                <address>
                    <table class="raw info">
                        <?php if ($sales->customer) : ?>
                            <tr>
                                <th>Customer:</th>
                                <td><?= $sales->customer->name; ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?= $sales->customer->phone; ?></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <th>Customer:</th>
                                <td>walk-in-customer</td>
                            </tr>
                        <?php endif ?>
                    </table>
                </address>
            </td>
            <td class="no-border">
                <table class="raw info">
                    <tr>
                        <th>Time:</th>
                        <td><?= date('d/m/y  h:i A', strtotime($sales->created_at)); ?></td>
                    </tr>
                    <tr>
                        <th>Reference:</th>
                        <td><?= $sales->invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Sales Person:</th>
                        <td><?= $sales->user->firstname; ?> <?= $sales->user->lastname; ?></td>
                    </tr>
                </table>
            </td>
        </tbody>
    </table>

    <table>
        <thead>
            <th>Item</th>
            <th>
                Qty
                <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                    | (Rtn)
                <?php endif ?>
            </th>
            <th>Price</th>
            <th>
                Sub Ttl
                <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                    | (Rtn Ttl)
                <?php endif ?>
            </th>
        </thead>
        <tbody>
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
                <tr>
                    <td><?= $row->product->name; ?> <?= $row->product->description ?? ''; ?>(<?= $row->product->unit->label; ?>)</td>
                    <td>
                        <?= floatval($row->qty); ?>
                        <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                            (<?= floatval($row->rtn_qty) ?>)
                        <?php endif ?>
                    </td>
                    <td>
                        <?= number_format($row->unit_price, 2); ?>
                    </td>
                    <td>
                        <?= number_format($row->subtotal, 2); ?>
                        <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                            (<?= $row->rtn_subtotal ?>)
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot class="sum">
            <tr>
                <th>Total Discount</th>
                <td></td>
                <td colspan="2">
                    GHS <?= number_format($total_discount, 2) ?>
                </td>
            </tr>
            <tr class="total">
                <th>Grand Total</th>
                <td></td>
                <td colspan="2">
                    GHS <?= number_format($sales->total_amount, 2) ?>
                </td>
            </tr>
            <tr>
                <th>
                    Paid
                    <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                        + Total Returns
                    <?php endif ?>
                </th>
                <td></td>
                <td colspan="2">
                    GHS <?= number_format($sales->paid, 2) ?>
                </td>
            </tr>
            <?php if ($sales->type === 'customer') : ?>
                <tr>
                    <th>
                        Due<?php if (model('SalesModel')->hasReturns($sales->id)) : ?>/Change<?php endif ?>
                    </th>
                    <td></td>
                    <td colspan="2">
                        GHS <?= $sales->total_amount - $sales->paid < 0 ? "(" . number_format(abs($sales->total_amount - $sales->paid), 2) . ")" : number_format($sales->total_amount - $sales->paid, 2) ?>
                    </td>
                </tr>
            <?php endif ?>
            <?php if (!model('SalesModel')->hasReturns($sales->id)) : ?>
                <tr>
                    <th>Change</th>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($sales->change, 2) ?>
                    </td>
                </tr>
            <?php endif ?>
            <?php if (model('SalesModel')->hasReturns($sales->id)) : ?>
                <tr>
                    <th>Total Rtn Discount</th>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($rtntotalDiscount, 2) ?>
                    </td>
                </tr>
                <tr>
                    <th>Total Returns</th>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($rtnTotalAmount, 2) ?>
                    </td>
                </tr>
            <?php endif ?>
        </tfoot>
        <tfoot class="sum text-sm">
            <?php if (setting('App.ShowTaxOnReceipt') === 'yes') : ?>
                <?php
                $taxRate = 0;
                foreach (model('TaxModel')->where('status', 'opened')->findAll() as $row) : ?>
                    <?php $taxRate += $row->rate; ?>
                    <tr>
                        <th><?= $row->label ?> (<?= number_format($row->rate, 2) ?>%):</th>
                        <td></td>
                        <td colspan="2"><?= number_format(0.00, 2)  ?></td>
                    </tr>
                <?php endforeach ?>
                <tr class="total">
                    <th>Total Taxes (<?= number_format($taxRate, 2) ?>%):</th>
                    <td></td>
                    <td colspan="2"><?= number_format($sales->tax, 2)  ?></td>
                </tr>
            <?php endif ?>
        </tfoot>
    </table>
</main>
<footer class="text-center">
    <p><b>Thank you for your business!</b></p>
    <p><small>Codewrite Technology Ltd. Copyright &copy; 2024 version <?= env('app.version') ?> Mobile: 0246092155</small></p>
</footer>
<?= $this->endSection() ?>