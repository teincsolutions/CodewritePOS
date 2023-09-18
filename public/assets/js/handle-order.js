/**
 * For search params
 */
const searchParams = {
  columns: [
    {
      name: "products.name",
      searchable: "true",
    },
    {
      name: "products.description",
      searchable: "true",
    },
    {
      name: "categories.name",
      searchable: "true",
    },
    {
      name: "products.barcode",
      searchable: "true",
    },
    {
      name: "products.sku",
      searchable: "true",
    },
  ],
  start: "0",
  length: "10",
  search: {
    value: "",
    regex: "false",
  },
};

prodIndex = prodIndex ? prodIndex : 0;
(dueTotal = 0), (grandTotal = 0), (supplierBalance = 0);

let form = $(".post-form");

form.validate({
  rules: {
    store_id: "required",
    paid: "required",
  },
  messages: {
    store_id: "Please choose a store",
    paid: "Please enter amount paid",
  },
  errorElement: "em",
  errorPlacement: function (t, e) {
    t.addClass("invalid-feedback"),
      "checkbox" === e.prop("type")
        ? t.insertAfter(e.nex$("label"))
        : t.insertAfter(e);
  },
  highlight: function (e, i, n) {
    $(e).addClass("is-invalid").removeClass("is-valid");
  },
  unhighlight: function (e, i, n) {
    $(e).addClass("is-valid").removeClass("is-invalid");
  },
});
let initCompleted = false;

let tableItems = $(".tr-items").DataTable({
  dom: "fti",
  pageLength: 100,
  rowCallback: function (row, data, dispNum) {
    $("td:eq(0)", row).html(dispNum + 1);
    if (initCompleted) updateTotals();
  },
  initComplete: function (settings, json) {
    initCompleted = true;
  },
});
if (initCompleted) updateTotals();

function updateItemRow(row) {
  let row1 = $(row).parents("tr").first();
  (qty = parseFloat(row1.find(".rqty").val())),
    (cost = parseFloat(row1.find(".runit_cost").val())),
    (discount = parseFloat(row1.find(".rdiscount").val())),
    (subtotal = qty * (cost - discount));
  $(".rsubtotal", row1).val(subtotal);
  $("td:eq(3)", row1).html(cost.toFixed(2));
  $("td:eq(4)", row1).html(discount.toFixed(2));
  $("td:eq(5)", row1).html(subtotal.toFixed(2));
  updateTotals();
}

// cost change
let editModal = new bootstrap.Modal($("#edit-product")[0]);
let rowSelected;
$(".tr-items").on("click", ".edit-cost", function () {
  rowSelected = this;
  let row = $(rowSelected).parents("tr").first();
  $("#edit-product #unit-cost").val($(".runit_cost", row).val());
  if (Settings.LimitCostChange === "yes")
    $("#edit-product #unit-cost").attr("min", $(".runit_cost", row).val());
  $("#edit-product #discount").val($(".rdiscount", row).val());

  editModal.show();
});

function updateProduct() {
  let row = $(rowSelected).parents("tr").first();
  if (
    $("#edit-product #unit-cost").val() >=
    $("#edit-product #unit-cost").attr("min")
  )
    $(".runit_cost", row).val($("#edit-product #unit-cost").val());
  $(".rdiscount", row).val($("#edit-product #discount").val());
  updateItemRow(rowSelected);
  editModal.hide();
}

function updateTotals() {
  let discountTotal = 0,
    discountAmtTotal = 0;
  (shipping = intVal($("[name='shipping']").val())),
    (orderDiscount = intVal($("[name='discount']").val())),
    (grandTotal = 0);
  for (let i = 0; i < tableItems.rows().data().length; i++) {
    const row = $(`tr:eq(${i + 1})`, ".tr-items");
    (qty = intVal($(".rqty", row).val())),
      (discountTotal += intVal($(".rdiscount", row).val()) * qty),
      (grandTotal += intVal($(".rsubtotal", row).val()));
  }
  $(".subTotal").html("GHS " + grandTotal.toFixed(2));
  orderDiscountAmt = (orderDiscount / 100) * grandTotal;
  discountTotal += orderDiscountAmt;
  grandTotal += shipping;
  grandTotal -= discountAmtTotal;
  dueTotal =
    supplierBalance + grandTotal - intVal($("input[name='paid']").val());

  $(".grandTotal").html("GHS " + grandTotal.toFixed(2));
  $("#purchase-total").val(grandTotal);
  $(".shippingTotal").html("GHS " + shipping.toFixed(2));
  $(".discountTotal").html("GHS " + discountTotal.toFixed(2));
  $(".dueTotal").html(
    "GHS " +
      (dueTotal < 0
        ? "(" + Math.abs(dueTotal).toFixed(2) + ")"
        : dueTotal.toFixed(2))
  );
}

function checkout() {
  const orderStatus = $("#order-status"),
    paymentStatus = $("#payment-status");
  const paidAmt = parseFloat($("input[name='paid']").val());
  orderStatus.val("completed");
  if (grandTotal - paidAmt > 0) paymentStatus.val("due");
  else paymentStatus.val("paid");
  return true;
}

function hold(e) {
  if (grandTotal <= 0) {
    Swal.fire({
      icon: "error",
      title: "Sale Alert!",
      text: "You cannot hold an empty purchases.",
    });
    return;
  }
  const orderStatus = $("#order-status"),
    paymentStatus = $("#payment-status");
  orderStatus.val("pending");
  paymentStatus.val("due");

  $.ajax({
    method: "POST",
    url: e.getAttribute("action"),
    data: new FormData(form[0]),
    enctype: "multipart/form-data",
    dataType: "json",
    contentType: false,
    processData: false,
    cache: false,
    success: function (d, r) {
      if (!d || r === "nocontent") {
        Swal.fire({
          icon: "error",
          text: "Malformed form data sumbitted! Please try agian.",
        });
        return;
      }
      if (typeof d.status !== "boolean" || typeof d.message !== "string") {
        Swal.fire({
          icon: "error",
          text: "Malformed data response! Please try agian.",
        });
        return;
      }

      if (d.status === true) {
        window.location.assign(baseUrl + "/purchases/pos");
        Swal.fire({
          icon: "success",
          text: d.message,
        });
      } else {
        Swal.fire({
          icon: "error",
          text: d.message,
        });
      }
    },
    error: function (r) {
      Swal.fire({
        icon: "error",
        text: "Unable to submit form! Please try agian.",
      });
    },
  });
}

$(".tr-items").on("click", ".delete-set", function () {
  tableItems.row($(this).parents("tr")).remove().draw();
  updateTotals();
});
//Increment Decrement value
$(".tr-items").on("click", ".inc.button", function () {
  var $this = $(this),
    $input = $this.prev("input"),
    newValue = parseFloat($input.val()) + 1;
  if (newValue > 0) $input.val(newValue);
  updateItemRow(this);
});
$(".tr-items").on("click", ".dec.button", function () {
  var $this = $(this),
    $input = $this.next("input"),
    newValue = parseFloat($input.val()) - 1;
  if (newValue > 0) $input.val(newValue);
  updateItemRow(this);
});

function autocomplete(inp) {
  var currentFocus;
  inp.addEventListener("input", function (e) {
    var a,
      b,
      i,
      val = this.value;
    closeAllLists();
    if (!val) {
      return false;
    }
    currentFocus = -1;
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    this.parentNode.appendChild(a);
    searchParams.search.value = val;

    b = document.createElement("DIV");
    b.innerHTML = "<i>Searching...</i>";
    a.appendChild(b);
    if (Settings.ProductDiffForStore === "yes")
      searchParams.store_id = $(".select2-store").val();

     $.ajax(`${baseUrl}products/search`,{data: searchParams}) 
    .done((d, s) => {
      a.innerHTML = "";
      if (s !== "success") {
        // if fail
        b = document.createElement("DIV");
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
          info = [];
          (item.category ? info.push(item.category.name) : null) ||
            (item.brand ? info.push(item.brand.name) : null);
          let instock = 0;

          if (item.inventory) {
            if ($(".select2-store").val() == "") {
              item.inventory.forEach((stock, i) => {
                instock += parseFloat(stock.instock);
              });
            } else {
              const storeId = $(".select2-store").val();
              const stock = item.inventory.filter(
                (stock, i) => storeId == stock.store_id
              );
              if (stock.length > 0) instock = stock[0].instock;
            }
          }
          info.push(`instock<strong>(${instock})</strong>`);

          info = info.join(",");

          b.innerHTML =
            item.discontinued == 1
              ? `<span class="d-flex justify-content-between" style="z-index:1000"><del><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></del>GHS ${item.unit_cost}</span>`
              : `<span class="d-flex justify-content-between" style="z-index:1000"><span><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></span>GHS ${item.unit_cost}</span>`;

          b.addEventListener("click", function (e) {
            let store = "";
            if ($(".select2-store").val() != "") {
              store = "(" + $(".select2-store option:selected").text() + ")";
            }
            inp.value = "";
            let row = ` <tr>
                                        <td>
                                        </td>
                                        <td class="productimgname">
                                        ${
                                          item.image_uri
                                            ? `<a class="product-img"><img src="${baseUrl}${item.image_uri}" alt="product"></a>`
                                            : '<a class="p-3"></a>'
                                        }
                                            <a target="_blank" href="${baseUrl}products/${
              item.id
            }">${Settings.ShowProductSKU === "yes" ? item.sku : ""} ${
              item.name
            }(${
              item.unit.label
            })</a><span class="badge bg-info">${instock}</span></td>
                                        <td>
                                        <div class="increment-decrement">
                                            <div class="input-groups">
                                                <input type='hidden' name="items[${prodIndex}][product_id]" value="${
              item.id
            }">
                                                <input type="hidden" name="items[${prodIndex}][unit_cost]" value="${
              item.unit_cost
            }" class="runit_cost">
            <input type="hidden" name="items[${prodIndex}][unit_price]" value="${
              item.unit_price
            }" class="runit_price">
                                                <input type="hidden" name="items[${prodIndex}][store_id]" value="${$(
              ".select2-store"
            ).val()}">
            <input type="hidden" name="items[${prodIndex}][discount]" class="rdiscount" value="${
              item?.pdiscount
            }">
              <input type="hidden" name="items[${prodIndex}][subtotal]" class="rsubtotal" value="${
              item.unit_cost - item?.pdiscount
            }"> 
                            <input type="button" value="-" class="button-minus dec button">
                                                <input onkeyup="updateItemRow(this)" min="0.1" type="text" name="items[${prodIndex}][qty]" value="1" class="quantity-field rqty" required>
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                        </td>
                                        <td>${parseFloat(
                                          item.unit_cost
                                        ).toFixed(2)}</td>
                                        <td>${item?.pdiscount}</td>
                                        <td>${parseFloat(
                                          item.unit_cost - item.pdiscount
                                        ).toFixed(2)}</td>
                                        <td>
                                        ${
                                          Settings.AllowCostChange === "yes" ||
                                          Settings.AllowSupplierDiscountChange ===
                                            "yes"
                                            ? `<span class="edit-cost btn btn-icon"><i class="fa fa-edit"></i></span>`
                                            : ""
                                        }
                                        <a   href="javascript:void(0);" class="delete-set"><i class="fa text-danger fa-trash"></i></a></td>
                                    </tr>`;
            tableItems.row.add($(row)).draw();
            tableItems.draw();
            prodIndex++;
            closeAllLists();
          });
          a.appendChild(b);
        });
        //for single item and paste event
        if (e.inputType === "insertFromPaste" && d.data.length === 1) {
          $(b).trigger("click");
        }
      }
    }).fail((err) => {
      b = document.createElement("DIV");
      b.innerHTML = "<span>Couldn't load data!</span>";
      a.appendChild(b);
    });
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function (e) {
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
    if (currentFocus < 0) currentFocus = x.length - 1;
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
  document.addEventListener("click", function (e) {
    closeAllLists(e.target);
  });
}

autocomplete(document.getElementById("search-products"));

form.on("submit", function (e) {
  e.preventDefault();

  if ($(this).valid() === true && checkout()) {
    Swal.fire({
      title: "Please wait !",
      allowOutsideClick: false,
      willOpen: () => {
        Swal.showLoading();
      },
    });
    $("#submit").attr("disabled", true);
    $.ajax({
      method: "POST",
      url: this.getAttribute("action"),
      data: new FormData(this),
      enctype: "multipart/form-data",
      dataType: "json",
      contentType: false,
      processData: false,
      cache: false,
      success: function (d, r) {
        $("#submit").attr("disabled", false);

        if (!d || r === "nocontent") {
          Swal.fire({
            icon: "error",
            text: "Malformed form data sumbitted! Please try agian.",
          });
          return;
        }
        if (typeof d.status !== "boolean" || typeof d.message !== "string") {
          Swal.fire({
            icon: "error",
            text: "Malformed data response! Please try agian.",
          });
          return;
        }

        if (d.status === true) {
          if (printInvoice(d)) {
            form.trigger("reset");
            $("input[name='invoice']").val(parseInt(d.data.invoice) + 1);
            $("#order-id").html(parseInt(d.data.invoice) + 1);
            tableItems.clear().draw();
            $(".select2-supplier").val("").trigger("select2:unselect");
            updateTotals();
          }
        } else {
          Swal.fire({
            icon: "error",
            text: d.message,
          });
        }
      },
      error: function (r) {
        $("#submit").attr("disabled", false);
        
        Swal.fire({
          icon: "error",
          text: "Unable to submit form! Please try agian.",
        });
      },
    });
  }
});
let select2Customer = $(".select2-suppliers")
  .select2({
    ajax: {
      url: `${baseUrl}suppliers/select2`,
      dataType: "json",
    },
    allowClear: true,
    placeholder: "Choose a supplier",
    templateResult: formatCustomer,
    templateSelection: formatCustomer,
  })
  .on("select2:select", function (e) {
    const data = e.params.data;
    supplierBalance = parseFloat(data.balance);
    $(".supplier-balance").html(
      supplierBalance < 0
        ? `(GHS ${Math.abs(supplierBalance).toFixed(2)})`
        : `GHS ${supplierBalance.toFixed(2)}`
    );
    $(".supplier").html(data.text);
    $("input[name='discount']").val(data.discount);
    $("#acc-bal").removeClass("d-none");
    updateTotals();
  })
  .on("select2:unselect", function (e) {
    supplierBalance = 0;
    $(".supplier-balance").html(
      supplierBalance < 0
        ? `(GHS ${Math.abs(supplierBalance).toFixed(2)})`
        : `GHS ${supplierBalance.toFixed(2)}`
    );
    $(".supplier").html("");
    $("input[name='discount']").val("");
    $("#acc-bal").addClass("d-none");
    updateTotals();
  });
$(".select2-store").select2({
  placeholder: "Seach a store",
});
