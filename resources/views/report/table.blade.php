<!--Export từng nhân viên như admin là view báo cáo còn nhân viên là màn dashboard-->
@php
    $total = [];
    $count = 0;
    $subCount = 0;
    $arrResults = [];
    $totalCount = 0;
@endphp

<table class="table table-primary" border="1px">
    <thead>
        <tr>
            <th>Dự án</th>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <th>{{ $i }}</th>
            @endfor
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($list as $project)
            <tr>
                <td>{{ $project['nameProject'] }}</td>
                @php
                    $days = [];
                    $dayWorks = $project['days'];
                    foreach ($dayWorks as $key => $value) {
                        array_push($days, $value['day_work']);
                    }
                @endphp
                @for ($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        if (empty($total[$i])) {
                            $total[$i] = 0;
                        }
                    @endphp
                    @if (in_array($i, $days))
                        <td>{{ $dayWorks[$count++]['hours'] }}
                        </td>
                        @php
                            array_push($arrResults, $dayWorks[$totalCount++]['hours']);
                            $total[$i] += $dayWorks[$subCount++]['hours'];
                        @endphp
                    @else
                        <td></td>
                    @endif
                @endfor
                @php
                    $count = 0;
                    $subCount = 0;
                    $totalCount = 0;
                @endphp
                <td> {{ (int) array_sum($arrResults) }}</td>
                @php
                    $arrResults=[];
                @endphp
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Total
            </th>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <td>{{ $total[$i] == 0 ? '' : $total[$i] }}</td>
            @endfor
            <td>{{ (int) array_sum($total) }}</td>
        </tr>
    </tfoot>
</table>

