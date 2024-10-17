<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>

<header>
    <h2><?= $title ?? "Purchase Receipt" ?></h2>
    <?php if (setting('App.Logo')) : ?>
        <p style="background: url(<?= base_url(setting('App.Logo')) ?>) no-repeat;"></p>
    <?php endif ?>
    <?php if (setting('App.companyName')) : ?>
        <h3><?= setting('App.companyName') ?></h3>
    <?php endif ?>

    <?php if (setting('App.ShowMainBranchAddress') === 'yes') : ?>
        <p class="text-center"><?= setting('App.companyAddress') ? 'Main Branch: ' : '' ?> <?= setting('App.companyAddress') ?> | tel:<?= setting('App.companyContacts') ?></p>
    <?php endif ?>

    <h4>Branch: <?= $purchases->store->name; ?> at <?= $purchases->store->location; ?></h4>
</header>
<main>
    <table>
        <tbody>
            <td class="no-border">
                <address>
                    <table class="raw info">
                        <?php if ($purchases->supplier) : ?>
                            <tr>
                                <th>Customer:</th>
                                <td><?= $purchases->supplier->name; ?></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?= $purchases->supplier->address; ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?= $purchases->supplier->phone; ?></td>
                            </tr>
                        <?php endif ?>
                    </table>
                </address>
            </td>
            <td class="no-border">
                <table class="raw info">
                    <tr>
                        <th>Time:</th>
                        <td><?= date('d/m/y  h:i a', strtotime($purchases->created_at)); ?></td>
                    </tr>
                    <tr>
                        <th>Reference:</th>
                        <td>INV<?= $purchases->invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Sales Person:</th>
                        <td><?= $purchases->user->firstname; ?> <?= $purchases->user->lastname; ?></td>
                    </tr>
                    <tr>
                        <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
                            <th>Contact Store:</th>
                            <td><?= $purchases->store->phone ?? ''; ?></td>
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
                <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                    | (Rtn)
                <?php endif ?>
            </th>
            <th>Cost</th>
            <th>
                Sub Ttl
                <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                    | (Rtn Ttl)
                <?php endif ?>
            </th>
        </thead>
        <tbody>
            <?php
            $total_discount = ($purchases->discount ?? 0);
            $rtntotalDiscount = $rtnTotalAmount = 0;
            if (model('PurchaseModel')->hasReturns($purchases->id))
                $items = model('PurchaseModel')->getItemsWithReturnItems($purchases->id);
            else $items = $purchases->items;
            foreach ($items as $k => $row) : ?>
                <?php
                $total_discount += $row->discount;
                if (model('PurchaseModel')->hasReturns($purchases->id)) {
                    $rtntotalDiscount += $row->rtn_discount;
                    $rtnTotalAmount += $row->rtn_subtotal;
                }
                ?>
                <tr>
                    <td><?= $row->product->name; ?> <?= $row->product->description ?? ''; ?> (<?= $row->product->unit->label; ?>)</td>
                    <td>
                        <?= floatval($row->qty); ?>
                        <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                            (<?= floatval($row->rtn_qty) ?>)
                        <?php endif ?>
                    </td>
                    <td>
                        <?= number_format($row->unit_cost, 2); ?>
                    </td>
                    <td>
                        <?= number_format($row->subtotal, 2); ?>
                        <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
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
                    GHS <?= number_format($purchases->total_amount, 2) ?>
                </td>
            </tr>
            <tr>
                <th>
                    Paid
                    <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                        + Total Returns
                    <?php endif ?>
                </th>
                <td></td>
                <td colspan="2">
                    GHS <?= number_format($purchases->paid, 2) ?>
                </td>
            </tr>
                <tr>
                    <th>
                        Due<?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>/Change<?php endif ?>
                    </th>
                    <td></td>
                    <td colspan="2">
                        GHS <?= $purchases->total_amount - $purchases->paid < 0 ? "(" . number_format(abs($purchases->total_amount - $purchases->paid), 2) . ")" : number_format($purchases->total_amount - $purchases->paid, 2) ?>
                    </td>
                </tr>
            <?php if (!model('PurchaseModel')->hasReturns($purchases->id)) : ?>
                <tr>
                    <th>Change</th>
                    <td></td>
                    <td colspan="2">
                        GHS <?= number_format($purchases->change, 2) ?>
                    </td>
                </tr>
            <?php endif ?>
            <?php if (model('PurchaseModel')->hasReturns($purchases->id)) : ?>
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
    </table>
</main>
<footer class="text-center">
    <p><b>Thank you for your business!</b></p>
    <p><small>Codewrite Technology Ltd. Copyright &copy; 2023 version <?= env('app.version') ?> Mobile: 0246092155/0553035684</small></p>
</footer>
<?= $this->endSection() ?>