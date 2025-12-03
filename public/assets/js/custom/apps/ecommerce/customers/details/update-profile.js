"use strict";
var KTEcommerceUpdateProfile = (function () {
  var e, t, i;
  return {
    init: function () {
      (i = document.querySelector("#kt_ecommerce_customer_profile")),
        (e = i.querySelector("#kt_ecommerce_customer_profile_submit")),
        (t = FormValidation.formValidation(i, {
          fields: {
            'agents_form[nom]': {
              validators: {
                notEmpty: { message: "Le nom de l'agent est requis" },
              },
            },
            'agents_form[civilite]': {
              validators: {
                notEmpty: { message: "Veuillez sélectionner une civilité" },
              },
            },
            'agents_form[anniversaire]': {
              validators: {
                notEmpty: { message: "Veuillez choisir une date" },
              },
            },
            'agents_form[email]': {
              validators: {
                  emailAddress: {
                      message: 'Veuillez saisir une adresse Email valide'
                  },
                  notEmpty: {
                      message: 'L\'adresse Email est requise'
                  }
              }
            },
            "agents_form[fonction]": {
                validators: {
                    notEmpty: {
                        message: 'La fonction est requise'
                    }
                }
            },
            "agents_form[agence]": {
                validators: {
                    notEmpty: {
                        message: 'Choisissez une agence'
                    }
                }
            },
            "agents_form[direction]": {
                validators: {
                    notEmpty: {
                        message: 'Choisissez une direction'
                    }
                }
            },
            "agents_form[service]": {
                validators: {
                    notEmpty: {
                        message: 'Choisissez un service'
                    }
                }
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
        })),
        e.addEventListener("click", function (c) {
          c.preventDefault(),
            t &&
              t.validate().then(function (t) {
                console.log("validated!"),
                  "Valid" == t
                    ? (e.setAttribute("data-kt-indicator", "on"),
                      (e.disabled = !0),
                      setTimeout(function () {
                        e.removeAttribute("data-kt-indicator"),
                        (e.disabled = !1,
                            i.submit()
                        );
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
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTEcommerceUpdateProfile.init();
});
