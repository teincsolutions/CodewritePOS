<?= $this->extend('template/blank') ?>
<?= $this->section('link') ?>
<style>
    .suffix-percent::after {
        content: "%";
    }

    .setvaluecash li.active {
        background-color: lavender;
    }

    .autocomplete {
        /*the container must be positioned relative:*/
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 199;
        /*position the autocomplete items to be the same width as the container:*/
        top: 100%;
        left: 0;
        right: 0;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    .autocomplete-items div:hover {
        /*when hovering an item:*/
        background-color: #e9e9e9;
    }

    .autocomplete-active {
        /*when navigating through the items using the arrow keys:*/
        background-color: DodgerBlue !important;
        color: #ffffff;
    }
</style>
<?= $this->endSection()  ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Ponit of Sales</h4>
            <h6>Manage your sales</h6>
        </div>
        <div class="page-btn">
            <a href="<?= site_url('sales-returns/create') ?>" class="btn btn-added"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" alt="img" class="me-1">Sales Return</a>
        </div>
    </div>
    <div class="row">
        <form class="col-sm-12 col-md-8 post-form" action="<?= site_url('sales') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= isset($sales) ? $sales->id : null ?>">
            <input type="hidden" name="invoice" value="<?= isset($invoice) ? $invoice : null ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer</label>
                                <div class="row">
                                    <div class="col-lg-10 col-sm-10 col-10">
                                        <select name="customer_id" class="select">
                                            <?php if (isset($sales) && $sales->type === 'walk-in') { ?>
                                                <option value="" selected>walk-in-customer</option>
                                                <?php
                                                if (isset($customers))
                                                    foreach ($customers as $row) { ?>
                                                    <option value="<?= $row->id ?>" <?= $row->id === $sales->customer_id ? 'selected' : null ?>>
                                                        <?= $row->name; ?><?= $row->address ? "($row->address)" : "($row->phone)"; ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <option value="">walk-in-customer</option>
                                                <?php
                                                if (isset($customers))
                                                    foreach ($customers as $row) { ?>
                                                    <option value="<?= $row->id ?>">
                                                        <?= $row->name; ?><?= $row->address ? "($row->address)" : "($row->phone)"; ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                        <div class="add-icon">
                                            <a href="<?= site_url('customers/create') ?>" class="btn btn-icon"><i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Store</label>
                                <select name="store_id" class="select">
                                    <option value="">Select a store</option>
                                    <?php
                                    if (isset($stores))
                                        foreach ($stores as $row) { ?>
                                        <option value="<?= $row->id ?>" <?= isset($sales) ? ($row->id === $sales->store_id ? 'selected' : '') : '' ?>>
                                            <?= $row->name; ?><?= $row->address ? "($row->address)" : null; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Date</label>
                                <div class="input-groupicon">
                                    <input name="sales_date" type="text" class="datetimepicker" value="<?= date('Y-m-d') ?>">
                                    <div class="addonset">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="form-outline autocomplete">
                                    <label class="form-label" for="form1">Search</label>
                                    <input autocomplete="off" id="search-products" type="search" class="form-control" placeholder="Enter product name, barcode, sku..." />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table tr-items">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>QTY</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Tax(%)</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Order Tax</label>
                                <input type="text" name="order_tax" value="0.00" class="form-control" placeholder="Sales taxes" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Discount</label>
                                <input onkeyup="updateTotals()" type="number" name="order_discount" value="0.00" class="form-control" placeholder="Sales discount" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Shipping</label>
                                <input onkeyup="updateTotals()" type="number" name="shipping" class="form-control" placeholder="Shipping amount">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="order_status" class="select">
                                    <option value="">Choose Status</option>
                                    <option value="completed" selected>Completed</option>
                                    <option value="pending">Inprogress</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 ">
                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                    <ul>
                                        <li>
                                            <h4>Order Tax</h4>
                                            <h5 class="orderTaxes">0.00 (0.00%)</h5>
                                        </li>
                                        <li>
                                            <h4>Discount </h4>
                                            <h5 class="discountTotal"> 0.00</h5>
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
        </form>
        <div class="col-lg-4 col-sm-12 ">
            <div class="order-list">
                <div class="orderid">
                    <h5>Transaction id : INV<?= $invoice; ?></h5>
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
                            <li>
                                <h6>Total Shipping </h6>
                                <h6 class="shippingTotal">0.00</h6>
                            </li>
                            <li>
                                <h6>Total Tax</h6>
                                <h6 class="orderTaxes">0.0</h6>
                            </li>
                            <li>
                                <h6>Total Discount</h6>
                                <h6 class="discountTotal">0.00</h6>
                            </li>
                            <li class="total-value">
                                <h5>Total </h5>
                                <h6 class="grandTotal">0.00</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="setvaluecash">
                        <ul>
                            <li class="active">
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
                    <a href="javascript:void(0);" onclick="$('.post-form').submit()" class="btn btn-success mb-5 d-flex justify-content-between">
                        <h5>Checkout</h5>
                        <h6 class="grandTotal">0.00</h6>
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

<?= $this->section('script') ?>
<script>
    table = $(".tr-items").DataTable({
        dom: "ftpi",
        length: 10,
        rowCallback: function(row, data, dispNum) {
            $('td:eq(0)', row).html(dispNum + 1);
            updateTotals();
        }
    });

    function updateRow(row) {
        row1 = $(row).parents('tr').first();
        let data = table.row(row1).data(),
            qty = parseFloat((row1).find("[name='qty']").val()),
            price = parseFloat(data[3]),
            discount = parseFloat($('td:eq(4)', row1).data('discount')),
            tax = parseFloat($('td:eq(5)', row1).data('tax')),
            subtotal = (qty * price) + (tax * price * qty) - (qty * discount);

        $('td:eq(4)', row1).html((qty * discount).toFixed(2));
        $('td:eq(6)', row1).html(subtotal.toFixed(2));
        table.draw();
    }

    function updateTotals() {
        var intVal = function(i) {
            return typeof i === "string" ?
                i.replace(/[\$,]/g, "") * 1 :
                typeof i === "number" ?
                i :
                0;
        };

        let grandTotal = 0,
            discountTotal = 0,
            taxTotal = 0,
            shipping = intVal($("[name='shipping']").val()),
            orderDiscount = intVal($("[name='order_discount']").val()),
            orderTax = intVal($("[name='order_tax']").val());
        for (let i = 0; i < table.rows().data().length; i++) {
            const row = $(`tr:eq(${i+1})`, ".tr-items");
            discountTotal += intVal($('td:eq(4)', row).html()),
                taxTotal += intVal($('td:eq(5)', row).html()),
                grandTotal += intVal($('td:eq(6)', row).html());
        }
        discountTotal += orderDiscount;
        taxTotal += orderTax;
        grandTotal += shipping + orderDiscount;
        grandTotal -= orderDiscount;

        $(".grandTotal").html("GHS " + grandTotal.toFixed(2));
        $(".shippingTotal").html("GHS " + shipping.toFixed(2));
        $(".discountTotal").html("GHS " + discountTotal.toFixed(2));
        $(".orderTaxes").html(taxTotal.toFixed(2) + "%")
    }

    $(".tr-items").on("click", ".delete-set", function() {
        table.row($(this).parents("tr")).remove().draw();
    });
    //Increment Decrement value
    $(".tr-items").on('click', '.inc.button', function() {
        var $this = $(this),
            $input = $this.prev('input'),
            $parent = $input.closest('div'),
            newValue = parseInt($input.val()) + 1;
        $parent.find('.inc').addClass('a' + newValue);
        if (newValue > 0)
            $input.val(newValue);
        newValue += newValue;
        updateRow(this);
    });
    $(".tr-items").on('click', '.dec.button', function() {
        var $this = $(this),
            $input = $this.next('input'),
            $parent = $input.closest('div'),
            newValue = parseInt($input.val()) - 1;
        $parent.find('.inc').addClass('a' + newValue);
        if (newValue > 0)
            $input.val(newValue);
        newValue += newValue;
        updateRow(this);
    });

    function autocomplete(inp) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);

            $.get(baseUrl + "/products/datatable", (d, s) => {
                if (s !== 'success') {
                    // if fail
                    b = document.createElement("DIV");
                    // no data b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<i>Unable load data!</i>";
                    a.appendChild(b);
                    // if fail
                    return;
                }

                if (d.data.length === 0) {
                    b = document.createElement("DIV");
                    b.innerHTML = "<span>No product found!</span>";
                    a.appendChild(b);
                    return;
                } else {
                    d.data.forEach((item, i) => {
                        b = document.createElement("DIV");
                        b.innerHTML = "<span>" + item.name + "</span>";
                        b.innerHTML += "<input type='hidden' name='products[]' value='" + item.id + "'>";
                        b.addEventListener("click", function(e) {
                            inp.value = "";
                            let id = this.getElementsByTagName("input")[0].value;
                            let row = ` <tr>
                                        <td></td>
                                        <td class="productimgname">
                                        ${item.image_uri ?`<a class="product-img"><img src="${baseUrl}/${item.image_uri}" alt="product"></a>`:'<a class="p-3"></a>'}
                                            <a target="_blank" href="${baseUrl}/products/${item.id}">${item.name}</a></td>
                                        <td>
                                        <div class="increment-decrement">
                                            <div class="input-groups">
                                                <input type="button" value="-" class="button-minus dec button">
                                                <input onkeyup="updateRow(this)" min="1" type="text" name="qty" value="1" class="quantity-field">
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                        </td>
                                        <td>${item.unit_price}</td>
                                        <td data-discount="${item?.discount}">${item?.discount}</td>
                                        <td data-tax="${item?.tax}" class="suffix-percent">${item?.tax*100}</td>
                                        <td>${(item.unit_price - item?.discount +(item.unit_price*item?.tax) ).toFixed(2)}</td>
                                        <td><a   href="javascript:void(0);" class="delete-set"><i class="fa text-danger fa-trash"></i></a></td>
                                    </tr>`;
                            table.row.add($(row)).draw();
                            table.draw();
                            closeAllLists();
                        });
                        a.appendChild(b);
                    })
                }
            }).fail((err) => {
                b = document.createElement("DIV");
                b.innerHTML = "<span>Couldn't load data!</span>";
                a.appendChild(b);
            });

        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) {
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });

        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function(e) {
            closeAllLists(e.target);
        });
    }

    autocomplete(document.getElementById("search-products"));
</script>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/js/handle-post.js') ?>"></script>
<?= $this->endSection() ?>