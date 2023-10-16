<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>General Settings</h4>
            <h6>Setup configuration for the system.</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-header">
                    <h5 class="card-title">General Settings</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item"><a class="nav-link active" href="#system-tab" data-bs-toggle="tab">System Information</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pos-tab" data-bs-toggle="tab">POS</a></li>
                        <li class="nav-item"><a class="nav-link" href="#reports-tab" data-bs-toggle="tab">Reports</a></li>
                        <li class="nav-item"><a class="nav-link" href="#receipt-tab" data-bs-toggle="tab">Receipt</a></li>
                        <li class="nav-item"><a class="nav-link" href="#invoice-tab" data-bs-toggle="tab">Invoice</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tax-tab" data-bs-toggle="tab">Tax</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="system-tab">
                            <form action="<?= site_url('settings') ?>" class="row post-form" method="post">
                                <p class="text-muted mt-5 mb-5">
                                    Edit the information of your business/company. Click on "Save Changes" button at the bottom of the page when done.
                                </p>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table w-100">
                                            <thead>
                                                <th style="width: 25%;">Business/Company</th>
                                                <th>Value</th>
                                            </thead>
                                            <tr>
                                                <td>Name</td>
                                                <td>
                                                    <input type="text" class="form-control border-0 border-bottom " name="companyName" value="<?= setting('App.companyName') ?>" placeholder="Business Name">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Contact(s)</td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-text text-info">
                                                            <i class="fa fa-phone"></i>
                                                        </div>
                                                        <input type="tel" class="form-control border-0 border-bottom" name="companyContacts" value="<?= setting('App.companyContacts') ?>" placeholder="Business Contact(s)">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td>
                                                    <textarea class="form-control border-0 border-bottom " name="companyAddress" placeholder="Business Address"><?= setting('App.companyAddress') ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>
                                                    <input type="email" class="form-control border-0 border-bottom w-75" name="companyEmail" value="<?= setting('App.companyEmail') ?>" placeholder="Business E-mail">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Logo</td>
                                                <td>
                                                    <div class="d-flex flex-row align-items-start gap-3">
                                                        <input type="file" class="form-control">
                                                        <div style="height: 50px;" class="w-10">
                                                            <img width="50" height="50" class="<?= setting('App.companyLogo') ? null : 'd-none'  ?>" src="<?= site_url(setting('App.companyLogo') ?? '') ?>" alt="Logo">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tax ID</td>
                                                <td>
                                                    <input type="text" class="form-control border-0 border-bottom w-75" name="companyTaxId" value="<?= setting('App.companyTaxId') ?>" placeholder="Tax ID">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="pos-tab">
                            <form action="<?= site_url('settings') ?>" class="row post-form" method="post">
                                <p class="text-muted mt-5 mb-5">
                                    Edit the configuration of your POS. Click on "Save Changes" button at the bottom of the page when done.
                                </p>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table w-100">
                                            <thead>
                                                <th style="width: 25%;">Products</th>
                                                <th>Value</th>
                                            </thead>
                                            <tr>
                                                <td>Allow Price Change</td>
                                                <td>
                                                    <div class="d-flex gap-5">
                                                        <label class="inputcheck text-capitalize">Yes
                                                            <input type="radio" name="AllowPriceChange" value="yes" <?= setting('App.AllowPriceChange') === 'yes' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="inputcheck text-capitalize">No
                                                            <input type="radio" name="AllowPriceChange" value="no" <?= setting('App.AllowPriceChange') === 'no' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Allow Cost Change</td>
                                                <td>
                                                    <div class="d-flex gap-5">
                                                        <label class="inputcheck text-capitalize">Yes
                                                            <input type="radio" name="AllowCostChange" value="yes" <?= setting('App.AllowCostChange') === 'yes' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="inputcheck text-capitalize">No
                                                            <input type="radio" name="AllowCostChange" value="no" <?= setting('App.AllowCostChange') === 'no' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Allow Customer Discount Change</td>
                                                <td>
                                                    <div class="d-flex gap-5">
                                                        <label class="inputcheck text-capitalize">Yes
                                                            <input type="radio" name="AllowCustomerDiscountChange" value="yes" <?= setting('App.AllowCustomerDiscountChange') === 'yes' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="inputcheck text-capitalize">No
                                                            <input type="radio" name="AllowCustomerDiscountChange" value="no" <?= setting('App.AllowCustomerDiscountChange') === 'no' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Allow Supplier Discount Change</td>
                                                <td>
                                                    <div class="d-flex gap-5">
                                                        <label class="inputcheck text-capitalize">Yes
                                                            <input type="radio" name="AllowSupplierDiscountChange" value="yes" <?= setting('App.AllowSupplierDiscountChange') === 'yes' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="inputcheck text-capitalize">No
                                                            <input type="radio" name="AllowSupplierDiscountChange" value="no" <?= setting('App.AllowSupplierDiscountChange') === 'no' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Limit Price Change to Default</td>
                                                <td>
                                                    <div class="d-flex gap-5">
                                                        <label class="inputcheck text-capitalize">Yes
                                                            <input type="radio" name="LimitPriceChange" value="yes" <?= setting('App.LimitPriceChange') === 'yes' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="inputcheck text-capitalize">No
                                                            <input type="radio" name="LimitPriceChange" value="no" <?= setting('App.LimitPriceChange') === 'no' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <thead class="border-top-0">
                                                <th style="width: 25%;">Sales</th>
                                                <th>Value</th>
                                            </thead>
                                            <tr>
                                                <td>Tax Rates(%)</td>
                                                <td>
                                                    <div class="input-group w-50">
                                                        <input type="number" class="form-control" min="0" name="SalesTax" value="<?= setting('App.SalesTax') ?>" placeholder="Tax rate">
                                                        <div class="input-group-text text-info">%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="reports-tab">
                            <form action="<?= site_url('settings') ?>" class="row post-form" method="post">
                                <p class="text-muted mt-5 mb-5">
                                    Edit the configuration of your Reports. Click on "Save Changes" button at the bottom of the page when done.
                                </p>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table w-100">
                                            <thead>
                                                <th style="width: 25%;">Sales</th>
                                                <th>Value</th>
                                            </thead>
                                            <tr>
                                                <td>Allow Delete Sales</td>
                                                <td>
                                                    <div class="d-flex gap-5">
                                                        <label class="inputcheck text-capitalize">Yes
                                                            <input type="radio" name="AllowDeleteSales" value="yes" <?= setting('App.AllowDeleteSales') === 'yes' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="inputcheck text-capitalize">No
                                                            <input type="radio" name="AllowDeleteSales" value="no" <?= setting('App.AllowDeleteSales') === 'no' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <thead class="border-top-0">
                                                <th style="width: 25%;">Sales Returns</th>
                                                <th>Value</th>
                                            </thead>
                                            <tr>
                                                <td>Allow Delete Sales Returns</td>
                                                <td>
                                                    <div class="d-flex gap-5">
                                                        <label class="inputcheck text-capitalize">Yes
                                                            <input type="radio" name="AllowDeleteSalesReturns" value="yes" <?= setting('App.AllowDeleteSalesReturns') === 'yes' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label class="inputcheck text-capitalize">No
                                                            <input type="radio" name="AllowDeleteSalesReturns" value="no" <?= setting('App.AllowDeleteSalesReturns') === 'no' ? 'checked' : '' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="receipt-tab">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="invoice-tab">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tax-tab">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>