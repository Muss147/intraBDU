"use strict";
var KTFileManagerList = (function () {
  var e, t, o, n, r, a, allDeletedPath;
  const l = () => {
      t.querySelectorAll(
        '[data-kt-filemanager-table-filter="delete_row"]'
      ).forEach((t) => {
        t.addEventListener("click", function (t) {
          t.preventDefault();
          const o = t.target.closest("tr"),
            form = t.target.closest("form"),
            n = o.querySelectorAll("td")[1].innerText;
          Swal.fire({
            html: "Êtes-vous sûr de vouloir supprimer l'élément <b>" + n + "</b> ?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Oui, supprimer !",
            cancelButtonText: "No, cancel",
            customClass: {
              confirmButton: "btn fw-bold btn-danger",
              cancelButton: "btn fw-bold btn-active-light-primary",
            },
          }).then(function (t) {
            t.value
              ? (
                  form.submit() //e.row($(o)).remove().draw()
                )
              : "cancel" === t.dismiss
          });
        });
      });
    },
    i = () => {
      var o = t.querySelectorAll('[type="checkbox"]');
      "folders" === t.getAttribute("data-kt-filemanager-table") &&
        (o = document.querySelectorAll(
          '#kt_file_manager_list_wrapper [type="checkbox"]'
        ));
      const n = document.querySelector(
        '[data-kt-filemanager-table-select="delete_selected"]'
      );
      allDeletedPath = n.getAttribute('data-deleted-root');
      o.forEach((e) => {
        e.addEventListener("click", function () {
          console.log(e),
            setTimeout(function () {
              s();
            }, 50);
        });
      }),
        n.addEventListener("click", function () {
          Swal.fire({
            text: "Êtes-vous sûr de vouloir supprimer la sélection ?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Oui, tout supprimer !",
            cancelButtonText: "Non, annuler",
            customClass: {
              confirmButton: "btn fw-bold btn-danger",
              cancelButton: "btn fw-bold btn-active-light-primary",
            },
          }).then(function (n) {
            var arraysOfIds = [];
            n.value
              ? (
                  o.forEach((t) => {
                    t.checked &&
                      e
                        .row($(t.closest("tbody tr")))
                        .remove()
                        .draw(),
                        (t.value != 0) && arraysOfIds.push(t.value)
                  }),
                  t.querySelectorAll('[type="checkbox"]')[0].checked = !1,
                  sendIdsToDelete(arraysOfIds)
                )
              : "cancel" === n.dismiss
          });
        });
    },
    s = () => {
      const e = document.querySelector(
          '[data-kt-filemanager-table-toolbar="base"]'
        ),
        o = document.querySelector(
          '[data-kt-filemanager-table-toolbar="selected"]'
        ),
        n = document.querySelector(
          '[data-kt-filemanager-table-select="selected_count"]'
        ),
        r = t.querySelectorAll('tbody [type="checkbox"]');
      let a = !1,
        l = 0;
      r.forEach((e) => {
        e.checked && ((a = !0), l++);
      }),
        a
          ? ((n.innerHTML = l),
            e.classList.add("d-none"),
            o.classList.remove("d-none"))
          : (e.classList.remove("d-none"), o.classList.add("d-none"));
    },
    c = () => {
      const e = t.querySelector("#kt_file_manager_new_folder_row");
      e && e.parentNode.removeChild(e);
    },
    d = () => {
      t.querySelectorAll('[data-kt-filemanager-table="rename"]').forEach(
        (e) => {
          e.addEventListener("click", u);
        }
      );
    },
    u = (o) => {
      let r;
      if (
        (o.preventDefault(),
        t.querySelectorAll("#kt_file_manager_rename_input").length > 0)
      )
        return void Swal.fire({
          text: "Une modification non enregistrée a été détecté. Veuillez enregistrer ou annuler l'élément en cours",
          icon: "warning",
          buttonsStyling: !1,
          confirmButtonText: "Ok, compris!",
          customClass: { confirmButton: "btn fw-bold btn-danger" },
        });
      const a = o.target.closest("tr"),
        id = a.querySelector('input[type="checkbox"]').value,
        l = a.querySelectorAll("td")[1],
        i = l.querySelector(".icon-wrapper");
      r = l.innerText;
      const s = n.cloneNode(!0);
      (s.querySelector("#kt_file_manager_rename_folder_icon").innerHTML =
        i.outerHTML),
        (l.innerHTML = s.innerHTML),
        (a.querySelector('[name="param_id"]').value = id),
        (a.querySelector("#kt_file_manager_rename_input").value = r);
      var c = FormValidation.formValidation(l, {
        fields: {
          param_libelle: {
            validators: { notEmpty: { message: "Le libellé est requis" } },
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
      });
      document
        .querySelector("#kt_file_manager_rename_folder")
        .addEventListener("click", (t) => {
          t.preventDefault(),
            c &&
              c.validate().then(function (t) {
                console.log("validated!"),
                  "Valid" == t &&
                    Swal.fire({
                      html: "Êtes-vous sûr de vouloir modifier l'élément <b>" + r + "</b> ?",
                      icon: "warning",
                      showCancelButton: !0,
                      buttonsStyling: !1,
                      confirmButtonText: "Oui, je suis sûr !",
                      cancelButtonText: "Non, annuler",
                      customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                      },
                    }).then(function (t) {
                      t.value
                        ? document.querySelector('#form-edit-param').submit()
                        : "cancel" === t.dismiss
                    });
              });
        });
      const d = document.querySelector("#kt_file_manager_rename_folder_cancel");
      d.addEventListener("click", (t) => {
        t.preventDefault(),
          d.setAttribute("data-kt-indicator", "on"),
          setTimeout(function () {
            const t = `<div class="d-flex align-items-center">\n                    ${i.outerHTML}\n                    <a href="?page=apps/file-manager/files/" class="text-gray-800 text-hover-primary">${r}</a>\n                </div>`;
            d.removeAttribute("data-kt-indicator"),
              e.cell($(l)).data(t).draw(),
              (toastr.options = {
                closeButton: !0,
                debug: !1,
                newestOnTop: !1,
                progressBar: !1,
                positionClass: "toastr-top-right",
                preventDuplicates: !1,
                showDuration: "300",
                hideDuration: "1000",
                timeOut: "5000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
              }),
              toastr.error("Modification annulée");
          }, 1e3);
      });
    },
    f = () => {
      document.getElementById("kt_file_manager_items_counter").innerText =
        e.rows().count() + " éléments";
    };
    const sendIdsToDelete = (ids) => {
        $.ajax({
            url: allDeletedPath, // Remplacez par l'URL de votre route
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ docsToDelete: ids }), // Convertit le tableau en JSON
            success: function(response) {
                console.log('Réponse du serveur:', response);
            },
            error: function(xhr, status, error) {
                console.error('Erreur:', status, error);
            }
        });
    };
  return {
    init: function () {
      (t = document.querySelector("#kt_file_manager_list")) &&
        ((o = document.querySelector(
          '[data-kt-filemanager-template="upload"]'
        )),
        (n = document.querySelector('[data-kt-filemanager-template="rename"]')),
        (r = document.querySelector('[data-kt-filemanager-template="action"]')),
        (a = document.querySelector(
          '[data-kt-filemanager-template="checkbox"]'
        )),
        (() => {
          t.querySelectorAll("tbody tr").forEach((e) => {
            const t = e.querySelectorAll("td")[4],
              o = moment(t.innerHTML, "DD MMM YYYY, LT").format();
            t.setAttribute("data-order", o);
          });
          const o = {
              info: !1,
              order: [],
              scrollY: "700px",
              scrollCollapse: !0,
              paging: !1,
              ordering: !1,
              columns: [
                { data: "checkbox" },
                { data: "name" },
                { data: "elt" },
                { data: "size" },
                { data: "date" },
                { data: "action" },
              ],
              language: {
                emptyTable: `<div class="d-flex flex-column flex-center">\n                    <img src="${hostUrl}media/illustrations/sketchy-1/5.png" class="mw-400px" />\n                    <div class="fs-1 fw-bolder text-dark">Aucun élément trouvé.</div>\n                    <div class="fs-6">Commencez à créer de nouveaux dossiers ou à télécharger un nouveau fichier !</div>\n                </div>`,
              },
            },
            n = {
              info: !1,
              order: [],
              pageLength: 10,
              lengthChange: !1,
              ordering: !1,
              columns: [
                { data: "checkbox" },
                { data: "name" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "item" },
                { data: "date" },
                { data: "action" },
              ],
              language: {
                emptyTable: `<div class="d-flex flex-column flex-center">\n                    <img src="${hostUrl}media/illustrations/sketchy-1/5.png" class="mw-400px" />\n                    <div class="fs-1 fw-bolder text-dark mb-4">Aucun élément trouvé.</div>\n                    <div class="fs-6">Commencez à créer de nouveaux dossiers ou à télécharger un nouveau fichier !</div>\n                </div>`,
              },
              conditionalPaging: !0,
            };
          var r;
          (r =
            "folders" === t.getAttribute("data-kt-filemanager-table") ? o : n),
            (e = $(t).DataTable(r)).on("draw", function () {
              i(), l(), s(), c(), KTMenu.createInstances(), f(), d();
            });
        })(),
        i(),
        document
          .querySelector('[data-kt-filemanager-table-filter="search"]')
          .addEventListener("keyup", function (t) {
            e.search(t.target.value).draw();
          }),
        l(),
        document
          .getElementById("kt_file_manager_new_folder")
          .addEventListener("click", (n) => {
            if (
              (n.preventDefault(),
              t.querySelector("#kt_file_manager_new_folder_row"))
            )
              return;
            const l = t.querySelector("tbody"),
              i = o.cloneNode(!0);
            l.prepend(i);
            const s = i.querySelector("#kt_file_manager_add_folder_form"),
              d = i.querySelector("#kt_file_manager_add_folder"),
              u = i.querySelector("#kt_file_manager_cancel_folder"),
              m = i.querySelector("#kt_file_manager_folder_icon"),
              f = i.querySelector('[name="parametres[libelle]"]');
            var p = FormValidation.formValidation(s, {
              fields: {
                'parametres[libelle]': {
                  validators: {
                    notEmpty: { message: "Le libellé est requis" },
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
            });
            d.addEventListener("click", (t) => {
              t.preventDefault(),
                d.setAttribute("data-kt-indicator", "on"),
                p &&
                  p.validate().then(function (t) {
                    console.log("validated!"),
                    "Valid" == t
                        ? setTimeout(function () {
                            s.querySelector('form').submit();

                            // (toastr.options = {
                            //     closeButton: !0,
                            //     debug: !1,
                            //     newestOnTop: !1,
                            //     progressBar: !1,
                            //     positionClass: "toastr-top-right",
                            //     preventDuplicates: !1,
                            //     showDuration: "300",
                            //     hideDuration: "1000",
                            //     timeOut: "5000",
                            //     extendedTimeOut: "1000",
                            //     showEasing: "swing",
                            //     hideEasing: "linear",
                            //     showMethod: "fadeIn",
                            //     hideMethod: "fadeOut",
                            // }),
                            // toastr.success(f.value + " was created!"),
                            // d.removeAttribute("data-kt-indicator"),
                            // (f.value = ""),
                            // e.draw(!1);
                        }, 2e3)
                        : d.removeAttribute("data-kt-indicator");
                    });
            }),
              u.addEventListener("click", (e) => {
                e.preventDefault(),
                  u.setAttribute("data-kt-indicator", "on"),
                  setTimeout(function () {
                    u.removeAttribute("data-kt-indicator"),
                      (toastr.options = {
                        closeButton: !0,
                        debug: !1,
                        newestOnTop: !1,
                        progressBar: !1,
                        positionClass: "toastr-top-right",
                        preventDuplicates: !1,
                        showDuration: "300",
                        hideDuration: "1000",
                        timeOut: "5000",
                        extendedTimeOut: "1000",
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                      }),
                      toastr.error("Création annulée"),
                      c();
                  }, 1e3);
              });
          }),
        d(),
        (() => {
          const e = document.querySelector("#kt_modal_move_to_folder"),
            t = e.querySelector("#kt_modal_move_to_folder_form"),
            o = t.querySelector("#kt_modal_move_to_folder_submit"),
            n = new bootstrap.Modal(e);
          var r = FormValidation.formValidation(t, {
            fields: {
              param_parent: {
                validators: {
                  notEmpty: { message: "Veuillez sélectionner un dossier." },
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
          });
          o.addEventListener("click", (e) => {
            e.preventDefault(),
              o.setAttribute("data-kt-indicator", "on"),
              r &&
                r.validate().then(function (e) {
                  console.log("validated!"),
                    "Valid" == e
                      ? setTimeout(function () {
                          Swal.fire({
                            text: "Êtes-vous sûr de vouloir déplacer vers ce dossier ?",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Oui, déplacer!",
                            cancelButtonText: "Non, annuler",
                            customClass: {
                              confirmButton: "btn btn-primary",
                              cancelButton: "btn btn-active-light",
                            },
                          }).then(function (e) {
                            e.isConfirmed
                              ? (t.submit(),
                                n.hide(),
                                o.removeAttribute("data-kt-indicator"))
                              : o.removeAttribute("data-kt-indicator");
                          });
                        }, 500)
                      : o.removeAttribute("data-kt-indicator");
                });
          });
        })(),
        f(),
        KTMenu.createInstances());
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTFileManagerList.init();
});
