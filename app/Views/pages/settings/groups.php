<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Group</h4>
            <h6>Manage Group Permissions</h6>
        </div>
        <div class="page-btn">
            <a class="btn btn-added" href="javascript:void(0)"><i class="me-1 fa fa-plus"></i> Add a Group</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="<?= site_url('assets/icons/search-white.svg') ?>" alt="img"></a>
                    </div>
                </div>
                <div class="wordset">
                </div>
            </div>
            <div class="table-responsive">
                <table id="dt-groups" class="table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>description</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (setting('AuthGroups.groups') as $key => $row) : ?>
                            <tr>
                                <td><?= $row['title'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td class="text-end">
                                    <a class="me-3" href="<?= site_url('settings/group-permissions/' . $key) ?>"> <i class="fa fa-eye fa-lg"></i></a>
                                    <a class="me-3 text-danger " onclick="deleteRecord('<?= $key ?>','<?= site_url('settings/groups') ?>','<?= site_url('settings/groups') ?>')" href="javascript:void(0);"><i class="fa fa-trash fa-lg"></i></a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/groups.js') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>

<?= $this->endSection() ?>