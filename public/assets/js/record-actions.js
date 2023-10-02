/**
 * Delete a record from a form
 * @param {number} id - record id
 * @param {number} path - route to sent request
 *  @param {number} path2 - route to redirect to after success update. if null it will go back in history
 */
window.deleteRecord = function (id, path, path2 = null) {
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
            if (path2 === null) history.back();
            else location.assign(path2);
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
 * Update a record from form
 * @param {object} data - request data
 * @param {number} path - route to sent request
 */
window.updateRow = function (data, path) {
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
};
