"use strict";
var KTAuthNewPassword = (function () {
  var t,
    e,
    r,
    o,
    n = function () {
      return o.getScore() > 50;
    };
  return {
    init: function () {
      (t = document.querySelector("#kt_new_password_form")),
        (e = document.querySelector("#kt_new_password_submit")),
        (o = KTPasswordMeter.getInstance(
          t.querySelector('[data-kt-password-meter="true"]')
        )),
        (r = FormValidation.formValidation(t, {
          fields: {
            'change_password_form[plainPassword][first]': {
              validators: {
                notEmpty: { message: "Le mot de passe est requis" },
                callback: {
                  message: "Veuillez entrer un mot de passe valide",
                  callback: function (t) {
                    if (t.value.length > 0) return n();
                  },
                },
              },
            },
            'change_password_form[plainPassword][second]': {
              validators: {
                notEmpty: { message: "Veuillez confirmer le mot de passe" },
                identical: {
                  compare: function () {
                    return t.querySelector('[name="change_password_form[plainPassword][first]"]').value;
                  },
                  message: "Le mot de passe et sa confirmation ne sont pas les mêmes",
                },
              },
            },
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger({
              event: { password: !1 },
            }),
            bootstrap: new FormValidation.plugins.Bootstrap5({
              rowSelector: ".fv-row",
              eleInvalidClass: "",
              eleValidClass: "",
            }),
          },
        })),
        t
          .querySelector('input[name="change_password_form[plainPassword][first]"]')
          .addEventListener("input", function () {
            this.value.length > 0 &&
              r.updateFieldStatus('change_password_form[plainPassword][first]', "NotValidated");
          }),
        !(function (t) {
          try {
            return new URL(t), !0;
          } catch (t) {
            return !1;
          }
        })(t.getAttribute("action"))
          ? e.addEventListener("click", function (n) {
              n.preventDefault(),
                r.revalidateField("password"),
                r.validate().then(function (r) {
                  "Valid" == r
                    ? (e.setAttribute("data-kt-indicator", "on"),
                      (e.disabled = !0),
                      setTimeout(function () {
                        e.removeAttribute("data-kt-indicator"),
                          (e.disabled = !1),      
                          o.reset(),
                          t.submit();
                      }, 1500))
                    : Swal.fire({
                        text: "Désolé il semble y avoir une erreur, veuillez réessayer.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, compris !",
                        customClass: { confirmButton: "btn btn-primary" },
                      });
                });
            })
          : e.addEventListener("click", function (o) {
              o.preventDefault(),
                r.revalidateField("password"),
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
                            t.reset();
                            const e = t.getAttribute("data-kt-redirect-url");
                            e && (location.href = e);
                          } else Swal.fire({ text: "Sorry, the email is incorrect, please try again.", icon: "error", buttonsStyling: !1, confirmButtonText: "Ok, compris !", customClass: { confirmButton: "btn btn-primary" } });
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
  KTAuthNewPassword.init();
});
