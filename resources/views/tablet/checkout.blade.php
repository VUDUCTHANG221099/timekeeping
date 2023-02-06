<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Vaix Daily Report System | CHECKOUT</title>
    @include('logo')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <style>
        body {
            background: #1260aad0;
        }

        #container {
            margin: 0 auto;
            padding: auto;
            width: 80%;
            min-height: 100vh;
            background: #e9ecef;
        }

        .banner {
            height: 200px;
            background-image: url('../assets/img/Hanoi - thanhnien.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body>
    <div id="container">
        <div class="container-full mb-3 banner">
            <div class="container-full" style=" display:flex; justify-content: space-between">
                <div style="display:flex; justify-content: space-between; width: 56%;">
                    <div>
                        <h3 style="line-height:200px; ">
                            <a href="https://vaixgroup.com/"><img width="70%"
                                    style="margin-left: 10px; border-radius:15% "
                                    src="{{ asset("$base_url/website/img/vaixgroup.jfif") }}" alt=""></a>
                        </h3>
                    </div>
                    <div>
                        <h3 style="line-height:200px; " class=" font-weight-bold text-dark">CHECKOUT</h3>
                    </div>
                </div>
                <div class="pr-3">
                    <h3 style="line-height:200px;"><u>
                            <a class="text-dark " href="{{ route('tabletLogoutGet') }}">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                            </a></u>
                </div>
            </div>
        </div>
        <div class="container align-middle text-dark"
            style="display:flex; justify-content: flex-end;font-size:25px;font-weight: 300">
            <div style="margin-right:25px"><span class="">ID: </span>
                <span class="font-weight-bold">{{ $user->emp_code }}</span>
            </div>
            <div><span>Name: </span> <span class="font-weight-bold">{{ $user->fullName }}</span></div>
        </div>
        <br>
        <div class="container">
            <form action="{{ route('tabletCheckoutPost') }}" method="post">
                <input type="hidden" name="id" value="{{ $user->id }}">
                @csrf
                <center>
                    {{-- <caption> --}}
                    <div class="d-flex justify-content-center">
                        <input id="tablet_flatpickr" name="dayWork" value="today" placeholder="Select Date.."
                            class="form-control  mb-3" type="text" style="width :20% !  important" />
                        <a class="input-button" href="#tablet_flatpickr" role="button"
                            style="margin: 7px 5px 0px -20px">
                            <i class="fas fa-calendar-alt"></i>
                        </a>
                    </div>
                    {{-- Create checkbox Nghỉ cả ngày --}}
                    <div class="w-25" style="display: inline-block">
                        <input class="form-check-input" name="dayOff" type="checkbox" value="1" id="dayOff">
                        <label class="form-check-label" for="dayOff">
                            Ngày nghỉ
                        </label>
                    </div>
                    {{-- Create checkbox Nghỉ cả ngày --}}
                    {{-- </caption> --}}
                    <div class="mt-3">
                        <table id="project" class=" w-50">
                            <thead>
                                {{-- <x-checkout :project="$project" onclick="handleClickAdd()" /> --}}
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <caption class="text-center">
                                    <div class="flashMessage text-danger">
                                        {{ session()->get('message') ?? false }}
                                        {{ session()->get('message-hours') ?? false }}
                                        {{ session()->get('message-project') ?? false }}
                                        {{ session()->get('message-empty') ?? false }}
                                    </div>
                                    <button type="submit" class="btn btn-info" id="clickSubmit">
                                        Save & Logout
                                    </button>
                                </caption>
                            </tfoot>
                        </table>
                    </div>
                </center>
            </form>
        </div>
    </div>
    <span id="listProject" data-listProject="{{ route('listProject') }}"></span>
    <span id="countProject" data-countProject="{{ route('countProject') }}"></span>
    {{-- <span id="apiCountProject" data-apiCountProject="{{ route('countProjectOfEmpolyee') }}"></span>
    <span id="apilistProjectOfEmp" data-apilistProjectOfEmp="{{ route('listPorjectOfEmployee') }}"></span> --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset("$base_url/js/tablet/checkout.js") }}"></script>
</body>
</html>
