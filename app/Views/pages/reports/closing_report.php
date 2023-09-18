<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Detailed Closing Report</h4>
            <h6>View detailed closing report for a period.</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="<?= site_url('reports/closing') ?>" class="card" method="get">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="date_from">Closing From:</label>
                                <div class="input-groupicon">
                                    <input type="text" name="date_from" class="datetimepicker2" placeholder="From date" id="date-from" value="<?= date('d-m-Y H:i:s', strtotime('first day of this month')) ?>">
                                    <div class="addonset d-flex text-warning">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="date_to">Closing To:</label>
                                <div class="input-groupicon">
                                    <input type="text" name="date_to" class="datetimepicker2" placeholder="To date" id="date-to" value="<?= date('d-m-Y H:i:s', strtotime('last day of this month')) ?>">
                                    <div class="addonset d-flex text-warning">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12" style="overflow-x: auto;">
                            <div class="form-group">
                                <label for="store_id">Store</label>
                                <select name="store_id" class="select2-store">
                                    <?php
                                    if (isset($stores))
                                        foreach ($stores as $row) { ?>
                                        <option value="<?= $row->id ?>" <?= ($row->id === $settings->get('App.DefaultStore', $context) ? 'selected' : '') ?>>
                                            <?= $row->name; ?> (<?= $row->location; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
        <?php if (isset($report) && $report) : ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center">Closing Report</h1>
                        <h3 class="text-center"><?= date('d-m-Y H:i:s', strtotime($report['from'])) ?> to <?= date('d-m-Y H:i:s', strtotime($report['to'])) ?></h3>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Income</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Sales,net</td>
                                    <td><?= number_format($report['sales']->totalAmount - $report['saleReturns']->totalAmount, 2) ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>(Less Discount and Promo)</td>
                                    <td>(<?= number_format($report['sales']->totalDiscount, 2) ?>)</td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total Income</th>
                                    <th></th>
                                    <th></th>
                                    <?php $totalIncome = $report['sales']->totalAmount - $report['saleReturns']->totalAmount - $report['sales']->totalDiscount; ?>
                                    <th><?= $totalIncome < 0 ? '(' . number_format(abs($totalIncome), 2) . ')' : number_format(abs($totalIncome), 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cost of Goods Sold</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Goods purchased,net</td>
                                    <td><?= number_format($report['purchases']->totalAmount - $report['purchaseReturns']->totalAmount, 2) ?></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total COGS</th>
                                    <th></th>
                                    <th></th>
                                    <?php $total = $report['purchases']->totalAmount - $report['purchaseReturns']->totalAmount; ?>
                                    <th><?= $total < 0 ? '(' . number_format(abs($total), 2) . ')' : number_format(abs($total), 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Operational Expenses</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalExpenses = 0;
                                foreach ($report['expenses'] as $expense) :
                                    $totalExpenses += $expense->subTotal;
                                ?>
                                    <?php if (sizeof($expense->subExpenses) > 0) : ?>
                                        <tr>
                                            <td><b>- <?= $expense->category ?></b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                        $total = 0;
                                        foreach ($expense->subExpenses as $row) :
                                            $total += $row->subTotal;
                                        ?>
                                            <tr>
                                                <td></td>
                                                <td><?= $row->subCategory ?></td>
                                                <td><?= number_format($row->subTotal, 2) ?></td>
                                                <td></td>
                                            </tr>

                                        <?php endforeach ?>
                                        <tr>
                                            <td></td>
                                            <td>Others</td>
                                            <td><?= number_format($expense->subTotal - $total, 2) ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total <?= $expense->category ?></b></td>
                                            <td></td>
                                            <td><?= number_format($expense->subTotal, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php else :  ?>
                                        <tr>
                                            <td></td>
                                            <td><?= $expense->category ?></td>
                                            <td><?= number_format($expense->subTotal, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endif ?>

                                <?php endforeach ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total Expenses</th>
                                    <th></th>
                                    <th></th>
                                    <th><?= $totalExpenses < 0 ? '(' . number_format(abs($totalExpenses), 2) . ')' : number_format(abs($totalExpenses), 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-closing-report.js?v=1') ?>"></script>
<?= $this->endSection() ?>