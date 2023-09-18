<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>

<header>
    <h2><?= $title ?? "Container Receivings Receipt" ?></h2>
    <?php if (setting('App.Logo')) : ?>
        <p style="background: url(<?= base_url(setting('App.Logo')) ?>) no-repeat;"></p>
    <?php endif ?>
    <?php if (setting('App.companyName')) : ?>
        <h3><?= setting('App.companyName') ?></h3>
    <?php endif ?>

    <?php if (setting('App.ShowMainBranchAddress') === 'yes') : ?>
        <p class="text-center"><?= setting('App.companyAddress') ? 'Main Branch: ' : '' ?> <?= setting('App.companyAddress') ?> | tel:<?= setting('App.companyContacts') ?></p>
    <?php endif ?>

    <h4>Branch: <?= $returns->store->name; ?> at <?= $returns->store->location; ?></h4>
</header>
<main>
    <table>
        <tbody>
            <td class="no-border">
                <address>
                    <table class="raw info">
                        <?php if ($returns->customer) : ?>
                            <tr>
                                <th>Customer:</th>
                                <td><?= $returns->customer->name; ?></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?= $returns->customer->address; ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?= $returns->customer->phone; ?></td>
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
                        <td><?= date('d/m/y  h:i a', strtotime($returns->created_at)); ?></td>
                    </tr>
                    <tr>
                        <th>Reference:</th>
                        <td><?= $returns->invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Sales Person:</th>
                        <td><?= $returns->user->firstname; ?> <?= $returns->user->lastname; ?></td>
                    </tr>
                    <tr>
                        <?php if (setting('App.ShowStoreContact') === 'yes') : ?>
                            <th>Contact Store:</th>
                            <td><?= $returns->store->phone ?? ''; ?></td>
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
            foreach ($returns->items as $k => $row) : ?>
                <tr>
                    <td><?= $row->container->name; ?>(<?= $row->container->unit->label; ?>)</td>
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
            <tr>
                <th>
                    Change
                </th>
                <td></td>
                <td colspan="2">
                    GHS <?= $returns->total_amount - $returns->paid < 0 ? "(" . number_format(abs($returns->total_amount - $returns->paid), 2) . ")" : number_format(0, 2) ?>
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