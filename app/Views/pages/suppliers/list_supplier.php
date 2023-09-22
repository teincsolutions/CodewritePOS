<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Supplier List</h4>
            <h6>Manage your Supplier</h6>
        </div>
        <div class="page-btn">
            <a href="https://dreamspos.dreamguystech.com/html/template/addsupplier.html" class="btn btn-added"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" alt="img">Add Supplier</a>
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
                                <input type="text" placeholder="Enter Supplier Code">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Enter Supplier">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Enter Phone">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                            <div class="form-group">
                                <a class="btn btn-filters ms-auto"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/search-whites.svg" alt="img"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Supplier Name</th>
                            <th>code</th>
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Apex Computers</a>
                            </td>
                            <td>201</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="92e6fafdfff3e1d2f7eaf3ffe2fef7bcf1fdff">[email&#160;protected]</a>
                            </td>
                            <td>China</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Modern Automobile</a>
                            </td>
                            <td>202</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="3a594f494e55575f487a5f425b574a565f14595557">[email&#160;protected]</a>
                            </td>
                            <td>USA</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">AIM Infotech</a>
                            </td>
                            <td>521</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="f0938583849f9d9582b09588919d809c95de939f9d">[email&#160;protected]</a>
                            </td>
                            <td>USA</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Best Power Tools</a>
                            </td>
                            <td>555</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="513323243a3d383f113429303c213d347f323e3c">[email&#160;protected]</a>
                            </td>
                            <td>Thailand</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">AIM Infotech</a>
                            </td>
                            <td>325</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="9ad8ffecffe8f6e3daffe2fbf7eaf6ffb4f9f5f7">[email&#160;protected]</a>
                            </td>
                            <td>Phuket island</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Best Power Tools</a>
                            </td>
                            <td>589</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="125a6770776052776a737f627e773c717d7f">[email&#160;protected]</a>
                            </td>
                            <td>Germany</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Apex Computers</a>
                            </td>
                            <td>254</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="23405650574c4e465163465b424e534f460d404c4e">[email&#160;protected]</a>
                            </td>
                            <td>Angola</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Vinayak Tools</a>
                            </td>
                            <td>681</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="dcb6b3b4b29cb9a4bdb1acb0b9f2bfb3b1">[email&#160;protected]</a>
                            </td>
                            <td>Albania</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Best Power Tools</a>
                            </td>
                            <td>555</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="0b69797e606762654b6e736a667b676e25686466">[email&#160;protected]</a>
                            </td>
                            <td>Thailand</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">AIM Infotech</a>
                            </td>
                            <td>325</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="a3e1c6d5c6d1cfdae3c6dbc2ced3cfc68dc0ccce">[email&#160;protected]</a>
                            </td>
                            <td>Phuket island</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Best Power Tools</a>
                            </td>
                            <td>589</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="3d75485f584f7d58455c504d5158135e5250">[email&#160;protected]</a>
                            </td>
                            <td>Germany</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Apex Computers</a>
                            </td>
                            <td>254</td>
                            <td>+12163547758 </td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="02617771766d6f677042677a636f726e672c616d6f">[email&#160;protected]</a>
                            </td>
                            <td>Angola</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/noimage.png" alt="product">
                                </a>
                                <a href="javascript:void(0);">Vinayak Tools</a>
                            </td>
                            <td>681</td>
                            <td>123-456-888</td>
                            <td><a href="https://dreamspos.dreamguystech.com/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="771d181f1937120f161a071b125914181a">[email&#160;protected]</a>
                            </td>
                            <td>Albania</td>
                            <td>
                                <a class="me-3" href="https://dreamspos.dreamguystech.com/html/template/editsupplier.html">
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