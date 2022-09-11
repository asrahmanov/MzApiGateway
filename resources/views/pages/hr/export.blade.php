<?php
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.xls");
header("Content-Transfer-Encoding: binary ");
?>
    <table border="1">
            <tr>
{{--                <th>№</th>--}}
                <th>Наименование вопроса</th>
                <th>Комментарии</th>
                <th>Примечание</th>
            </tr>
            <tbody>
        @foreach($report_type as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>
                    @foreach($report[$item['id']] as $repo)
                   {{ $repo  }}
                    @endforeach
                </td>
                <td>{{ $item['info'] }}</td>
            </tr>
{{--                    @foreach($report as $el)--}}
{{--                    <span>{{ $report_type($el['report_type_id']) }}</span><br></td>--}}
{{--        @endforeach--}}

{{--            <tr v-for="(item, index) in report_type">--}}
{{--                <td> {{ index + 1 }}</td>--}}
{{--                <td class="wordspace"> {{ item.name }}</td>--}}

{{--                <td class="wordspace">{{ item.info }}</td>--}}
{{--            </tr>--}}
            </tbody>
        @endforeach
    </table>





