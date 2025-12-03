"use strict";
var KTUsersAddPermission = (function () {
  const t = document.getElementById("kt_modal_add_project"),
    e = t.querySelector("#kt_modal_add_project_form"),
    n = new bootstrap.Modal(t);
  return {
    init: function () {
      (() => {
        var o = FormValidation.formValidation(e, {
          fields: {
            'projets[nom]': {
              validators: {
                notEmpty: { message: "Le libellé est requis" },
              },
            },
            'projets[dateDebut]': {
              validators: {
                notEmpty: { message: "La date de début est requise" },
              },
            },
            'projets[dateFin]': {
              validators: {
                notEmpty: { message: "La date de fin est requise" },
              },
            },
            'projets[client]': {
              validators: {
                notEmpty: { message: "Veuillez sélectionner un client" },
              },
            },
            'projets[secteurActivite]': {
              validators: {
                notEmpty: { message: "Veuillez sélectionner un client" },
              },
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
          .querySelector('[data-kt-project-modal-action="close"]')
          .addEventListener("click", (t) => {
            t.preventDefault(),
            n.hide();
          }),
          t
            .querySelector('[data-kt-project-modal-action="cancel"]')
            .addEventListener("click", (t) => {
                t.preventDefault(),
                (e.reset(), n.hide())
            });
        const i = t.querySelector(
          '[data-kt-project-modal-action="submit"]'
        );
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
                        confirmButtonText: "Ok, compris !",
                        customClass: { confirmButton: "btn btn-primary" },
                      });
              });
        });
      })();
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTUsersAddPermission.init();
});
