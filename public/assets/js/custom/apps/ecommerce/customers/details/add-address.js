"use strict";
var KTModalAddAddress = (function () {
  var t, e, n, o, r, i;
  return {
    init: function () {
      (i = new bootstrap.Modal(
        document.querySelector("#kt_modal_add_address")
      )),
        (r = document.querySelector("#kt_modal_add_address_form")),
        (t = r.querySelector("#kt_modal_add_address_submit")),
        (e = r.querySelector("#kt_modal_add_address_cancel")),
        (n = r.querySelector("#kt_modal_add_address_close")),
        (o = FormValidation.formValidation(r, {
          fields: {
            'enfants[nomComplet]': {
              validators: { notEmpty: { message: "Le nom de l'enfant est requis" } },
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
          o.revalidateField("country");
        }),
        t.addEventListener("click", function (e) {
          e.preventDefault(),
            o &&
              o.validate().then(function (e) {
                console.log("validated!"),
                  "Valid" == e
                    ? (t.setAttribute("data-kt-indicator", "on"),
                      (t.disabled = !0),
                      setTimeout(function () {
                        t.removeAttribute("data-kt-indicator"),
                        (i.hide(), (t.disabled = !1), r.submit());
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
        n.addEventListener("click", function (t) {
          t.preventDefault(),
          (r.reset(), i.hide())
        });
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTModalAddAddress.init();
});
