<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Customer List</h4>
            <h6>Manage your Customers</h6>
        </div>
        <div class="page-btn">
            <a href="https://dreamspos.dreamguystech.com/html/template/addcustomer.html" class="btn btn-added"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" alt="img">Add Customer</a>
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
                        <a class="btn btn-searchset"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/search-white.svg" alt="img"></a>
                    </div>
                </div>
                <div class="wordset">
                    <ul>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/pdf.svg" alt="img"></a>
                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/excel.svg" alt="img"></a>
                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/printer.svg" alt="img"></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card" id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Enter Customer Code">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Enter Customer Name">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Enter Phone Number">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12  ms-auto">
                            <div class="form-group">
                                <a class="btn btn-filters ms-auto"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/search-whites.svg" alt="img"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table  datanew">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Customer Name</th>
                            <th>code</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>email</th>
                            <th>Country</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer1.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">Thomas</a>
                            </td>
                            <td>201</td>
                            <td>Thomas</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="e99d818684889aa98c91888499858cc78a8684">[email&#160;protected]</a>
                            </td>
                            <td>USA</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer2.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">Benjamin</a>
                            </td>
                            <td>202</td>
                            <td>Benjamin</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="75160006011a18100735100d14180519105b161a18">[email&#160;protected]</a>
                            </td>
                            <td>USA</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer3.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">James</a>
                            </td>
                            <td>521</td>
                            <td>James</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="583b2d2b2c37353d2a183d20393528343d763b3735">[email&#160;protected]</a>
                            </td>
                            <td>USA</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer3.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">Bruklin</a>
                            </td>
                            <td>555</td>
                            <td>Bruklin</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="6e0c1c1b050207002e0b160f031e020b400d0103">[email&#160;protected]</a>
                            </td>
                            <td>Thailand</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer4.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">Beverly</a>
                            </td>
                            <td>325</td>
                            <td>Beverly</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="4f0d2a392a3d23360f2a372e223f232a612c2022">[email&#160;protected]</a>
                            </td>
                            <td>Phuket island</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer5.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">B. Huber</a>
                            </td>
                            <td>589</td>
                            <td>B. Huber </td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="165e6374736456736e777b667a733875797b">[email&#160;protected]</a>
                            </td>
                            <td>Germany</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer6.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">James Stawberry</a>
                            </td>
                            <td>254</td>
                            <td>James Stawberry</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="3c5f494f485351594e7c59445d514c5059125f5351">[email&#160;protected]</a>
                            </td>
                            <td>Angola</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-imgs">
                                    WC
                                </a>
                                <a href="javascript:void(0);">James Stawberry</a>
                            </td>
                            <td>681</td>
                            <td>Fred john</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="88e2e7e0e6c8edf0e9e5f8e4eda6ebe7e5">[email&#160;protected]</a>
                            </td>
                            <td>Albania</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer5.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">B. Huber</a>
                            </td>
                            <td>589</td>
                            <td>B. Huber </td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="f1b984939483b19489909c819d94df929e9c">[email&#160;protected]</a>
                            </td>
                            <td>Germany</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer6.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">James Stawberry</a>
                            </td>
                            <td>254</td>
                            <td>James Stawberry</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="cba8beb8bfa4a6aeb98baeb3aaa6bba7aee5a8a4a6">[email&#160;protected]</a>
                            </td>
                            <td>Angola</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-imgs">
                                    WC
                                </a>
                                <a href="javascript:void(0);">James Stawberry</a>
                            </td>
                            <td>681</td>
                            <td>Fred john</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="a9c3c6c1c7e9ccd1c8c4d9c5cc87cac6c4">[email&#160;protected]</a>
                            </td>
                            <td>Albania</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer5.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">B. Huber</a>
                            </td>
                            <td>589</td>
                            <td>B. Huber </td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="f9b18c9b9c8bb99c81989489959cd79a9694">[email&#160;protected]</a>
                            </td>
                            <td>Germany</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-img">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/customer/customer6.jpg" alt="product">
                                </a>
                                <a href="javascript:void(0);">James Stawberry</a>
                            </td>
                            <td>254</td>
                            <td>James Stawberry</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="d3b0a6a0a7bcbeb6a193b6abb2bea3bfb6fdb0bcbe">[email&#160;protected]</a>
                            </td>
                            <td>Angola</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td class="productimgname">
                                <a href="javascript:void(0);" class="product-imgs">
                                    WC
                                </a>
                                <a href="javascript:void(0);">James Stawberry</a>
                            </td>
                            <td>681</td>
                            <td>Fred john</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="bdd7d2d5d3fdd8c5dcd0cdd1d893ded2d0">[email&#160;protected]</a>
                            </td>
                            <td>Albania</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editcustomer.html">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<div class="modal fade" id="showpayment" tabindex="-1" aria-labelledby="showpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Show Payments</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Amount </th>
                                <th>Paid By </th>
                                <th>Paid By </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bor-b1">
                                <td>2022-03-07 </td>
                                <td>INV/SL0101</td>
                                <td>$ 1500.00 </td>
                                <td>Cash</td>
                                <td>
                                    <a class="me-2" href="javascript:void(0);">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/printer.svg" alt="img">
                                    </a>
                                    <a class="me-2" href="javascript:void(0);" data-bs-target="#editpayment" data-bs-toggle="modal" data-bs-dismiss="modal">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit.svg" alt="img">
                                    </a>
                                    <a class="me-2 confirm-text" href="javascript:void(0);">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="img">
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="createpayment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Payment</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Customer</label>
                            <div class="input-group">
                                <input type="text" value="2022-03-07" class="datetimepicker">
                                <a class="scanner-set input-group-text">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/datepicker.svg" alt="img">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" value="INV/SL0101">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Received Amount</label>
                            <input type="text" value="1500.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="text" value="1500.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment type</label>
                            <select class="select">
                                <option>Cash</option>
                                <option>Online</option>
                                <option>Inprogress</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Note</label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editpayment" tabindex="-1" aria-labelledby="editpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Payment</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Customer</label>
                            <div class="input-group">
                                <input type="text" value="2022-03-07" class="datetimepicker">
                                <a class="scanner-set input-group-text">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/datepicker.svg" alt="img">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" value="INV/SL0101">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Received Amount</label>
                            <input type="text" value="1500.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="text" value="1500.00">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment type</label>
                            <select class="select">
                                <option>Cash</option>
                                <option>Online</option>
                                <option>Inprogress</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Note</label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>