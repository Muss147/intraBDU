"use strict";
var KTUsersPermissionsList = (function () {
  var t, e;
  return {
    init: function () {
      (e = document.querySelector("#kt_media_card")) &&
        (e
          .querySelectorAll('[data-kt-media-card-filter="delete_card"]')
          .forEach((e) => {
            e.addEventListener("click", function (e) {
              e.preventDefault();
              const form = e.target.closest("form");
              const n = e.target.closest(".card"),
                o = n.querySelectorAll(".card-info")[0].innerText;
              Swal.fire({
                html: "Êtes-vous sûr de vouloir supprimer <b>" + o + "</b> ?",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Oui, supprimer !",
                cancelButtonText: "Non, annuler",
                customClass: {
                  confirmButton: "btn fw-bold btn-danger",
                  cancelButton: "btn fw-bold btn-active-light-primary",
                },
              }).then(function (e) {
                e.value
                  ? form.submit()
                  : "cancel" === e.dismiss
              });
            });
          }));
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTUsersPermissionsList.init();
});
