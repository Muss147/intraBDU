"use strict";
var KTAppEcommerceSaveProduct = (function () {
    const t = () => {
            document
                .querySelectorAll(
                    '[data-kt-ecommerce-catalog-add-product="product_option"]'
                )
                .forEach((e) => {
                    $(e).hasClass("select2-hidden-accessible") ||
                        $(e).select2({ minimumResultsForSearch: -1 });
                });
        };
    return {
        init: function () {
            var o, a;
            [
                "#page_content",
                "#style_content",
                "#script_content",
            ].forEach((e) => {
                let t = document.querySelector(e);
                // Cibler le champ caché de Symfony
                const hiddenInput = t.closest('.content_div').querySelector(".hidden_input");
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

                // Initialiser avec la valeur existante (utile en édition)
                if (hiddenInput.value) {
                    t.root.innerHTML = hiddenInput.value;
                }

                // Synchroniser Quill -> champ caché à chaque changement
                t.on("text-change", function () {
                    hiddenInput.value = t.root.innerHTML;
                });
            }),
                [
                    "#kt_ecommerce_add_product_category",
                    "#kt_ecommerce_add_product_tags",
                ].forEach((e) => {
                    const t = document.querySelector(e);
                    t &&
                        new Tagify(t, {
                            whitelist: [
                                "new",
                                "trending",
                                "sale",
                                "discounted",
                                "selling fast",
                                "last 10",
                            ],
                            dropdown: {
                                maxItems: 20,
                                classname: "tagify__inline__suggestions",
                                enabled: 0,
                                closeOnSelect: !1,
                            },
                        });
                }),
                t(),
                (() => {
                    const e = document.getElementById(
                            "kt_ecommerce_add_product_status"
                        ),
                        t = document.getElementById(
                            "kt_ecommerce_add_product_status_select"
                        ),
                        o = ["bg-success", "bg-warning", "bg-danger"];
                    $(t).on("change", function (t) {
                        switch (t.target.value) {
                            case "published":
                                e.classList.remove(...o),
                                    e.classList.add("bg-success");
                                break;
                            case "scheduled":
                                e.classList.remove(...o),
                                    e.classList.add("bg-warning");
                                break;
                            case "inactive":
                                e.classList.remove(...o),
                                    e.classList.add("bg-danger");
                                break;
                            case "draft":
                                e.classList.remove(...o),
                                    e.classList.add("bg-[#2b62aa]");
                        }
                    });
                })(),
                (() => {
                    let e;
                    const t = document.getElementById(
                            "kt_ecommerce_add_product_form"
                        ),
                        o = document.getElementById(
                            "kt_ecommerce_add_product_submit"
                        );
                    (e = FormValidation.formValidation(t, {
                        fields: {
                            product_name: {
                                validators: {
                                    notEmpty: {
                                        message: "Product name is required",
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
                                                      ((o.disabled = !1), t.submit());
                                                  }, 2e3))
                                                : Swal.fire({
                                                      html: "Sorry, looks like there are some errors detected, please try again. <br/><br/>Please note that there may be errors in the <strong>General</strong> or <strong>Advanced</strong> tabs",
                                                      icon: "error",
                                                      buttonsStyling: !1,
                                                      confirmButtonText:
                                                          "Ok, got it!",
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
    KTAppEcommerceSaveProduct.init();
});
