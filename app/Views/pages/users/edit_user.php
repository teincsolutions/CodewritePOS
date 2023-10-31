<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>User Management</h4>
            <h6>Save/Update User</h6>
        </div>
    </div>

    <form action="<?= site_url('users') ?>" autocomplete="off" class="card post-form" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= isset($user) ? $user->id : null ?>">
        <input type="hidden" name="_method" value="<?= isset($user) ? 'put' : 'post' ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>First Name<sup class="text-danger">*</sup></label>
                        <input name="firstname" class="form-control" type="text" value="<?= isset($user) ? $user->firstname : null ?>" placeholder="First Name" required>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Last Name<sup class="text-danger">*</sup></label>
                        <input name="lastname" class="form-control" type="text" value="<?= isset($user) ? $user->lastname : null ?>" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" class="form-control" type="email" autocomplete="email" value="<?= isset($user) ? $user->email : null ?>" placeholder="Email">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Phone</label>
                        <input name="phone" class="form-control" type="text" autocomplete="phone" value="<?= isset($user) ? $user->phone : null ?>" placeholder="Phone number">
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" autocomplete="address" name="address" value="<?= isset($user) ? $user->address : null ?>" placeholder="User address">
                    </div>
                </div>
                <?php if (!isset($user)) : ?>
                    <div class="col-log-8 border-top">
                        <div class="row mt-3">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>User Name<sup class="text-danger">*</sup></label>
                                    <input name="username" type="text" class="form-control" autocomplete="username" placeholder="User Name" value="<?= isset($user) ? $user->username : null ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Role(s)<sup class="text-danger">*</sup></label>
                                    <select name="groups[]" id="select2-groups" multiple>
                                        <option value=""></option>
                                        <?php foreach (setting('AuthGroups.groups') as $key => $row) : ?>
                                            <option value="<?= $key ?>"><?= $row['title'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Password<sup class="text-danger">*</sup></label>
                                    <input name="password" type="password" class="form-control" autocomplete="password" inputmode="text" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Confirm Password<sup class="text-danger">*</sup></label>
                                    <input name="password_confirm" type="password" autocomplete="password_confirm" class="form-control" inputmode="text" placeholder="Password (again)" required>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>User Name<sup class="text-danger">*</sup></label>
                            <input name="username" type="text" class="form-control" autocomplete="username" placeholder="User Name" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Role(s)<sup class="text-danger">*</sup></label>
                            <select name="groups[]" id="select2-groups" multiple required>
                                <option value=""></option>
                                <?php foreach (setting('AuthGroups.groups') as $key => $row) : ?>
                                    <option value="<?= $key ?>" <?= in_array($key, $user->groups) ? 'selected' : '' ?>><?= $row['title'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                <?php endif ?>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Save</button>
                    <a href="<?= site_url('users') ?>" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<script src="<?= base_url('assets/js/handle-adduser.js') ?>"></script>
<?= $this->endSection() ?>