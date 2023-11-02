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
      name: "concat(products.name,' ',categories.name)",
      searchable: "true",
    },
    {
      name: "concat(products.name,' ',brands.name)",
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
adjustmentItemIds = [];
(dueTotal = 0), (grandTotal = 0);

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
  let data = tableItems.row(row1).data(),
    price = parseFloat(data[2]),
    instockQty = parseFloat(data[3]),
    qty = $(".quantity-field", row1).val(),
    diff = qty - instockQty,
    subtotal = diff * price;
  $("td:eq(5)", row1).html(diff.toFixed(2));
  $("td:eq(6)", row1).html(subtotal.toFixed(2));
  $(".rsubtotal", row1).val(subtotal);

  tableItems.draw();
}

function updateTotals() {
  grandTotal = 0;
  for (let i = 0; i < tableItems.rows().data().length; i++) {
    const row = $(`tr:eq(${i + 1})`, ".tr-items");
    grandTotal += intVal($("td:eq(6)", row).html());
  }
  $(".grandTotal").html("GHS " + grandTotal.toFixed(2));
  $("#adjustment-total").val(grandTotal);
}

function checkout() {
  return true;
}

$(".tr-items").on("click", ".delete-set", function () {
  let id = $(this).data("item-id");
  adjustmentItemIds = adjustmentItemIds.filter((item) => item != id);
  tableItems.row($(this).parents("tr")).remove().draw();
  updateTotals();
});
//Increment Decrement value
$(".tr-items").on("click", ".inc.button", function () {
  var $this = $(this),
    $input = $this.prev("input"),
    newValue = parseFloat($input.val()) + 1;
  $input.val(newValue);
  updateItemRow(this);
});
$(".tr-items").on("click", ".dec.button", function () {
  var $this = $(this),
    $input = $this.next("input"),
    newValue = parseFloat($input.val()) - 1;
  $input.val(newValue);
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

    if (Settings.ProductDiffForStore === "yes")
      searchParams.store_id = $(".select2-store").val();

    $.get(`${baseUrl}products/search`, searchParams, (d, s) => {
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
          if (adjustmentItemIds.includes(item.id)) return;

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
            }">${item.name}(${item.unit.label})</a></td>
            <td>${parseFloat(item.unit_cost).toFixed(2)}</td>
            <td>${parseFloat(instock).toFixed(2)}</td>
                                        <td>
                                        <div class="increment-decrement">
                                            <div class="input-groups">
                                             
                                                <input type='hidden' name="items[${prodIndex}][product_id]" value="${
              item.id
            }">
                                              <input type="hidden" name="items[${prodIndex}][instock_qty]" value="${instock}" required>
                                                <input type="hidden" name="items[${prodIndex}][unit_cost]" value="${
              item.unit_cost
            }" calss="runit_cost">
                                                <input type="hidden" name="items[${prodIndex}][store_id]" value="${$(
              ".select2-store"
            ).val()}">
                                <input type="hidden" name="items[${prodIndex}][subtotal]" class="rsubtotal" value="${(
              item.unit_cost * instock
            ).toFixed(2)}">
            <input type="hidden" name="items[${prodIndex}][diff]" class="diff" value="0.00">
                                                <input type="button" value="-" class="button-minus dec button">
                                                <input onblur="updateItemRow(this)" type="text" name="items[${prodIndex}][qty]" value="${instock}" class="quantity-field rqty" required>
                                                <input type="button" value="+" class="button-plus inc button">
                                            </div>
                                        </div>
                                        </td>
                                        <td>0.00</td>
                                        <td>0.00</td>
                                        <td><a   href="javascript:void(0);" class="delete-set" data-item-id="${
                                          item.id
                                        }"><i class="fa text-danger fa-trash"></i></a></td>
                                    </tr>`;
            tableItems.row.add($(row)).draw();
            adjustmentItemIds.push(item.id);
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

autocomplete(document.getElementById("search-products"));

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
            adjustmentItemIds = [];
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

$(".select2-store").select2({
  placeholder: "Seach a store",
});
