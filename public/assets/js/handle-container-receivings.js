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

let prodIndex = 0,
  saleItemIds = [],
  dueTotal = 0,
  grandTotal = 0,
  customerBalance = 0;

let form = $(".post-form");
const settlementType = $(".select2-settlement");

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
$("div.toolbar")
  .html("<span class='btn btn-danger clear-all'>Clear all</span>")
  .on("click", ".clear-all", function (e) {
    tableItems.rows().remove().draw();
    saleItemIds = [];
    updateTotals();
  });

function updateItemRow(row) {
  let row1 = $(row).parents("tr").first();
  (qty = parseFloat(row1.find(".rqty").val())),
    (price = parseFloat(row1.find(".runit_price").val())),
    (subtotal = qty * price);
  $(".rsubtotal", row1).val(subtotal);
  $("td:eq(3)", row1).html(price.toFixed(2));
  $("td:eq(4)", row1).html(subtotal.toFixed(2));
  tableItems.draw();
}

function updateTotals() {
  amountTotal = 0;
  grandTotal = 0;
  for (let i = 0; i < tableItems.rows().data().length; i++) {
    const row = $(`tr:eq(${i + 1})`, ".tr-items");
    (qty = intVal($(".rqty", row).val())),
      (amountTotal += intVal($(".runit_price", row).val()) * qty),
      (grandTotal += intVal($(".rsubtotal", row).val()));
  }
  $(".subTotal").html("GHS " + grandTotal.toFixed(2));
  let paidAmt = 0;

  if (settlementType.val() === "cash")
    paidAmt = parseFloat($("input[name='paid']").val());
  else paidAmt = grandTotal;

  dueTotal = grandTotal + customerBalance - paidAmt;

  $(".grandTotal").html("GHS " + grandTotal.toFixed(2));
  $("#sales-total").val(grandTotal);
  $(".dueTotal").html(
    "GHS " +
      (dueTotal < 0
        ? "(" + Math.abs(dueTotal).toFixed(2) + ")"
        : dueTotal.toFixed(2))
  );
}

function checkout() {
  const paymentStatus = $("#payment-status");
  if (dueTotal > 0 && settlementType.val() === "cash") {
    Swal.fire({
      icon: "error",
      title: "Due Payment Alert!",
      text: "You cannot owe container receivings.",
    });
    return false;
  }
  paymentStatus.val("paid");
  return true;
}

$(".tr-items").on("click", ".delete-set", function () {
  let id = $(this).data("item-id");
  saleItemIds = saleItemIds.filter((item) => item != id);
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

    $.get(`${baseUrl}containers/search`, searchParams, (d, s) => {
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
          if (saleItemIds.includes(item.container_id)) return;

          b = document.createElement("DIV");
          info = [];
          (item.category ? info.push(item.category.name) : null) ||
            (item.brand ? info.push(item.brand.name) : null);
          let instock = 0,
            storeId = $(".select2-store").val();

          if (item.inventory) {
            const stock = item.inventory.filter(
              (stock, i) => storeId == stock.store_id
            );
            if (stock.length > 0) instock = stock[0].instock;
          }
          info.push(`instock<strong>(${instock})</strong>`);

          info = info.join(",");

          b.innerHTML =
            item.discontinued == 1
              ? `<span class="d-flex justify-content-between" style="z-index:1000"><del><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></del>GHS ${item.unit_price}</span>`
              : `<span class="d-flex justify-content-between" style="z-index:1000"><span><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></span>GHS ${item.unit_price}</span>`;

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
                                                <input type='hidden' name="items[${prodIndex}][container_id]" value="${
              item.id
            }">
                                                <input type="hidden" name="items[${prodIndex}][unit_price]" value="${
              item.unit_price
            }" class="runit_price">
              <input type="hidden" name="items[${prodIndex}][unit_cost]" value="${
              item.unit_cost
            }" class="runit_cost">
                                                <input type="hidden" name="items[${prodIndex}][store_id]" value="${storeId}">
                                                <input type="hidden" name="items[${prodIndex}][subtotal]" class="rsubtotal" value="${
              item.unit_price
            }">
                                                <input type="button" value="-" class="button-minus dec button">
                                                <input onblur="updateItemRow(this)" min=".1" step="any" type="text" name="items[${prodIndex}][qty]" value="1" class="quantity-field rqty" required>
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                        </td>
                                        <td>${item.unit_price}</td>
                                        <td>${item.unit_price}</td>
                                        <td><a   href="javascript:void(0);" class="delete-set"><i class="fa text-danger fa-trash"></i></a></td>
                                    </tr>`;
            saleItemIds.push(item.container_id);
            tableItems.row.add($(row)).draw();
            tableItems.draw();
            prodIndex++;
            closeAllLists();
          });
          a.appendChild(b);
        });
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
            $("input[name='invoice']").val(parseInt(d.data.invoice) + 1);
            $("#order-id").html(parseInt(d.data.invoice) + 1);
            tableItems.clear().draw();
            saleItemIds = [];
            $(".select2-customer").val("");
            $(".select2-customer").trigger("select2:unselect change");
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
let select2Customer = $(".select2-customer")
  .select2({
    ajax: {
      url: `${baseUrl}customers/select2`,
      dataType: "json",
    },
    allowClear: true,
    placeholder: "walk-in-customer",
    templateResult: formatCustomer,
    templateSelection: formatCustomer,
  })
  .on("select2:select", function (e) {
    const data = e.params.data;
    customerBalance = parseFloat(data.balance);
    $(".customer-balance").html(
      customerBalance < 0
        ? `(GHS ${Math.abs(customerBalance).toFixed(2)})`
        : `GHS ${customerBalance.toFixed(2)}`
    );
    $(".customer").html(data.text);
    $("#acc-bal").removeClass("d-none");
    updateTotals();
  })
  .on("select2:unselect", function (e) {
    customerBalance = 0;
    $(".customer-balance").html(
      customerBalance < 0
        ? `(GHS ${Math.abs(customerBalance).toFixed(2)})`
        : `GHS ${customerBalance.toFixed(2)}`
    );
    $(".customer").html("walk-in-customer");
    $("#acc-bal").addClass("d-none");
    updateTotals();
  });

$(".select2-store").select2({
  placeholder: "Seach a store",
});
$(".select2-settlement")
  .select2({
    placeholder: "Select a settlement",
  })
  .on("select2:select select2:unselect", (e) => {
    if ($(".select2-settlement").val() === "cash") {
      $("input[name='paid']").prop("readonly", false);
      $("input[name='paid']").val("");
    } else {
      $("input[name='paid']").prop("readonly", true);
      $("input[name='paid']").val(0);
    }
    updateTotals();
  });
