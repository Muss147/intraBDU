"use strict";
var KTCustomerViewStatements = {
  init: function () {
    const tables = document.querySelectorAll('[id^="kt_customer_view_statement_table_"]');

    tables.forEach((table) => {
      // Formatter les dates pour le tri
      table.querySelectorAll("tbody tr").forEach((row) => {
        const tds = row.querySelectorAll("td");
        const formattedDate = moment(tds[0].innerHTML, "DD MMM YYYY, LT").format();
        tds[0].setAttribute("data-order", formattedDate);
      });

      // Initialiser DataTable
      $(table).DataTable({
        info: false,
        order: [],
        pageLength: 10,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 4 }],
      });
    });
  },
};

KTUtil.onDOMContentLoaded(function () {
  KTCustomerViewStatements.init();
});