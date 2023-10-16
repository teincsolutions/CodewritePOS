<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= $title ?? "Add Purchase" ?></h4>
            <h6>Manage your purchase</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('purchases/returns/create') ?>" class="btn btn-added"><i class="fa fa-plus me-1"></i> Purchase Return</a>
        </div>
    </div>
    <form class="post-form" action="<?= site_url('purchases') ?>" method="post">

        <div class="row">
            <div class="col-sm-12 col-lg-8">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= isset($purchase) ? $purchase->id : null ?>">
                <input type="hidden" name="invoice" value="<?= isset($purchase) ? $purchase->invoice : $invoice ?>">
                <input id="purchase-type" type="hidden" name="type" value="<?= isset($purchase) ? $purchase->type : null ?>">
                <input id="order-status" type="hidden" name="order_status" value="<?= isset($purchase) ? $purchase->order_status : null ?>">
                <input id="payment-status" type="hidden" name="payment_status" value="<?= isset($purchase) ? $purchase->payment_status : null ?>">
                <input id="purchase-total" type="hidden" name="total_amount" value="<?= isset($purchase) ? $purchase->total_amount : 0.00 ?>">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?php if (isset($error)) : ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $error ?>
                                    <a href="<?= site_url('purchase/pos') ?>" type="button" class="btn-close" aria-label="Close"></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <div class="row">
                                        <div class="col-lg-10 col-sm-10 col-10">
                                            <select name="supplier_id" class="select2-suppliers" required>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                            <div class="add-icon">
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add-supplier" class="btn btn-icon"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store_id" class="select2-store">
                                        <option value=""></option>
                                        <?php
                                        if (isset($stores))
                                            foreach ($stores as $row) { ?>
                                            <option value="<?= $row->id ?>" <?= isset($purchase) ? ($row->id === $purchase->store_id ? 'selected' : '') : null ?>>
                                                <?= $row->name; ?><?= $row->location ? "($row->location)" : null; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="input-groupicon">
                                        <input name="purchase_date" type="text" class="datetimepicker" value="<?= date('d-m-Y', time()) ?>" required>
                                        <div class="addonset">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12 mb-3">
                                <div class="form-group">
                                    <div class="form-outline autocomplete">
                                        <label class="form-label" for="form1">Search</label>
                                        <input autocomplete="off" id="search-products" type="search" class="form-control" placeholder="Enter product name, barcode, sku..." />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div style="max-height: 500px !important;overflow-y:scroll" class="table-responsive mb-3">
                                <table class="table tr-items">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>QTY</th>
                                            <th>Cost</th>
                                            <th>Discount</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($purchase))
                                            foreach ($purchase->items as $key => $row) : ?>
                                            <tr>
                                                <td>
                                                </td>
                                                <td class="productimgname">
                                                    <?= $row->product->image_uri
                                                        ? '<a class="product-img"><img src="' . base_url($row->product->image_uri) . '" alt="product"></a>'
                                                        : '<a class="p-3"></a>' ?>
                                                    <a target="_blank" href="<?= site_url('products/' . $row->product_id) ?>">
                                                        <?= $row->product->name ?>
                                                        <?php if ($row->store) { ?>
                                                            <?= $row->store->name; ?><?= $row->store->location ? "(" . $row->store->location . ")" : null; ?></a>
                                                <?php } ?>
                                                </td>
                                                <td>
                                                    <div class="increment-decrement">
                                                        <div class="input-groups">
                                                            <input type='hidden' name="items[<?= $key ?>][id]" value="<?= $row->id ?>">
                                                            <input type='hidden' name="items[<?= $key ?>][purchase_id]" value="<?= $row->purchase_id ?>">
                                                            <input type='hidden' name="items[<?= $key ?>][product_id]" value="<?= $row->product_id ?>">
                                                            <input type="hidden" name="items[<?= $key ?>][unit_cost]" value="<?= $row->unit_cost; ?>" class="runit_cost">
                                                            <input type="hidden" name="items[<?= $key ?>][unit_price]" value="<?= $row->unit_price; ?>" class="runit_price">
                                                            <input type="hidden" name="items[<?= $key ?>][discount]" value="<?= $row->discount; ?>" class="rdiscount">
                                                            <input type="hidden" name="items[<?= $key ?>][store_id]" value="<?= $row->store_id; ?>">
                                                            <input type="hidden" name="items[<?= $key ?>][subtotal]" class="rsubtotal" value="<?= $row->subtotal ?>">
                                                            <input type="button" value="-" class="button-minus dec button">
                                                            <input onblur="updateItemRow(this)" min="1" type="text" name="items[<?= $key ?>][qty]" value="<?= $row->qty ?>" class="quantity-field rqty" required>
                                                            <input type="button" value="+" class="button-plus inc button">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= $row->unit_cost ?></td>
                                                <td><?= $row->discount ?></td>
                                                <td><?= number_format($row->subtotal, 2) ?></td>
                                                <td><?= setting("App.AllowCostChange") === "yes" || setting("App.AllowSupplierDiscountChange") === "yes"
                                                        ? '<span class="edit-cost btn btn-icon"><i class="fa fa-edit"></i></span>'
                                                        : "" ?>
                                                    <a href="javascript:void(0);" class="delete-set"><i class="fa text-danger fa-trash"></i></a>
                                                </td>

                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier Discount</label>
                                    <div class="input-group">
                                        <input onkeyup="updateTotals()" type="number" name="discount" value="<?= isset($purchase) ? $purchase->discount : null ?>" class="form-control addon-inline" placeholder="Purchase discount" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Shipping</label>
                                    <input onkeyup="updateTotals()" type="number" name="shipping" value="<?= isset($purchase) ? $purchase->shipping : null ?>" class="form-control" placeholder="Shipping amount">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                            </li>
                                            <li>
                                                <h4>Discount </h4>
                                                <h5 class="discountTotal"> 0.00(0.00%)</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Shipping</h4>
                                                <h5 class="shippingTotal">0.00</h5>
                                            </li>
                                            <li class="total">
                                                <h4>Grand Total</h4>
                                                <h5 class="grandTotal">0.00</h5>
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
                        <h5>Transaction id : INV<span id="order-id"><?= isset($purchase) ? $purchase->invoice : $invoice; ?></span></h5>
                    </div>
                    <div class="actionproducts">
                        <ul>
                            <?php if (isset($purchase)) { ?>
                                <li>
                                    <a onclick="deleteRecord(<?= $purchase->id ?>,'<?= site_url('purchases') ?>', '<?= site_url('purchases') ?>')" href="javascript:void(0);" class="deletebg confirm-text"><img src="<?= base_url('assets/icons/delete-2.svg') ?>" alt="img"></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="card card-order">
                    <div class="card-body pb-2">
                        <div class="setvalue">
                            <ul>
                                <li>
                                    <h5>Supplier </h5>
                                    <h6 class="supplier"><?= isset($purchase) ? ($purchase->supplier ? $purchase->supplier->name : '') : '' ?></h6>
                                </li>
                                <li id="acc-bal" class="<?= isset($purchase) ? ($purchase->supplier ? $purchase->supplier->name : 'd-none') : 'd-none' ?>">
                                    <h5>A/c Balance</h5>
                                    <?php
                                    if (isset($purchase) && $purchase->supplier) : ?>
                                        <h6 class="supplier-balance" data-balance="<?= $purchase->supplier->balance ?>">GHS
                                            <?= $purchase->supplier->balance < 0 ? "(" . number_format(abs($purchase->supplier->balance), 2, '.', '') . ")" : $purchase->supplier->balance ?>
                                        </h6>
                                    <?php else : ?>
                                        <h6 class="supplier-balance" data-balance="0.00">GHS 0.00</h6>
                                    <?php endif ?>
                                </li>
                                <li>
                                    <h6>Total Purchase </h6>
                                    <h6 class="subTotal">GHS 0.00</h6>
                                </li>
                                <li>
                                    <h6>Total Shipping </h6>
                                    <h6 class="shippingTotal">GHS 0.00</h6>
                                </li>
                                <li>
                                    <h6>Total Discount</h6>
                                    <h6 class="discountTotal">GHS 0.00</h6>
                                </li>
                                <li class="total-value">
                                    <h5>Grand Total </h5>
                                    <h6 class="grandTotal">GHS 0.00</h6>
                                </li>
                                <li class="text-danger">
                                    <h5>Due </h5>
                                    <h6 class="dueTotal">GHS 0.00</h6>
                                </li>
                            </ul>
                        </div>
                        <div class="setvalue">
                            <input onkeyup="updateTotals()" onchange="updateTotals()" type="number" name="paid" value="<?= isset($purchase) ? $purchase->paid : null ?>" step="any" min="0" class="form-control" placeholder="Enter paid amount">
                        </div>
                        <div class="setvaluecash">
                            <ul>
                                <li>
                                    <input type="radio" class="btn-check" name="payment_type" id="cash" autocomplete="off" value="cash" <?= isset($purchase) ? ($purchase->payment_type === 'cash' ? 'checked' : null) : 'checked' ?>>
                                    <label style="height: 90px;" class="btn-outline-primary border d-flex flex-column align-items-center justify-content-center rounded" for="cash">
                                        <img src="<?= base_url('assets/icons/cash.svg') ?>" alt="img" class="me-2">
                                        <span>Cash</span>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" class="btn-check" name="payment_type" id="momo" autocomplete="off" value="momo">
                                    <label style="height: 90px;" class="btn-outline-primary border d-flex flex-column align-items-center justify-content-center rounded" for="momo" <?= isset($purchase) ? ($purchase->payment_type === 'momo' ? 'checked' : null) : null ?>>
                                        <img src="<?= base_url('assets/icons/scan.svg') ?>" alt="img" class="me-2">
                                        <span>MoMo</span>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" class="btn-check" name="payment_type" id="debit" autocomplete="off" value="debit" disabled <?= isset($purchase) ? ($purchase->payment_type === 'debit' ? 'checked' : null) : null ?>>
                                    <label style="height: 90px;" class="btn-outline-primary border d-flex flex-column align-items-center justify-content-center rounded" for="debit">
                                        <img src="<?= base_url('assets/icons/debitcard.svg') ?>" alt="img" class="me-2">
                                        <span class="text-muted">Debit Card</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:void(0);" onclick="$('.post-form').submit()" class="btn btn-success mb-5 d-flex justify-content-between">
                            <h5>Checkout</h5>
                            <h6 class="grandTotal">0.00</h6>
                        </a>
                        <div class="btn-pos">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" onclick="hold(this)" action="<?= site_url('purchase/hold') ?>" class="btn"><i class="fa fa-pause me-1"></i> Hold</a>
                                </li>
                                <li>
                                    <a class="btn" data-bs-toggle="modal" data-bs-target="#recents"><i class="fa fa-list me-1"></i> Transaction</a>
                                </li>
                                <li>
                                    <a class="btn" data-bs-toggle="modal" data-bs-target="#add-payment"><i class="fa fa-plus me-1"></i> Add Payment</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
<?= $this->section('modal') ?>
<form action="<?= site_url('suppliers') ?>" class="modal fade" id="add-supplier" tabindex="-1" aria-labelledby="create" aria-hidden="true" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="post">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Supplier</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Supplier Name</label>
                            <input type="text" name="name" placeholder="Supplier Name">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" placeholder="Address">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" name="discount" class="form-control addon-inline" placeholder="Supplier discount">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-12">
                    <button class="btn btn-submit me-2">Submit</button>
                    <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="recents" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recent Transactions</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tabs-sets">
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button" aria-controls="purchase" aria-selected="true" role="tab">Purchase</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" aria-controls="payment" aria-selected="false" role="tab">Payment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return" type="button" aria-controls="return" aria-selected="false" role="tab">Return</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="purchase" role="tabpanel" aria-labelledby="purchase-tab">
                            <div class="table-top">

                                <div class="purchase-wordset">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dt-purchases" class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice No.</th>
                                            <th>Supplier</th>
                                            <th>Order Status</th>
                                            <th>Amount </th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $badges =  [
                                            'completed' => "bg-lightgreen",
                                            'pending' => "bg-lightred",
                                        ];
                                        if (isset($purchaseList))
                                            foreach ($purchaseList as $key => $row) {
                                        ?>
                                            <tr>
                                                <td><?= $row->purchase_date; ?></td>
                                                <td><a target="_blank" href="<?= site_url('purchases/' . $row->id) ?>" class="btn btn-link btn-sm"><?= $row->invoice; ?></a></td>
                                                <td>
                                                    <?php if ($row->supplier) : ?>
                                                        <a target="_blank" href="<?= site_url('suppliers/' . $row->supplier_id) ?>" class="btn btn-link btn-sm"><?= $row->supplier->name ?></a>
                                                    <?php endif ?>
                                                </td>
                                                <td><span class="badges <?= $badges[$row->order_status]; ?>"><?= $row->order_status; ?></span></td>
                                                <td><?= $row->total_amount < 0 ? "(" . number_format(abs($row->total_amount), 2) . ")" : number_format($row->total_amount, 2); ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <a target="_blank" href="<?= site_url('purchases/' . $row->id) ?>" class="btn btn-icon btn-sm"><i class="fa fa-eye fa-lg"></i></a>
                                                        <?php if ($row->order_status === 'completed') : ?>
                                                            <a class="me-3 text-secondary" href="<?= site_url('returns/purchases/create?invoice=' . $row->invoice) ?>"><i class="fa fa-reply fa-lg"></i></a>
                                                        <?php else : ?>
                                                            <a class="me-3 text-secondary" href="<?= site_url('purchase/pos/' . $row->id) ?>"><i class="fa fa-play fa-lg"></i></a>
                                                            <a class="text-danger" href="javascript:void(0);" onclick="deleteRecord(<?= $row->id ?>,'<?= site_url('purchases') ?>', '<?= site_url('purchase/pos') ?>')"><i class="fa fa-trash fa-lg"></i></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                            } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            <div class="table-top">
                                <div class="payments-wordset">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dt-payments" class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice No.</th>
                                            <th>Supplier</th>
                                            <th>Due</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $badges =  [
                                            'completed' => "bg-lightgreen",
                                            'pending' => "bg-lightred",
                                        ];
                                        if (isset($ledgerList))
                                            foreach ($ledgerList as $key => $row) {
                                                $row->balance = $row->debit - $row->credit;
                                        ?>
                                            <tr>
                                                <td><?= $row->tdate; ?></td>
                                                <td><a target="_blank" href="<?= site_url('purchases/' . $row->purchase_id) ?>" class="btn btn-link btn-sm"><?= $row->purchase->invoice; ?></a></td>
                                                <td>
                                                    <a target="_blank" href="<?= site_url('suppliers/' . $row->supplier_id) ?>" class="btn btn-link btn-sm"><?= $row->supplier->name ?></a>
                                                </td>
                                                <td><?= number_format($row->debit, 2); ?></td>
                                                <td><?= number_format($row->credit, 2); ?></td>
                                                <td><?= $row->balance < 0 ? "(" . number_format(abs($row->balance), 2) . ")" : number_format($row->balance, 2); ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <a class="me-3 text-secondary" href="<?= site_url('suppliers/ledgers/edit/' . $row->id) ?>"><i class="fa fa-edit fa-lg"></i></a>
                                                        <a class="text-danger" href="javascript:void(0);" onclick="deleteRecord(<?= $row->id ?>,'<?= site_url('suppliers/ledgers') ?>', '<?= site_url('purchase/pos') ?>')"><i class="fa fa-trash fa-lg"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                            } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="return" role="tabpanel">
                            <div class="table-top">
                                <div class="returns-wordset">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dt-returns" class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice No.</th>
                                            <th>Supplier</th>
                                            <th>Return Status</th>
                                            <th>Return Amount </th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $badges =  [
                                            'completed' => "bg-lightgreen",
                                            'pending' => "bg-lightred",
                                        ];
                                        if (isset($returnList))
                                            foreach ($returnList as $key => $row) {
                                        ?>
                                            <tr>
                                                <td><?= $row->return_date; ?></td>
                                                <td><a target="_blank" href="<?= site_url('returns/purchases/' . $row->id) ?>" class="btn btn-link btn-sm"><?= $row->invoice; ?></a></td>
                                                <td>
                                                    <?php if ($row->purchase->supplier) : ?>
                                                        <a target="_blank" href="<?= site_url('suppliers/' . $row->purchase->supplier_id) ?>" class="btn btn-link btn-sm"><?= $row->purchase->supplier->name ?></a>
                                                    <?php endif ?>
                                                </td>
                                                <td><span class="badges <?= $badges[$row->order_status]; ?>"><?= $row->order_status; ?></span></td>
                                                <td><?= $row->total_amount < 0 ? "(" . number_format(abs($row->total_amount), 2) . ")" : number_format($row->total_amount, 2); ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?= setting("App.AllowCosthange") === "yes" || setting("App.AllowSupplierDiscountChange") === "yes"
                                                            ? '<span class="edit-cost btn btn-icon"><i class="fa fa-edit"></i></span>'
                                                            : "" ?>
                                                        <a class="text-danger" href="javascript:void(0);" onclick="deleteRecord(<?= $row->id ?>,'<?= site_url('purchases') ?>', '<?= site_url('returns/purchases') ?>')"><i class="fa fa-trash fa-lg"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                            } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?= site_url('suppliers/ledgers') ?>" class="modal fade" id="add-payment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment Date</label>
                            <div class="input-groupicon">
                                <input type="text" name="tdate" value="<?= date('d-m-Y', time()) ?>" class="datetimepicker" required>
                                <div class="addonset">
                                    <i class="fa fa-calendar fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Reference</label>
                            <select name="purchase_id" class="select2-invoices" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="supplier_id">
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Invoice Balance</label>
                            <input id="inv-bal" type="text" value="0.00" placeholder="Enter Amount" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="text" onkeyup="$('#inv-due').val(($('#inv-bal').val()- $(this).val()).toFixed(2))" name="debit" min="0" value="" placeholder="Enter Amount" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Amount Due</label>
                            <input id="inv-due" type="text" value="0.00" placeholder="Enter Amount" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Payment type</label>
                            <select name="payment_type" class="select" required>
                                <option value="cash">Cash</option>
                                <option value="momo">MoMo</option>
                                <option value="credit">Credit Card</option>
                            </select>
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
<div class="modal fade" id="edit-product" tabindex="-1" aria-labelledby="editproduct" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?php if (auth()->user()->can('products.edit')) : ?>
                    <div class="row">
                        <div <?= setting('App.AllowCostChange') === 'yes' ? '' : 'hidden'  ?> class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Unit Cost</label>
                                <input id="unit-cost" min="0" type="number" class="form-control" placeholder="Unit Cost">
                            </div>
                        </div>
                        <div <?= setting('App.AllowSupplierDiscountChange') === 'yes' ? '' : 'hidden'  ?> class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Discount</label>
                                <input id="discount" type="number" placeholder="Discount Amount" class="form-control">
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        You don't have permission to Edit Products. Please Contact Admin
                    </div>
                <?php endif ?>
            </div>
            <div class="modal-footer">
                <?php if (auth()->user()->can('products.edit')) : ?>
                    <button onclick="updateProduct()" type="submit" class="btn btn-submit">Update</button>
                <?php endif ?>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-order.js?v=7') ?>"></script>
<script src="<?= base_url('assets/js/datatables/order.modal.js') ?>"></script>
<script src="<?= base_url('assets/js/record-actions.js') ?>"></script>
<?php if (isset($purchase) && $purchase->supplier) {
    $supplier = $purchase->supplier;
    $supplier->text = $supplier->name . " (" . ($supplier->address ? $supplier->address : $supplier->phone) . ")";
?>
    <script>
        $(() => {
            let supplierData = <?= json_encode($supplier) ?>;
            var option = new Option(supplierData.text, supplierData.id, true, true);
            select2Supplier.append(option).trigger('change');
            select2Supplier.trigger({
                type: 'select2:select',
                params: {
                    data: supplierData
                }
            });
        });
    </script>
<?php } ?>
<?= $this->endSection() ?>