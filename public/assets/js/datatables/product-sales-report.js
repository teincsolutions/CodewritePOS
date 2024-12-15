let table;

$(function () {
  table = $("#dt-product-sales").DataTable({
    ajax: {
      url: baseUrl + "product-sales/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#filter_inputs0 input, #filter_inputs0 select");
        filterForm.each((i, item) => {
          field = $(item);
          if (field.prop("tagName") === "SELECT") {
            if (
              typeof field.children("option:selected").val() !== "undefined" &&
              field.children("option:selected").val() != ""
            )
              filter[field.attr("name")] = field
                .children("option:selected")
                .val();
          } else {
            if (filter[field.attr("name")])
              filter[field.attr("name")] = field.val();
          }
        });
        params.store_id = $(".select2-store").val();
        params.date_range_column = "sales_date";
        params.date_from = $("#date-from").val();
        params.date_to = $("#date-to").val();
        
        params.fields = filter;
      },
    },
    processing: true,
    bFilter: true,
    dom: "fBtlpi",
    buttons: [
      {
        extend: "print",
        text: '<a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fa text-secondary fa-print"></i></a>',
        className: "btn btn-light",
      },
      "spacer",
      {
        extend: "excel",
        text: '<a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i class="fa text-success fa-file-excel"></i></a>',
        className: "btn btn-light",
      },
      "spacer",
      {
        extend: "pdf",
        text: '<a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><i class="fa text-danger fa-file-pdf"></i></a>',
        className: "btn btn-light",
      },
    ],
    pagingType: "numbers",
    ordering: true,
    language: {
      search: " ",
      sLengthMenu: "_MENU_",
      searchPlaceholder: "Search...",
      info: "_START_ - _END_ of _TOTAL_ items",
    },
    columns: [
      {
        data: "product_id",
      },
      {
        data: "product_name",
      },
      { data: "unit_price" },
      { data: "qty" },
      { data: "discount" },
      { data: "subtotal" },
    ],
    initComplete: (settings, json) => {
      $(".dataTables_filter").appendTo("#tableSearch");
      $(".dataTables_filter").appendTo(".search-input");
    },
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      // Remove the formatting to get integer data for summation
      var intVal = function (i) {
        return typeof i === "string"
          ? i.replace(/[\$,]/g, "") * 1
          : typeof i === "number"
          ? i
          : 0;
      };

      // Total over this page
      pageTotal = api
        .column(5, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);
      // Update footer
      $(api.column(5).footer()).html(" " + pageTotal.toFixed(2));
    },
    order: [[0, "desc"]],
  });
  table.buttons().container().appendTo(".wordset");

  $(".filter").on("click select2:select select2:unselect", function (params) {
    table.ajax.reload();
  });

  $(".filter-clear").on("click", function (params) {
    $("#date-from,#date-to").val("");
    table.ajax.reload();
  });
  $(".select2-customer").select2({
    ajax: {
      url: `${baseUrl}customers/select2`,
      dataType: "json",
    },
    allowClear: true,
    placeholder: "Seach a customer",
  });

  $(".select2-store").select2({
    placeholder: "Seach a store",
  });

  $(".select2-category").select2({
    placeholder: "Choose a category",
    allowClear: true,
  });
  $(".select2-brand").select2({
    placeholder: "Choose a brand",
    allowClear: true,
  });
  $(".select2-unit").select2({
    placeholder: "Choose a unit",
    allowClear: true,
  });
});
