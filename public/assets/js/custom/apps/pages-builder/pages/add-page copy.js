"use strict";
var KTAppEcommerceSaveCategory = (function () {
    const t = () => {
            document
                .querySelectorAll(
                    '[data-kt-ecommerce-catalog-add-category="condition_type"]'
                )
                .forEach((e) => {
                    $(e).hasClass("select2-hidden-accessible") ||
                        $(e).select2({ minimumResultsForSearch: -1 });
                });
            document
                .querySelectorAll(
                    '[data-kt-ecommerce-catalog-add-category="condition_equals"]'
                )
                .forEach((e) => {
                    $(e).hasClass("select2-hidden-accessible") ||
                        $(e).select2({ minimumResultsForSearch: -1 });
                });
        };
    return {
        init: function () {
            [
                "#kt_ecommerce_add_category_description",
                "#kt_ecommerce_add_category_meta_description",
            ].forEach((e) => {
                let t = document.querySelector(e);
                t &&
                    (t = new Quill(e, {
                        modules: {
                            toolbar: [
                                [{ header: [1, 2, !1] }],
                                ["bold", "italic", "underline"],
                                ["image", "code-block"],
                            ],
                        },
                        placeholder: "Type your text here...",
                        theme: "snow",
                    }));
            }),
            ["#kt_ecommerce_add_category_meta_keywords"].forEach((e) => {
                const t = document.querySelector(e);
                t && new Tagify(t);
            }),
            t(),
            (() => {
                const e = document.getElementById(
                        "kt_ecommerce_add_category_status"
                    ),
                    t = document.getElementById(
                        "pages_form_statut"
                    ),
                    o = ["bg-success", "bg-warning", "bg-danger"];
                $(t).on("change", function (t) {
                    // alert(t.target.value);
                    switch (t.target.value) {
                        case "1":
                            e.classList.remove(...o),
                                e.classList.add("bg-success");
                            break;
                        case "0":
                            e.classList.remove(...o),
                                e.classList.add("bg-danger");
                    }
                });
            })(),
            (() => {
                const e = document.querySelectorAll(
                        '[name="method"][type="radio"]'
                    ),
                    t = document.querySelector(
                        '[data-kt-ecommerce-catalog-add-category="auto-options"]'
                    );
                e.forEach((e) => {
                    e.addEventListener("change", (e) => {
                        "1" === e.target.value
                            ? t.classList.remove("d-none")
                            : t.classList.add("d-none");
                    });
                });
            })(),
            (() => {
                let e;
                const t = document.getElementById(
                        "kt_ecommerce_add_category_form"
                    ),
                    o = document.getElementById(
                        "kt_ecommerce_add_category_submit"
                    );
                (e = FormValidation.formValidation(t, {
                    fields: {
                        'pages_form[titre]': {
                            validators: {
                                notEmpty: {
                                    message: "Le nom de la page est requis",
                                },
                            },
                        },
                        'pages_form[emplacement]': {
                            validators: {
                                notEmpty: {
                                    message: "Veuillez sélectionner un emplacement",
                                },
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
                    o.addEventListener("click", (a) => {
                        a.preventDefault(),
                            e &&
                                e.validate().then(function (e) {
                                    console.log("validated!"),
                                        "Valid" == e
                                            ? (o.setAttribute(
                                                    "data-kt-indicator",
                                                    "on"
                                                ),
                                                (o.disabled = !0),
                                                setTimeout(function () {
                                                    o.removeAttribute(
                                                        "data-kt-indicator"
                                                    ),
                                                    t.submit();
                                                }, 2e3))
                                            : Swal.fire({
                                                    text: "Désolé, il semble que des erreurs aient été détectées. Veuillez réessayer.",
                                                    icon: "error",
                                                    buttonsStyling: !1,
                                                    confirmButtonText:
                                                        "Ok, compris !",
                                                    customClass: {
                                                        confirmButton:
                                                            "btn btn-primary",
                                                    },
                                                });
                                });
                    });
            })();
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSaveCategory.init();
});
