prodIndex = 1;
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

    $(".select2-product").each((i, sel) => {
      if (!$(sel).hasClass("select2-hidden-accessible")) {
        $(sel)
          .select2({
            placeholder: "Search a product",
            ajax: {
              url: `${baseUrl}products/select2`,
              dataType: "json",
              data: function (params) {
                params.filter = {};
                params.exclude = [];

                $(".select2-product").each((i, sel) => {
                  if ($(sel).val() !== "") params.exclude.push($(sel).val());
                });
                return params;
              },
            },
          })
          .on("select2:select select2:unselect", (e) => {
            console.log(e);
            updateItemRow(this);
          });
      }
    });
  },
  initComplete: function (settings, json) {
    initCompleted = true;
  },
});


function checkout() {
  return true;
}

function updateItemRow(row) {
  let row1 = $(row).parents("tr").first(),
    qty = parseFloat(row1.find(".quantity-field").val()),
    fromProduct = row1.find(".from-product").first(),
    toProduct = row1.find(".to-product").first(),
    data1 =
      typeof fromProduct.select2("data") === "undefined"
        ? {}
        : fromProduct.select2("data")[0],
    data2 =
      typeof toProduct.select2("data") === "undefined"
        ? {}
        : toProduct.select2("data")[0],
    from_unit_qty = parseFloat(data1.unit_qty ?? 1),
    to_unit_qty = parseFloat(data2.unit_qty ?? 1);
  $("td:eq(4)", row1).html((qty * (from_unit_qty / to_unit_qty)).toFixed(2));
  $(".to_unit_qty", row1).val((qty * (from_unit_qty / to_unit_qty)).toFixed(2));

  updateTotals();
}

$(".tr-items").on("click", ".delete-set", function () {
  if (tableItems.rows().data().length > 1)
    tableItems.row($(this).parents("tr")).remove().draw();
});
$(".tr-items").on("click", ".add-set", function () {
  let row = `<tr>
  <td></td>
  <td>
      <select name="items[${prodIndex}][from_product_id]" class="select2-product from-product form-control">
          <option value=""></option>
      </select>
  </td>
  <td>
      <div class="increment-decrement">
          <div class="input-groups">
              <input type="hidden" name="items[${prodIndex}][to_unit_qty]" value="0" class="to_unit_qty" required>
              <input type="button" value="-" class="button-minus dec button">
              <input onkeyup="updateItemRow(this)" min=".1" type="text" name="items[${prodIndex}][from_unit_qty]" value="0" class="quantity-field" required>
              <input type="button" value="+" class="button-plus inc button">
          </div>
      </div>
  </td>
  <td>
      <select name="items[${prodIndex}][to_product_id]" class="select2-product to-product form-control">
          <option value=""></option>
      </select>
  </td>
  <td>0.00</td>
  <td>
      <a href="javascript:void(0);" class="add-set btn btn-info text-white"><i class="fa fa-plus"></i></a>
      <a href="javascript:void(0);" class="delete-set btn btn-danger text-white"><i class="fa fa-times"></i></a>
  </td>
</tr> `;
  tableItems.row.add($(row)).draw();
  tableItems.draw();
  prodIndex++;
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
  if (newValue > 0) $input.val(newValue);
  updateItemRow(this);
});

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
            location.reload();
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
