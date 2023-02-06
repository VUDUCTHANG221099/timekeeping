<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @include('logo')
    <title>Vaix Daily Report System | Đăng nhập</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <!-- Styles -->
    <link href="{{ asset("$base_url/login/login.css") }}" rel="stylesheet" />

    <style>
        .error {
            color: red;
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand lh-base" href="{{ route('login.get') }}">
                    Vaix Daily Report System
                </a>
            </div>
        </nav>
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Đăng nhập</div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('login.post') }}">
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                                        @csrf
                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email"
                                                autofocus placeholder="VD: abc@vaixgroup.com"
                                                value="{{ old('email') ?? (session()->get('email') ?? false) }}" />
                                            <span class="error">
                                                @error('email')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-form-label text-md-end">Mật
                                            khẩu</label>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-center">

                                                <input id="password" type="password" class="form-control" name="password"
                                                    placeholder="Mật khẩu" />
                                                    <span id="icon-eye" style="margin: 7px 5px 0px -23px">
                                                        <i id="eye" class="fa-sharp fa-solid fa-eye-slash"></i>
                                                    </span>
                                            </div>
                                            <span class="error">
                                                @error('password')
                                                    {{ $message }}
                                                @enderror
                                                {{ session()->get('message') ?? false }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3" style="display: none">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" />
                                                <label class="form-check-label" for="remember">
                                                    Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                Đăng nhập
                                            </button>
                                            <a class="btn btn-link" href="" style="display: none">
                                                Forgot Your Password?
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="{{ asset("$base_url/js/eye.js") }}"></script>
</body>

</html>
