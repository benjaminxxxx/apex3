<div>
    <x-card class="mt-5">
        <div class="grid grid-cols-4 gap-5">
            <div class="col-span-4 md:col-span-1">
                <x-label>Seleccione el proyecto</x-label>
                <x-select wire:model="selectedProject" wire:change="updateProject">
                    <option value="">Seleccionar el proyecto</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </x-select>
            </div>
            @if ($selectedProject)
                <div class="col-span-4 md:col-span-1">
                    <x-label>Seleccione el grupo</x-label>
                    <x-select wire:model="selectedGroup" wire:change="createGridData">
                        <option value="">Seleccionar el grupo</option>
                        @if ($groups)
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        @endif
                    </x-select>
                </div>
            @endif
            @if ($selectedGroup)
                <div class="col-span-4 md:col-span-1">
                    <x-label>Agregar un nuevo gráfico</x-label>
                    <form class="flex items-center" wire:submit="addChart">
                        <x-input wire:model="chart_name" class="!w-auto"></x-input>
                        <x-button type="submit" class="ml-2">Agregar Gráfico</x-button>
                    </form>
                </div>
            @endif
        </div>
    </x-card>
    @if ($selectedGroup)
        <x-card>
            <div class="grid grid-cols-4 gap-5">
                <div class="col-span-4 md:col-span-1">
                    <x-label>Seleccione el gráfico</x-label>
                    <x-select wire:model="selectedChartId" wire:change="selectChart">
                        <option value="">Seleccionar el gráfico</option>
                        @if ($charts)
                            @foreach ($charts as $chart)
                                <option value="{{ $chart->id }}">{{ $chart->title }}</option>
                            @endforeach
                        @endif
                    </x-select>
                </div>
                @if ($selectedChartId)
                    <div class="col-span-4 md:col-span-2">
                        <x-label>Agregar una nueva columna</x-label>
                        <form class="flex items-center" wire:submit="addColumn">
                            <x-input wire:model="chart_column" class="!w-auto"></x-input>
                            <x-button type="submit" class="ml-2">Agregar Columna</x-button>
                        </form>
                    </div>
                @endif
            </div>
        </x-card>

    @endif

    @if ($selectedChartId)
        <x-card>
            <x-table>
                <x-slot name="thead">
                    <tr>
                        <x-th class="w-48">

                        </x-th>
                        @if ($chart_columns)
                            @foreach ($chart_columns as $column)
                                <x-th class="!w-24 text-center">
                                    {{ $column->name }}
                                </x-th>
                            @endforeach
                            @for ($i = $chart_columns->count(); $i < 10; $i++)
                                <x-th class="!w-24 text-center"> - </x-th>
                            @endfor
                        @endif
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @if ($partners)
                        @foreach ($partners as $partner)
                            <tr>
                                <x-td>
                                    <b>{{ $partner->name }}</b>
                                </x-td>
                                @if ($chart_columns)
                                    @foreach ($chart_columns as $column)
                                        <x-td class="text-center !px-2">
                                            <x-input wire:model.defer="data.{{ $partner->id }}.{{ $column->id }}"
                                                wire:change="updateData({{ $partner->id }}, {{ $column->id }}, $event.target.value)"
                                                class="!rounded-none !p-2 inline text-center" />
                                        </x-td>
                                    @endforeach
                                    @for ($i = $chart_columns->count(); $i < 10; $i++)
                                        <x-td class="text-center !px-2"> - </x-td>
                                    @endfor
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <x-td>

                            </x-td>
                            @if ($chart_columns)
                                @foreach ($chart_columns as $column)
                                    <x-td class="text-center !px-2">
                                        <x-danger-button wire:click="removeColumn({{ $column->id }})">
                                            <i class="icon icon-trash"></i>
                                        </x-danger-button>
                                    </x-td>
                                @endforeach
                            @endif
                        </tr>
                    @endif
                </x-slot>
            </x-table>
            <div class="w-full text-right mt-5">
                <x-button wire:click="updateInformation">
                    Actualizar información
                </x-button>
            </div>
        </x-card>


    @endif
    <x-card>
        <canvas id="myChartLive"></canvas>
    </x-card>
    @if (session()->has('message'))
        <x-toast class="bg-green-600">
            {{ session('message') }}
        </x-toast>
    @endif
    @if (session()->has('error'))
        <x-toast class="bg-red-600">
            {{ session('error') }}
        </x-toast>
    @endif
    @script
        <script>
            const ctx = document.getElementById('myChartLive').getContext('2d');
            let chartInstance;

            let chartData = null; //@json($chart->data ?? null);
            let sampleData = [
                ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                ['Juan', '12', '12', '12', '12.50', '13.50', '14'],
                ['Maria', '210', '220', '230', '240', '250', '260'],
                ['Jose', '300', '310', '320', '330', '340', '350'],
                ['Ana', '400', '410', '420', '430', '440', '450'],
                ['Luis', '500', '510', '520', '530', '540', '550']
            ];

            let dataToLoad = chartData ? JSON.parse(chartData) : sampleData;
            const hot = {
                colHeaders: true,
                rowHeaders: true,
                height: 350,
                minRows: 17,
                minCols: 37,
                minSpareRows: 1,
                minSpareCols: 1,
                contextMenu: true,
                stretchH: 'all',
                data: dataToLoad,
            };
/*
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
*/
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

                for (let i = 0; i < cols.length; i++) {
                    matrizDatosInversa.push(new Array(rows.length).fill(null));
                }
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

            const config = {
                type: 'polarArea', // Default chart type
                data: {
                    //labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
                    datasets: [{
                        label: 'Ventas Mensuales',
                        data: hot.data,
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

            const updateChart = (columnsHeader,rowHeader,dataAll) => {

                const chartType = document.getElementById('chart_type').value;
                const chartHeight = document.getElementById('chart_height').value;
                const showLabels = document.getElementById('showlabels').checked;
                const showLegend = document.getElementById('showlegend').checked;
                const parse_in = document.getElementById('parse_in').value;

                const hotData = dataAll;
                

                const headers = columnsHeader;

                console.log(headers);
                const rowHeaders = rowHeader;
                const myData = hotData;

                const totalData = generarDatosParaChart(rowHeaders, myData);
                totalDataGeneral = totalData.length;
                config.data.labels = headers;
                config.data.datasets = totalData;
                config.type = chartType;
                if (totalDataGeneral > 0) {
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

                document.getElementById('myChartLive').height = chartHeight;
                chartInstance = new Chart(ctx, config);
            };

            const ch = ['2','3'];
            const rh = ['juan','maria','jose'];
            const dt = [
                ['12', '12', '12', '12.50', '13.50', '14'],
                ['210', '220', '230', '240', '250', '260'],
                ['300', '310', '320', '330', '340', '350'],
                ['400', '410', '420', '430', '440', '450'],
                ['500', '510', '520', '530', '540', '550']
            ];
            updateChart(ch,rh,dt);

            Livewire.on('loadChart', (data) => {
                const columnsHeader = data.columnsHeader;
                const rowHeader = data.rowHeader;
                const dataAll = data.data;
                updateChart(columnsHeader,rowHeader,dataAll);
            });
        </script>
    @endscript
</div>
