const eyeLogin = () => {
    templateEye('#icon-eye', '#password', '#eye')
}
const eyePasswordChange=()=> {
    templateEye('#icon-eye-pass', '#password', '#eye-pass')
    templateEye('#icon-eye-pass-confirm', '#password_confirmation', '#eye-pass-confirm')

}
eyePasswordChange();
eyeLogin();

function templateEye(iconEye, inputPass, showHideEye) {
    let eye = $(iconEye)
    let inputPassword = $(inputPass);
    eye.click(() => {
        if (inputPassword.attr('type') === 'password') {
            inputPassword.attr("type", "text");
            $(showHideEye).attr("class", 'fa-sharp fa-solid fa-eye')
        } else {
            inputPassword.attr("type", "password");
            $(showHideEye).attr("class", 'fa-sharp fa-solid fa-eye-slash')
        }

    })
}
