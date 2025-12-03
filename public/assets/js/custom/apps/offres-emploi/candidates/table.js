"use strict";
var KTCustomersList = (function () {
  var t, e,
    o = () => {
      e.querySelectorAll(
        '[data-kt-customer-table-filter="action_row"]'
      ).forEach((e) => {
        e.addEventListener("click", function (e) {
          e.preventDefault();
          const o = e.target.closest("tr"),
            a = e.target.innerText.toLowerCase(),
            n = o.querySelector('td [data-kt-customer-filter="agent_name"]').innerText;
          Swal.fire({
            html: "Êtes-vous sûr de vouloir "+ a +" la candidature de <b>" + n + "</b> ?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Oui, "+ a +" !",
            cancelButtonText: "Non, annuler",
            customClass: {
              confirmButton: "btn fw-bold btn-danger",
              cancelButton: "btn fw-bold btn-active-light-primary",
            },
          }).then(function (t) {
            t.value
              ? e.target.closest("form").submit()
              : "cancel" === t.dismiss
          });
        });
      });
    };
  return {
    init: function () {
      (e = document.querySelector("#kt_customers_table")) &&
      (e.querySelectorAll("tbody tr").forEach((t) => {
        const e = t.querySelectorAll("td"),
          o = moment(e[4].innerHTML, "DD MMM YYYY, LT").format();
        e[4].setAttribute("data-order", o);
      }),
      (t = $(e).DataTable({
        info: !1,
        order: [],
        columnDefs: [
          { orderable: !1, targets: 0 },
          { orderable: !1, targets: 5 },
        ],
      })).on("draw", function () {
        o()
      }),
      o(),
      document
        .querySelector('[data-kt-customer-table-filter="search"]')
        .addEventListener("keyup", function (e) {
          t.search(e.target.value).draw();
        })
      );
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTCustomersList.init();
});
