<!--Export các nhân viên khi là admin-->
<table class="w-100 table table-bordered">
    <thead>
        <tr>
            <th>Ngày</th>
            <th>Tên dự án</th>
            <th>Số giờ</th>
        </tr>
    </thead>
    <tbody>
        @for ($i = 1; $i <= $daysInMonth; $i++)
            <tr>
                <td class="align-middle text-center">{{ $i }}</td>
                <td>
                    @foreach ($list as $project)
                        @php
                            $day = (int) $carbon::parse($project->day_work)->translatedFormat('d');
                        @endphp
                        @if ($day == $i)
                            <ul>
                                <li>{{ $project->name }}</li>
                            </ul>
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($list as $project)
                        @php
                            $day = (int) $carbon::parse($project->day_work)->translatedFormat('d');
                        @endphp
                        @if ($day == $i)
                            <ul>
                                <li>{{ $project->working_hours }}h</li>
                            </ul>
                        @endif
                    @endforeach
                </td>
            </tr>
        @endfor
    </tbody>
</table>
