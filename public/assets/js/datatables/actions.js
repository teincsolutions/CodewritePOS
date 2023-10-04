/**
 * Delete a row from a table
 * @param {object} table
 * @param {number} id
 */
window.deleteRow = function (table, id, path, table2 = null, table3 = null) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Please wait !",
        allowOutsideClick: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });

      $.ajax({
        method: "DELETE",
        url: path + `/${id}`,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        success: function (d, r) {
          if (!d || r === "nocontent") {
            Swal.fire({
              icon: "error",
              text: "Malformed data sumbitted! Please try agian.",
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
            Swal.fire({
              icon: "success",
              text: d.message,
            });
            table.ajax.reload();
            if (table2) table2.ajax.reload();
            if (table3) table3.ajax.reload();
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
};

/**
 * Update a row from a table
 * @param {object} table
 * @param {object} data
 */
window.updateRow = function (table, data, path) {
  let form = new FormData();
  Object.getOwnPropertyNames(data).forEach((n, i) => {
    form.append(n, data[n]);
  });
  $.ajax({
    method: "POST",
    url: path,
    dataType: "json",
    data: form,
    contentType: false,
    processData: false,
    cache: false,
    success: function (d, r) {
      if (!d || r === "nocontent") {
        Swal.fire({
          icon: "error",
          text: "Malformed data sumbitted! Please try agian.",
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
        table.ajax.reload();
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
};

/**
 * Edit a row from a table
 * @param {string} target
 * @param {object} data
 */
window.editRow = function (target = "#form", data = {}, selectOption = {}) {
  $(target).modal("show");

  let fields = $(target)[0];
  if (typeof data === "object")
    for (let i = 0; i < fields.length; i++) {
      const field = $(fields[i]);
      const name = field.attr("name");

      if (
        typeof name !== "undefined" &&
        field.prop("tagName") === "SELECT" &&
        typeof data[name] !== "undefined"
      ) {
        if (
          typeof selectOption.text !== "undefined" &&
          typeof selectOption.id !== "undefined" &&
          selectOption.name === name
        ) {
          var option = new Option(
            selectOption.text,
            selectOption.id,
            true,
            true
          );
          field.append(option).trigger("change");
        } else {
          field.val(data[name]).trigger("change");
        }
      } else if (
        typeof name !== "undefined" &&
        typeof data[name] !== "undefined"
      ) {
        field.val(data[name]);
      }
    }
};
