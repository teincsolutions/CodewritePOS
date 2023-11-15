/**
 * For search params
 */
const searchParams = {
  columns: [
    {
      name: "containers.name",
      searchable: "true",
    },
    {
      name: "containers.description",
      searchable: "true",
    },
    {
      name: "containers.barcode",
      searchable: "true",
    },
    {
      name: "containers.sku",
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
let supplierBalance = 0;
prodIndex = prodIndex ? prodIndex : 0;
purchaseItemIds = [];
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
  dom: "f<'toolbar'>ti",
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
$("div.toolbar").html(
  "<span class='btn btn-danger clear-all'>Clear all</span>"
).on("click",".clear-all", function (e) {
  tableItems.rows().remove().draw();
  purchaseItemIds = [];
  updateTotals();
});

function updateItemRow(row) {
  let row1 = $(row).parents("tr").first();
  let data = tableItems.row(row1).data(),
    qty = parseFloat(row1.find(".rqty").val()),
    cost = parseFloat(row1.find(".runit_cost").val()),
    subtotal = qty * cost;
  $(".rsubtotal", row1).val(subtotal);
  $("td:eq(4)", row1).html(subtotal.toFixed(2));
  tableItems.draw();
}

function updateTotals() {
  let discountTotal = 0,
    discountAmtTotal = 0;
  (taxTotal = 0),
    (taxAmtTotal = 0),
    (shipping = intVal($("[name='shipping']").val())),
    (orderDiscount = intVal($("[name='discount']").val())),
    (orderTax = intVal($("[name='tax']").val()));
  grandTotal = 0;
  for (let i = 0; i < tableItems.rows().data().length; i++) {
    const row = $(`tr:eq(${i + 1})`, ".tr-items");
    grandTotal += intVal($("td:eq(4)", row).html());
  }
  $(".subTotal").html("GHS " + grandTotal.toFixed(2));
  discountAmtTotal = (orderDiscount / 100) * grandTotal;
  taxTotal += orderTax;
  discountTotal += discountAmtTotal;
  grandTotal += discountAmtTotal;
  grandTotal += shipping;
  grandTotal -= discountAmtTotal;
  dueTotal = supplierBalance - grandTotal;

  $(".grandTotal").html("GHS " + grandTotal.toFixed(2));
  $("#purchases-total").val(grandTotal);
  $(".shippingTotal").html("GHS " + shipping.toFixed(2));
  $(".discountTotal").html("GHS " + discountTotal.toFixed(2));
  $(".orderTaxes").html(
    "GHS " + taxAmtTotal.toFixed(2) + " (" + taxTotal.toFixed(2) + "%)"
  );

  $(".dueTotal").html(
    "GHS " +
      (dueTotal < 0
        ? "(" + Math.abs(dueTotal).toFixed(2) + ")"
        : dueTotal.toFixed(2))
  );
  if(dueTotal < 0) $("input[name='paid']").val(Math.abs(dueTotal));
  else $("input[name='paid']").val(0.00);
}

function checkout() {
  return true;
}

$(".tr-items").on("click", ".delete-set", function () {
  let id = $(this).data("item-id");
  purchaseItemIds = purchaseItemIds.filter((item) => item != id);
  tableItems.row($(this).parents("tr")).remove().draw();
  updateTotals();
});
//Increment Decrement value
$(".tr-items").on("click", ".inc.button", function () {
  var $this = $(this),
    $input = $this.prev("input"),
    newValue = parseFloat($input.val()) + 1;
  if (newValue > 0 && newValue <= $input.attr("max")) $input.val(newValue);
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

    $.get(
      `${baseUrl}containers/search`,
      searchParams,
      (d, s) => {
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
          b.innerHTML = "<span>No container found!</span>";
          a.appendChild(b);
          return;
        } else {
          d.data.forEach((item, i) => {
            if (purchaseItemIds.includes(item.purchase_item_id) || item.max_qty <= 0) return;

            b = document.createElement("DIV");
            info = [];
            (item.category ? info.push(item.category.name) : null) ||
              (item.brand ? info.push(item.brand.name) : null);
            let instock = 0;

            if (item.inventory) {
              const stock = item.inventory.filter(
                (stock, i) => item.store_id == stock.store_id
              );
              if (stock.length > 0) instock = stock[0].instock;
            }
            info.push(`instock<strong>(${instock})</strong>`);

            info = info.join(",");

            b.innerHTML =
              item.discontinued == 1
                ? `<span class="d-flex justify-content-between" style="z-index:1000"><del><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></del>GHS ${item.unit_cost}</span>`
                : `<span class="d-flex justify-content-between" style="z-index:1000"><span><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></span>GHS ${item.unit_cost}</span>`;

            b.addEventListener("click", function (e) {
 
              inp.value = "";
              let row = ` <tr>
                                        <td>
                                        </td>
                                        <td class="productimgname">
                                        ${
                                          item.image_uri
                                            ? `<a class="product-img"><img src="${baseUrl}${item.image_uri}" alt="container"></a>`
                                            : '<a class="p-3"></a>'
                                        }
                                            <a target="_blank" href="${baseUrl}containers/${
                item.id
              }">${item.name}(${item.unit.label})</a></td>
                                        <td>
                                        <div class="increment-decrement">
                                            <div class="input-groups">
                                                <input type='hidden' name="items[${prodIndex}][purchase_item_id]" value="${
                item.purchase_item_id
              }">
              <input type='hidden' name="items[${prodIndex}][container_id]" value="${
                item.id
              }">
                                                <input type="hidden" name="items[${prodIndex}][unit_cost]" value="${
                item.unit_cost
              }" class="runit_cost">
              <input type="hidden" name="items[${prodIndex}][unit_price]" value="${
                item.unit_price
              }" class="runit_price">
                      
                                                <input type="hidden" name="items[${prodIndex}][store_id]" value="${
                item.store_id
              }">
                                                <input type="hidden" name="items[${prodIndex}][subtotal]" class="rsubtotal" value="${
                item.unit_cost
              }">
                                                <input type="button" value="-" class="button-minus dec button">
                                                <input onblur="updateItemRow(this)" min=".1" max="${
                                                  item.max_qty
                                                }" type="text" name="items[${prodIndex}][qty]" value="${
                item.max_qty
              }" class="quantity-field rqty" required>
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                        </td>
                                        <td>${item.unit_cost}</td>
                                        <td>${parseFloat(
                                          item.unit_cost
                                        ).toFixed(2)}</td>
                                        <td><a   href="javascript:void(0);" class="delete-set" data-item-id="${
                                          item.purchase_item_id
                                        }"><i class="fa text-danger fa-trash"></i></a></td>
                                    </tr>`;
              purchaseItemIds.push(item.purchase_item_id);
              tableItems.row.add($(row)).draw();
              tableItems.draw();
              prodIndex++;
              closeAllLists();
            });
            a.appendChild(b);
          });
        }
      }
    ).fail((err) => {
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

autocomplete(document.getElementById("search-containers"));

form.on("submit", function (e) {
  e.preventDefault();

  if ($(this).valid() === true && checkout()) {
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
            purchaseItemIds = [];
            $(".select2-supplier").val(null).trigger("select2:unselect");
            $("select").trigger("change");
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
        Swal.fire({
          icon: "error",
          text: "Unable to submit form! Please try agian.",
        });
      },
    });
  }
});
let select2Supplier = $(".select2-suppliers")
  .select2({
    placeholder: "Search supplier",
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

let select2Invoices = $(".select2-invoices")
  .select2({
    ajax: {
      url: `${baseUrl}purchases/select2`,
      dataType: "json",
    },
    allowClear: true,
    minimumInputLength: 3,
    placeholder: "Enter invoice/receipt reference",
  })
  .on("select2:select", function (e) {
    const data = e.params.data;
    location.assign(
      `${baseUrl}purchases/returns/create?invoice=${data.invoice}`
    );
  })
  .on("select2:unselect", function (e) {
    location.assign(`${baseUrl}purchases/returns/create`);
  });
