"use strict";
var KTCustomerViewPaymentTable = (function () {
    var e = document.querySelector("#kt_ecommerce_customer_addresses");
    return {
        init: function () {
            e &&
            (e
            .querySelectorAll('[data-kt-customer-payment-method="delete"]')
            .forEach((e) => {
                e.addEventListener("click", function (e) {
                    e.preventDefault();
                    const form = e.target.closest("form");
                    const n = e.target.closest(".flex-stack.flex-wrap"),
                    o = n.querySelector(".nomComplet").innerText;
                    Swal.fire({
                        html: "Êtes-vous sûr de vouloir supprimer l'enfant <b>" + o + "</b> ?",
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
    KTCustomerViewPaymentTable.init();
});