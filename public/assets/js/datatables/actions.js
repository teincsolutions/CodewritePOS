/**
 * Delete a row from a table
 * @param {object} table
 * @param {number} id
 */
window.deleteRow = function (table, id, path) {
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
