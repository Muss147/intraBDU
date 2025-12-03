"use strict";
var KTProjectSettings = {
  init: function () {
    !(function () {
      var t;
      $("#kt_datepicker_1").flatpickr();
      var e = document.getElementById("kt_project_settings_form"),
        i = e.querySelector("#kt_project_settings_submit");
      (t = FormValidation.formValidation(e, {
        fields: {
          'projets[nom]': {
            validators: { notEmpty: { message: "Le nom du projet est requis" } },
          },
          'projets[client]': {
            validators: { notEmpty: { message: "Veuillez sélectionner un client" } },
          },
          'projets[secteurActivite]': {
            validators: {
              notEmpty: { message: "Veuillez sélectionner un secteur d'activité" },
            },
          },
          'projets[dateDebut]': {
            validators: { notEmpty: { message: "La date de début est requise" } },
          },
          'projets[dateFint]': {
            validators: { notEmpty: { message: "La date de fin est requise" } },
          },
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          submitButton: new FormValidation.plugins.SubmitButton(),
          bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: ".fv-row",
          }),
        },
      })),
        i.addEventListener("click", function (e) {
          e.preventDefault(),
            t.validate().then(function (t) {
              "Valid" == t
                ? e.submit()
                : swal.fire({
                    text: "Désolé il semble y avoir une erreur, veuillez vérrifier les champs obligatoires.",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, compris !",
                    customClass: {
                      confirmButton: "btn fw-bold btn-light-primary",
                    },
                  });
            });
        });
    })();
  },
};
KTUtil.onDOMContentLoaded(function () {
  KTProjectSettings.init();
});
