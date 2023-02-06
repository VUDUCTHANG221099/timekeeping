<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

</head>

<body>
    <center>
        <table border="1" style="width:100%; border-collapse: collapse;"  >
            <thead>
                <tr>
                    <th style="width: 3%; text-align: center" >STT</th>
                    <th style="width: 20%; text-align: center">Họ và tên</th>
                    <th style="width: 30%; text-align: center">Email</th>
                    <th style="width: 14%; text-align: center">Mật khẩu</th>
                    <th style="width: 16%; text-align: center">Mã nhân viên</th>
                    <th style="width: 7%; text-align: center">Mã pin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($arr as $item)
                    <tr>
                        <td style="text-align: center">{{ $count++ }}</td>
                        <td>{{ $item['fullname'] }}</td>
                        <td>{{ $item['email'] }}</td>
                        <td>{{ $item['password'] }}</td>
                        <td>{{ $item['emp_code'] }}</td>
                        <td>{{ $item['emp_pin'] }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </center>
</body>

</html>
