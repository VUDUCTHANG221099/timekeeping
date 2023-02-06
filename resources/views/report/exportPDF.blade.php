<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bảng chấm công tháng {{ $year }}/{{ $month < 10 ? "0$month" : $month }} của nhân viên
        {{ $name }}</title>
    <style>
        table {
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid;
            border-collapse: collapse;
        }

        #day {
            width: 10%;
            text-align: center;
        }

        .days {
            text-align: center;
        }

        .list {
            list-style-type: none;
        }
    </style>
</head>

<body>
    <!--Export các nhân viên khi là admin-->
    <div style="text-align: center; margin-bottom: 3%">
        <h2>Bảng chấm công tháng {{ $year }}/{{ $month }} của {{ $name }} </h2>
    </div>
    <table>
        <thead>
            <tr>
                <th id="day">Ngày</th>
                <th>Tên dự án</th>
                <th>Số giờ</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <tr>
                    <td class="days">{{ $i }}</td>
                    <td>
                        @foreach ($list as $project)
                            @php
                                $day = (int) $carbon::parse($project->day_work)->translatedFormat('d');
                            @endphp
                            @if ($day == $i)
                                <table>
                                    <tr>
                                        <td>
                                            {{ $project->name }}
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($list as $project)
                            @php
                                $day = (int) $carbon::parse($project->day_work)->translatedFormat('d');
                            @endphp
                            @if ($day == $i)
                                <table>
                                    <tr>
                                        <td>
                                            {{ $project->working_hours }}h
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>

</html>
