<div id="{{ $chartId }}" class="highchart-container"></div>


@push('scripts')

<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        Highcharts.chart('{{ $chartId }}', {
            chart: {
                type: '{{ $chartType }}'
            },
            title: {
                text: '{{ $title }}'
            },
            subtitle: {
                text: '{{ $subtitle }}'
            },

            xAxis: {
                categories: @json($categories)
            },

            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series: [
                @if(isset($series) && count($series)>0)
                {
                    name: '{{$seriesName}}',
                    data: @json($series),
                    color: '#3393ff'
                },
                @endif
                @if(isset($pendingSeries)&& count($pendingSeries)>0)
                {
                    name: 'Pending',
                    data: @json($pendingSeries),
                    color: '#ffc107'
                },
                @endif
                @if(isset($approvedSeries) && count($approvedSeries)>0)
                {
                    name: 'Approved',
                    data: @json($approvedSeries),
                    color: '#28a745'
                },
                @endif
                @if(isset($rejectedSeries) && count($rejectedSeries)>0)
                {
                    name: 'Rejected',
                    data: @json($rejectedSeries),
                    color: '#dc3545'
                },
                @endif
            ],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    });
</script>
@endpush