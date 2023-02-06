@php
    $count = 0;
    $subCount = 0;
    $countToProject = 0;
    $total = [];
    $sumTotal = [];
    $totalToProject = [];
@endphp
@foreach ($list as $item)
    <table border="1px">
        <thead>
            <tr>
                <td colspan="{{ $daysInMonth + 2 }}">{{ $item->name }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Dự án</th>
                @for ($i = 1; $i <= $daysInMonth; $i++)
                    <th>{{ $i }}</th>
                @endfor
                <th>Total</th>
            </tr>
            @if ($item->name_day_hours != 0)
                @foreach ($item->name_day_hours as $project)
                    <tr>
                        @if ($project->id == $item->id)
                            <th>{{ $project->nameProject }}</th>
                        @endif
                        @php
                            $days = [];
                            $dayWorks = $project->days;
                            foreach ($dayWorks as $key => $value) {
                                array_push($days, $value->day_work);
                            }
                        @endphp
                        @for ($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                if (empty($total["$i-$item->id"])) {
                                    $total["$i-$item->id"] = 0;
                                }
                            @endphp
                            @if (in_array($i, $days))
                                <td>{{ $dayWorks[$count++]->hours }}</td>
                                @php
                                    array_push($totalToProject, $dayWorks[$countToProject++]->hours);
                                    $total["$i-$item->id"] += $dayWorks[$subCount++]->hours;
                                @endphp
                            @else
                                <td></td>
                            @endif
                        @endfor
                        <td>{{ array_sum($totalToProject) }}</td>
                        @php
                            $totalToProject = [];
                            $count = 0;
                            $subCount = 0;
                            $countToProject = 0;
                        @endphp
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                @for ($i = 1; $i <= $daysInMonth; $i++)
                    <td>{{ $total["$i-$item->id"] == 0 ? '' : $total["$i-$item->id"] }}</td>
                    @php
                        if (!($total["$i-$item->id"] == 0)) {
                            array_push($sumTotal, $total["$i-$item->id"]);
                        }
                    @endphp
                @endfor
                <td>{{ array_sum($sumTotal) }}</td>
                @php
                    $sumTotal = [];
                @endphp
            </tr>
        </tfoot>
    </table>
@endforeach
