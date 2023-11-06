<?php
$permissions = [];
foreach (setting('AuthGroups.permissions') as $key => $value) {
    if (isset($permissions[explode('.', $key)[0]]))
        $permissions[explode('.', $key)[0]][] =  explode('.', $key)[1];
    else  $permissions[explode('.', $key)[0]][] = explode('.', $key)[1];
}
$skipGroups = ['developer'];
$disabled = array_merge(setting('AuthGroups.disabledGroup'), [setting('AuthGroups.defaultGroup')]);

$skip = $user->inGroup(...$skipGroups) ? [] : ['updates','email-settings','payment-settings','sms-settings'];
$disabled =  $user->inGroup(...$disabled);
?>
<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>User Details</h4>
            <h6>Full details of a user</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('users') ?>" class="btn btn-added"><i class="fa fa-arrow-left me-2"></i>List Users</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white">
                <div class="card-header">
                    <h5 class="card-title">Details of <?= $user->firstname; ?> <?= $user->lastname; ?></h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item"><a class="nav-link active" href="#user-tab" data-bs-toggle="tab">User</a></li>
                        <li class="nav-item"><a class="nav-link" href="#bills-tab" data-bs-toggle="tab">Sales</a></li>
                        <li class="nav-item"><a class="nav-link" href="#returns-tab" data-bs-toggle="tab">Returns</a></li>
                        <li class="nav-item"><a class="nav-link" href="#supplier-bills-tab" data-bs-toggle="tab">Purchases</a></li>
                        <li class="nav-item"><a class="nav-link" href="#supplier-returns-tab" data-bs-toggle="tab">Purchase Returns</a></li>
                        <li class="nav-item"><a class="nav-link" href="#permission-tab" data-bs-toggle="tab">Permissions</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="user-tab">
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <h3>Personal Information</h3>
                                    <div class="productdetails">
                                        <ul class="product-bar">
                                            <li>
                                                <h4>UserName</h4>
                                                <h6><?= $user->username ?></h6>
                                            </li>
                                            <li>
                                                <h4>Full Name</h4>
                                                <h6><?= $user->firstname ?> <?= $user->lastname ?></h6>
                                            </li>
                                            <li>
                                                <h4>Address</h4>
                                                <h6><?= $user->address ?></h6>
                                            </li>
                                            <li>
                                                <h4>Email</h4>
                                                <h6><?= $user->email ?></h6>
                                            </li>
                                            <li>
                                                <h4>Phone Number</h4>
                                                <h6><?= $user->phone ?></h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3>Account Information</h3>
                                    <div class="productdetails">
                                        <ul class="product-bar">
                                            <li>
                                                <h4>User Groups</h4>
                                                <h6 class="text-uppercase"><?= join(",", $user->groups); ?></h6>
                                            </li>
                                            <li>
                                                <h4>Active</h4>
                                                <h6 class="text-capitalize <?= ['text-danger', 'text-success'][$user->active] ?>"><?= $user->active ? 'Yes' : 'No' ?></h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bills-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search">
                                                    <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                                                    <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                        </div>
                                    </div>

                                    <div class="card" id="filter_inputs">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <input type="hidden" name="user_id" value="<?= $user->id; ?>">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <select name="payment_status" class="select">
                                                            <option value="">Select a status</option>
                                                            <option value="due">Due</option>
                                                            <option value="paid">Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="dt-sales" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                    </th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th>Total</th>
                                                    <th>Paid</th>
                                                    <th>Due</th>
                                                    <th>Biller</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="returns-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search1">
                                                    <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                                                    <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                        </div>
                                    </div>

                                    <div class="card" id="filter_inputs1">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <input type="hidden" name="sales.user_id" value="<?= $user->id; ?>">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <select name="payment_status" class="select">
                                                            <option value="">Select a status</option>
                                                            <option value="due">Due</option>
                                                            <option value="paid">Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="dt-returns" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                    </th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th>Total</th>
                                                    <th>Paid</th>
                                                    <th>Biller</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="supplier-bills-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search">
                                                    <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                                                    <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                        </div>
                                    </div>

                                    <div class="card" id="filter_inputs">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <input type="hidden" name="user_id" value="<?= $user->id; ?>">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <select name="payment_status" class="select">
                                                            <option value="">Select a status</option>
                                                            <option value="due">Due</option>
                                                            <option value="paid">Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="dt-purchases" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                    </th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th>Total</th>
                                                    <th>Paid</th>
                                                    <th>Due</th>
                                                    <th>Biller</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="supplier-returns-tab">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="table-top">
                                        <div class="search-set">
                                            <div class="search-path">
                                                <a class="btn btn-filter" id="filter_search1">
                                                    <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                                                    <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                                                </a>
                                            </div>
                                            <div class="search-input">
                                                <a class="btn btn-searchset"><i class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                        <div class="wordset">
                                        </div>
                                    </div>

                                    <div class="card" id="filter_inputs1">
                                        <div class="card-body pb-0">
                                            <div class="row">
                                                <input type="hidden" name="purchases.user_id" value="<?= $user->id; ?>">
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="invoice" placeholder="Enter Reference No" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <select name="payment_status" class="select">
                                                            <option value="">Select a status</option>
                                                            <option value="due">Due</option>
                                                            <option value="paid">Paid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <a class="btn btn-filters ms-auto filter"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="dt-supplier-returns" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                    </th>
                                                    <th>Date</th>
                                                    <th>Reference</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th>Total</th>
                                                    <th>Paid</th>
                                                    <th>Biller</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="permission-tab">
                            <form action="<?= site_url("users/$user->id/permissions") ?>" class="post-form mt-3" method="post">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="productdetails product-respon">
                                            <ul>
                                                <?php
                                                foreach (setting('AuthGroups.permissionItems') as $role => $desc) : ?>
                                                    <?php
                                                    if (!isset($permissions[$role]) || in_array($role, $skip)) continue;
                                                    $perm = $permissions[$role];
                                                    ?>
                                                    <li>
                                                        <h4><?= $desc ?></h4>
                                                        <div class="input-checkset">
                                                            <ul>
                                                                <?php foreach ($perm as $action) :
                                                                    if($action === '*')continue;
                                                                    ?>
                                                                    <li>
                                                                        <label class="inputcheck text-capitalize"><?= $action ?>
                                                                            <input name="permissions[]" value="<?= $role ?>.<?= $action ?>" type="checkbox" <?= $user->can("$role.$action") ? 'checked' : '' ?> <?= $disabled ? 'disabled' : null ?>>
                                                                            <span class="checkmark"></span>
                                                                        </label>
                                                                    </li>
                                                                <?php endforeach ?>
                                                                    <li>
                                                                        <label class="inputcheck">Select All
                                                                            <input type="checkbox" name="permissions[]" value="<?= $role ?>.*" <?= $user->can($role . ".*") ? 'checked' : '' ?> <?= $disabled ? 'disabled' : null ?>>
                                                                            <span class="checkmark"></span>
                                                                        </label>
                                                                    </li>
                                                                
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <button type="submit" class="btn btn-submit me-2">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js?v=1') ?>"></script>
<script src="<?= base_url('assets/js/user-details.js?v=1') ?>"></script>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>