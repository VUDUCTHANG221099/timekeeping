<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vaix Daily Report System | Đăng nhập</title>
    @include('logo')
    <link rel="stylesheet" href="{{ asset("$base_url/login/tablet/login.css") }}">
</head>

<body>
    <div class="wrapper">
        <div class="logo">
            <a href="https://vaixgroup.com/">
                <img src="{{ asset("$base_url/website/img/vaixgroup.jfif") }}" alt="VaixGroup">
            </a>
        </div>
        <form action="{{ route('tabletLoginPost') }}" method="post" id="loginTable">
            @csrf
            <div class="form-field d-flex align-items-center">
                <span class="far fa-user"></span>
                <input type="text" name="emp_code" onkeypress='validate(event)' autocomplete="off" maxlength="4"
                    id="emp_code" placeholder="Mã Nhân viên" autofocus value="{{ old('emp_code') }}">
            </div>
            <div class="form-field d-flex align-items-center">
                <span class="fas fa-key"></span>
                <input type="password" name="password" autocomplete="new-password" id="pwd" maxlength="4"
                    onkeypress='validate(event)' placeholder="Mật khẩu">
            </div>
        </form>
        <div style="text-align: center; color:red">
            <span>{{ session()->get('error') ?? false }}</span>
        </div>
    </div>
    <script>
        var inputEmail = document.getElementById("emp_code");
        var inputPassword = document.getElementById("pwd");
        inputEmail.focus();
        inputEmail.addEventListener("keyup", function() {
            let uLenght = inputEmail.value.length
            if (uLenght == 4) {
                inputPassword.focus();
            }
        });
        inputPassword.addEventListener("keyup", function(e) {
            if (e.keyCode == 17 || e.keyCode == 91) {
                return false;
            }
            let uLenght = inputPassword.value.length
            if (uLenght == 4) {
                checkSubmit();
            }
        });

        function checkSubmit() {
            let pwLenght = inputPassword.value.length
            let uLenght = inputEmail.value.length
            console.log(pwLenght)
            console.log(uLenght)
            // return false;
            if (pwLenght == 4 && uLenght == 4) {
                setTimeout(() => {
                    document.getElementById("loginTable").submit();
                    // return false;
                }, 1);
            }
        }
        // submitloginTablet()
        // const inputs = document.querySelectorAll("input");
        // console.log(inputs);
        // // function submitloginTablet() {
        // //     inputs.forEach((input, key) => {
        // //         if (key !== 0) {
        // //             input.addEventListener("click", function() {
        // //                 inputs[0].focus();
        // //             });
        // //         }
        // //     });
        // //     inputs.forEach((input, key) => {
        // //         input.addEventListener("keyup", function() {
        // //             if (input.value.length == 4) {
        // //                 if (key === 1) {
        // //                     // document.getElementById("csrf").innerHTML = '@csrf';
        // //                     document.getElementById("loginTable").submit();
        // //                 } else {
        // //                     inputs[key + 1].focus();
        // //                     console.log(10);
        // //                 }
        // //             }
        // //         });
        // //     });
        // // }
    </script>
    <script src="{{ asset("$base_url/js/tablet/login.js") }}"></script>
</body>

</html>
