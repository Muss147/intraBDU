"use strict";
var KTUsersAddRole = (function () {
  const t = document.getElementById("kt_modal_add_role"),
    e = t.querySelector("#kt_modal_add_role_form"),
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
              n.hide();
          }),
          t
            .querySelector('[data-kt-roles-modal-action="cancel"]')
            .addEventListener("click", (t) => {
                t.preventDefault(),
                (e.reset(), n.hide())
            });
        const r = t.querySelector('[data-kt-roles-modal-action="submit"]');
        r.addEventListener("click", function (t) {
          t.preventDefault(),
            o &&
              o.validate().then(function (t) {
                console.log("validated!"),
                  "Valid" == t
                    ? (r.setAttribute("data-kt-indicator", "on"),
                      (r.disabled = !0),
                      setTimeout(function () {
                        r.removeAttribute("data-kt-indicator"),
                          (r.disabled = !1),
                          n.hide(),
                          e.submit();
                      }, 2e3))
                    : Swal.fire({
                        text: "Désolé il semble y avoir une erreur, veuillez vérrifier les champs obligatoires.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, compris !",
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
  KTUsersAddRole.init();
});
