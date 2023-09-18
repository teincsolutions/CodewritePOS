let table;

$(function () {
  table = $("#dt-containers").DataTable({
    ajax: {
      url: baseUrl + "containers/customers/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#containers-tab #filter_inputs input, #containers-tab #filter_inputs select"
        );
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
        data: "container",
        render: function (data, type, row) {
          return data
            ? `<a target="_blank" href="${baseUrl}containers/${data.id}" class="btn btn-link btn-sm">${data.sku} ${data.name} (${data.unit.label})</a>`
            : "";
        },
      },
      {
        data: "container",
        render: function (data, type, row) {
          return data?.unit_price;
        },
      },
      { data: "instock" },
      {
        data: "total_amount",
        render: function (data, type, row) {
          return parseFloat(data).toFixed(2);
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#containers-tab .dataTables_filter").appendTo("#containers-tab #tableSearch");
      $("#containers-tab .dataTables_filter").appendTo("#containers-tab .search-input");
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
  table.buttons().container().appendTo("#containers-tab .wordset");

  $("#containers-tab .filter").on("click select2:select select2:unselect", function (params) {
    table.ajax.reload();
  });
});
