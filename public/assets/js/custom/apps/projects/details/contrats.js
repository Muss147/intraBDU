"use strict";
var KTProjectTargets = {
    init: function () {
      var t = '';
      (function () {
        t = document.getElementById("kt_profile_overview_table");
        t.querySelectorAll("tbody tr").forEach((t) => {
          const e = t.querySelectorAll("td"),
            o = moment(e[6].innerHTML, "MMM D, YYYY").format();
          e[0].setAttribute("data-order", o);
        }),
          $(t).DataTable({ info: !1, order: [], paging: !1 });
      })(),
      t
        .querySelectorAll('[data-kt-project-contrat-filter="delete_row"]')
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
        });
    },
};
KTUtil.onDOMContentLoaded(function () {
  KTProjectTargets.init();
});