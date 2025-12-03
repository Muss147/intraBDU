"use strict";
var KTUsersUpdatePermissions = (function () {
  const t = document.getElementById("kt_modal_update_role"),
    e = t.querySelector("#kt_modal_update_role_form"),
    n = new bootstrap.Modal(t);
  return {
    init: function () {
      (() => {
        var o = FormValidation.formValidation(e, {
          fields: {
            'roles[libelle]': {
              validators: { notEmpty: { message: "Le nom du rôle est requis" } },
            },
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
        t
          .querySelector('[data-kt-roles-modal-action="close"]')
          .addEventListener("click", (t) => {
            t.preventDefault(),
              n.hide()
          }),
          t
            .querySelector('[data-kt-roles-modal-action="cancel"]')
            .addEventListener("click", (t) => {
              t.preventDefault(),
                (e.submit(), n.hide())
            });
        const i = t.querySelector('[data-kt-roles-modal-action="submit"]');
        i.addEventListener("click", function (t) {
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
                            n.hide();
                      }, 2e3))
                    : Swal.fire({
                        text: "Désolé il semble y avoir une erreur, veuillez vérrifier les champs obligatoires.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, compris ",
                        customClass: { confirmButton: "btn btn-primary" },
                      });
              });
        });
      })(),
        (() => {
          const t = e.querySelector("#roles_allAccess"),
            n = e.querySelectorAll('[type="checkbox"]');
          t.addEventListener("change", (t) => {
            n.forEach((e) => {
              e.checked = t.target.checked;
            });
          });
        })();
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTUsersUpdatePermissions.init();
});
