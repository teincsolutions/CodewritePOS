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
                        <li class="nav-item"><a class="nav-link" href="#receipt-tab" data-bs-toggle="tab">Receipt</a></li>
                        <li class="nav-item"><a class="nav-link" href="#invoice-tab" data-bs-toggle="tab">Invoice</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tax-tab" data-bs-toggle="tab">Tax</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="system-tab">
                            <form action="<?= site_url('settings/general') ?>" class="row post-form" method="post">
                                <input type="hidden" name="_method" value="put">
                                <p class="text-muted m-5">
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
                                                    <input type="text" class="form-control border-0 border-bottom w-50" name="companyName" value="<?= setting('App.companyName') ?>" placeholder="Business Name">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Contact(s)</td>
                                                <td>
                                                    <div class="input-group  w-50">
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
                                                    <textarea class="form-control border-0 border-bottom w-50" name="companyAddress" value="<?= setting('App.companyAddress') ?>" placeholder="Business Address"></textarea>
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
                                                        <input type="file" class="form-control w-50" name="companyLogo">
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