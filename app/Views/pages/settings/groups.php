<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= $title ?? '' ?></h4>
            <h6>Manage Group Permissions</h6>
        </div>
        <div class="page-btn">
            <a class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-group" href="javascript:void(0)"><i class="me-1 fa fa-plus"></i> Add a Group</a>
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

<?= $this->section('modal') ?>
<form action="<?= site_url('settings/groups') ?>" class="modal fade post-form refresh-page" id="add-group" method="post" tabindex="-1" aria-labelledby="creategroup" aria-hidden="true">
    <input type="hidden" name="_method" value="post">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Group/Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Title<sup class="text-danger">*</sup></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Description<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" name="description" placeholder="Description" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/groups.js') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>