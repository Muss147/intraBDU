"use strict";
var KTCustomersList = (function () {
  var t,
    e,
    o = () => {
        e.querySelectorAll(
            '[data-kt-params-table-filter="delete_row"]'
        ).forEach((e) => {
            e.addEventListener("click", function (e) {
                e.preventDefault();
                const o = e.target.closest("tr"),
                    n = o.querySelector('td [data-kt-params-table-filter="product_name"]').innerText;
                Swal.fire({
                    html: "Êtes-vous sûr de vouloir supprimer <b>" + n + "</b> ?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Oui, supprimer !",
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
            (e = document.querySelector("#kt_params_table")) &&
            (e.querySelectorAll("tbody tr").forEach((t) => {
                const e = t.querySelectorAll("td"),
                o = moment(e[3].innerHTML, "DD MMM YYYY, LT").format();
                e[3].setAttribute("data-order", o);
            }),
            (t = $(e).DataTable({
                info: !1,
                order: [],
                columnDefs: [
                    { orderable: !1, targets: 0 },
                    { orderable: !1, targets: 4 },
                ],
            })).on("draw", function () {
                o();
            }),
            document
            .querySelector('[data-kt-params-table-filter="search"]')
            .addEventListener("keyup", function (e) {
                t.search(e.target.value).draw();
            }),
            o(),
            (() => {
                const e = document.querySelector(
                    '[data-kt-ecommerce-order-filter="status"]'
                );
                $(e).on("change", (e) => {
                    let o = e.target.value;
                    "all" === o && (o = ""), t.column(3).search(o).draw();
                });
            })());
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
  KTCustomersList.init();
});
