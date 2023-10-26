let table1, table2, table3, paymentTable;

$(function () {
  // table1
  table1 = $("#dt-sales").DataTable({
    ajax: {
      url: baseUrl + "sales/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#bills-tab #filter_inputs input,#bills-tab #filter_inputs select"
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
          } else if (typeof field.attr("name") !== "undefined") {
            filter[field.attr("name")] = field.val();
          }
        });
        filter["store_id"] = $(".select2-store").val();
        params.date_range_column = "sales_date";
        params.date_from = $("#bills-tab #date-from").val();
        params.date_to = $("#bills-tab  #date-to").val();
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
      { data: "sales_date", name: "sales.sales_date" },
      { data: "invoice", name: "sales.invoice" },
      {
        data: "order_status",
        name: "sales.order_status",
        render: function (data, type) {
          if (type === "display") {
            const badges = {
              completed: "bg-lightgreen",
              pending: "bg-lightred",
            };
            return `<span class="badges ${badges[data]}">${data}</span>`;
          }

          return data;
        },
      },
      {
        data: "payment_status",
        name: "sales.payment_status",
        render: function (data, type) {
          if (type === "display") {
            const badges = {
              paid: "bg-lightgreen",
              due: "bg-lightred",
            };
            return `<span class="badges ${badges[data]}">${data}</span>`;
          }

          return data;
        },
      },
      {
        data: "total_amount",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "paid",
        name: "sales.paid",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          due = data.total_amount - data.paid;
          return due < 0
            ? `(GHS ${Math.abs(due).toFixed(2)})`
            : `GHS ${due.toFixed(2)}`;
        },
      },
      {
        data: "user",
        name: "sales.user_id",
        render: function (data, type, row) {
          if (type === "display")
            return data ? `${data.firstname} ${data.lastname}` : null;
          return data ? data.id : null;
        },
      },
      {
        data: "id",
        name: "sales.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
                        <a target="_blank" href="${baseUrl}sales/${data}" class="me-3"><i class="fa fa-eye fa-lg"></i></a>
                        ${
                          row.order_status === "completed"
                            ? `<a href="${baseUrl}sales/returns/create?invoice=${row.invoice}" class="me-3"><i class="fa fa-reply fa-lg"></i></a>`
                            : ""
                        }
                        <a ${
                          Settings.AllowDeleteSales === "yes" ? "" : "hidden"
                        } class="text-danger" href="javascript:void(0);" onclick="deleteRow(table1, ${
              row.id
            }, '${baseUrl}sales',table2,table3)"><i class="fa fa-trash fa-lg"></i></a>
                    </div>`;
          }
          return data;
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#bills-tab .dataTables_filter").appendTo("#bills-tab #tableSearch");
      $("#bills-tab .dataTables_filter").appendTo("#bills-tab .search-input");
      if ($('#bills-tab [data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('#bills-tab [data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#bills-tab #select-all";
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

      // Total over this page
      pageTotal = api
        .column(5, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Update footer
      $(api.column(5).footer()).html("GHS " + pageTotal.toFixed(2));

      // Total over this page
      pageTotal = api
        .column(6, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Update footer
      $(api.column(6).footer()).html("GHS " + pageTotal.toFixed(2));
      // Total over this page
      pageTotal = api
        .column(7, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.total_amount - b.paid);
        }, 0);

      // Update footer
      $(api.column(7).footer()).html("GHS " + pageTotal.toFixed(2));
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
  table1.buttons().container().appendTo("#bills-tab .wordset");

  $("#bills-tab .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table1.ajax.reload();
    }
  );
  // table2
  table2 = $("#dt-ledger").DataTable({
    ajax: {
      url: baseUrl + "reports/ledgers/customers/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#ledger-tab #filter_inputs input, #ledger-tab #filter_inputs select"
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
          } else if (typeof field.attr("name") !== "undefined") {
            filter[field.attr("name")] = field.val();
          }
        });
        filter["store_id"] = $(".select2-store").val();
        params.date_range_column = "tdate";
        params.date_from = $("#ledger-tab #date-from").val();
        params.date_to = $("#ledger-tab #date-to").val();
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
      { data: "id", name: "customer_ledgers.id" },
      { data: "tdate", name: "customer_ledgers.tdate" },
      { data: "ledger_type", name: "customer_ledgers.ledger_type" },
      {
        data: "total_due",
        render: function (data) {
          return data < 0
            ? `(GHS ${Math.abs(parseFloat(data)).toFixed(2)})`
            : `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "total_debit",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "total_credit",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "total_balance",
        render: function (data) {
          return data < 0
            ? `(GHS ${Math.abs(data).toFixed(2)})`
            : `GHS ${data.toFixed(2)}`;
        },
      },
      { data: "payment_type", name: "customer_ledgers.payment_type" },
      {
        data: "user",
        name: "customer_ledgers.user_id",
        render: function (data, type, row) {
          return data ? `${data.firstname} ${data.lastname}` : "";
        },
      },
      {
        data: "id",
        name: "customer_ledgers.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
              <a  href="javascript:void(0);" class="me-3" onclick="printReceiptRow(this)"><i class="fa fa-print fa-lg"></i></a>
              <a  href="javascript:void(0);" onclick="viewCusPayments(table2,this,paymentTable)"><i class="fa fa-eye fa-lg"></i></a>
              </div>`;
          }
          return data;
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#ledger-tab .dataTables_filter").appendTo("#ledger-tab #tableSearch");
      $("#ledger-tab .dataTables_filter").appendTo("#ledger-tab .search-input");
      if ($('#ledger-tab [data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('#ledger-tab [data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#ledger-tab #select-all";
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
    order: [],
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
  table2.buttons().container().appendTo("#ledger-tab .wordset");

  $("#ledger-tab .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table2.ajax.reload();
    }
  );
  // table3
  table3 = $("#dt-returns").DataTable({
    ajax: {
      url: baseUrl + "sales/returns/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#returns-tab #filter_inputs input, #returns-tab #filter_inputs select"
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
          } else if (typeof field.attr("name") !== "undefined") {
            filter[field.attr("name")] = field.val();
          }
        });
        filter["sales.store_id"] = $(".select2-store").val();
        params.date_range_column = "return_date";
        params.date_from = $("#returns-tab #date-from").val();
        params.date_to = $("#returns-tab #date-to").val();
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
        data: "order_status",
        name: "sales_returns.order_status",
        render: function (data, type) {
          if (type === "display") {
            const badges = {
              completed: "bg-lightgreen",
              pending: "bg-lightred",
            };
            return `<span class="badges ${badges[data]}">${data}</span>`;
          }

          return data;
        },
      },
      {
        data: "payment_status",
        name: "sales_returns.payment_status",
        render: function (data, type) {
          if (type === "display") {
            const badges = {
              paid: "bg-lightgreen",
              due: "bg-lightred",
            };
            return `<span class="badges ${badges[data]}">${data}</span>`;
          }

          return data;
        },
      },
      {
        data: "total_amount",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "paid",
        name: "sales_returns.paid",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "user",
        name: "sales_returns.user_id",
        render: function (data, type, row) {
          if (type === "display")
            return data ? `${data.firstname} ${data.lastname}` : null;
          return data ? data.id : null;
        },
      },
      {
        data: "id",
        name: "sales_returns.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
                          <a target="_blank" href="${baseUrl}sales/returns/${data}" class="me-3"><i class="fa fa-eye fa-lg"></i></a>
                          <a  ${
                            Settings.AllowDeleteSalesReturns === "yes"
                              ? ""
                              : "hidden"
                          } class="text-danger" href="javascript:void(0);" onclick="deleteRow(table3, ${data}, '${baseUrl}sales/returns',table2,table1)"><i class="fa fa-trash fa-lg"></i></a>
                      </div>`;
          }
          return data;
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#returns-tab .dataTables_filter").appendTo(
        "#returns-tab #tableSearch"
      );
      $("#returns-tab .dataTables_filter").appendTo(
        "#returns-tab .search-input"
      );
      if ($('#returns-tab [data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('#returns-tab [data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#returns-tab #select-all";
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

      // Total over this page
      pageTotal = api
        .column(5, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Update footer
      $(api.column(5).footer()).html("GHS " + pageTotal.toFixed(2));

      // Total over this page
      pageTotal = api
        .column(6, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Update footer
      $(api.column(6).footer()).html("GHS " + pageTotal.toFixed(2));
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
  table3.buttons().container().appendTo("#returns-tab .wordset");

  $("#returns-tab .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table3.ajax.reload();
    }
  );

  // paymentTable
  paymentTable = $("#dt-customer-payments").DataTable({
    ajax: {
      url: baseUrl + "customers/ledger/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#input_filter input");
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
        filter["store_id"] = $(".select2-store").val();
        params.date_range_column = "created_at";
        params.date_from = $("#input_filter input[name='created_at']").val();
        params.date_to = $("#input_filter input[name='created_at']").val();

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
      { data: "id", name: "customer_ledgers.id" },
      { data: "tdate", name: "customer_ledgers.tdate" },
      {
        data: null,
        render: function (data, type, row) {
          return data.ledger_type === "sales"
            ? `<a target="_blank" href="${baseUrl}sales/${data.sale_id}" class="btn btn-link btn-sm">${data.sale.invoice}</a>`
            : `<a target="_blank" href="${baseUrl}sales/returns/${data.sales_return_id}" class="btn btn-link btn-sm">${data.sales_return.invoice}(RF: ${data.sale.invoice})</a>`;
        },
      },
      { data: "ledger_type", name: "customer_ledgers.ledger_type" },
      {
        data: "debit",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "credit",
        render: function (data) {
          return `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "balance",
        render: function (data) {
          return data < 0
            ? `(GHS ${Math.abs(data).toFixed(2)})`
            : `GHS ${data.toFixed(2)}`;
        },
      },
      { data: "payment_type", name: "customer_ledgers.payment_type" },
      {
        data: "user",
        name: "customer_ledgers.user_id",
        render: function (data, type, row) {
          return data ? `${data.firstname} ${data.lastname}` : "";
        },
      },
      {
        data: "id",
        name: "customer_ledgers.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
            <a  href="javascript:void(0);" class="me-3" onclick="printReceiptRow(this)"><i class="fa fa-print fa-lg"></i></a>
            <a  href="javascript:void(0);" class="me-3" onclick="editRow('#edit-payment',{id:${
              row.id
            },tdate:'${moment(row.tdate).format("DD-MM-YYYY")}',sale_id:${
              row.sale_id
            },payment_type:'${row.payment_type}',debit:${row.debit},credit:${
              row.credit
            }},{text:'${row.sale.invoice} (${row.customer.name} - GHS ${
              row.sale.total_amount
            })',id:${
              row.sale.id
            },name:'sale_id'})"><i class="fa fa-edit fa-lg"></i></a>
                        <a class="text-danger" href="javascript:void(0);" onclick="deleteRow(paymentTable, ${
                          row.id
                        }, '${baseUrl}customers/ledger', table1,table2)"><i class="fa fa-trash fa-lg"></i></a>
                    </div>`;
          }
          return data;
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#view-payments .dataTables_filter").appendTo(
        "#view-payments #tableSearch"
      );
      $("#view-payments .dataTables_filter").appendTo(
        "#view-payments .search-input"
      );
      if ($('#view-payments [data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('#view-payments [data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#view-payments #select-all";
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
    order: [[1, "desc"]],
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
  paymentTable.buttons().container().appendTo("#view-payments .wordset");

  $("#view-payments .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      paymentTable.ajax.reload();
    }
  );


  // table4
  table4 = $("#dt-containers").DataTable({
    ajax: {
      url: baseUrl + "reports/customers/containers/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#containers-tab #filter_inputs4 input, #containers-tab #filter_inputs4 select"
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
      {
        data: "container",
        name: "container_id",
        render: function (data, type, row) {
          if (type === "display") {
            return data
              ? `<a target="_blank" href="${baseUrl}containers/${data.id}" class="btn btn-link btn-sm"><span class="text-warning">${data.sku??''}</span> ${data.name}</a>`
              : "";
          }
          return data ? data.id : null;
        },
      },
      { data: "unit_price"},
      { data: "qty_in"},
      { data: "qty_out"},
      { data: "qty_bal"},
      {
        data: "total_bal",
        render: function (data) {
          return data < 0
            ? `(GHS ${Math.abs(parseFloat(data)).toFixed(2)})`
            : `GHS ${parseFloat(data).toFixed(2)}`;
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#containers-tab .dataTables_filter").appendTo("#containers-tab #tableSearch");
      $("#containers-tab .dataTables_filter").appendTo("#containers-tab .search-input");
      if ($('#containers-tab [data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(
          document.querySelectorAll('#containers-tab [data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }

      var selectAllItems = "#containers-tab #select-all";
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
    order: [[1, "desc"]],
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
  table4.buttons().container().appendTo("#containers-tab .wordset");

  $("#containers-tab .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table4.ajax.reload();
    }
  );

  let form3 = $("#add-payment");
  form3.validate({
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

  form3.on("submit", function (e) {
    e.preventDefault();

    if ($(this).valid() === true) {

      Swal.fire({
        title: "Please wait !",
        allowOutsideClick: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });

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
            form3.trigger("reset");
            form3.find("select").val("").trigger("change.select2");
            form3.modal("hide");
            table4.ajax.reload();
            table1.ajax.reload();
            table3.ajax.reload();
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

  let form4 = $("#edit-payment");
  form4.validate({
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

  form4.on("submit", function (e) {
    e.preventDefault();

    if ($(this).valid() === true) {

      Swal.fire({
        title: "Please wait !",
        allowOutsideClick: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });

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
            form4.find("select").val("").trigger("change.select2");
            form4.modal("hide");
            table4.ajax.reload();
            table1.ajax.reload();
            table3.ajax.reload();
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

  let form5 = $("#add-bulk-payment");
  form5.validate({
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

  form5.on("submit", function (e) {
    e.preventDefault();

    if ($(this).valid() === true) {

      Swal.fire({
        title: "Please wait !",
        allowOutsideClick: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });

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
            form5.trigger("reset");
            form5.modal("hide");
            table2.ajax.reload();
            table1.ajax.reload();
            table3.ajax.reload();
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

  let form6 = $("#add-debit");
  form6.validate({
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

  form6.on("submit", function (e) {
    e.preventDefault();

    if ($(this).valid() === true) {

      Swal.fire({
        title: "Please wait !",
        allowOutsideClick: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });
      
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
            form6.trigger("reset");
            form6.modal("hide");
            table2.ajax.reload();
            table1.ajax.reload();
            table3.ajax.reload();
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

  let select2Invoices = $(".select2-invoices")
    .select2({
      ajax: {
        url: `${baseUrl}sales/select2`,
        dataType: "json",
        data: function (params) {
          params.filter = {
            payment_status: "due",
          };

          return params;
        },
      },
      allowClear: true,
      minimumInputLength: 3,
      placeholder: "Enter invoice/receipt reference",
      dropdownParent: $("#add-payment"),
    })
    .on("select2:select", function (e) {
      const data = e.params.data;
      $("#inv-bal").val((data.total_amount - data.paid).toFixed(2));
      $("#inv-due").val((data.total_amount - data.paid).toFixed(2));
      $("input[name='customer_id']").val(data.customer_id);
    })
    .on("select2:unselect", function (e) {
      $("#inv-bal").val((0).toFixed(2));
      $("#inv-due").val((0).toFixed(2));
    });

  $(".select2-store").select2({
    placeholder: "Seach a store",
  });

  $(".select2-store").on("select2:select select2:unselect", function (params) {
    table1.ajax.reload();
    table2.ajax.reload();
    table3.ajax.reload();
    table.ajax.reload();
    $("input[name='store_id']").val($(this).val());
  });
  $("input[name='store_id']").val($(".select2-store").val());
});
