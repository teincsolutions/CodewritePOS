
<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Profile Settings</h4>
            <h6>User profile settings</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="profile-set">
                <form action="<?=site_url('account') ?>" class="profile-top post-form" method="post">
                    <div class="profile-content">
                        <div class="profile-contentimg">
                            <img src="<?= $user->photo_uri ? base_url('photos/users/' . $user->photo_uri) : base_url('assets/icons/user.png') ?>" alt="img" id="blah">
                            <div class="profileupload">
                                <input name="photo" type="file" id="imgInp">
                                <a href="javascript:void(0);"><i class="fa fa-camera text-white fa-lg"></i></a>
                            </div>
                        </div>
                        <div class="profile-contentname">
                            <h2><?= $user->firstname ?> <?= $user->lastname ?></h2>
                            <h4>Updates Your Photo and Personal Details.</h4>
                        </div>
                    </div>
                    <div class="ms-auto">
                        <button type="submit" class="btn btn-submit me-2">Save</button>
                        <button type="reset" class="btn btn-cancel">Cancel</button>
                    </div>
                </form>
            </div>
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item"><a class="nav-link active" href="#user-tab" data-bs-toggle="tab">Update Info</a></li>
                <li class="nav-item"><a class="nav-link" href="#password-tab" data-bs-toggle="tab">Update Password</a></li>
                <li class="nav-item"><a class="nav-link" href="#preference-tab" data-bs-toggle="tab">Preferences</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="user-tab">
                    <form action="<?=site_url('account') ?>" class="row post-form mt-5">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="firstname" value="<?= $user->firstname ?>" placeholder="First Name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="lastname" value="<?= $user->lastname ?>" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?= $user->email ?>" placeholder="mail@example.com" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" value="<?= $user->phone ?>" placeholder="+1452 876 5432">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="address" value="<?= $user->address ?>" placeholder="Address">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit me-2">Submit </button>
                            <button type="reset" class="btn btn-cancel">Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="password-tab">
                    <form action="<?=site_url('account/update-password') ?>" class="row post-form mt-5">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Old Password<sup class="text-danger">*</sup></label>
                                <input name="old_password" type="password" class="form-control" autocomplete="oldpassword" inputmode="text" placeholder="Old Password" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>New Password<sup class="text-danger">*</sup></label>
                                <input name="password" type="password" class="form-control" autocomplete="newpassword" inputmode="text" placeholder="New Password" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Confirm Password<sup class="text-danger">*</sup></label>
                                <input name="password_confirm" type="password" autocomplete="newpassword_confirm" class="form-control" inputmode="text" placeholder="Password (again)" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Update Password</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="preference-tab">

                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>