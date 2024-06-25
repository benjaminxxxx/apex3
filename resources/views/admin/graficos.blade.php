<x-app-layout>
    <x-slot name="title">
        Gráficos
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Gráficos estadisticos
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2 md:p-10 mt-2 md:mt-5">
        <div class="border shadow-md mb-5">
            <x-sectionheader-border>
                Data
            </x-sectionheader-border>
            <div id="gridData"></div>
            <x-section-border>
                <div class="flex flex-col md:flex-row items-start md:items-end">
                    <div class="flex-1 mb-2 md:mb-0 md:mr-2">
                        <form id="importForm" action="{{ route('charts.import') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <x-label class="mb-2">Importar CSV/Excel</x-label>
                            <input type="file" name="archivo" id="archivo"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                style="display: none;">
                            <x-button type="button" onclick="document.getElementById('archivo').click();"
                                class="w-full md:w-auto">Importar</x-button>
                            <x-button type="submit" class="hidden"></x-button>
                        </form>
                    </div>
                    <div class="flex-1 md:ml-2 md:text-right">
                        <x-button id="exportCSV" class="w-full md:w-auto">Exportar</x-button>
                    </div>
                </div>

            </x-section-border>
        </div>
        <div class="border shadow-md mb-5">
            <x-sectionheader-border>
                Chart
            </x-sectionheader-border>
            <div class="p-2 md:px-24 flex items-center justify-center">
                <canvas id="myChart" height="100"></canvas>
            </div>
            <x-section-border>
                <div class="flex flex-wrap">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <x-label for="chart_type" value="Tipo" />
                        <x-select name="chart_type" id="chart_type">
                            <option value="bar">Bar </option>
                            <option value="line">Line </option>
                            <option value="bubble">Bubble </option>
                            <option value="doughnut">Doughnut </option>
                            <option value="pie">Pie </option>
                            <option value="polarArea">Polar area</option>
                            <option value="radar">Radar </option>
                            <option value="scatter">Scatter </option>
                        </x-select>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <x-label for="chart_height" value="Alto" />
                        <x-input name="chart_height" id="chart_height" value="100" />
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/2 p-2">
                        <x-label for="chart_legend" value="Titulo" />
                        <x-input name="chart_legend" id="chart_legend" value="Mi título" />
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <x-label for="parse_in" value="Analizar datos en" />
                        <x-select name="parse_in" id="parse_in">
                            <option value="columns">Columnas </option>
                            <option value="rows">Filas </option>
                        </x-select>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <div class="flex items-center mb-4">
                            <input name="showlabels" id="showlabels" type="checkbox"
                                class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500 dark:focus:ring-cyan-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="showlabels"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mostrar
                                etiquetas</label>
                        </div>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <div class="flex items-center mb-4">
                            <input name="showlegend" id="showlegend" type="checkbox"
                                class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500 dark:focus:ring-cyan-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="showlegend"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mostrar
                                leyenda</label>
                        </div>
                    </div>
                    <div class="w-full p-2 text-right">
                        <x-button id="generar_grafico">
                            Previsualizar Gráfico
                        </x-button>
                        <x-button id="guardar_grafico" class="ml-2" style="display:none">
                            Guardar Gráfico
                        </x-button>
                    </div>
                </div>

            </x-section-border>
        </div>
        <div class="border shadow-md mb-5">
            <x-table>
                <x-slot name="thead">
                    <tr>
                        <x-th value="Titulo" />
                        <x-th value="Tipo" />
                        <x-th value="Alto" />
                        <x-th value="Orden" />
                        <x-th value="Mostrar etiqueta" />
                        <x-th value="Mostrar leyenda" />
                        <x-th value="Editar" />
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @foreach ($charts as $chart)
                        <x-tr>
                            <x-th value="{{ $chart->title }}" />
                            <x-th value="{{ $chart->type }}" />
                            <x-th value="{{ $chart->height }}" />
                            <x-th value="{{ $chart->order_by }}" />
                            <x-th value="{{ $chart->showlabels=='0'?'No':'Si' }}" />
                            <x-th value="{{ $chart->showlegend=='0'?'No':'Si' }}" />
                            <td>
                                <x-secondary-button wire:click="edit({{ $chart->id }})">
                                    <i class="icon-pencil"></i>
                                </x-secondary-button>  
                                <x-success-button>
                                    <i class="icon-share"></i>
                                </x-success-button>                               
                                <x-danger-button wire:click="confirmDelete({{ $chart->id }})" class="ml-1">
                                    <i class="icon-trash"></i>
                                </x-danger-button>
                            </td>
                        </x-tr>
                    @endforeach
                </x-slot>
            </x-table>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/handsontable.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/handsontable.js') }}"></script>

    <script>
        const container = document.querySelector('#gridData');

        const hot = new Handsontable(container, {
            colHeaders: true,
            rowHeaders: true,
            height: 350,
            minRows: 17,
            minCols: 37,
            minSpareRows: 1,
            minSpareCols: 1,
            contextMenu: true,
            stretchH: 'all'
        });


        function exportToCSV() {

            const hotData = hot.getData();
            let csvContent = "data:text/csv;charset=utf-8,";

            hotData.forEach(function(rowArray) {
                let row = rowArray.join(";");
                csvContent += row + "\r\n";
            });
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "datos.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        document.getElementById('exportCSV').addEventListener('click', function() {
            exportToCSV();
        });
        document.getElementById('archivo').addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const formData = new FormData();
                formData.append('archivo', file);

                axios.post('{{ route('charts.import') }}', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        const jsonData = response.data;
                        hot.loadData(jsonData);
                    })
                    .catch(error => {
                        console.error('Error al cargar el archivo:', error);
                        let errorMessage = 'Ocurrió un error, asegurese de subir solo archivos CSV.';
                        if (error.response && error.response.data && error.response.data.error) {
                            //errorMessage = error.response.data.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                        });
                    });
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            var totalDataGeneral = 0;
            function obtenerHeadersValidos(data) {
                const headers = [];

                for (let i = 1; i < data[0].length; i++) {
                    let validDataFound = false;
                    for (let j = 1; j < data.length; j++) {
                        if (data[j][i] !== null && data[j][i] !== undefined && data[j][i] !== '') {
                            validDataFound = true;
                            break;
                        }
                    }
                    if (!validDataFound) {
                        break;
                    }
                    headers.push(data[0][i]);
                }

                return headers;
            }

            function obtenerRowHeadersValidos(data) {
                const rowHeaders = [];
                for (let i = 1; i < data.length; i++) {
                    let validDataFound = false;
                    if (data[i][0] !== null && data[i][0] !== undefined && data[i][0] !== "") {
                        validDataFound = true;
                        rowHeaders.push(data[i][0]);
                    } else {
                        for (let j = i + 1; j < data.length; j++) {
                            if (data[j][0] !== null && data[j][0] !== undefined && data[j][0] !== "") {
                                validDataFound = true;
                                break;
                            }
                        }
                        if (!validDataFound) {
                            break;
                        }
                    }
                }
                return rowHeaders;
            }

            function generarMatrizDatos(cols, rows, data) {
                const matrizDatos = [];

                for (let i = 0; i < rows.length; i++) {
                    const rowData = [];
                    for (let j = 0; j < cols.length; j++) {
                        if (data[i + 1] && data[i + 1][j + 1] !== null && data[i + 1][j + 1] !== undefined) {
                            rowData.push(data[i + 1][j + 1]);
                        } else {
                            rowData.push(null);
                        }
                    }
                    matrizDatos.push(rowData);
                }

                return matrizDatos;
            }

            function generarMatrizDatosInverso(cols, rows, data) {
                const matrizDatosInversa = [];

                // Crear un array vacío para cada columna de datos
                for (let i = 0; i < cols.length; i++) {
                    matrizDatosInversa.push(new Array(rows.length).fill(null));
                }

                // Llenar la matriz de datos inversa
                for (let i = 0; i < cols.length; i++) {
                    const colHeader = cols[i];
                    const colIndex = data[0].indexOf(colHeader);

                    if (colIndex !== -1) {
                        for (let j = 0; j < rows.length; j++) {
                            const rowIndex = data.findIndex(row => row[0] === rows[j]);
                            if (rowIndex !== -1) {
                                matrizDatosInversa[i][j] = data[rowIndex][colIndex];
                            }
                        }
                    }
                }

                return matrizDatosInversa;
            }

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

            const ctx = document.getElementById('myChart').getContext('2d');
            let chartInstance;

            const config = {
                type: 'polarArea', // Default chart type
                data: {
                    //labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
                    datasets: [{
                        label: 'Ventas Mensuales',
                        data: hot.getData(),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js PolarArea Chart'
                        }
                    }
                }
            };

            const updateChart = () => {

                const chartType = document.getElementById('chart_type').value;
                const chartHeight = document.getElementById('chart_height').value;
                const showLabels = document.getElementById('showlabels').checked;
                const showLegend = document.getElementById('showlegend').checked;
                const parse_in = document.getElementById('parse_in').value;

                const hotData = hot.getData();

                const headers = parse_in == "columns" ? obtenerHeadersValidos(hotData) :
                    obtenerRowHeadersValidos(hotData);
                const rowHeaders = parse_in == "columns" ? obtenerRowHeadersValidos(hotData) :
                    obtenerHeadersValidos(hotData);
                const myData = parse_in == "columns" ? generarMatrizDatos(headers, rowHeaders, hotData) :
                    generarMatrizDatosInverso(rowHeaders, headers, hotData);

                const totalData = generarDatosParaChart(rowHeaders, myData);
                totalDataGeneral = totalData.length;
                config.data.labels = headers;
                config.data.datasets = totalData;
                config.type = chartType;
                if(totalDataGeneral>0){
                    document.getElementById('guardar_grafico').style.display = 'inline-block';
                }

                config.type = chartType;
                config.options.plugins.legend.display = showLegend;
                config.options.plugins.title.text = document.getElementById('chart_legend').value;
                config.options.scales = {
                    x: {
                        display: showLabels
                    }
                };
                if (chartInstance) {
                    chartInstance.destroy();
                }

                document.getElementById('myChart').height = chartHeight;
                chartInstance = new Chart(ctx, config);
            };
            updateChart();
            document.getElementById('generar_grafico').addEventListener('click', updateChart);
            document.getElementById('guardar_grafico').addEventListener('click', function() {

                if(totalDataGeneral==0){
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "Debe generar información primero",
                        });
                        return;
                }
                const chartType = document.getElementById('chart_type').value;
                const chartHeight = document.getElementById('chart_height').value;
                const showLabels = document.getElementById('showlabels').checked ? '1' : '0';
                const showLegend = document.getElementById('showlegend').checked ? '1' : '0';
                const order_by = document.getElementById('parse_in').value;
                const chartTitle = document.getElementById('chart_legend').value;
                const data = hot.getData();

                axios.post('{{ route('charts.store') }}', {
                        data: JSON.stringify(data),
                        type: chartType,
                        height: chartHeight,
                        title: chartTitle,
                        order_by: order_by,
                        showlabels: showLabels,
                        showlegend: showLegend
                    })
                    .then(function(response) {
                        console.log(response.data);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.data.message,
                        });
                    })
                    .catch(function(error) {
                        console.error(error);
                       
                        let errorMessage = 'Ocurrió un error,';
                        if (error.response && error.response.data && error.response.data.error) {
                            errorMessage = error.response.data.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                        });
                    });
            });
        });
    </script>
</x-app-layout>
