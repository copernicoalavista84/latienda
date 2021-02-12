/**
 * Carts Guru
 *
 * @author    LINKT IT
 * @copyright Copyright (c) LINKT IT 2016
 * @license   Commercial license
 */

(function ($) {
    // On ready
    $(document).ready(function ($) {
        var password = document.getElementById("password"),
          form = document.getElementById("cg-register-form"),
          isPasswordValid = false,
          passwordErrorLabel = document.getElementById('passwordErrorLabel');

        //Declare global functions
        window.cg_switchView = switchView;

        password.onchange = onPasswordInputChange;
        form.onsubmit = formSubmit;

        // Switch active view
        function switchView(view, backToView) {
            if (!view){
                view =  window.cg_backto;
                 window.cg_backto = null;
            }

            $('#cartsguru-welcome').removeClass();
            switch(view){
                case 'view-try-it':
                case 'view-have-account':
                case 'view-success':
                case 'view-no-store-selected':
                     $('#cartsguru-welcome').addClass(view);
            }

            window.cg_backto = backToView;
        }

        function validatePassword(value) {
          var regexp = /^(?=.*[0-9])(?=.*[a-zA-Z])(.+)$/;
          var isValid = value && value.length >= 7 && value.length < 64 && regexp.test(value);
          return isValid;
        }

        function onPasswordInputChange(event) {
          var value = event && event.target && event.target.value;
          if (value) {
            isPasswordValid = validatePassword(value);
            passwordErrorLabel.style.display = (isPasswordValid) ? 'none' : 'block';
          }
        }

        function formSubmit(evt) {
          if (!isPasswordValid) {
            evt.preventDefault();
            return false;
          }
        }
    });
})(jQuery);
