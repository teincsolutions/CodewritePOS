<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Account Closing</h4>
            <h6>Create a closing for daily sales and purchases</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('closing') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-2"></i>List Closing</a>
        </div>
    </div>

    <form action="<?= site_url('closing/save') ?>" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($closing) ? $closing->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($closing) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label>Store</label>
                        <select id="stores" name="store_id" required>
                            <option value=""></option>
                            <?php
                            if (isset($stores))
                                foreach ($stores as $row) { ?>
                                <?php if (isset($store)) : ?>
                                    <option value="<?= $row->id ?>" <?= $row->id === $store->id ? 'selected' : '' ?>>
                                        <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                    </option>
                                <?php else : ?>
                                    <option value="<?= $row->id ?>" <?= isset($closing) ? ($row->id === $closing->store_id ? 'selected' : '') : null ?>>
                                        <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                    </option>
                                <?php endif ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php if (isset($store) && $store) : ?>
                <div class="row">
                    <div class="col-lg-6 col-sm-6 border-end">
                        <h5 class="text-success">Inflows</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="opening_balance">Opening Balance</label>
                                    <input type="number" onchange="updateClosing()" onkeyup="updateClosing()" name="opening_balance" value="<?= isset($closing) ? $closing->opening_balance : number_format(($opening_balance ?? 0), 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cashup">Cashup</label>
                                    <input type="number" onchange="updateClosing()" onkeyup="updateClosing()" name="cashup" value="<?= isset($closing) ? $closing->cashup : number_format(($cashup ?? 0), 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_payment">Customer Payments</label>
                                    <input type="number" name="customer_payment" value="<?= isset($closing) ? $closing->customer_payment : number_format($customer_payment ?? 0, 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_total">Total Walk in Sales</label>
                                    <input type="number" name="sale_total" value="<?= isset($closing) ? $closing->sale_total : number_format($sale_total ?? 0, 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchase_return_total">Total Purchase Returns</label>
                                    <input type="number" name="purchase_return_total" value="<?= isset($closing) ? $closing->purchase_return_total : number_format($purchase_return_total ?? 0, 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_transfer_balance">Transfer Payment</label>
                                    <input type="number" name="product_transfer_balance" value="<?= isset($closing) ? $closing->product_transfer_balance : number_format($product_transfer_balance ?? 0, 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <h5 class="text-danger">Outflows</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cash_in_hand">Cash in Hand</label>
                                    <input type="number" onchange="updateClosing()" onkeyup="updateClosing()" name="cash_in_hand" value="<?= isset($closing) ? $closing->cash_in_hand : null ?>" class="form-control" placeholder="Cash in Hand" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_payment">Supplier Payments</label>
                                    <input type="number" name="supplier_payment" value="<?= isset($closing) ? $closing->supplier_payment : number_format($supplier_payment ?? 0, 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_return_total">Total Sale Returns</label>
                                    <input type="number" name="sale_return_total" value="<?= isset($closing) ? $closing->sale_return_total : number_format($sale_return_total ?? 0, 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_total">Total Expenses</label>
                                    <input type="number" name="expense_total" value="<?= isset($closing) ? $closing->expense_total :  number_format($expense_total ?? 0, 2, '.', '') ?>" class="form-control" readonly required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="closing_balance">Closing Balance</label>
                                <input type="number" name="closing_balance" value="<?= isset($closing) ? $closing->closing_balance : null ?>" class="form-control" placeholder="Closing Balance" readonly required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="<?= site_url('closing') ?>" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<script>
    function updateClosing() {
        // inflows
        let openingBalance = intVal($("[name='opening_balance']").val()),
            productTransferBalance = intVal($("[name='product_transfer_balance']").val()),
            customerPayment = intVal($("[name='customer_payment']").val()),
            cashup = intVal($("[name='cashup']").val()),
            saleTotal = intVal($("[name='sale_total']").val()),
            purchaseReturnTotal = intVal($("[name='purchase_return_total']").val()),
            totalInflows = openingBalance + productTransferBalance + cashup + customerPayment + saleTotal;

        let supplierPayment = intVal($("[name='supplier_payment']").val()),
            saleReturnTotal = intVal($("[name='sale_return_total']").val()),
            expenseTotal = intVal($("[name='expense_total']").val()),
            cashInHand = intVal($("[name='cash_in_hand']").val()),
            totalOutflows = supplierPayment + saleReturnTotal + cashInHand + expenseTotal;

        $("[name='closing_balance']").val((totalInflows - totalOutflows).toFixed(2));
    }
    $(() => {
        updateClosing();
        $("#stores")
            .select2({
                placeholder: "Select a store",
                allowClear: true,
            })
            .on("select2:select", function(e) {
                const data = e.params.data;
                location.assign(`${baseUrl}closing/store?store_id=${$(this).val()}`);
            })
            .on("select2:unselect", function(e) {
                location.assign(`${baseUrl}closing/store`);
            });
    });
</script>
<?= $this->endSection() ?>