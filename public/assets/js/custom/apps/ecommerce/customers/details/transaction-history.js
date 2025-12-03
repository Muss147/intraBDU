"use strict";
var KTCustomerViewPaymentTable = (function () {
    var t,
    e = document.querySelector("#kt_table_customers_payment");
    return {
        init: function () {
            e &&
            (e.querySelectorAll("tbody tr").forEach((t) => {
                const e = t.querySelectorAll("td"),
                n = moment(e[3].innerHTML, "DD MMM YYYY, LT").format();
                e[3].setAttribute("data-order", n);
            }),
            (t = $(e).DataTable({
                info: !1,
                order: [],
                pageLength: 5,
                lengthChange: !1,
                columnDefs: [{ orderable: !1, targets: 4 }],
            })));
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTCustomerViewPaymentTable.init();
});
