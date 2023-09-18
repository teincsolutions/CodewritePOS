<?= $this->include('template/page_start'); ?>

<div id="global-loader">
    <div class="whirly-loader"></div>
</div>
<div class="main-wrapper">
    <?= $this->include('template/header') ?>
    <div class="page-wrapper ms-0">
        <?= $this->renderSection('content') ?>
    </div>
    <?= $this->include('template/footer') ?>
</div>
<?= $this->renderSection('modal') ?>
<?= $this->include('template/page_end'); ?>