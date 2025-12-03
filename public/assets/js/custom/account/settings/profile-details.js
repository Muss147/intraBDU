"use strict";
var KTAccountSettingsProfileDetails = (function () {
    const m = document.getElementById("kt_modal_update_role"),
    f = m.querySelector('input[name="users[role]"]'),
    n = new bootstrap.Modal(m);
    var e, o, i;
    return {
        init: function () {
            (e = document.getElementById("kt_account_profile_details_form")) &&
            (i = e.querySelector("#kt_account_profile_details_submit"),
                (o = FormValidation.formValidation(e, {
                    fields: {
                        'users[nomComplet]': {
                            validators: { notEmpty: { message: "Le nom complet est requis" } },
                        },
                        'users[role]': {
                            validators: {
                                notEmpty: {
                                    message: 'Veuillez sélectionner un rôle'
                                }
                            }
                        },
                        // 'users[avatar]': {
                        //     validators: {
                        //         file: {
                        //             extension: 'jpg,jpeg,png',
                        //             type: 'image/jpeg,image/png',
                        //             message: 'L\'image sélectionnée n\'est pas valide'
                        //         },
                        //     }
                        // },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                        }),
                    },
                }))
            );
            i.addEventListener("click", (t) => {
                t.preventDefault(),
                o &&
                o.validate().then(function (t) {
                    console.log("validated!"),
                    "Valid" == t
                    ? (i.setAttribute("data-kt-indicator", "on"),
                            (i.disabled = !0),
                            setTimeout(function () {
                                i.removeAttribute("data-kt-indicator"),
                                (i.disabled = !1),
                                e.submit();
                            }, 2e3)
                        )
                    : Swal.fire({
                            text: "Désolé, il semble que des erreurs aient été détectées, veuillez vérifier les champs.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, compris !",
                            customClass: { confirmButton: "btn btn-primary" },
                        })
                    ;
                });
            });
            e
                .querySelector('[data-kt-users-modal-action="submit"]')
                .addEventListener("click", (t) => {
                    t.preventDefault(),
                    n.hide(),
                    e.querySelector('span.role_libelle').innerText = e.querySelector('input[name="users[role]"]:checked').getAttribute('data-libelle')
                })
            ,
            e
                .querySelector('[data-kt-users-modal-action="cancel"]')
                .addEventListener("click", (t) => {
                    t.preventDefault(),
                    r(), n.hide()
                }),
            e
                .querySelector('[data-kt-users-modal-action="close"]')
                .addEventListener("click", (t) => {
                    t.preventDefault(),
                    r(), n.hide()
                })
            ;
            const r = () => {
                e.querySelector('input[name="users[role]"]').checked = e.querySelector('input[name="users[role]"]').defaultChecked;
            }
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
  KTAccountSettingsProfileDetails.init();
});
