"use strict";
var KTUsersPermissionsList = (function () {
  var t, e;
  return {
    init: function () {
      (e = document.querySelector("#kt_permissions_table")) &&
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
          .querySelector('[data-kt-permissions-table-filter="search"]')
          .addEventListener("keyup", function (e) {
            t.search(e.target.value).draw();
          }),
        e
          .querySelectorAll('[data-kt-permissions-table-filter="delete_row"]')
          .forEach((e) => {
            e.addEventListener("click", function (e) {
              e.preventDefault();
              const form = e.target.closest("form");
              const n = e.target.closest("tr"),
                o = n.querySelectorAll("td")[0].innerText;
              Swal.fire({
                html: "Êtes-vous sûr de vouloir supprimer la permission <b>" + o + "</b> ?",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Oui, supprimer !",
                cancelButtonText: "Non, annuler",
                customClass: {
                  confirmButton: "btn fw-bold btn-danger",
                  cancelButton: "btn fw-bold btn-active-light-primary",
                },
              }).then(function (e) {
                e.value
                  ? form.submit()
                  : "cancel" === e.dismiss
              });
            });
          }));
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTUsersPermissionsList.init();
});
