let table, table2, table3;

$(function () {
  table = $("#dt-sales").DataTable({
    ajax: {
      url: baseUrl + "sales/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#sales #input-filter input, #sales #input-filter select"
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
        filter["sales.user_id"] = $("input[name='user_id']").val();

        params.date_range_column = "sales_date";
        params.date_from = moment().format("YYYY-MM-DD");
        params.date_to = moment().format("YYYY-MM-DD");

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
        data: "total_amount",
        render: function (data) {
          return ` ${parseFloat(data).toFixed(2)}`;
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
                            : `<a href="${baseUrl}sales/pos/${data}" class="me-3"><i class="fa fa-play fa-lg"></i></a>`
                        }
                        <a ${
                          row.order_status === "completed" ? "hidden" : ""
                        } class="text-danger" href="javascript:void(0);" onclick="deleteRow(table, ${data}, '${baseUrl}sales')"><i class="fa fa-trash fa-lg"></i></a>
                    </div>`;
          }
          return data;
        },
      },
    ],
    initComplete: (settings, json) => {
      $("#sales .dataTables_filter").appendTo("#sales #tableSearch");
      $("#sales .dataTables_filter").appendTo("#sales .search-input");
      if ($('#sales [data-bs-toggle="tooltip"]').length > 0) {
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
    order: [[3, "desc"]],
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
  table.buttons().container().appendTo(".sales-wordset");

  $("#sales .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table.ajax.reload();
    }
  );
  // table2
  table2 = $("#dt-ledger").DataTable({
    ajax: {
      url: baseUrl + "customers/ledger/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $(
          "#ledger-tab #filter_inputs3 input, #ledger-tab #filter_inputs3 select"
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
        filter["customer_ledgers.store_id"] = $(".select2-store").val();
        filter["customer_ledgers.user_id"] = $("input[name='user_id']").val();

        params.date_range_column = "tdate";
        params.date_from = moment().format("YYYY-MM-DD");
        params.date_to = moment().format("YYYY-MM-DD");

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
      { data: "id", name: "customer_ledgers.id" },
      { data: "tdate", name: "customer_ledgers.tdate" },
      {
        data: null,
        render: function (data, type, row) {
          return data.sale
            ? `${data.sale.invoice}`
            : `${data.sales_return.invoice}`;
        },
      },
      {
        data: "customer",
        name: "customer_ledgers.customer_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}customers/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : "walk-in-customer";
          return data ? data.name : "walk-in-customer";
        },
      },
      {
        data: "debit",
        render: function (data) {
          return ` ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "credit",
        render: function (data) {
          return ` ${parseFloat(data).toFixed(2)}`;
        },
      },
      {
        data: "balance",
        render: function (data) {
          return data < 0
            ? `(${Math.abs(data).toFixed(2)})`
            : ` ${data.toFixed(2)}`;
        },
      },
      {
        data: "id",
        name: "customer_ledgers.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
            <a  href="javascript:void(0);" class="me-3" onclick="printReceiptRow(this)"><i class="fa fa-print fa-lg"></i></a>
            <a  href="javascript:void(0);" class="me-3" onclick="editRow('#edit-payment',{id:${data},tdate:'${moment(
              row.tdate
            ).format("DD-MM-YYYY")}',sale_id:${row.sale_id},payment_type:'${
              row.payment_type
            }',credit:${row.credit}},{text:'${row.sale.invoice} (${
              row.customer.name
            } -  ${row.sale.total_amount})',id:${
              row.sale.id
            },name:'sale_id'})"><i class="fa fa-edit fa-lg"></i></a>
                        <a class="text-danger" href="javascript:void(0);" onclick="deleteRow(table2, ${
                          row.id
                        }, '${baseUrl}customers/ledger',table3,table1)"><i class="fa fa-trash fa-lg"></i></a>
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
    order: [[0, "desc"]],
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
          "#returns-tab #filter_inputs1 input, #returns-tab #filter_inputs1 select"
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
        filter["sales_returns.store_id"] = $(".select2-store").val();
        filter["sales_returns.user_id"] = $("input[name='user_id']").val();

        params.date_range_column = "return_date";
        params.date_from = moment().format("YYYY-MM-DD");
        params.date_to = moment().format("YYYY-MM-DD");

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
          return ` ${parseFloat(data).toFixed(2)}`;
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
  table3.buttons().container().appendTo("#returns-tab .wordset");

  $("#returns-tab .filter").on(
    "click select2:select select2:unselect",
    function (params) {
      table3.ajax.reload();
    }
  );
  let form2 = $("#add-customer");

  form2.validate({
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

  form2.on("submit", function (e) {
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
            form2.trigger("reset");
            form2.modal("hide");
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

  $(".select2-category,.select2-brand, .select2-unit, .select2-tax").select2({
    placeholder: "Choose an option",
    allowClear: true,
  });

  $(".select2-tax").on("change", (e) => {
    $("[name='tax']").val($(this).data("rate"));
  });

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
            form3.modal("hide");
            table.ajax.reload();
            table2.ajax.reload();
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
          let filter = {};
          filter["sales.type"] = "customer";
          filter["sales.payment_status"] = 'due';
          filter["sales.order_status"] = 'completed';
          params.filter = filter;
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
});
