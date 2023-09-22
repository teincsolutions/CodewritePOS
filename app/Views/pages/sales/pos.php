<?= $this->extend('template/blank') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Ponit of Sales</h4>
            <h6>Manage your sales</h6>
        </div>
        <div class="page-btn">
            <a href="<?=site_url('sales-returns/create') ?>" class="btn btn-added"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" alt="img" class="me-1">Sales Return</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer</label>
                                <div class="row">
                                    <div class="col-lg-10 col-sm-10 col-10">
                                        <select name="customer_id" class="select">
                                            <option value="">walk-in-customer</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                        <div class="add-icon">
                                            <span><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus1.svg" alt="img"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Store</label>
                                <select class="select">
                                    <option>Store 1</option>
                                    <option>Store 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-groupicon">
                                    <input type="text" class="datetimepicker" value="10-02-2022">
                                    <div class="addonset">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/calendars.svg" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Product Name</label>
                                <div class="input-groupicon">
                                    <input type="text" placeholder="Please type product code and select...">
                                    <div class="addonset">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/scanner.svg" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>QTY</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Tax</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td class="productimgname">
                                            <a class="product-img">
                                                <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/product7.jpg" alt="product">
                                            </a>
                                            <a href="javascript:void(0);">Apple Earpods</a>
                                        </td>
                                        <td>1.00</td>
                                        <td>15000.00</td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td>1500.00</td>
                                        <td>
                                            <a href="javascript:void(0);" class="delete-set"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="svg"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="productimgname">
                                            <a class="product-img">
                                                <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/product8.jpg" alt="product">
                                            </a>
                                            <a href="javascript:void(0);">iPhone 11</a>
                                        </td>
                                        <td>1.00</td>
                                        <td>1500.00</td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td>1500.00</td>
                                        <td>
                                            <a href="javascript:void(0);" class="delete-set"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="svg"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="productimgname">
                                            <a class="product-img">
                                                <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/product/product1.jpg" alt="product">
                                            </a>
                                            <a href="javascript:void(0);">Macbook pro</a>
                                        </td>
                                        <td>1.00</td>
                                        <td>1500.00</td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td>1500.00</td>
                                        <td>
                                            <a href="javascript:void(0);" class="delete-set"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg" alt="svg"></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 offset-lg-8">
                            <div class="totalitem">
                                <h4>Total items : 4</h4>
                                <a href="javascript:void(0);">Clear all</a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Order Tax</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Discount</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Shipping</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="select">
                                    <option>Choose Status</option>
                                    <option>Completed</option>
                                    <option>Inprogress</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 ">
                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                    <ul>
                                        <li>
                                            <h4>Order Tax</h4>
                                            <h5>$ 0.00 (0.00%)</h5>
                                        </li>
                                        <li>
                                            <h4>Discount </h4>
                                            <h5>$ 0.00</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 ">
                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                    <ul>
                                        <li>
                                            <h4>Shipping</h4>
                                            <h5>$ 0.00</h5>
                                        </li>
                                        <li class="total">
                                            <h4>Grand Total</h4>
                                            <h5>$ 1750.00</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 ">
            <div class="order-list">
                <div class="orderid">
                    <h5>Transaction id : #65565</h5>
                </div>
                <div class="actionproducts">
                    <ul>
                        <li>
                            <a href="javascript:void(0);" class="deletebg confirm-text"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete-2.svg" alt="img"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-order">
                <div class="card-body pb-2">
                    <div class="setvalue">
                        <ul>
                            <li>
                                <h5>Customer </h5>
                                <h6></h6>
                            </li>
                            <li class="total-value">
                                <h5>Total </h5>
                                <h6>60.00$</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="setvaluecash">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="paymentmethod">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/cash.svg" alt="img" class="me-2">
                                    Cash
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="paymentmethod">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/debitcard.svg" alt="img" class="me-2">
                                    Debit
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="paymentmethod">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/scan.svg" alt="img" class="me-2">
                                    MoMo
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="#" class="btn btn-success mb-5 d-flex justify-content-between">
                        <h5>Checkout</h5>
                        <h6>60.00$</h6>
                    </a>
                    <div class="btn-pos">
                        <ul>
                            <li>
                                <a class="btn"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/pause1.svg" alt="img" class="me-1">Hold</a>
                            </li>
                            <li>
                                <a class="btn"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/edit-6.svg" alt="img" class="me-1">Quotation</a>
                            </li>
                            <li>
                                <a class="btn" data-bs-toggle="modal" data-bs-target="#recents"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/transcation.svg" alt="img" class="me-1"> Transaction</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>