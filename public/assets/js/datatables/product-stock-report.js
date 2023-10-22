let tableSale, tablePurchase,table3, table4,table5,table6;

$(function () {
  table6 = $("#dt-adjustments").DataTable({
    ajax: {
      url: baseUrl + "adjustments/reports/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#filter_inputs6 input, #filter_inputs6 select");
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
          } else if (typeof field.attr("name") !== "undefined") {
            filter[field.attr("name")] = field.val();
          }
        });

        params.date_range_column = "stock_adjustment_date";
        params.date_from = $("#date-from").val();
        params.date_to = $("#date-to").val();

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
      { data: "adj_date", name: "stock_adjustments.adj_date" },
      { data: "invoice", name: "stock_adjustments.invoice" },
      {
        data: "store",
        name: "stock_adjustments.store_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}stores/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : null;
          return data ? data.id : null;
        },
      },
      {
        data: "qtyInstock",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
      {
        data: "qty",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
      {
        data: "diffQty",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#adjustments-tabs .dataTables_filter").appendTo("#adjustments-tabs #tableSearch");
      $("#adjustments-tabs .dataTables_filter").appendTo("#adjustments-tabs .search-input");
      if ($('[data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#adjustments-tabs #select-all";
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
  table6.buttons().container().appendTo("#adjustments-tabs .wordset");

  $("#adjustments-tabs .filter").on("click select2:select select2:unselect", function (params) {
    table6.ajax.reload();
  });

  table5 = $("#dt-transfers").DataTable({
    ajax: {
      url: baseUrl + "/transfers/products/reports/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#filter_inputs5 input, #filter_inputs5 select");
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
      { data: "transfer_date", name: "product_transfers.transfer_date" },
      { data: "invoice", name: "product_transfers.invoice" },
      {
        data: "fromStore",
        name: "product_transfers.from_store_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}stores/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          return data ? data.id : null;
        },
      },
      {
        data: "toStore",
        name: "product_transfers.to_store_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}stores/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          return data ? data.id : null;
        },
      },
      {
        data: "qty",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#transfers-tab .dataTables_filter").appendTo("#transfers-tab #tableSearch");
      $("#transfers-tab .dataTables_filter").appendTo("#transfers-tab .search-input");
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
    order: [[2, "desc"]],
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
  table5.buttons().container().appendTo("#transfers-tab .wordset");

  $("#transfers-tab .filter").on("click select2:select select2:unselect", function (params) {
    table5.ajax.reload();
  });
  
  table3 = $("#dt-sales-returns").DataTable({
    ajax: {
      url: baseUrl + "sales/returns/stocks/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#sales-returns-tab #filter_inputs input, #sales-returns-tab #filter_inputs select"
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
      { data: "return_date", name: "sales_returns.return_date" },
      { data: "invoice", name: "sales_returns.invoice" },
      {
        data: "customer",
        name: "sales.customer_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}customers/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "walk-in-customer";
          return data ? data.id : null;
        },
      },
       {
        data: "store",
        name: "sales.store_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}stores/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      {
        data: "qty",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#sales-returns-tab .dataTables_filter").appendTo(
        "#sales-returns-tab #tableSearch"
      );
      $("#sales-returns-tab .dataTables_filter").appendTo(
        "#sales-returns-tab .search-input"
      );
      if ($('#sales-returns-tab [data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('#sales-returns-tab [data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#sales-returns-tab #select-all";
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
    order: [[2, "desc"]],
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
  table3.buttons().container().appendTo("#sales-returns-tab .wordset");

  $("#sales-returns-tab .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table3.ajax.reload();
    }
  );

  table4 = $("#dt-purchase-returns").DataTable({
    ajax: {
      url: baseUrl + "purchases/returns/stocks/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#purchase-returns-tab #filter_inputs input, #purchase-returns-tab #filter_inputs select"
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
      { data: "return_date", name: "purchase_returns.return_date" },
      { data: "invoice", name: "purchase_returns.invoice" },
      {
        data: "supplier",
        name: "purchases.supplier_id",
        render: function (data, type, row) {
          if (type === "display")
            return `<a target="_blank" href="${baseUrl}suppliers/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`;
          return data ? data.id : null;
        },
      },
       {
        data: "store",
        name: "purchases.store_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}stores/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      {
        data: "qty",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#purchase-returns-tab .dataTables_filter").appendTo(
        "#purchase-returns-tab #tableSearch"
      );
      $("#purchase-returns-tab .dataTables_filter").appendTo(
        "#purchase-returns-tab .search-input"
      );
      if ($('#purchase-returns-tab [data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('#purchase-returns-tab [data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#purchase-returns-tab #select-all";
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
    order: [[2, "desc"]],
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
  table4.buttons().container().appendTo("#purchase-returns-tab .wordset");

  $("#purchase-returns-tab .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table4.ajax.reload();
    }
  );

  tableSale = $("#dt-sales").DataTable({
    ajax: {
      url: baseUrl + "sales/stocks/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#sales-tab #filter_inputs input, #sales-tab #filter_inputs select");
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
        params.fields = filter;
        params.product_id = $("input[name='product_id']").val();
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
      {
        data: "sales_date",
        name: "sales_date",
      },
      { data: "invoice", name: "sales.invoice" },
      {
        data: "customer",
        name: "sales.customer_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}customers/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "walk-in-customer";
          return data ? data.id : null;
        },
      },
       {
        data: "store",
        name: "sales.store_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}stores/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      {
        data: "qty",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#sales-tab .dataTables_filter").appendTo("#sales-tab #tableSearch");
      $("#sales-tab .dataTables_filter").appendTo("#sales-tab .search-input");
      if ($('[data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#sales-tab #select-all";
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
    order: [[2, "desc"]],
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
  tableSale.buttons().container().appendTo("#sales-tab .wordset");

  $("#sales-tab .filter").on("click select2:select select2:unselect", function (params) {
    tableSale.ajax.reload();
  });

  tablePurchase = $("#dt-purchases").DataTable({
    ajax: {
      url: baseUrl + "purchases/stocks/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#filter_inputs1 input,  #filter_inputs1 select");
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
        params.fields = filter;
        params.product_id = $("input[name='product_id']").val();
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
      {
        data: "purchase_date",
        name: "purchase_date",
      },
      { data: "invoice", name: "purchases.invoice" },
      {
        data: "supplier",
        name: "purchases.supplier_id",
        render: function (data, type, row) {
          if (type === "display")
            return `<a target="_blank" href="${baseUrl}suppliers/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`;
          return data ? data.id : null;
        },
      },
       {
        data: "store",
        name: "purchases.store_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}stores/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      {
        data: "qty",
        render: function (data) {
          return data ? data : "0.00";
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#purchases-tab .dataTables_filter").appendTo("#purchases-tab #tableSearch");
      $("#purchases-tab .dataTables_filter").appendTo("#purchases-tab .search-input");
      if ($('[data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = " #select-all";
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
    order: [[2, "desc"]],
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
  tablePurchase.buttons().container().appendTo("#purchases-tab .wordset");

  $("#purchases-tab .filter").on("click select2:select select2:unselect", function (params) {
    tablePurchase.ajax.reload();
  });



  $(".select2-store").select2({
    placeholder: "Seach a store",
  });
});
