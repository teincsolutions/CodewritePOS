$(function () {
  $(".select2-store")
    .select2({
      allowClear: true,
      placeholder: "All stores",
    })
    .on("select2:select", function (e) {
      location.assign(baseUrl + `?store=${$(this).val()}`);
    })
    .on("select2:unselect", function (e) {
      location.assign(baseUrl);
    });
});