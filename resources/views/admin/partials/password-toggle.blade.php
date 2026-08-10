<style>
    .password-input-wrap {
        position: relative;
        display: block;
        width: 100%;
    }
    .password-input-wrap .form-control,
    .password-input-wrap .password-field-input {
        padding-right: 2.85rem !important;
    }
    .password-toggle-btn {
        position: absolute;
        right: 2px;
        top: 50%;
        transform: translateY(-50%);
        border: 0 !important;
        background: transparent !important;
        color: #6c757d !important;
        padding: 0.4rem 0.75rem;
        line-height: 1;
        cursor: pointer;
        z-index: 5;
        box-shadow: none !important;
    }
    .password-toggle-btn:hover,
    .password-toggle-btn:focus {
        color: #FE7701 !important;
        outline: none !important;
    }
    .password-toggle-btn i {
        font-size: 1rem;
        pointer-events: none;
    }
</style>
<script>
(function () {
    function ensurePasswordToggles(root) {
        var scope = root || document;
        var inputs = scope.querySelectorAll('input[type="password"]');
        inputs.forEach(function (input) {
            if (input.closest('.password-input-wrap')) {
                return;
            }
            var wrap = document.createElement('div');
            wrap.className = 'password-input-wrap position-relative';
            input.parentNode.insertBefore(wrap, input);
            wrap.appendChild(input);
            input.classList.add('password-field-input');
            if (!input.classList.contains('form-control')) {
                input.classList.add('form-control');
            }
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'password-toggle-btn';
            btn.setAttribute('tabindex', '-1');
            btn.setAttribute('aria-label', 'Show password');
            btn.innerHTML = '<i class="fas fa-eye-slash" aria-hidden="true"></i>';
            wrap.appendChild(btn);
        });
    }

    function bindPasswordToggle() {
        if (window.__passwordToggleBound) return;
        window.__passwordToggleBound = true;
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.password-toggle-btn');
            if (!btn) return;
            e.preventDefault();
            var wrap = btn.closest('.password-input-wrap');
            if (!wrap) return;
            var input = wrap.querySelector('input');
            var icon = btn.querySelector('i');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
                btn.setAttribute('aria-label', 'Hide password');
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
                btn.setAttribute('aria-label', 'Show password');
            }
        });
    }

    function boot() {
        ensurePasswordToggles(document);
        bindPasswordToggle();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    window.initPasswordToggles = ensurePasswordToggles;
})();
</script>
