<section class="card card-body bg-white mb-5 overflow-auto">
    <div class="mb-3">日別レポート</div>
    <table class="table bg-white ">
        <!--ヘッド-->
        <thead>
            <tr class="bg-white">
                <th scope="col">
                    日付
                </th>
                <th scope="col"  class="text-center">
                    顧客数
                </th>
                <th scope="col"  class="text-center">
                    売上
                </th>
            </tr>
        </thead>
        <tbody>
            @php $weeks = ['日','月','火','水','木','金','土',]; @endphp
            @forelse ($day_reports as $day_report)
                <tr>
                    @php $w = $day_report['date']->format('w'); @endphp
                    <td>{{ $day_report['date']->format( 'd日('.$weeks[$w].')' ) }}</td>
                    <td class="text-end">
                        <number-comma-component number="{{ $day_report['visiters']->count() }}"></number-comma-component>
                    </td>
                    <td class="text-end">
                        <number-comma-component number="{{ $day_report['sales'] }}"></number-comma-component>
                    </td>
                </tr>

            @empty
            @endforelse
        </tbody>
    </table>
</section>
