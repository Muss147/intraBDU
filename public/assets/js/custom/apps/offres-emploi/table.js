"use strict";
var KTOffresList = (function () {
    var e,
        t,
        n,
        r,
        allDeletedPath = document.querySelector('[data-kt-offre-table-select="delete_selected"]').getAttribute('data-deleted-root'),
        o = document.getElementById("kt_table_offres"),
        c = () => {
        o.querySelectorAll('[data-kt-offres-table-filter="delete_row"]').forEach(
            (t) => {
            t.addEventListener("click", function (t) {
                t.preventDefault();
                const form = t.target.closest("form");
                const n = t.target.closest("tr"),
                r = n
                    .querySelectorAll("td")[1]
                    .querySelectorAll("a")[1].innerText;
                Swal.fire({
                html: "Êtes-vous sûr de vouloir supprimer l'offre <b>" + r + "</b> ?",
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
                    ? form.submit()
                    : "cancel" === t.dismiss
                });
            });
            }
        );
        },
        l = () => {
        const c = o.querySelectorAll('[type="checkbox"]');
        (t = document.querySelector('[data-kt-offre-table-toolbar="base"]')),
            (n = document.querySelector('[data-kt-offre-table-toolbar="selected"]')),
            (r = document.querySelector(
            '[data-kt-offre-table-select="selected_count"]'
            ));
        const s = document.querySelector(
            '[data-kt-offre-table-select="delete_selected"]'
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
            }).then(function (t) {
                var arraysOfIds = [];
                t.value
                ? (
                    c.forEach((t) => {
                    t.checked &&
                        e
                        .row($(t.closest("tbody tr")))
                        .remove()
                        .draw(),
                        t.value != 0 && arraysOfIds.push(t.value)
                    }),
                    o.querySelectorAll('[type="checkbox"]')[0].checked = !1,
                    sendIdsToDelete(arraysOfIds),
                    a(), l()
                )
                : "cancel" === t.dismiss
            });
            });
        };
    const a = () => {
        const e = o.querySelectorAll('tbody [type="checkbox"]');
        let c = !1,
        l = 0;
        e.forEach((e) => {
        e.checked && ((c = !0), l++);
        }),
        c
            ? ((r.innerHTML = l),
            t.classList.add("d-none"),
            n.classList.remove("d-none"))
            : (t.classList.remove("d-none"), n.classList.add("d-none"));
    };
    const sendIdsToDelete = (ids) => {
        $.ajax({
            url: allDeletedPath, // Remplacez par l'URL de votre route
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ offresDeleted: ids }), // Convertit le tableau en JSON
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
        o &&
            (o.querySelectorAll("tbody tr").forEach((e) => {
            const t = e.querySelectorAll("td"),
                n = t[3].innerText.toLowerCase();
            let r = 0,
                o = "minutes";
            n.includes("yesterday")
                ? ((r = 1), (o = "days"))
                : n.includes("mins")
                ? ((r = parseInt(n.replace(/\D/g, ""))), (o = "minutes"))
                : n.includes("hours")
                ? ((r = parseInt(n.replace(/\D/g, ""))), (o = "hours"))
                : n.includes("days")
                ? ((r = parseInt(n.replace(/\D/g, ""))), (o = "days"))
                : n.includes("weeks") &&
                ((r = parseInt(n.replace(/\D/g, ""))), (o = "weeks"));
            const c = moment().subtract(r, o).format();
            t[3].setAttribute("data-order", c);
            const l = moment(t[5].innerHTML, "DD MMM YYYY, LT").format();
            t[5].setAttribute("data-order", l);
            }),
            (e = $(o).DataTable({
            info: !1,
            order: [],
            pageLength: 10,
            lengthChange: !1,
            columnDefs: [
                { orderable: !1, targets: 0 },
                { orderable: !1, targets: 6 },
            ],
            })).on("draw", function () {
            l(), c(), a();
            }),
            l(),
            document
            .querySelector('[data-kt-offre-table-filter="search"]')
            .addEventListener("keyup", function (t) {
                e.search(t.target.value).draw();
            }),
            c());
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
  KTOffresList.init();
});
