let table;

$(function () {
  table = $("#dt-purchases").DataTable({
    ajax: {
      url: baseUrl + "/purchases/datatable",
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
          }else if (typeof field.attr("name") !== "undefined") {
            filter[field.attr("name")] = field.val();
          }
        });

        params.date_range_column = "purchase_date";
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
      { data: "purchase_date", name: "purchases.purchase_date" },
      {
        data: "supplier",
        name: "purchases.supplier_id",
        render: function (data, type, row) {
          if (type === "display")
            return data
              ? `<a target="_blank" href="${baseUrl}suppliers/${data.id}" class="btn btn-link btn-sm">${data.name}</a>`
              : null;
          return data ? data.id : null;
        },
      },
      { data: "invoice", name: "purchases.invoice" },
      {
        data: "order_status",
        name: "purchases.order_status",
        render: function (data, type) {
          if (type === "display") {
            const badges = {
              completed: "bg-lightgreen",
              pending: "lightred",
            };
            return `<span class="badges ${badges[data]}">${data}</span>`;
          }

          return data;
        },
      },
      {
        data: "payment_status",
        name: "purchases.payment_status",
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
        name: "purchases.paid",
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
        name: "purchases.user_id",
        render: function (data, type, row) {
          if (type === "display")
            return data ? `${data.firstname} ${data.lastname}` : null;
          return data ? data.id : null;
        },
      },
      {
        data: "id",
        name: "purchases.id",
        render: function (data, type, row) {
          if (type === "display") {
            return `<div class="d-flex align-items-center">
            ${
              row.payment_status === "due"
                ? `  <a  href="javascript:void(0);" class="me-3" onclick="editRow('#add-payment',{purchase_id:${data},invoice_balance:${(
                    row.total_amount - row.paid
                  ).toFixed(2)},debit:${(row.total_amount - row.paid).toFixed(
                    2
                  )}},{text:'${row.invoice} (${row.supplier.name} - GHS ${
                    row.total_amount
                  })',id:${
                    row.id
                  },name:'purchase_id'})"><i class="fa fa-money-bill fa-lg"></i></a>`
                : ""
            }
                        <a target="_blank" href="${baseUrl}purchases/${data}" class="me-3"><i class="fa fa-eye fa-lg"></i></a>
                        <a href="${baseUrl}purchases/returns/create?invoice=${row.invoice}" class="me-3"><i class="fa fa-reply fa-lg"></i></a>
                        <a hidden class="text-danger" href="javascript:void(0);" onclick="deleteRow(table, ${data}, '${baseUrl}purchases')"><i class="fa fa-trash fa-lg"></i></a>
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


  $(".select2-supplier").select2({
    ajax: {
      url: `${baseUrl}suppliers/select2`,
      dataType: "json",
    },
    allowClear: true,
    placeholder: "Seach a supplier",
  });
  $(".select2-store").select2({
    placeholder: "Seach a store",
    allowClear: true,
  });

  $(".select2-store").select2({
    placeholder: "Seach a store",
    allowClear:true,
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
        url: `${baseUrl}purchases/select2`,
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
      $("input[name='supplier_id']").val(data.customer_id);
    })
    .on("select2:unselect", function (e) {
      $("#inv-bal").val((0).toFixed(2));
      $("#inv-due").val((0).toFixed(2));
    });
});
