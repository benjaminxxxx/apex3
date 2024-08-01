@props(['title', 'type','charttype', 'data', 'showlabels', 'showlegend'])

<div>
    <canvas id="chart-{{ $attributes->get('id') }}" class="chart"></canvas>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function generarDatosParaChart(rowHeaders, myData) {
                    const colors = [
                        '#f59e0b',
                        '#8b5cf6',
                        '#d946ef',
                        '#3b82f6',
                        '#84cc16',
                    ];
                    const chartData = [];
                    for (let i = 0; i < rowHeaders.length; i++) {
                        chartData.push({
                            label: rowHeaders[i],
                            data: myData[i],
                            borderWidth: 1,
                            backgroundColor: colors[i % colors.length]
                        });
                    }
                    return chartData;
                }

                const ctx = document.getElementById('chart-{{ $attributes->get('id') }}').getContext('2d');
                const parsedData = JSON.parse(@json($data));
              
                const data = {
                    labels: parsedData.columnsHeader,
                    datasets: generarDatosParaChart(parsedData.rowHeader, parsedData.data)
                };

                const options = {
                    // Customize chart options here
                    plugins: {
                        legend: {
                            display: {{ $showlegend == '1' ? 'true' : 'false' }}
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw;
                                }
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: {{ $showlabels == '1' ? 'true' : 'false' }}
                        },
                        y: {
                            display: {{ $showlabels == '1' ? 'true' : 'false' }}
                        }
                    }
                };

                const chart = new Chart(ctx, {
                    type: "{{$type}}",
                    data: data,
                    options: options
                });

            });
        </script>
    @endpush
</div>
