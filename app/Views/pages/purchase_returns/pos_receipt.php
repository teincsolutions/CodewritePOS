<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>

<header>
    <h2><?= $title ?? "Purchase Return Receipt" ?></h2>
    <?php if (setting('App.Logo')) : ?>
        <p style="background: url(<?= base_url(setting('App.Logo')) ?>) no-repeat;"></p>
    <?php endif ?>
    <?php if (setting('App.companyName')) : ?>
        <h3><?= setting('App.companyName') ?></h3>
    <?php endif ?>

    <?php if (setting('App.ShowMainBranchAddress') === 'yes') : ?>
        <p class="text-center"><?= setting('App.companyAddress') ? 'Main Branch: ' : '' ?> <?= setting('App.companyAddress') ?> | tel:<?= setting('App.companyContacts') ?></p>
    <?php endif ?>

    <h4>Branch: <?= $return->purchase->store->name; ?> at <?= $return->purchase->store->location; ?></h4>
</header>
<main>
    <table>
        <tbody>
            <td class="no-border">
                <address>
                    <table class="raw info">
                        <?php if ($return->purchase->supplier) : ?>
                            <tr>
                                <th>Customer:</th>
                                <td><?= $return->purchase->supplier->name; ?></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?= $return->purchase->supplier->address; ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?= $return->purchase->supplier->phone; ?></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <th>Customer:</th>
                                <td>walk-in-supplier</td>
                            </tr>
                        <?php endif ?>
                    </table>
                </address>
            </td>
            <td class="no-border">
                <table class="raw info">
                    <tr>
                        <th>Time:</th>
                        <td><?= date('d/m/y  h:i a', strtotime($return->created_at)); ?></td>
                    </tr>
                    <tr>
                        <th>Reference:</th>
                        <td>INV<?= $return->invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Purchase Ref.:</th>
                        <td>INV<?= $return->purchase->invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Sales Person:</th>
                        <td><?= $return->user->firstname; ?> <?= $return->user->lastname; ?></td>
                    </tr>
                    <tr>
                        <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
                            <th>Contact Store:</th>
                            <td><?= $return->purchase->store->phone ?? ''; ?></td>
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
            <th>Cost</th>
            <th>
                Sub Ttl
            </th>
        </thead>
        <tbody>
            <?php
            $total_discount = ($return->discount ?? 0);
            $items = $return->items;
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
                        <?= number_format($row->unit_cost, 2); ?>
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
                    GHS <?= number_format($return->total_amount, 2) ?>
                </td>
            </tr>
            <tr>
                <th>
                    Paid
                </th>
                <td></td>
                <td colspan="2">
                    GHS <?= number_format($return->paid, 2) ?>
                </td>
            </tr>
            <tr>
                <th>
                    Change
                </th>
                <td></td>
                <td colspan="2">
                    GHS <?= $return->total_amount - $return->paid < 0 ? "(" . number_format(abs($return->total_amount - $return->paid), 2) . ")" : number_format($return->total_amount - $return->paid, 2) ?>
                </td>
            </tr>
        </tfoot>
    </table>
</main>
<footer class="text-center">
    <p><b>Thank you for your business!</b></p>
    <p><small>Codewrite Technology Ltd. Copyright &copy; 2023 version <?= env('app.version') ?> Mobile: 0246092155/0553035684</small></p>
</footer>
<?= $this->endSection() ?>