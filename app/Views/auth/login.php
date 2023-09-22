<?= $this->extend('template/auth') ?>
<?= $this->section('content') ?>
<div class="account-content">
    <div class="login-wrapper">
        <div class="login-content">
            <div class="login-userset">
                <div class="login-logo logo-normal">
                    <img src="<?= base_url('assets/images/pos-logo.png') ?>" alt="img">
                </div>
                <a href="<?= site_url() ?>" class="login-logo logo-white">
                    <img src="<?= site_url('assets/images/pos-logo.png') ?>" alt>
                </a>
                <div class="login-userheading">
                    <h3>Sign In</h3>
                    <h4>Please login to your account</h4>
                </div>
                <div class="form-login">
                    <label>Email</label>
                    <div class="form-addons">
                        <input type="text" placeholder="Enter your email address">
                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/mail.svg" alt="img">
                    </div>
                </div>
                <div class="form-login">
                    <label>Password</label>
                    <div class="pass-group">
                        <input type="password" class="pass-input" placeholder="Enter your password">
                        <span class="fas toggle-password fa-eye-slash"></span>
                    </div>
                </div>
                <div class="form-login">
                    <div class="alreadyuser">
                        <h4><a href="https://dreamspos.dreamguystech.com/html/template/forgetpassword.html" class="hover-a">Forgot Password?</a></h4>
                    </div>
                </div>
                <div class="form-login">
                    <a class="btn btn-login" href="https://dreamspos.dreamguystech.com/html/template/index.html">Sign In</a>
                </div>

            </div>
        </div>
        <div class="login-img">
            <img src="<?= base_url('assets/images/login-bg.jpg') ?>" alt="img">
        </div>
    </div>
</div>
<?= $this->endSection() ?>