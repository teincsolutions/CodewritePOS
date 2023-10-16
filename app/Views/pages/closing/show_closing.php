<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Closing Details</h4>
            <h6>Full details of a closing</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('closing') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-2"></i>List Closing</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-body">
                    <h5 class="card-title">Closing Report of <?= date('jS M Y H:i A', strtotime($closing->created_at)); ?></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="text-success">InFlows</h3>
                            <div class="productdetails">
                                <ul class="product-bar">
                                    <li>
                                        <h4>Opening Balance</h4>
                                        <h6>GHS <?= $closing->opening_balance < 0 ? "(" . number_format(abs($closing->opening_balance), 2) . ")" : number_format($closing->opening_balance, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Cashup</h4>
                                        <h6>GHS <?= number_format($closing->cashup, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Customer Payments</h4>
                                        <h6>GHS <?= number_format($closing->customer_payment, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Walk in Sales</h4>
                                        <h6>GHS <?= number_format($closing->sale_total, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Total Purchase Return</h4>
                                        <h6>GHS <?= number_format($closing->purchase_return_total, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Transfer Payment</h4>
                                        <h6>GHS <?= number_format($closing->product_transfer_balance, 2) ?></h6>
                                    </li>
                                </ul>
                            </div>
                            <div class="productdetails mt-1">
                                <h3>Status Information</h3>
                                <ul class="product-bar">
                                    <li>
                                        <h4>Status</h4>
                                        <?php
                                        $status = [
                                            'pending' => 'text-warning',
                                            'approved' => 'text-success',
                                            'dispute' => 'text-danger'
                                        ];
                                        ?>
                                        <h6 class="text-capitalize <?= $status[$closing->status] ?>"><?= $closing->status ?></h6>
                                    </li>
                                    <?php if ($closing->status === 'approved') : ?>
                                        <li>
                                            <h4>Approval Time</h4>
                                            <h6><?= date('d/m/Y H:i A', strtotime($closing->approved_at)) ?></h6>
                                        </li>
                                        <li>
                                            <h4>Approved By</h4>
                                            <h6><?= $closing->approvalUser->firstname ?> <?= $closing->approvalUser->lastname ?></h6>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="text-danger">Out Flows</h3>
                            <div class="productdetails">
                                <ul class="product-bar">
                                    <li>
                                        <h4>Cash in Hand</h4>
                                        <h6>GHS <?= number_format($closing->cash_in_hand, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Supplier Payment</h4>
                                        <h6>GHS <?= number_format($closing->supplier_payment, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Total Sale Returns</h4>
                                        <h6>GHS <?= number_format($closing->sale_return_total, 2) ?></h6>
                                    </li>

                                    <li>
                                        <h4>Total Expenses</h4>
                                        <h6>GHS <?= number_format($closing->expense_total, 2) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Closing Balance</h4>
                                        <h6>GHS <?= $closing->closing_balance < 0 ? "(" . number_format(abs($closing->closing_balance), 2) . ")" : number_format($closing->closing_balance, 2) ?></h6>
                                    </li>
                                </ul>
                            </div>
                            <div class="productdetails mt-1">
                                <h3>Management Information</h3>
                                <ul class="product-bar">
                                    <li>
                                        <h4>Closing Time</h4>
                                        <h6><?= date('d/m/Y H:i A', strtotime($closing->approved_at)) ?></h6>
                                    </li>
                                    <li>
                                        <h4>Closing By</h4>
                                        <h6><?= $closing->user->firstname ?> <?= $closing->user->lastname ?></h6>
                                    </li>

                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit-closing" class="btn btn-primary">Update Status</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<form action="<?= site_url('closing/update') ?>" class="modal fade post-form refresh-page" id="edit-closing" tabindex="-1" aria-labelledby="editclosing" aria-hidden="true">
    <input type="hidden" name="id" value="<?= $closing->id ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Closing Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="select" required>
                                <option value="pending" <?= $closing->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $closing->status === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="dispute" <?= $closing->status === 'dispute' ? 'selected' : '' ?>>In Dispute</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-submit">Save Changes</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js?v=2') ?>"></script>
<?= $this->endSection() ?>