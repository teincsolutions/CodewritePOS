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

table = $(".tr-items").DataTable({
  dom: "ftpi",
  length: 10,
  rowCallback: function (row, data, dispNum) {
    $("td:eq(0)", row).html(dispNum + 1);
    updateTotals();
  },
});

function updateRow(row) {
  let row1 = $(row).parents("tr").first();
  let data = table.row(row1).data(),
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
  table.draw();
}

function updateTotals() {
  var intVal = function (i) {
    return typeof i === "string"
      ? i.replace(/[\$,]/g, "") * 1
      : typeof i === "number"
      ? i
      : 0;
  };

  let grandTotal = 0,
    discountTotal = 0,
    taxTotal = 0,
    shipping = intVal($("[name='shipping']").val()),
    orderDiscount = intVal($("[name='discount']").val()),
    orderTax = intVal($("[name='tax']").val());
  for (let i = 0; i < table.rows().data().length; i++) {
    const row = $(`tr:eq(${i + 1})`, ".tr-items");
    (discountTotal += intVal($("td:eq(4)", row).html())),
      (taxTotal += intVal($("td:eq(5)", row).html())),
      (grandTotal += intVal($("td:eq(6)", row).html()));
  }
  discountTotal += orderDiscount;
  taxTotal += orderTax;
  grandTotal += shipping + orderDiscount;
  grandTotal -= orderDiscount;
  dueTotal = grandTotal - $("input[name='paid']").first().val();

  $(".grandTotal").html("GHS " + grandTotal.toFixed(2));
  $(".shippingTotal").html("GHS " + shipping.toFixed(2));
  $(".discountTotal").html("GHS " + discountTotal.toFixed(2));
  $(".orderTaxes").html("GHS " + taxTotal.toFixed(2));
  $(".dueTotal").html("GHS " + dueTotal.toFixed(2));
}

$(".tr-items").on("click", ".delete-set", function () {
  table.row($(this).parents("tr")).remove().draw();
});
//Increment Decrement value
$(".tr-items").on("click", ".inc.button", function () {
  var $this = $(this),
    $input = $this.prev("input"),
    $parent = $input.closest("div"),
    newValue = parseInt($input.val()) + 1;
  $parent.find(".inc").addClass("a" + newValue);
  if (newValue > 0) $input.val(newValue);
  newValue += newValue;
  updateRow(this);
});
$(".tr-items").on("click", ".dec.button", function () {
  var $this = $(this),
    $input = $(".quantity-field"),
    $parent = $input.closest("div"),
    newValue = parseInt($input.val()) - 1;
  $parent.find(".inc").addClass("a" + newValue);
  if (newValue > 0) $input.val(newValue);
  newValue += newValue;
  updateRow(this);
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

    $.get(`${baseUrl}/products/search`, searchParams, (d, s) => {
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
              ? `<span class="d-flex justify-content-between"><del><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></del>GHS ${item.unit_price}</span>`
              : `<span class="d-flex justify-content-between"><span><code>${item.sku}</code> ${item.name}(${item.unit.label}) - <i>${info}</i></span>GHS ${item.unit_price}</span>`;

          b.addEventListener("click", function (e) {
            if (instock <= item.min_qty) {
              Swal.fire({
                icon: "warning",
                title: "Short Stock Alert!",
                text: "You have (" + instock + ") stock left for " + item.name,
              });
            }
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
                                            ? `<a class="product-img"><img src="${baseUrl}/${item.image_uri}" alt="product"></a>`
                                            : '<a class="p-3"></a>'
                                        }
                                            <a target="_blank" href="${baseUrl}/products/${
              item.id
            }">${item.name}${store}</a></td>
                                        <td>
                                        <div class="increment-decrement">
                                            <div class="input-groups">
                                                <input type="button" value="-" class="button-minus dec button">
                                                <input type='hidden' name="items[${i}][product_id]" value="${
              item.id
            }">
                                                <input type="hidden" name="items[${i}][unit_price]" value="${
              item.unit_price
            }">
                                                <input type="hidden" name="items[${i}][tax_id]" value="${
              item.tax_id ? item.tax_id : ""
            }">
                                                <input type="hidden" name="items[${i}][store_id]" value="${$(
              ".select2-store"
            ).val()}">
                                                <input type="hidden" name="items[${i}][tax]" class="rtax" value="${
              (item.unit_price * (item.tax ? item.tax.rate : 0)) / 100
            }">
                                                <input type="hidden" name="items[${i}][discount]" class="rdiscount" value="${
              item?.discount
            }">
                                                <input type="hidden" name="items[${i}][subtotal]" class="rsubtotal" value="${
              item.unit_price -
              item?.discount +
              (item.unit_price * (item.tax ? item.tax.rate : 0.0)) / 100
            }">
                                                <input onblur="updateRow(this)" min="1" type="text" name="items[${i}][qty]" value="1" class="quantity-field" required>
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                        </td>
                                        <td>${item.unit_price}</td>
                                        <td data-discount="${item?.discount}">${
              item?.discount
            }</td>
                                        <td data-tax="${
                                          item.tax ? item.tax.rate : 0
                                        }">${parseFloat(
              (item.unit_price * (item.tax ? item.tax.rate : 0)) / 100
            ).toFixed(2)}</td>
                                        <td>${(
                                          item.unit_price -
                                          item?.discount +
                                          (item.unit_price *
                                            (item.tax ? item.tax.rate : 0.0)) /
                                            100
                                        ).toFixed(2)}</td>
                                        <td><a   href="javascript:void(0);" class="delete-set"><i class="fa text-danger fa-trash"></i></a></td>
                                    </tr>`;
            table.row.add($(row)).draw();
            table.draw();
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

autocomplete(document.getElementById("search-products"));

let form = $(".post-form");

form.validate({
  rules: {},
  messages: {},
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

form.on("submit", function (e) {
  e.preventDefault();

  if ($(this).valid() === true) {
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
          if (typeof d.input === "object") {
            if (d.input._method === "post") {
              window.location.reload();
            }
          }

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
});

$(".select2-customer").select2({
  placeholder: "Seach a customer",
  allowClear: true,
});
$(".select2-supplier").select2({
  placeholder: "Seach a supplier",
  allowClear: true,
});
$(".select2-store").select2({
  placeholder: "Seach a store",
});
