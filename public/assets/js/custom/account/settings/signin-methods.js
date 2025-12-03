"use strict";
var KTAccountSettingsSigninMethods = (function () {
  var t,
    e,
    n,
    o,
    i,
    s,
    r,
    a,
    l,
    d = function () {
      e.classList.toggle("d-none"),
        s.classList.toggle("d-none"),
        n.classList.toggle("d-none");
    },
    c = function () {
      o.classList.toggle("d-none"),
        a.classList.toggle("d-none"),
        i.classList.toggle("d-none");
    };
  return {
    init: function () {
      var m;
      (t = document.getElementById("kt_signin_change_email")),
        (e = document.getElementById("kt_signin_email")),
        (n = document.getElementById("kt_signin_email_edit")),
        (o = document.getElementById("kt_signin_password")),
        (i = document.getElementById("kt_signin_password_edit")),
        (s = document.getElementById("kt_signin_email_button")),
        (r = document.getElementById("kt_signin_cancel")),
        (a = document.getElementById("kt_signin_password_button")),
        (l = document.getElementById("kt_password_cancel")),
        e &&
          (s.querySelector("button").addEventListener("click", function () {
            d();
          }),
          r.addEventListener("click", function () {
            t.reset(), m.resetForm(), d();
          }),
          a.querySelector("button").addEventListener("click", function () {
            c();
          })
        //   ,
        //   l.addEventListener("click", function () {
        //     document.getElementById("kt_signin_change_password").reset(), e.resetForm(), c();
        //   })
        ),
        t &&
          ((m = FormValidation.formValidation(t, {
            fields: {
                'users[email]': {
                    validators: {
                        notEmpty: { message: "Une adresse email valide est requise" },
                        emailAddress: {
                            message: "Ceci n'est pasune adresse email valide",
                        },
                    },
                },
                'users[currentPassword]': {
                    validators: { notEmpty: { message: "Entrer le mot de passe pour confirmer" } },
                },
            },
            plugins: {
              trigger: new FormValidation.plugins.Trigger(),
              bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: ".fv-row",
              }),
            },
          })),
          t
            .querySelector("#kt_signin_submit")
            .addEventListener("click", function (e) {
              e.preventDefault(),
                console.log("click"),
                m.validate().then(function (e) {
                  "Valid" == e
                    ? (t.querySelector("#kt_signin_submit").setAttribute("data-kt-indicator", "on"),
                        (t.querySelector("#kt_signin_submit").disabled = !0),
                        setTimeout(function () {
                            t.querySelector("#kt_signin_submit").removeAttribute("data-kt-indicator"),
                            (t.querySelector("#kt_signin_submit").disabled = !1),
                            d(), t.submit();
                        }, 2e3)
                    )
                    : swal.fire({
                        text: "Désolé, il semble que des erreurs aient été détectées, veuillez vérifier les champs.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, compris !",
                        customClass: {
                          confirmButton:
                            "btn font-weight-bold btn-light-primary",
                        },
                      });
                });
            })),
        (function (t) {
            var e,
            n = document.getElementById("kt_signin_change_password");
            n &&
            ((e = FormValidation.formValidation(n, {
                fields: {
                    'users[currentPassword]': {
                        validators: {
                            notEmpty: { message: "Veuillez saisir votre mot de passe actuel" },
                        },
                    },
                    'users[password]': {
                        validators: {
                            notEmpty: { message: "Veuillez saisir le nouveau mot de passe" },
                            callback: {
                                message: 'Veuillez saisir un mot de passe valide',
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        },
                    },
                    'users[confirmPassword]': {
                        validators: {
                            notEmpty: { message: "Veuillez confirmer le mot de passe" },
                            identical: {
                            compare: function () {
                                return n.querySelector('[name="users[password]"]').value;
                            },
                            message: "Le mot de passe et sa confirmation ne sont pas les même",
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                    }),
                },
            })),
            n
                .querySelector("#kt_password_submit")
                .addEventListener("click", function (t) {
                    t.preventDefault(),
                    console.log("click"),
                    e.validate().then(function (t) {
                        "Valid" == t
                        ? (n.querySelector("#kt_password_submit").setAttribute("data-kt-indicator", "on"),
                                (n.querySelector("#kt_password_submit").disabled = !0),
                                setTimeout(function () {
                                    n.querySelector("#kt_password_submit").removeAttribute("data-kt-indicator"),
                                    (n.querySelector("#kt_password_submit").disabled = !1),
                                    c(), n.submit();
                                }, 2e3)
                            )
                        : swal.fire({
                            text: "Désolé, il semble que des erreurs aient été détectées, veuillez vérifier les champs.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, compris !",
                            customClass: {
                                confirmButton:
                                "btn font-weight-bold btn-light-primary",
                            },
                        });
                    });
                }))
            ;
            l.addEventListener("click", function () {
                n.reset(), e.resetForm(), c();
            })
        })();
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTAccountSettingsSigninMethods.init();
});
