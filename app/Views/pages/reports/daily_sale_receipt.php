<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h2 class="text-uppercase"><?= $title ?? "Daily Sales Report" ?></h2>
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
            <p class="text-uppercase">Branch: <?= $report->store->name; ?> at <?= $report->store->location; ?></p>
        </div><!--End Info-->
    </center>
    <div class="d-flex flex-row justify-content-between gap-1" style="margin-top:3px; margin-bottom: 3px;">
        <div class="info">
            <strong>Date : </strong><span><?= date('d/m/y', strtotime($report->sales_date)); ?></span></br>
        </div>
    </div>
    <div id="bot">
        <table class="table">
            <thead>
                <th>Description</th>
                <th>Cash</th>
                <th>MoMo</th>
                <th>Total</th>
            </thead>

            <body>
                <tr>
                    <td><strong>Today's Total Sales</strong></td>
                    <td></td>
                    <td></td>
                    <td>
                        <strong>GHS <?= number_format($report->total_sales, 2) ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Today's Sales Payments</strong>
                    </td>
                    <td><?= number_format($report->total_cash_sales, 2) ?></td>
                    <td>
                        <?= number_format($report->total_momo_sales, 2) ?>
                    </td>
                    <td>
                        GHS <?= number_format($report->total_cash_sales + $report->total_momo_sales, 2) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Today's Debt Payments</strong>
                    </td>
                    <td>
                        <?= number_format($report->cash_debt_paid, 2) ?>
                    </td>
                    <td>
                        <?= number_format($report->momo_debt_paid, 2) ?>
                    </td>
                    <td>
                        GHS <?= number_format($report->total_debt_paid, 2) ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Today's Total Due</strong></td>
                    <td></td>
                    <td></td>
                    <td>
                        <strong>GHS <?= number_format($report->due_sales, 2) ?></strong>
                    </td>
                </tr>
            </body>
           
        </table>
        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your business!</strong></p>
            <p class="developer"><small>Codewrite Technology Ltd. Copyright &copy; 2023 version <?= env('app.version') ?> Mobile: 0246092155/0553035684</small></p>
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
<?= $this->endSection() ?>