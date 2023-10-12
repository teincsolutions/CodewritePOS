<?php
$permissions = [];
foreach (setting('AuthGroups.permissions') as $key => $value) {
    if (isset($permissions[explode('.', $key)[0]]))
        $permissions[explode('.', $key)[0]][] =  explode('.', $key)[1];
    else  $permissions[explode('.', $key)[0]][] = explode('.', $key)[1];
}
$skipRoles = ['developer'];
$disabled = array_merge(setting('AuthGroups.disabledGroup'), [setting('AuthGroups.defaultGroup')]);

$skip = in_array($group->alias, $skipRoles) ? [] : ['updates','email-settings','payment-settings','sms-settings'];
$disabled =  in_array($group->alias, $disabled);
?>
<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= $title ?? "Edit Permission" ?></h4>
            <h6>Manage Edit Permissions</h6>
        </div>
        <div class="page-btn">
            <a class="btn btn-added" href="<?= site_url('settings/groups') ?>"><i class="fa fa-arrow-left me-1"></i> List Group</a>
        </div>
    </div>
    <form action="<?= site_url('settings/group-permissions') ?>" class="post-form" method="post">
        <input type="hidden" name="group" value="<?= $group->alias ?>">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control" value="<?= $group->title ?>" readonly>
                        </div>
                    </div>
                    <div class="col-lg-9 col-sm-12">
                        <div class="form-group">
                            <label>Role Description</label>
                            <input type="text" class="form-control" value="<?= $group->description ?>" readonly>
                        </div>
                    </div>
                </div>
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
                                                    if ($action === '*') continue;
                                                ?>
                                                    <li>
                                                        <label class="inputcheck text-capitalize"><?= $action ?>
                                                            <input name="permissions[]" value="<?= $role ?>.<?= $action ?>" type="checkbox" <?= $group->can("$role.$action") ? 'checked' : '' ?> <?= $disabled ? 'disabled' : null ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </li>
                                                <?php endforeach ?>
                                                <li>
                                                    <label class="inputcheck">Select All
                                                        <input type="checkbox" name="permissions[]" value="<?= $role ?>.*" <?= $group->can($role . ".*") ? 'checked' : '' ?> <?= $disabled ? 'disabled' : null ?>>
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
                        <button type="submit" class="btn btn-submit me-2">Save</button>
                        <a href="<?= site_url('settings/groups') ?>" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>