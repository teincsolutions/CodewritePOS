<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>

<header>
    <h2><?= $title ?? "Sales Return Receipt" ?></h2>
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
        <h4>Branch: <?= $returns->sales->store->name; ?></h4>
        <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
            <h5>Mobile: <?= $sales->store->phone ?? ''; ?></h5>
        <?php endif ?>
    <?php endif ?>

    <?php if (setting('App.ShowOrderNum')) : ?>
        <h2>Order# <?= substr($returns->invoice, -4) ?></h2>
    <?php endif ?>
</header>
<main>
    <table>
        <tbody>
            <td class="no-border">
                <address>
                    <table class="raw info">
                        <?php if ($returns->sale->customer) : ?>
                            <tr>
                                <th>Customer:</th>
                                <td><?= $returns->sale->customer->name; ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?= $returns->sale->customer->phone; ?></td>
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
                        <td><?= date('d/m/y  h:i A', strtotime($returns->created_at)); ?></td>
                    </tr>
                    <tr>
                        <th>Reference:</th>
                        <td>INV<?= $returns->invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Sales Ref.:</th>
                        <td>INV<?= $returns->sale->invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Sales Person:</th>
                        <td><?= $returns->user->firstname; ?> <?= $returns->user->lastname; ?></td>
                    </tr>
                    <tr>
                        <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
                            <th>Contact Store:</th>
                            <td><?= $returns->sale->store->phone ?? ''; ?></td>
                        <?php endif ?>
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
            </th>
            <th>Price</th>
            <th>
                Sub Ttl
            </th>
        </thead>
        <tbody>
            <?php
            $total_discount = ($returns->discount ?? 0);
            $items = $returns->items;
            foreach ($items as $k => $row) : ?>
                <?php
                $total_discount += $row->discount;
                ?>
                <tr>
                    <td><?= $row->product->name; ?>(<?= $row->product->unit->label; ?>)</td>
                    <td>
                        <?= floatval($row->qty); ?>
                    </td>
                    <td>
                        <?= number_format($row->unit_price, 2); ?>
                    </td>
                    <td>
                        <?= number_format($row->subtotal, 2); ?>
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
                    GHS <?= number_format($returns->total_amount, 2) ?>
                </td>
            </tr>
            <tr>
                <th>
                    Paid
                </th>
                <td></td>
                <td colspan="2">
                    GHS <?= number_format($returns->paid, 2) ?>
                </td>
            </tr>
            <?php if ($returns->sale->type === 'customer') : ?>
                <tr>
                    <th>
                        Change
                    </th>
                    <td></td>
                    <td colspan="2">
                        GHS <?= $returns->total_amount - $returns->paid < 0 ? "(" . number_format(abs($returns->total_amount - $returns->paid), 2) . ")" : number_format($returns->total_amount - $returns->paid, 2) ?>
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
                    <td colspan="2"><?= number_format($returns->tax, 2)  ?></td>
                </tr>
            <?php endif ?>
        </tfoot>
    </table>
</main>
<footer class="text-center">
    <p><b>Thank you for your business!</b></p>
    <p><small>Codewrite Technology Ltd. Copyright &copy; 2023 version <?= env('app.version') ?> Mobile: 0246092155/0553035684</small></p>
</footer>
<?= $this->endSection() ?>