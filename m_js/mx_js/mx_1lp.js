function validate_login_re(){return document.loginform_re.inputEmail.value==null||document.loginform_re.inputEmail.value==""?(alert("E-mail is needed."),document.loginform_re.inputEmail.focus(),!1):echeck(document.loginform_re.inputEmail.value)==!1?(document.loginform_re.inputEmail.value="",document.loginform_re.inputEmail.focus(),!1):void 0}function my_load_login_re(){document.loginform_re.inputEmail.focus()}function disableEnterKey(n){var t;return t=window.event?window.event.keyCode:n.which,t!=13}