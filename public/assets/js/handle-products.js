let form = $(".post-form");

form.validate({
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

form.on("submit", function (e) {
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
          if (typeof d.input === "object") {
            if (d.input._method === "post") {
              //$("select").val("").trigger("change.select2");
            }
            if (form.hasClass("modal")) form.modal('hide');
            if (form.hasClass("refresh-page")) location.reload();
          }

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
$(".select2-store").select2({
  placeholder: "Seach a store",
});
$(".select2-subcategory").select2({
  placeholder: "Choose a subcategory",
  allowClear: true,
});

$(".select2-tax").on("change", (e) => {
  $("[name='tax']").val($(this).data("rate"));
});
