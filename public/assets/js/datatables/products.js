let table;

$(function () {
  table = $("#productstable").DataTable({
    ajax: {
      url: baseUrl + "products/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#filter_inputs input, #filter_inputs select");
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
            filter[field.attr("name")] = field.val();
          }
        });
        params.store_id = $(".select2-store").val();

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
        data: null,
        render: function (data, type, row) {
          return null;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          const desc = data.description!==""?` - ${data.description} `:"";
          return `${data.name} ${desc}(${data.unit.label})`;
        },
      },
      { data: "barcode", name: "products.barcode" },
      { data: "sku", name: "products.sku" },
      {
        data: "brand",
        name: "products.brand_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}brands/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      {
        data: "category",
        name: "products.category_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}categories/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      { data: "unit_cost", name: "products.unit_cost" },
      { data: "unit_price", name: "products.unit_price" },
      { data: "instock" },
      {
        data: "discontinued",
        name: "products.discontinued",
        render: function (data, type, row) {
          if (type === "display") {
            if (Settings.ProductDiffForStore === "yes") {
              return `<label class="checkboxs"><input disabled type="checkbox" ${
                ["", "checked"][data]
              }><span class="checkmarks"></span></label>`;
            } else {
              return `<label class="checkboxs"><input type="checkbox"
                        ${
                          ["", "checked"][data]
                        }><span onclick="updateRow(table,{id:${
                row.id
              },discontinued:${
                data == 0 ? 1 : 0
              }},'${baseUrl}products')" class="checkmarks"></span></label>`;
            }
          }
          return data;
        },
      },
      {
        data: "user",
        name: "products.user_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}users/${data.id}" class="btn btn-link btn-sm">${data.firstname} ${data.lastname}</a>`
              : null;
          return data ? data.id : null;
        },
      },
      {
        data: "id",
        name: "products.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
                        <a class="me-3" href="${baseUrl}products/${row.id}"><i class="fa fa-eye fa-lg"></i></a>
                        <a class="me-3" href="${baseUrl}products/edit/${row.id}"><i class="fa fa-edit fa-lg"></i></a>
                        <a class="text-danger" href="javascript:void(0);" onclick="deleteRow(table, ${row.id}, '${baseUrl}products')"><i class="fa fa-trash fa-lg"></i></a>
                    </div>`;
          }
          return data;
        },
      },
    ],
    initComplete: (settings, json) => {
      $(".dataTables_filter").appendTo("#tableSearch");
      $(".dataTables_filter").appendTo(".search-input");
      if ($('[data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#select-all";
      var checkboxItem = ":checkbox";

      $(selectAllItems).click(function () {
        if (this.checked) {
          $(checkboxItem).each(function () {
            this.checked = true;
          });
        } else {
          $(checkboxItem).each(function () {
            this.checked = false;
          });
        }
      });
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
    },
    order: [[0, "desc"]],
    columnDefs: [
      {
        orderable: false,
        className: "select-checkbox",
        targets: 0,
      },
    ],
    select: {
      style: "multi",
      selector: "td:first-child",
    },
  });
  table.buttons().container().appendTo(".wordset");

  $(".filter").on("click select2:select select2:unselect", function (params) {
    table.ajax.reload();
  });

  $(".filter-clear").on("click", function (params) {
    $("#date-from,#date-to").val("");
    table.ajax.reload();
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
