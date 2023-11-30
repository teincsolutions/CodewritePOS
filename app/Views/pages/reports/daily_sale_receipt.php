<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h2 class="text-uppercase"><?= $title ?? "Sales Report" ?></h2>
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
    <div class="d-flex flex-row justify-content-between gap-1" style="margin-top:3px">
        <div class="info">
            <strong>Date : </strong><span><?= date('d/m/y', strtotime($report->sales_date)); ?></span></br>
        </div>
    </div>
    <div id="bot">
        <div class="row">
            <div class="col-md-4">
                <h5>Description</h5>
            </div>
            <div class="col-md-4">
                <h5>Amount</h5>
            </div>
            <div class="col-md-4">
                <h5>Total</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <b>Today's Sales</b>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <b>Cash</b>
                        <span><?= number_format($report->total_cash_sales, 2) ?></span>
                    </div>
                    <div class="col-md-12">
                        <b>MoMo</b>
                        <span><?= number_format($report->total_momo_sales, 2) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <b>GHS <?= number_format($report->total_cash_sales + $report->total_momo_sales, 2) ?></b>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <b>Today's Debt Payments</b>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <b>Cash</b>
                        <span><?= number_format($report->total_cash_sales, 2) ?></span>
                    </div>
                    <div class="col-md-12">
                        <b>MoMo</b>
                        <span><?= number_format($report->total_momo_sales, 2) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <b>GHS <?= number_format($report->total_cash_sales + $report->total_momo_sales, 2) ?></b>
            </div>
        </div>
        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your business!</strong></p>
            <p class="developer"><small>Codewrite Technology Ltd. Copyright &copy; 2023 version <?= env('app.version') ?> Mobile: 0246092155/0553035684</small></p>
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
<?= $this->endSection() ?>