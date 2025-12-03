"use strict";
var KTUsersTrashList = (function () {
  var t, e;
  return {
    init: function () {
      (e = document.querySelector("#kt_tash_table")) &&
        (e.querySelectorAll("tbody tr").forEach((t) => {
          const e = t.querySelectorAll("td"),
            n = moment(e[2].innerHTML, "DD MMM YYYY, LT").format();
          e[2].setAttribute("data-order", n);
        }),
        (t = $(e).DataTable({
          info: !1,
          order: [],
          columnDefs: [
            { orderable: !1, targets: 1 },
            { orderable: !1, targets: 3 },
          ],
        })),
        document
          .querySelector('[data-kt-trash-table-filter="search"]')
          .addEventListener("keyup", function (e) {
            t.search(e.target.value).draw();
          }));
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTUsersTrashList.init();
});
