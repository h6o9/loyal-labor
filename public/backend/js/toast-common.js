"use strict";

(function () {
    if (typeof iziToast === "undefined") {
        return;
    }

    var toastDefaults = {
        position: "topRight",
        timeout: 2200,
        resetOnHover: true,
        pauseOnHover: true,
        progressBar: true,
        closeOnClick: true,
        displayMode: 0,
        layout: 2,
        balloon: false,
        animateInside: false,
        transitionIn: "fadeInRight",
        transitionOut: "fadeOutRight",
        transitionInMobile: "fadeInRight",
        transitionOutMobile: "fadeOutRight"
    };

    iziToast.settings(toastDefaults);

    function showToast(type, message, options) {
        var config = Object.assign({}, toastDefaults, options || {}, {
            message: message
        });

        if (typeof iziToast[type] === "function") {
            return iziToast[type](config);
        }
    }

    window.showToast = {
        success: function (message, options) {
            return showToast("success", message, options);
        },
        error: function (message, options) {
            return showToast("error", message, options);
        },
        warning: function (message, options) {
            return showToast("warning", message, options);
        },
        info: function (message, options) {
            return showToast("info", message, options);
        }
    };

    window.toastr = {
        success: function (message, title) {
            return showToast("success", message, title ? { title: title } : undefined);
        },
        error: function (message, title) {
            return showToast("error", message, title ? { title: title } : undefined);
        },
        warning: function (message, title) {
            return showToast("warning", message, title ? { title: title } : undefined);
        },
        info: function (message, title) {
            return showToast("info", message, title ? { title: title } : undefined);
        }
    };
})();
