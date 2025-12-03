"use strict";
var KTModalUpdateAddress = (function () {
  var t, e, n, o, r, i, a;
  return {
    init: function () {
      (t = document.querySelector("#kt_modal_update_address")),
        (i = new bootstrap.Modal(t)),
        (r = t.querySelector("#kt_modal_update_address_form")),
        (e = r.querySelector("#kt_modal_update_address_submit")),
        (n = r.querySelector("#kt_modal_update_address_cancel")),
        (o = t.querySelector("#kt_modal_update_address_close")),
        (a = FormValidation.formValidation(r, {
          fields: {
            'clients[nom]': {
              validators: { notEmpty: { message: "Le nom du client est requis" } },
            },
            'clients[registreCommerce]': {
              validators: { notEmpty: { message: "Le numéro du registre de commerce est requis" } },
            },
            'clients[regimeImposition]': {
              validators: { notEmpty: { message: "Le regime d'imposition est requis" } },
            },
            'clients[telephone]': {
              validators: { notEmpty: { message: "Le numéro de téléphone est requis" } },
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
          a.revalidateField("country");
        }),
        e.addEventListener("click", function (t) {
          t.preventDefault(),
            a &&
              a.validate().then(function (t) {
                console.log("validated!"),
                  "Valid" == t
                    ? (e.setAttribute("data-kt-indicator", "on"),
                      (e.disabled = !0),
                      setTimeout(function () {
                        e.removeAttribute("data-kt-indicator"),
                        (i.hide(), (e.disabled = !1), r.submit());
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
        n.addEventListener("click", function (t) {
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
  KTModalUpdateAddress.init();
});
