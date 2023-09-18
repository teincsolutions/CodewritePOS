<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Tax Rates</h4>
            <h6>Manage Tax Rates</h6>
        </div>
        <div class="page-btn">
            <a class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addpayment"><i class="fa fa-plus fa-lg me-1"></i> New Tax Rates</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                    </div>
                </div>
                <div class="wordset">

                </div>
            </div>
            <div class="table-responsive">
                <table id="dt-taxes" class="table w-100">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Tax name</th>
                            <th>Description</th>
                            <th>Tax (%)</th>
                            <th>Status</th>
                            <th>Added By</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('modal') ?>
<form class="modal fade post-form refresh-page" action="<?= site_url('settings/taxes') ?>" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add TAX </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tax Name<span class="manitory">*</span></label>
                            <input type="text" name="label" class="form-control" placeholder="Tax name" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tax Rate(%)<span class="manitory">*</span></label>
                            <input type="number" step="any" name="rate" class="form-control" placeholder="Rate (e.g 1.00 for 1%)" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tax Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Description">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label>Status</label>
                            <select name="status" class="select" required>
                                <option value="">Choose Status</option>
                                <option value="opened"> Active</option>
                                <option value="closed"> InActive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-submit">Confirm</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</form>

<form class="modal fade post-form" id="editpayment" action="<?= site_url('settings/taxes') ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tax</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tax Name<span class="manitory">*</span></label>
                            <input type="text" name="label" class="form-control" placeholder="Tax name" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tax Rate(%)<span class="manitory">*</span></label>
                            <input type="number" step="any" name="rate" class="form-control" placeholder="Rate (e.g 1.00 for 1%)" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tax Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Description">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label>Status</label>
                            <select name="status" class="select" required>
                                <option value="">Choose Status</option>
                                <option value="opened"> Active</option>
                                <option value="closed"> InActive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-submit">Update</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables/taxes.js') ?>"></script>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>