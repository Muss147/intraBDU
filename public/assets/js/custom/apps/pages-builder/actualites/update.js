"use strict";
var KTModalActualitesUpdate = (function () {
  var t, e, o, n, r, i;
  return {
    init: function () {
      (i = new bootstrap.Modal(
        document.querySelector("#kt_modal_update_actualite")
      )),
        (r = document.querySelector("#kt_modal_update_actualite_form")),
        (t = r.querySelector("#kt_modal_update_actualite_submit")),
        (e = r.querySelector("#kt_modal_update_actualite_reset")),
        (n = FormValidation.formValidation(r, {
          fields: {
            // 'actualite_cover': {
            //     validators: {
            //         notEmpty: {
            //             message: 'Veuillez charger une image'
            //         },
            //         file: {
            //             extension: 'jpg,jpeg,png',
            //             type: 'image/jpeg,image/png',
            //             message: 'Le fichier sélectionné n\'est pas valide'
            //         },
            //     }
            // },
            'actualite_titre': {
              validators: {
                notEmpty: { message: "Le titre est requis" },
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
                        r.submit()
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
        });
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTModalActualitesUpdate.init();
});
