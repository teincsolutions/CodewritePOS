<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="CODEWRITE POS - Point of sale system for all sort of business.">
    <meta name="keywords" content="admin, estimates, business, corporate, creative, management, minimal, modern">
    <meta name="author" content="Codewrite Technology Ltd - CODEWRITE POS">
    <meta name="robots" content="noindex, nofollow">
    <title>Codewrite Pos</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/bootstrap-datetimepicker.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/datatables.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/font-awesome/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script src="<?= base_url('assets/js/plugins/excel-to-json.min.js') ?>"></script>
    <?= $this->renderSection('link') ?>
</head>

<body class="<?= isset($bodyClass) ? $bodyClass : null ?>">
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>
    <div class="main-wrapper">
        <?= $this->include('template/header') ?>
        <?= $this->include('template/sidebar') ?>
        <div class="page-wrapper">
            <?= $this->renderSection('content') ?>
        </div>
        <?= $this->include('template/footer') ?>
    </div>
    <?= $this->renderSection('modal') ?>
    <script src="<?= base_url('assets/js/jquery/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?=base_url('assets/js/feather.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/select2.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/moment.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/bootstrap-datetimepicker.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/sweetalert2.all.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    <?= $this->renderSection('script') ?>
</body>

</html>