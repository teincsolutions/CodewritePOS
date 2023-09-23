<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Expenses LIST </h4>
            <h6>Manage your Expenses</h6>
        </div>
        <div class="page-btn">
            <a href="<?=site_url("expenses/create");?>" class="btn btn-added"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" class="me-2" alt="img">Add New Expense</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-path">
                        <a class="btn btn-filter" id="filter_search">
                            <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/filter.svg" alt="img">
                            <span><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/closes.svg" alt="img"></span>
                        </a>
                    </div>
                    <div class="search-input">
                        <a class="btn btn-searchset">
                            <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/search-white.svg" alt="img">
                        </a>
                    </div>
                </div>
                <div class="wordset">
                    
                </div>
            </div>

             <div class="card"  id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">
                       
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input name="expense_date" type="text" placeholder="Enter Date">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div  class="form-group">
                                <input name="amount" type="text" placeholder="Enter Expense Amount">
                            </div>
                        </div>
                        
                        <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                            <div class="form-group">
                                <button type="button" class="btn btn-filters filter ms-auto"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/search-whites.svg" alt="img"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                 <table class="table" id="expensestable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Expenses Date</th>
                            <th>Store Id</th>
                            <th>Category</th>
                            <th>Amount</th>
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
<script src="<?= base_url('assets/js/datatables/expenses.js') ?>"></script>
<?= $this->endSection() ?>