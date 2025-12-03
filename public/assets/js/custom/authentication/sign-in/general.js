"use strict";
var KTSigninGeneral = (function () {
  var t, e, r;
  return {
    init: function () {
      (t = document.querySelector("#kt_sign_in_form")),
        (e = document.querySelector("#kt_sign_in_submit")),
        (r = FormValidation.formValidation(t, {
          fields: {
            '_username': {
              validators: {
                regexp: {
                  regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                  message: "Ceci n'est pas une adresse email valide",
                },
                notEmpty: { message: "L'adresse Email est requise" },
              },
            },
            '_password': {
              validators: { notEmpty: { message: "Le mot de passe est requis" } },
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
        !(function (t) {
          try {
            return new URL(t), !0;
          } catch (t) {
            return !1;
          }
        })(e.closest("form").getAttribute("action"))
          ? e.addEventListener("click", function (i) {
              i.preventDefault(),
                r.validate().then(function (r) {
                  "Valid" == r
                    ? (e.setAttribute("data-kt-indicator", "on"),
                      (e.disabled = !0),
                      setTimeout(function () {
                        e.removeAttribute("data-kt-indicator"),
                          (e.disabled = !1),
                          t.submit()
                      }, 2e3))
                    : Swal.fire({
                        text: "Désolé il semble y avoir une erreur, veuillez vérrifier les champs obligatoires.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, compris !",
                        customClass: { confirmButton: "btn btn-primary" },
                      });
                });
            })
          : e.addEventListener("click", function (i) {
              i.preventDefault(),
                r.validate().then(function (r) {
                  "Valid" == r
                    ? (e.setAttribute("data-kt-indicator", "on"),
                      (e.disabled = !0),
                      axios
                        .post(
                          e.closest("form").getAttribute("action"),
                          new FormData(t)
                        )
                        .then(function (e) {
                          if (e) {
                            t.submit();
                            // t.reset(),
                            // const e = t.getAttribute("data-kt-redirect-url");
                            // e && (location.href = e);
                          } else Swal.fire({ text: "Désolé il semble y avoir une erreur, veuillez réessayer.", icon: "error", buttonsStyling: !1, confirmButtonText: "Ok, compris !", customClass: { confirmButton: "btn btn-primary" } });
                        })
                        .catch(function (t) {
                          Swal.fire({
                            text: "Désolé il semble y avoir une erreur, veuillez réessayer.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, compris !",
                            customClass: { confirmButton: "btn btn-primary" },
                          });
                        })
                        .then(() => {
                          e.removeAttribute("data-kt-indicator"),
                            (e.disabled = !1);
                        }))
                    : Swal.fire({
                        text: "Désolé il semble y avoir une erreur, veuillez réessayer.",
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
  KTSigninGeneral.init();
});
