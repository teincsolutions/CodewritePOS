<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>User List</h4>
            <h6>Manage your Users</h6>
        </div>
        <div class="page-btn">
            <a href="<?=site_url('users/create') ?>" class="btn btn-added"><i class="fa fa-plus fa-lg me-1"></i>Add User</a>
        </div>
    </div> 

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-path">
                        <a class="btn btn-filter" id="filter_search">
                            <img src="<?= base_url('assets/icons/filter.svg') ?>" alt="img">
                            <span><img src="<?= base_url('assets/icons/closes.svg') ?>" alt="img"></span>
                        </a>
                    </div>
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="<?= base_url('assets/icons/search-white.svg') ?>" alt="img"></a>
                    </div>
                </div>
                <div class="wordset">
                </div>
            </div>

            <div class="card" id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">
                       
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="name" type="text" placeholder="Enter User Name">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div  class="form-group">
                                <input name="phone" type="text" placeholder="Enter Phone Number">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="email" type="text" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                            <div class="form-group">
                                <a class="btn btn-filters filter ms-auto"><img src="<?=base_url('assets/icons/search-white.svg') ?>" alt="img"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="dt-users" class="table">
                    <thead>
                        <tr>
                            <th>     
                            </th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>UserName</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Added By</th>
                             <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/datatables/actions.js') ?>"></script>
<script src="<?= base_url('assets/js/datatables/users.js') ?>"></script>
<?= $this->endSection() ?>
<?= $this->section('modal') ?>
<?= $this->endSection() ?>