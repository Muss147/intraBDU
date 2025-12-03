"use strict";
var KTCustomersList = (function () {
  var t,
    e,
    allDeletedPath = document.querySelector('[data-kt-customer-table-select="delete_selected"]').getAttribute('data-deleted-root'),
    o = () => {
      e.querySelectorAll(
        '[data-kt-customer-table-filter="delete_row"]'
      ).forEach((e) => {
        e.addEventListener("click", function (e) {
          e.preventDefault();
          const o = e.target.closest("tr"),
            n = o.querySelector('td [data-kt-customer-filter="product_name"]').innerText;
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
    },
    n = () => {
      const o = e.querySelectorAll('[type="checkbox"]'),
        n = document.querySelector(
          '[data-kt-customer-table-select="delete_selected"]'
        );
      o.forEach((t) => {
        t.addEventListener("click", function () {
          setTimeout(function () {
            c();
          }, 50);
        });
      }),
        n.addEventListener("click", function () {
          Swal.fire({
            text: "Êtes-vous sûr de vouloir supprimer la sélection ?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Oui, tout supprimer !",
            cancelButtonText: "Non, annuler",
            customClass: {
              confirmButton: "btn fw-bold btn-danger",
              cancelButton: "btn fw-bold btn-active-light-primary",
            },
          }).then(function (n) {
            var arraysOfIds = [];
            n.value
              ? (
                  o.forEach((e) => {
                    e.checked &&
                      t
                        .row($(e.closest("tbody tr")))
                        .remove()
                        .draw(),
                        (e.value != 0) && arraysOfIds.push(e.value)
                  }),
                  e.querySelectorAll('[type="checkbox"]')[0].checked = !1,
                  sendIdsToDelete(arraysOfIds)
                )
              : "cancel" === n.dismiss
          });
        });
    };
  const c = () => {
    const t = document.querySelector('[data-kt-customer-table-toolbar="base"]'),
      o = document.querySelector('[data-kt-customer-table-toolbar="selected"]'),
      n = document.querySelector('[data-kt-customer-table-select="selected_count"]'),
      c = e.querySelectorAll('tbody [type="checkbox"]');
    let r = !1,
      l = 0;
    c.forEach((t) => {
      t.checked && ((r = !0), l++);
    }),
      r
        ? ((n.innerHTML = l),
          t.classList.add("d-none"),
          o.classList.remove("d-none"))
        : (t.classList.remove("d-none"), o.classList.add("d-none"));
  };
  const sendIdsToDelete = (ids) => {
      $.ajax({
          url: allDeletedPath, // Remplacez par l'URL de votre route
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({ agentsToDelete: ids }), // Convertit le tableau en JSON
          success: function(response) {
              console.log('Réponse du serveur:', response);
          },
          error: function(xhr, status, error) {
              console.error('Erreur:', status, error);
          }
      });
  };
  return {
    init: function () {
      (e = document.querySelector("#kt_customers_table")) &&
        (e.querySelectorAll("tbody tr").forEach((t) => {
          const e = t.querySelectorAll("td"),
            o = moment(e[5].innerHTML, "DD MMM YYYY, LT").format();
          e[5].setAttribute("data-order", o);
        }),
        (t = $(e).DataTable({
          info: !1,
          order: [],
          columnDefs: [
            { orderable: !1, targets: 0 },
            { orderable: !1, targets: 6 },
          ],
        })).on("draw", function () {
          n(), o(), c();
        }),
        n(),
        document
          .querySelector('[data-kt-customer-table-filter="search"]')
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
