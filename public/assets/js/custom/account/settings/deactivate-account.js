"use strict";
var KTAccountSettingsDeactivateAccount = (function () {
  var f, n, e;
  return {
    init: function () {
      (f = document.querySelector("#kt_account_deactivate_form")) &&
        ((e = document.querySelector("#kt_account_deactivate_account_submit")),
        (n = FormValidation.formValidation(f, {
          fields: {
            'deactivate': {
              validators: {
                notEmpty: {
                  message: "Veuillez cocher la case pour désactiver le compte",
                },
              },
            },
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            submitButton: new FormValidation.plugins.SubmitButton(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
              rowSelector: ".fv-row",
              eleInvalidClass: "",
              eleValidClass: "",
            }),
          },
        })),
        e.addEventListener("click", function (t) {
          t.preventDefault(),
            n.validate().then(function (t) {
              "Valid" == t
                ? swal
                    .fire({
                      text: "Êtes-vous sûr de vouloir désactiver votre compte ?",
                      icon: "warning",
                      buttonsStyling: !1,
                      showDenyButton: !0,
                      confirmButtonText: "Oui",
                      denyButtonText: "Non, annuler",
                      customClass: {
                        confirmButton: "btn btn-light-primary",
                        denyButton: "btn btn-danger",
                      },
                    })
                    .then((t) => {
                      t.isConfirmed
                        ? f.submit()
                        : t.isDenied;
                    })
                : swal.fire({
                    text: "Désolé, il semble que des erreurs aient été détectées, veuillez vérifier les champs.",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, compris !",
                    customClass: { confirmButton: "btn btn-light-primary" },
                  });
            });
        }));
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTAccountSettingsDeactivateAccount.init();
});
