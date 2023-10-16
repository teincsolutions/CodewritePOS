<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="CODEWRITE POS - Point of sale system for all sort of business.">
    <meta name="keywords" content="admin, estimates, business, corporate, creative, management, minimal, modern">
    <meta name="author" content="Codewrite Technology Ltd - CODEWRITE POS">
    <meta name="robots" content="noindex, nofollow">
    <meta name="base-url" content="<?= site_url() ?>">
    <title><?= $title ?? $this->renderSection('title') ?> - Codewrite POS </title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/bootstrap-datetimepicker.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/datatables.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/owl.carousel.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/font-awesome/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/search.css') ?>">
    <?= $this->renderSection('link') ?>
    <script>
        <?php
        $settings = [
            'AllowPriceChange', 
            'LimitPriceChange', 
            'AllowCostChange', 
            'LimitCostChange', 
            'AllowCustomerDiscountChange', 
            'AllowSupplierDiscountChange', 
            'AllowDeleteSales',
            'AllowDeleteSalesReturns',
            'AllowDeletePurchases',
            'AllowDeletePurchaseReturns',
            'AllowDeleteQuotes',
            'companyName', 
            'companyContacts',
        ];
        $data = [];
        foreach ($settings as  $key)
            $data = array_merge($data, [$key => setting("App.$key")]);
        ?>
        var Settings = <?= json_encode($data) ?>;
    </script>
</head>

<body class="<?= isset($bodyClass) ? $bodyClass : null ?>">