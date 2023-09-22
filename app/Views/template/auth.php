<?= $this->include('template/page_start', ['bodyCalss' => 'account-page']); ?>
<div class="main-wrapper">
    <?= $this->renderSection('content') ?>
</div>
<?= $this->include('template/page_end'); ?>