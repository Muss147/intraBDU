"use strict";
var KTUsersViewRole = (function () {
    var t,
        e,
        allDeletedPath,
        o = () => {
        const r = e.querySelectorAll('[type="checkbox"]'),
            c = document.querySelector(
            '[data-kt-view-roles-table-select="delete_selected"]'
            ),
            allDeletedPath = c.getAttribute('data-deleted-root');
        r.forEach((t) => {
            t.addEventListener("click", function () {
            setTimeout(function () {
                n();
            }, 50);
            });
        }),
            c.addEventListener("click", function () {
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
            }).then(function (c) {
                var arraysOfIds = [];
                c.value
                ? (
                        r.forEach((e) => {
                        e.checked &&
                            t
                            .row($(e.closest("tbody tr")))
                            .remove()
                            .draw(),
                            arraysOfIds.push(t.value)
                        }),
                        e.querySelectorAll('[type="checkbox"]')[0].checked = !1,
                        sendIdsToDelete(arraysOfIds),
                        n(), o()
                    )
                : "cancel" === c.dismiss
            });
            });
        };
    const n = () => {
        const t = document.querySelector(
            '[data-kt-view-roles-table-toolbar="base"]'
        ),
        o = document.querySelector(
            '[data-kt-view-roles-table-toolbar="selected"]'
        ),
        n = document.querySelector(
            '[data-kt-view-roles-table-select="selected_count"]'
        ),
        r = e.querySelectorAll('tbody [type="checkbox"]');
        let c = !1,
        l = 0;
        r.forEach((t) => {
        t.checked && ((c = !0), l++);
        }),
        c
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
            data: JSON.stringify({ rolesDeleted: ids }), // Convertit le tableau en JSON
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
        (e = document.querySelector("#kt_roles_view_table")) &&
            (e.querySelectorAll("tbody tr").forEach((t) => {
            const e = t.querySelectorAll("td"),
                o = moment(e[3].innerHTML, "DD MMM YYYY, LT").format();
            e[3].setAttribute("data-order", o);
            }),
            (t = $(e).DataTable({
            info: !1,
            order: [],
            pageLength: 5,
            lengthChange: !1,
            columnDefs: [
                { orderable: !1, targets: 0 },
                { orderable: !1, targets: 4 },
            ],
            })),
            document
            .querySelector('[data-kt-roles-table-filter="search"]')
            .addEventListener("keyup", function (e) {
                t.search(e.target.value).draw();
            }),
            e
            .querySelectorAll('[data-kt-roles-table-filter="delete_row"]')
            .forEach((e) => {
                e.addEventListener("click", function (e) {
                e.preventDefault();
                const form = e.target.closest("form");
                const o = e.target.closest("tr"),
                    n = o.querySelectorAll("td")[1].innerText;
                Swal.fire({
                    html: "Êtes-vous sûr de vouloir supprimer l'utilisateur <b>" + n + "</b> ?",
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
                    ? (t.row($(o)).remove().draw(), form.submit())
                    : "cancel" === e.dismiss
                });
                });
            }),
            o());
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
  KTUsersViewRole.init();
});
