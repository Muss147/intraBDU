"use strict";
var KTModalCustomersAdd = (function () {
  var t, e, o, n, r, i;
  return {
    init: function () {
      (i = new bootstrap.Modal(
        document.querySelector("#kt_modal_add_customer")
      )),
        (r = document.querySelector("#kt_modal_add_customer_form")),
        (t = r.querySelector("#kt_modal_add_customer_submit")),
        (e = r.querySelector("#kt_modal_add_customer_cancel")),
        (o = r.querySelector("#kt_modal_add_customer_close")),
        (n = FormValidation.formValidation(r, {
          fields: {
            'agents_form[matricule]': {
              validators: {
                notEmpty: { message: "Le matricule de l'agent est requis" },
              },
            },
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
            'agents_form[fixe]': {
              validators: {
                  notEmpty: {
                      message: 'Le numéro IP est requis'
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
            "agence": {
                validators: {
                    notEmpty: {
                        message: 'Choisissez une agence'
                    }
                }
            },
            "direction": {
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
        $(r.querySelector('[name="country"]')).on("change", function () {
          n.revalidateField("country");
        }),
        t.addEventListener("click", function (e) {
          e.preventDefault(),
            n &&
              n.validate().then(function (e) {
                console.log("validated!"),
                  "Valid" == e
                    ? (t.setAttribute("data-kt-indicator", "on"),
                      (t.disabled = !0),
                      setTimeout(function () {
                        t.removeAttribute("data-kt-indicator"),
                        (i.hide(),
                            (t.disabled = !1),
                            r.submit()
                        )
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
        e.addEventListener("click", function (t) {
          t.preventDefault(),
            (r.reset(), i.hide())
        }),
        o.addEventListener("click", function (t) {
          t.preventDefault(),
            (r.reset(), i.hide())
        });
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTModalCustomersAdd.init();
});
