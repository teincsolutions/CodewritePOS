let table;

$(function () {
  table = $("#dt-containers").DataTable({
    ajax: {
      url: baseUrl + "containers/datatable",
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
    serverSide: true,
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
      { data: "name", name: "containers.name" },
      { data: "barcode", name: "containers.barcode" },
      { data: "sku", name: "containers.sku" },
      {
        data: "brand",
        name: "containers.brand_id",
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
        name: "containers.category_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}categories/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      { data: "unit_cost", name: "containers.unit_cost" },
      { data: "unit_price", name: "containers.unit_price" },
      {
        data: "unit",
        name: "containers.unit_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data ? data.label : "";
          }
          return data ? data.id : null;
        },
      },
      { data: "instock" },
      {
        data: "discontinued",
        name: "containers.discontinued",
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
              }},'${baseUrl}containers')" class="checkmarks"></span></label>`;
            }
          }
          return data;
        },
      },
      {
        data: "user",
        name: "containers.user_id",
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
        name: "containers.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
                        <a class="me-3" href="${baseUrl}containers/${row.id}"><i class="fa fa-eye fa-lg"></i></a>
                        <a class="me-3" href="${baseUrl}containers/edit/${row.id}"><i class="fa fa-edit fa-lg"></i></a>
                        <a class="text-danger" href="javascript:void(0);" onclick="deleteRow(table, ${row.id}, '${baseUrl}containers')"><i class="fa fa-trash fa-lg"></i></a>
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
});
