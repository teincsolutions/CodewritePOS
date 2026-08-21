let table;

$(function () {
  table = $("#dt-sales").DataTable({
    ajax: {
      url: baseUrl + "/sales/datatable",
      dataType: "json",
      contentType: "application/json",
      data: function (params) {
        let filter = {};
        let filterForm = $("#filter_inputs9 input, #filter_inputs9 select");
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
              ? '<a target="_blank" href="' + baseUrl + 'customers/' + data.id + '" class="btn btn-link btn-sm">' + data.name + '</a>'
              : "walk-in-customer";
          return data ? data.name : null;
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
            return '<span class="badges ' + badges[data] + '">' + data + '</span>';
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
            return '<span class="badges ' + badges[data] + '">' + data + '</span>';
          }
          return data;
        },
      },
      {
        data: "total_amount",
        render: function (data) {
          return ' ' + parseFloat(data).toFixed(2);
        },
      },
      {
        data: "paid",
        name: "sales.paid",
        render: function (data) {
          return ' ' + parseFloat(data).toFixed(2);
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          due = data.total_amount - data.paid;
          return due < 0
            ? '(' + Math.abs(due).toFixed(2) + ')'
            : ' ' + due.toFixed(2);
        },
      },
      {
        data: "user",
        name: "sales.user_id",
        render: function (data, type, row) {
          if (type === "display")
            return data ? data.firstname + ' ' + data.lastname : null;
          return data ? data.firstname + ' ' + data.lastname : null;
        },
      },
      {
        data: "id",
        name: "sales.id",
        render: function (data, type, row) {
          if (type === "display") {
            var html = '<div class="d-flex align-items-center">';
            
            // Payment button
            if (row.payment_status === "due" && row.customer) {
              var balance = row.total_amount - row.paid;
              html += '  <a  href="javascript:void(0);" class="me-3" onclick="editRow(\'#add-payment\',{sale_id:' + data + ',invoice_balance:' + balance.toFixed(2) + ',credit:' + balance.toFixed(2) + '},{text:\'' + row.invoice + ' (' + row.customer.name + ' -  ' + row.total_amount + ')\',id:' + row.id + ',name:\'sale_id\'})"><i class="fa fa-money-bill fa-lg"></i></a>';
            }
            
            // View button
            html += '<a target="_blank" href="' + baseUrl + 'sales/' + data + '" class="me-3"><i class="fa fa-eye fa-lg"></i></a>';
            
            // Return or Edit button
            if (row.order_status === "completed") {
              html += ' <a href="' + baseUrl + 'sales/returns/create?invoice=' + row.invoice + '" class="me-3"><i class="fa fa-reply fa-lg"></i></a>';
            } else {
              html += ' <a href="' + baseUrl + 'sales/pos/' + data + '" class="me-3"><i class="fa fa-play fa-lg"></i></a>';
            }
            
            // Edit Items Button (NEW) - navigate to edit page like other buttons
            html += ' <a href="' + baseUrl + 'sales/items/edit/' + data + '" class="me-3" title="Edit Items"><i class="fa fa-edit fa-lg text-primary"></i></a>';
            
            // Delete button
            if (row.order_status === "completed") {
              if (typeof Settings !== "undefined" && Settings.AllowDeleteSales === "yes") {
                html += '<a class="text-danger" href="javascript:void(0);" onclick="deleteRow(table, ' + data + ', \'' + baseUrl + 'sales\')"><i class="fa fa-trash fa-lg"></i></a>';
              } else {
                html += '<a class="text-danger hidden" href="javascript:void(0);" onclick="deleteRow(table, ' + data + ', \'' + baseUrl + 'sales\')"><i class="fa fa-trash fa-lg"></i></a>';
              }
            } else {
              html += '<a class="text-danger" href="javascript:void(0);" onclick="deleteRow(table, ' + data + ', \'' + baseUrl + 'sales\')"><i class="fa fa-trash fa-lg"></i></a>';
            }
            
            html += '</div>';
            return html;
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

      // Total over this page
      pageTotal = api
        .column(6, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Update footer
      $(api.column(6).footer()).html(" " + pageTotal.toFixed(2));

      // Total over this page
      pageTotal = api
        .column(7, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Update footer
      $(api.column(7).footer()).html(" " + pageTotal.toFixed(2));
      // Total over this page
      pageTotal = api
        .column(8, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.total_amount - b.paid);
        }, 0);

      // Update footer
      $(api.column(8).footer()).html(" " + pageTotal.toFixed(2));
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
      url: baseUrl + "customers/select2",
      dataType: "json",
    },
    allowClear: true,
    placeholder: "Seach a customer",
  });
  $(".select2-store").select2({
    placeholder: "Seach a store",
    allowClear: true,
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
        url: baseUrl + "sales/select2",
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

  // Handle Edit Items button click for sales
  $(document).on("click", ".edit-sales-items", function () {
    var id = $(this).data("id");
    var invoice = $(this).data("invoice");
    
    // Show loading state
    Swal.fire({
      title: "Loading items...",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    
    // AJAX load items for this sale
    $.ajax({
      url: baseUrl + "/sales/items/datatable",
      type: "GET",
      data: { sale_id: id },
      dataType: "json",
      success: function (response) {
        if (response.status && response.data) {
          var items = response.data;
          var form = $("#editItemsForm");
          
          // Set sale_id hidden field
          form.find("input[name='sale_id']").val(id);
          
          // Clear and populate item table
          var tbody = form.find("tbody");
          tbody.empty();
          
          var idx = 1;
          $.each(items, function (k, row) {
            var subtotal = row.subtotal ? parseFloat(row.subtotal) : 0;
            var unit_price = row.unit_price ? parseFloat(row.unit_price) : 0;
            var qty = row.qty ? parseFloat(row.qty) : 0;
            var discount = row.discount ? parseFloat(row.discount) : 0;
            var tax = row.tax ? parseFloat(row.tax) : 0;
            
            var rowHtml = '<tr>' +
              '<td>' + idx + '</td>' +
              '<td>' +
                (row.product_name ? row.product_name : 'N/A') +
                (row.product_sku ? ' (' + row.product_sku + ')' : '') +
              '</td>' +
              '<td>' +
                '<input type="number" name="items[' + row.id + '][qty]" value="' + qty + '" min="0" class="form-control form-control-sm" required>' +
              '</td>' +
              '<td>' +
                '<input type="number" name="items[' + row.id + '][unit_price]" value="' + unit_price + '" min="0" step="0.01" class="form-control form-control-sm" required>' +
              '</td>' +
              '<td>' +
                '<input type="number" name="items[' + row.id + '][discount]" value="' + discount + '" min="0" step="0.01" class="form-control form-control-sm">' +
              '</td>' +
              '<td>' +
                '<input type="number" name="items[' + row.id + '][tax]" value="' + tax + '" min="0" step="0.01" class="form-control form-control-sm">' +
              '</td>' +
              '<td>' +
                '<input type="text" name="items[' + row.id + '][subtotal]" value="' + subtotal.toFixed(2) + '" readonly class="form-control form-control-sm">' +
              '</td>' +
              '<td>' +
                '<button type="button" class="btn btn-danger btn-sm delete-item-row" data-id="' + row.id + '"><i class="fa fa-trash"></i> Remove</button>' +
              '</td>' +
            '</tr>';
            tbody.append(rowHtml);
            idx++;
          });
          
          // Show modal
          $("#editItemsModal").find(".modal-title").text("Edit Sales Items - " + invoice);
          $("#editItemsModal").modal("show");
        } else {
          Swal.fire({
            icon: "error",
            text: response.message || "Failed to load items!"
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          text: "Unable to load items. Please try again."
        });
      }
    });
  });
  
  // Handle form submit for editing items
  $(document).on("submit", "#editItemsForm", function (e) {
    e.preventDefault();
    
    var form = $(this);
    var formData = new FormData(form[0]);
    
    Swal.fire({
      title: "Saving changes...",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    
    $.ajax({
      url: form.attr("action"),
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.status) {
          Swal.fire({
            icon: "success",
            text: response.message
          });
          $("#editItemsModal").modal("hide");
          // Reload the sales datatable
          table = $("#dt-sales").DataTable();
          table.ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            text: response.message
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          text: "Unable to save changes. Please try again."
        });
      }
    });
  });
  
  // Handle remove row in edit items form
  $(document).on("click", ".delete-item-row", function () {
    $(this).closest("tr").remove();
    recalculateEditItemsTotal();
  });
  
  function recalculateEditItemsTotal() {
    var total = 0;
    $("#editItemsForm tbody tr").each(function () {
      var subtotal = parseFloat($(this).find("input[name*='subtotal']").val()) || 0;
      total += subtotal;
    });
    $("#editItemsTotal").html(total.toFixed(2));
  }
});