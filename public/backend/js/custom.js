(function ($) {
    "use strict";
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).ready(function () {
        tinymce.init({
            selector: ".summernote",
            menubar: false,
            plugins:
                "anchor autolink charmap link lists searchreplace visualblocks wordcount ",
            toolbar:
                "blocks fontsize | bold italic underline | link | align lineheight | numlist bullist",
            tinycomments_mode: "embedded",
            tinycomments_author: "Author name",
            mergetags_list: [
                {
                    value: "First.Name",
                    title: "First Name",
                },
                {
                    value: "Email",
                    title: "Email",
                },
            ],
        });
        $(".select2").select2();
        $(".sub_cat_one").select2();
        const inputElem = document.querySelector('.tags')
        if (inputElem) {
            var tagify = new Tagify(inputElem)
        }
        $(".datetimepicker_mask").datetimepicker({
            format: "Y-m-d H:i",
        });

        $(".custom-icon-picker").iconpicker({
            templates: {
                popover:
                    '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
                    '<div class="popover-title"></div><div class="popover-content"></div></div>',
                footer: '<div class="popover-footer"></div>',
                buttons:
                    '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
                    ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
                search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
                iconpicker:
                    '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
                iconpickerItem:
                    '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>',
            },
        });
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd",
            startDate: "-Infinity",
        });
        $(".clockpicker").clockpicker();
        if (typeof window.initBootstrapToggles === 'function') {
            window.initBootstrapToggles(document);
        } else if (typeof $.fn.bootstrapToggle !== 'undefined') {
            $('input[data-toggle="toggle"]').bootstrapToggle();
        }

        // #region agent log
        $(document).on('change', 'input[data-toggle="toggle"]', function () {
            fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Debug-Session-Id': '545283' },
                body: JSON.stringify({
                    sessionId: '545283',
                    location: 'custom.js:toggle-change',
                    message: 'toggle change event fired',
                    data: { id: this.id, checked: this.checked },
                    timestamp: Date.now(),
                    hypothesisId: 'C'
                })
            }).catch(function () {});
        });
        // #endregion

        /* Admin menu search method start */
        const inputSelector = "#search_menu";
        const listSelector = "#admin_menu_list";
        const notFoundSelector = ".not-found-message";

        function filterMenuList() {
            const query = $(inputSelector).val().toLowerCase();
            let hasResults = false;

            $(listSelector + " a").each(function () {
                const areaName = $(this).text().toLowerCase();
                const shouldShow = areaName.includes(query);
                $(this).toggleClass("d-none", !shouldShow);
                if (shouldShow) {
                    hasResults = true;
                }
            });

            // Show or hide the "Not Found" message based on search results
            if (hasResults) {
                $(notFoundSelector).addClass("d-none");
            } else {
                $(notFoundSelector).removeClass("d-none");
            }
        }
        $(inputSelector).on("input focus", function () {
            filterMenuList();
            $(listSelector).removeClass("d-none");
        });

        $(document).on("click", function (e) {
            if (
                !$(e.target).closest(inputSelector).length &&
                !$(e.target).closest(listSelector).length
            ) {
                $(listSelector).addClass("d-none");
            }
        });

        $(document).on("click", ".search-menu-item", function (e) {
            const activeTab = $(this).attr("data-active-tab");
            if (activeTab) {
                localStorage.setItem("activeTab", activeTab);
            }
        });
        /* Admin menu search method end */

        //Translate button text update
        var selectedTranslation = $('#selected-language').text();
        var btnText = `${translate_to} ${selectedTranslation}`;
        $('#translate-btn').text(btnText);
    });

    $(document).on('change', '.on-change-submit', function () {
        $(this).closest('form').submit();
      });

      $(document).on("click", ".activate-default-theme", function (e) {
        var form = $(this).data('form-id');
        $(form).submit();
    });
})(jQuery);

//Tab active setup locally
function activeTabSetupLocally(tabContainerId) {
    "use strict";
    var activeTab = localStorage.getItem(tabContainerId + "ActiveTab");
    if (activeTab) {
        $("#" + tabContainerId + ' a[href="#' + activeTab + '"]').tab("show");
    } else {
        $("#" + tabContainerId + " a:first").tab("show");
    }

    $("#" + tabContainerId + ' a[data-bs-toggle="tab"]').on(
        "shown.bs.tab",
        function (e) {
            localStorage.setItem(
                tabContainerId + "ActiveTab",
                $(e.target).attr("href").substring(1)
            );
        }
    );
}
/**
 * Translates all inputs one by one to the specified language.
 *
 * @param {string} lang - The language to translate the inputs to.
 */
function translateAllTo(lang) {
    if (isDemo == "demo") {
        toastr.error(demo_mode_error);
        return;
    }

    $("#translate-btn").prop("disabled", true);
    $("#update-btn").prop("disabled", true);

    var inputs = $('[data-translate="true"]').toArray();

    var isTranslatingInputs = true;

    function translateOneByOne(inputs, index = 0) {
        if (index >= inputs.length) {
            if (isTranslatingInputs) {
                isTranslatingInputs = false;
                translateAllTextarea();
            }
            $("#translate-btn").prop("disabled", false);
            $("#update-btn").prop("disabled", false);
            return;
        }

        var $input = $(inputs[index]);
        var inputValue = $input.val();

        if (inputValue) {
            $.ajax({
                url: `${base_url}/admin/languages/update-single`,
                type: "POST",
                data: {
                    lang: lang,
                    text: inputValue,
                },
                dataType: "json",
                beforeSend: function () {
                    $input.prop("disabled", true);
                    iziToast.show({
                        timeout: false,
                        close: true,
                        theme: "dark",
                        icon: "loader",
                        iconUrl:
                            "https://hub.izmirnic.com/Files/Images/loading.gif",
                        title: translation_processing,
                        position: "center",
                    });
                },
                success: function (response) {
                    $input.val(response);

                    // check input is tinymce and set content
                    var classesToCheck = ["summernote"];
                    if (classesToCheck.some(cls => $input.hasClass(cls))) {
                        var inputId = $input.attr("id");
                        tinymce.get(inputId).setContent(response);
                    }

                    $input.prop("disabled", false);
                    iziToast.destroy();
                    toastr.success(translation_success);
                    translateOneByOne(inputs, index + 1);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    iziToast.destroy();
                    toastr.error("Error", errorThrown);
                },
            });
        } else {
            translateOneByOne(inputs, index + 1);
        }
    }

    function translateAllTextarea() {
        var inputs = $('textarea[data-translate="true"]').toArray();
        if (inputs.length === 0) {
            return;
        }
        translateOneByOne(inputs);
    }

    translateOneByOne(inputs);
}

// addon side menu hide and show
document.addEventListener("DOMContentLoaded", function () {
    const addonMenu = document.querySelector(".addon_menu");
    const addonSideMenu = document.querySelector("#addon_sidemenu");

    if (addonMenu && addonSideMenu) {
        if (addonMenu.querySelectorAll("li").length === 0) {
            addonSideMenu.style.display = "none";
        }
    }
});

// auto active addon menu when li have class active
document.addEventListener('DOMContentLoaded', () => {
    const addonMenu = document.querySelector('.addon_menu');
    const addonSidemenu = document.getElementById('addon_sidemenu');

    if (addonMenu && addonMenu.querySelector('li.active')) {
        addonSidemenu.classList.add('active', 'show');
    }
});

function convertToSlug(Text) {
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/ +/g, '-');
}