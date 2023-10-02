<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?=site_url() ?>">
    <title><?= $title ?? 'POS-Receipt' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/pos-receipt.css') ?>">
</head>

<body>
    <?= $this->renderSection('content') ?>
</body>

</html>