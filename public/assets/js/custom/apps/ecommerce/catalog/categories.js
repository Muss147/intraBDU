"use strict";
var KTAppEcommerceCategories = (function () {
  var t,
    e,
    b,
    d,
    r,
    allDeletedPath = document.querySelector('[data-kt-category-table-select="delete_selected"]').getAttribute('data-deleted-root'),
    n = () => {
      t.querySelectorAll(
        '[data-kt-ecommerce-category-filter="delete_row"]'
      ).forEach((t) => {
        t.addEventListener("click", function (t) {
          t.preventDefault();
          const form = t.target.closest("form");
          const n = t.target.closest("tr"),
            o = n.querySelector(
              '[data-kt-ecommerce-category-filter="category_name"]'
            ).innerText;
          Swal.fire({
            html: "Êtes-vous sûr de vouloir supprimer <b>" + o + "</b> ?",
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
              ? (
                  // e.row($(n)).remove().draw(),
                  form.submit()
                )
              : "cancel" === t.dismiss
          });
        });
      });
    },
    l = () => {
        const c = t.querySelectorAll('[type="checkbox"]');
        (b = document.querySelector('[data-kt-category-table-toolbar="base"]')),
        (d = document.querySelector('[data-kt-category-table-toolbar="selected"]')),
        (r = document.querySelector(
            '[data-kt-category-table-select="selected_count"]'
        ));
        const s = document.querySelector(
            '[data-kt-category-table-select="delete_selected"]'
        );
        c.forEach((e) => {
            e.addEventListener("click", function () {
            setTimeout(function () {
                a();
            }, 50);
            });
        }),
        s.addEventListener("click", function () {
            Swal.fire({
                text: "Êtes-vous sûr de vouloir supprimer la sélection ?",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Oui, tout supprimer!",
                cancelButtonText: "Non, annuler",
                customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary",
                },
            }).then(function (f) {
                var arraysOfIds = [];
                f.value
                ? (
                    c.forEach((f) => {
                    f.checked &&
                        e
                        .row($(f.closest("tbody tr")))
                        .remove()
                        .draw(),
                        f.value != 0 && arraysOfIds.push(f.value)
                    }),
                    t.querySelectorAll('[type="checkbox"]')[0].checked = !1,
                    sendIdsToDelete(arraysOfIds),
                    a(), l()
                )
                : "cancel" === t.dismiss
            });
        });
    };
  const a = () => {
    const e = t.querySelectorAll('tbody [type="checkbox"]');
    let c = !1,
    l = 0;
    e.forEach((e) => {
      e.checked && ((c = !0), l++);
    }),
    c
      ? ((r.innerHTML = l),
        b.classList.add("d-none"),
        d.classList.remove("d-none"))
      : (b.classList.remove("d-none"), d.classList.add("d-none"));
  };
  const sendIdsToDelete = (ids) => {
      $.ajax({
          url: allDeletedPath, // Remplacez par l'URL de votre route
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({ itemsDeleted: ids }), // Convertit le tableau en JSON
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
      (t = document.querySelector("#kt_ecommerce_category_table")) &&
      ((e = $(t).DataTable({
          info: !1,
          order: [],
          pageLength: 10,
          columnDefs: [
            { orderable: !1, targets: 0 },
            { orderable: !1, targets: 3 },
          ],
        })).on("draw", function () {
          n(), l(), a();
        }),
        l(),
        document
          .querySelector('[data-kt-ecommerce-category-filter="search"]')
          .addEventListener("keyup", function (t) {
            e.search(t.target.value).draw();
          }),
        n()
      );
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTAppEcommerceCategories.init();
});
