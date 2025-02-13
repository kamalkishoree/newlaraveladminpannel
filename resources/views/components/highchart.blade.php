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
            series: [{
                    name: 'Clicks',
                    data: @json($series)
                }],
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