<?= $this->include('template/page_start'); ?>

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
<?= $this->include('template/page_end'); ?>