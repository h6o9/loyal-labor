"use strict";

(function () {
    if (typeof iziToast === "undefined") {
        return;
    }

    var toastDefaults = {
        position: "topRight",
        timeout: 5000,
        closeOnClick: true,
        pauseOnHover: true,
        progressBar: true,
        animateInside: true,
        transitionIn: "fadeInDown",
        transitionOut: "fadeOutUp"
    };

    iziToast.settings(toastDefaults);

    function showToast(type, message, options) {
        var config = Object.assign({}, toastDefaults, options || {}, {
            message: message
        });

        if (typeof iziToast[type] === "function") {
            iziToast[type](config);
        }
    }

    window.showToast = {
        success: function (message, options) {
            showToast("success", message, options);
        },
        error: function (message, options) {
            showToast("error", message, options);
        },
        warning: function (message, options) {
            showToast("warning", message, options);
        },
        info: function (message, options) {
            showToast("info", message, options);
        }
    };

    window.toastr = {
        success: function (message) {
            showToast("success", message);
        },
        error: function (message) {
            showToast("error", message);
        },
        warning: function (message) {
            showToast("warning", message);
        },
        info: function (message) {
            showToast("info", message);
        }
    };
})();
