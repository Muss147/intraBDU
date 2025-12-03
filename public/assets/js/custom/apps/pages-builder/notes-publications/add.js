"use strict";
var KTModalNotesAdd = (function () {
  var t, e, o, n, r, i;
  return {
    init: function () {
      (i = new bootstrap.Modal(
        document.querySelector("#kt_modal_add_note")
      )),
        (r = document.querySelector("#kt_modal_add_note_form")),
        (t = r.querySelector("#kt_modal_add_note_submit")),
        (e = r.querySelector("#kt_modal_add_note_reset")),
        (n = FormValidation.formValidation(r, {
          fields: {
            'notes_publications_form[titre]': {
              validators: {
                notEmpty: { message: "Le titre est requis" },
              },
            },
            'notes_publications_form[resume]': {
              validators: {
                notEmpty: { message: "Le résumé est requis" },
              },
            },
            'notes_publications_form[description]': {
              validators: {
                notEmpty: { message: "La description est requise" },
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
  KTModalNotesAdd.init();
});
