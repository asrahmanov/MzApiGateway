<?php
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.xls");
header("Content-Transfer-Encoding: binary ");
?>
    <table border="1">
        <tr>
            <th class="wordspace">Дата</th>
            <th class="wordspace">ПЛАН ЗАПУСКА В ШТ.</th>
            <th class="wordspace">ПЛАН ЗАПУСКА ПО ССЗ</th>
            <th class="wordspace">ФАКТ ЗАПУСКА В ШТ</th>
            <th class="wordspace">ОПРОБОВАНИЯ</th>
            <th class="wordspace">ФАКТ ПЕРЕДАЧИ В ОТК, В ШТ.</th>
            <th class="wordspace">ФАКТ ПЕРЕДАЧИ НА СКЛАД В ШТ. </th>
            <th class="wordspace">ПРОЦЕНТ ВЫХОДА ГОДНЫХ</th>
        </tr>
            <tbody>
        @foreach($report as $item)
            <tr>
                <td>{{ $item['report_day'] }}</td>
                <td>{{ $item['launch_plan'] }}</td>
                <td>{{ $item['launch_plan_ssz'] }}</td>
                <td>{{ $item['launch_fact'] }}</td>
                <td>{{ $item['sampling'] }}</td>
                <td>{{ $item['fact_of_transfer_to_otk'] }}</td>
                <td>{{ $item['fact_of_transfer_to_warehouse'] }}</td>
                <td>{{ $item['fact_of_transfer_to_otk']/$item['launch_previously'] * 100 }}</td>
            </tr>
            </tbody>
        @endforeach
    </table>





