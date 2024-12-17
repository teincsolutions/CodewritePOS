<script src="<?= base_url('assets/js/jquery/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/js/feather.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery/jquery.slimscroll.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/owl.carousel.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js?v=10') ?>"></script>
<?= $this->renderSection('script') ?>

<script>
    var versionInfo = `<?= view('template/app-version-info') ?>`;
    //software update alert
    if (!localStorage.getItem('v1.1.1') && !location.href.includes("login")) {
        Swal.fire({
            html: versionInfo,
        });
        localStorage.setItem('v1.1.1', 'seen')
    }
</script>
</body>

</html>