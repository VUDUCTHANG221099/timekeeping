@php
    $count = 0;
    $countTotalY = 0;
    $totalY = [];
    $totalX = 0;
    $countTotalX = 0;
@endphp
<style>
    table,
    th,
    td {
        border: 1px solid black;
    }
    th {
        font-weight: bold;
        text-align: center;
    }
</style>
<table border="1px">
    <thead>
        <tr>
            <th>Dự án</th>
            <th>Thành viên</th>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <th>{{ $i }}</th>
            @endfor
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $projects)
            @if (!empty($projects['count'] > 0))
                <tr>
                    <td rowspan="{{$projects['count'] > 0 ? $projects['count'] : 1}}">
                        {{ $projects[0][0]['nameProject'] }}
                    </td>
                    @foreach ($projects[0] as $name)
                        <td>{{ $name['name'] }}</td>
                        @php
                            $days = [];
                            $dayWorks = $name['dayWorks'];
                            foreach ($dayWorks as $key => $value) {
                                array_push($days, $value['day']);
                            }
                            $sum = 0;
                        @endphp
                        @for ($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                if (empty($totalY[$i])) {
                                    $totalY[$i] = 0;
                                }
                            @endphp
                            @if (in_array($i, $days))
                                <td>{{ $dayWorks[$count++]['hours'] }}</td>
                                @php
                                    $totalY[$i] += $dayWorks[$countTotalY++]['hours'];
                                    $totalX = $dayWorks[$countTotalX++]['hours'] + $totalX;
                                @endphp
                            @else
                                <td></td>
                            @endif
                        @endfor
                        <td>{{ $totalX }}</td>
                        @php
                            $totalX = 0;
                            $count = 0;
                            $countTotalY = 0;
                            $countTotalX = 0;
                        @endphp
                </tr>
            @endforeach
        @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            <td></td>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <td>{{ $totalY[$i] == 0 ? '' : $totalY[$i] }}</td>
            @endfor
            <td>{{ array_sum($totalY) }}</td>
        </tr>
    </tfoot>
</table>

