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

let prodIndex = 0;
(dueTotal = 0), (grandTotal = 0);

let form = $(".post-form");

form.validate({
  rules: {
    from_store_id: "required",
    to_store_id: "required",
    paid: "required",
  },
  messages: {
    from_store_id: "Choose a store transfer from",
    to_store_id: "Choose a store transfer to",
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
  let data = tableItems.row(row1).data(),
    qty = parseFloat(row1.find(".quantity-field").val()),
    price = parseFloat(data[3]),
    discount = parseFloat($("td:eq(4)", row1).data("discount")),
    tax = parseFloat($("td:eq(5)", row1).data("tax")),
    subtotal = qty * price + (tax / 100) * price * qty - qty * discount;

  $(".rtax", row1).val((tax / 100) * qty * price);
  $(".rsubtotal", row1).val(subtotal);
  $(".rdiscount", row1).val(qty * discount);
  $("td:eq(5)", row1).html(((tax / 100) * qty * price).toFixed(2));
  $("td:eq(4)", row1).html((qty * discount).toFixed(2));
  $("td:eq(6)", row1).html(subtotal.toFixed(2));
  updateTotals();
}

function updateTotals() {
  let discountTotal = 0,
    discountAmtTotal = 0;
  (taxTotal = 0),
    (taxAmtTotal = 0),
    (shipping = intVal($("[name='shipping']").val())),
    (orderDiscount = intVal($("[name='discount']").val()));
  grandTotal = 0;
  for (let i = 0; i < tableItems.rows().data().length; i++) {
    const row = $(`tr:eq(${i + 1})`, ".tr-items");
    (discountTotal += intVal($("td:eq(4)", row).html())),
      (taxTotal += intVal($("td:eq(5)", row).html())),
      (taxAmtTotal +=
        intVal($("td:eq(5)", row).html()) * intVal($("td:eq(3)", row).html())),
      (grandTotal += intVal($("td:eq(6)", row).html()));
  }
  $(".subTotal").html("GHS " + grandTotal.toFixed(2));
  discountAmtTotal = (orderDiscount / 100) * grandTotal;
  discountTotal += discountAmtTotal;
  grandTotal += shipping;
  grandTotal -= discountAmtTotal;
  dueTotal = grandTotal - $("input[name='paid']").first().val();

  $(".grandTotal").html("GHS " + grandTotal.toFixed(2));
  $("#transfers-total").val(grandTotal);
  $(".shippingTotal").html("GHS " + shipping.toFixed(2));
  $(".discountTotal").html("GHS " + discountAmtTotal.toFixed(2));
  $(".orderTaxes").html(
    "GHS " + taxAmtTotal.toFixed(2) + " (" + taxTotal.toFixed(2) + "%)"
  );
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

$(".tr-items").on("click", ".delete-set", function () {
  tableItems.row($(this).parents("tr")).remove().draw();
  updateTotals();
});
//Increment Decrement value
$(".tr-items").on("click", ".inc.button", function () {
  var $this = $(this),
    $input = $this.prev("input"),
    newValue = parseFloat($input.val()) + 1;
  if (newValue > 0 && newValue <= intVal($input.attr("max")))
    $input.val(newValue);
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
      searchParams.store_id = $(".select2-from-store").val();

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
            if ($("[name='from_store_id']").val() == "") {
              item.inventory.forEach((stock, i) => {
                instock += parseFloat(stock.instock);
              });
            } else {
              const storeId = $("[name='from_store_id']").val();
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
              ? `<span class="d-flex justify-content-between" style="z-index:1000"><del><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></del>GHS ${item.unit_price}</span>`
              : `<span class="d-flex justify-content-between" style="z-index:1000"><span><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></span>GHS ${item.unit_price}</span>`;

          b.addEventListener("click", function (e) {
            if (instock <= item.min_qty) {
              Swal.fire({
                icon: "warning",
                title: "Short Stock Alert!",
                text: "You have (" + instock + ") stock left for " + item.name,
              });
            }
            let store = "";
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
                                                <input type="hidden" name="items[${prodIndex}][unit_price]" value="${
              item.unit_price
            }">
            <input type="hidden" name="items[${prodIndex}][unit_cost]" value="${
              item.unit_cost
            }">
                                                <input type="hidden" name="items[${prodIndex}][tax_id]" value="${
              item.tax_id ? item.tax_id : ""
            }">
                                                <input type="hidden" name="items[${prodIndex}][from_store_id]" value="${$(
              "[name='from_store_id']"
            ).val()}">
            <input type="hidden" name="items[${prodIndex}][to_store_id]" value="${$(
              "[name='to_store_id']"
            ).val()}">
                                                <input type="hidden" name="items[${prodIndex}][tax]" class="rtax" value="${
              (item.unit_price * (item.tax ? item.tax : 0)) / 100
            }">
                                                <input type="hidden" name="items[${prodIndex}][discount]" class="rdiscount" value="${
              item?.discount
            }">
                                                <input type="hidden" name="items[${prodIndex}][subtotal]" class="rsubtotal" value="${
              item.unit_price -
              item?.discount +
              (item.unit_price * (item.tax ? item.tax : 0.0)) / 100
            }">
                                                <input type="button" value="-" class="button-minus dec button">
                                                <input onkeyup="updateItemRow(this)" min="0.1" step="any" max="${instock}" type="text" name="items[${prodIndex}][qty]" value="0" class="quantity-field" required>
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                        </td>
                                        <td>${item.unit_price}</td>
                                        <td data-discount="${
                                          item?.discount
                                        }" class="suffix-percent">${
              item?.discount
            }</td>
                                        <td data-tax="${
                                          item.tax ? item.tax : 0
                                        }">${parseFloat(
              (item.unit_price * (item.tax ? item.tax : 0)) / 100
            ).toFixed(2)}</td>
                                        <td>${(
                                          item.unit_price -
                                          item?.discount +
                                          (item.unit_price *
                                            (item.tax ? item.tax : 0.0)) /
                                            100
                                        ).toFixed(2)}</td>
                                        <td><a   href="javascript:void(0);" class="delete-set"><i class="fa text-danger fa-trash"></i></a></td>
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
            $(".select2-customer").val(null).trigger("select2:unselect");
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
        $("#submit").attr("disabled", false);
        Swal.fire({
          icon: "error",
          text: "Unable to submit form! Please try agian.",
        });
      },
    });
  }
});

$(".select2-store").select2({
  placeholder: "Seach a store",
});
