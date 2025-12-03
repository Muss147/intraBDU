"use strict";
var KTUsersAddUser = (function () {
  const t = document.getElementById("kt_modal_add_user"),
    e = t.querySelector("#kt_modal_add_user_form"),
    n = new bootstrap.Modal(t);
  return {
    init: function () {
      (() => {
        var o = FormValidation.formValidation(e, {
          fields: {
            'users[nomComplet]': {
              validators: { notEmpty: { message: "Le nom complet est requis" } },
            },
            'users[email]': {
                validators: {
                    regexp: {
                        regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                        message: "Ceci n'est pas une adresse email valide",
                    },
                    notEmpty: { message: "L'adresse Email est requise" },
                },
            },
            // 'users[role]': {
            //     validators: {
            //         notEmpty: {
            //             message: 'Le rôle est requis'
            //         }
            //     }
            // },
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
              rowSelector: ".fv-row",
              eleInvalidClass: "",
              eleValidClass: "",
            }),
          },
        });
        const i = t.querySelector('[data-kt-users-modal-action="submit"]');
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
                          e.submit(),
                          n.hide()
                      }, 2e3))
                    : Swal.fire({
                        text: "Désolé il semble y avoir une erreur, veuillez vérrifier les champs obligatoires.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, compris !",
                        customClass: { confirmButton: "btn btn-primary" },
                      });
              });
        }),
          t
            .querySelector('[data-kt-users-modal-action="cancel"]')
            .addEventListener("click", (t) => {
              t.preventDefault(),
                (e.reset(), n.hide())
            }),
          t
            .querySelector('[data-kt-users-modal-action="close"]')
            .addEventListener("click", (t) => {
              t.preventDefault(),
                (e.reset(), n.hide())
            });
      })();
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTUsersAddUser.init();
});
