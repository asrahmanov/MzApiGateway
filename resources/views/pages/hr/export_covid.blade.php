<?php
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.xls");
header("Content-Transfer-Encoding: binary ");
?>
    <table border="1">
        <thead>
        <tr>
            <th class="wordspace">Дата</th>
            <th class="wordspace">ДЗО</th>
            <th class="wordspace">Списочная численность сотрудников (без учета внешних и внутренних совместителей)
            </th>
            <th class="wordspace">Всего больничные</th>
            <th class="wordspace">в т.ч. COVID-19 (карантин, самоизоляция)</th>
            <th class="wordspace">Количество летальных случаев от COVID-19</th>
            <th class="wordspace">Количество выздоровевших (накопительным итогом)</th>
            <th class="wordspace">Количество полностью вакцинированных работников (всеми компонентами)</th>
            <th class="wordspace">Количество работников, болеющих COVID-19</th>
            <th class="wordspace">В том числе из фактически вышедших на рабочее место в течение последних 14 дней
            </th>
        </tr>
        </thead>
        @foreach($report as $item)
            <tbody>
            <tr>
                <td>{{ $item['report_day'] }}</td>
                <td>{{ $companies[$item['company_id']] }}</td>
                <td>{{ $item['n_1'] }}</td>
                <td>{{ $item['n_2'] }}</td>
                <td>{{ $item['n_3'] }}</td>
                <td>{{ $item['n_4'] }}</td>
                <td>{{ $item['n_5'] }}</td>
                <td>{{ $item['n_6'] }}</td>
                <td>{{ $item['n_7'] }}</td>
                <td>{{ $item['n_8'] }}</td>
            </tr>
            </tbody>
        @endforeach
    </table>





