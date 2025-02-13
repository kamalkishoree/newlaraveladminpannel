<div id="{{ $chartId }}" class="highchart-container"></div>

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var allSeries = {!! json_encode($series) !!}; // All series for each year
    var allCategories = {!! json_encode($categories) !!}; // All years for X-axis
    var chart;

    // Function to update the chart based on the selected year
    function updateChart() {
        var selectedYear = document.getElementById('yearSelect').value;
        var selectedIndex = allCategories.indexOf(parseInt(selectedYear));
        
        // Update the chart with the selected year's data
        chart.series[0].setData(allSeries[selectedIndex].data);
        chart.xAxis[0].setCategories(allCategories[selectedIndex]);
    }

    // Create the chart initially with the first year data
    document.addEventListener("DOMContentLoaded", function () {
        chart = Highcharts.chart('{{ $chartId }}', {
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
                categories: allCategories[0], // Initially show the first year's months
                title: {
                    text: 'Month'
                }
            },
            yAxis: {
                title: {
                    text: 'Number of Clicks'
                }
            },
            series: [{
                name: allCategories[0], // Set the first year as the default line
                data: allSeries[0].data
            }]
        });
    });
</script>
@endpush
