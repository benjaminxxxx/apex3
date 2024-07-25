<div>
    <div class="lg:flex gap-5 mt-3">
        <x-card class="flex-1">
            <x-table>
                <x-slot name="thead">
                    <tr>
                        <x-th value="Titulo" />
                        <x-th value="Proyecto" />
                        <x-th value="Tipo de gráfico" />
                        <x-th value="Mostrar etiqueta" />
                        <x-th value="Mostrar leyenda" />
                        <x-th value="Editar" />
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @if ($charts)
                        @foreach ($charts as $chartdata)
                            <x-tr class="{{ $chartdata->chart_id == $chartId ? 'bg-cyan-100' : '' }}">
                                <x-th value="{{ $chartdata->title }}" />
                                <x-th value="{{ $chartdata->project->name }}" />
                                <x-th value="{{ $chartdata->chart_type == 1 ? 'General' : 'Socios' }}" />
                                <x-th value="{{ $chartdata->showlabels == '0' ? 'No' : 'Si' }}" />
                                <x-th value="{{ $chartdata->showlegend == '0' ? 'No' : 'Si' }}" />
                                <td>

                                    <x-secondary-button wire:click="edit('{{ $chartdata->chart_id }}')">
                                        <i class="icon-pencil"></i>
                                    </x-secondary-button>
                                    <x-secondary-button wire:click="showdata('{{ $chartdata->chart_id }}')">
                                        <i class="icon-doc"></i>
                                    </x-secondary-button>
                                    <x-button wire:click="showchart('{{ $chartdata->chart_id }}')">
                                        <i class="icon-eye"></i>
                                    </x-button>
                                    <x-danger-button wire:confirm="Seguro que desea eliminar este gráfico??"
                                        wire:click="delete('{{ $chartdata->chart_id }}')" class="ml-1">
                                        <i class="icon-trash"></i>
                                    </x-danger-button>

                                </td>
                            </x-tr>
                        @endforeach
                    @endif
                </x-slot>
            </x-table>
        </x-card>
        <x-card class="lg:w-96">
            <x-h3>Crear un nuevo gráfico</x-h3>
            <form wire:submit="addChart">

                <div class="mt-4">
                    <x-label>Nombre del gráfico</x-label>
                    <x-input wire:model="chart_name" />
                    <x-input-error for="chart_name" />
                </div>
                <div class="mt-4">
                    <x-label>Proyecto</x-label>
                    <x-select wire:model="selectedProject">
                        <option value="">Seleccionar el proyecto</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="selectedProject" />
                </div>
                <div class="mt-4">
                    <x-label>Tipo de gráfico</x-label>
                    <x-select wire:model="selectedChartType" wire:change="updateChartType">
                        <option value="">Seleccionar</option>
                        <option value="1">Progreso del proyecto</option>
                        <option value="2">Progreso de cada socio dentro de un proyecto</option>
                    </x-select>
                    <x-input-error for="selectedChartType" />
                </div>
                <div class="mt-4 text-right">
                    <x-button type="submit">Crear nuevo gráfico</x-button>
                    @if ($chartId)
                        <x-secondary-button type="button" wire:click="cancelar">Cancelar</x-secondary-button>
                    @endif
                </div>

            </form>
        </x-card>
    </div>

    <x-dialog-modal wire:model="isFormData" maxWidth="full">
        <x-slot name="title">
            Agregar información para generar el gráfico
        </x-slot>

        <x-slot name="content">

            @if ($isFormData)

                <div class="col-span-4 md:col-span-2 my-4">
                    @if (($selectedChart->chart_type == 1 && $selectedChart->rows->count() != 0) || $selectedChart->chart_type == 2)
                        <x-label>Agregar una nueva columna</x-label>
                        <form class="flex items-center" wire:submit="addColumn">

                            <div>
                                <x-input wire:model="chart_column" class="!w-auto"></x-input>
                                <x-input-error for="chart_column" />
                            </div>
                            <x-button type="submit" class="ml-2">Agregar Columna</x-button>

                        </form>
                    @endif
                </div>

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
                        @if ($selectedChart->chart_type == 1)
                            @if ($chart_rows)
                                @foreach ($chart_rows as $row)
                                    <tr>
                                        <x-td>
                                            <b>{{ $row->name }}</b>
                                        </x-td>
                                        @if ($chart_columns)
                                            @foreach ($chart_columns as $column)
                                                <x-td class="text-center !px-2">
                                                    <x-input
                                                        wire:model.defer="data.{{ $row->id }}.{{ $column->id }}"
                                                        wire:change="updateData({{ $row->id }}, {{ $column->id }}, $event.target.value)"
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
                        @else
                            @if ($partners)
                                @foreach ($partners as $partner)
                                    <tr>
                                        <x-td>
                                            <b>{{ $partner->name }}</b>
                                        </x-td>
                                        @if ($chart_columns)
                                            @foreach ($chart_columns as $column)
                                                <x-td class="text-center !px-2">
                                                    <x-input
                                                        wire:model.defer="data.{{ $partner->id }}.{{ $column->id }}"
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
                        @endif
                        @if ($selectedChart->chart_type == 1)
                            <tr>
                                <x-td colspan="100%">
                                    <form class="flex items-center" wire:submit="addRow">

                                        <div>
                                            <x-input wire:model="chart_row" class="!w-auto"></x-input>
                                            <x-input-error for="chart_row" />
                                        </div>
                                        <x-button type="submit" class="ml-2">Agregar Fila</x-button>

                                    </form>
                                </x-td>
                            </tr>
                        @endif
                    </x-slot>
                </x-table>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button type="button" wire:click="closeForm" class="mr-2">Cancelar</x-secondary-button>
            <x-button wire:click="updateAndClose">
                Actualizar información
            </x-button>
        </x-slot>
    </x-dialog-modal>
    <x-card wire:ignore class="max-h-[30rem] hidden panel-grafico">
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
                        data: {},
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

            const updateChart = (columnsHeader, rowHeader, dataAll) => {

                const card = document.querySelector('.panel-grafico');
        if (card.classList.contains('hidden')) {
            card.classList.remove('hidden');
        }

                const chartType = 'bar';
                const chartHeight = 200;
                const showLabels = true;
                const showLegend = true;

                const headers = columnsHeader;

                console.log(headers);
                const rowHeaders = rowHeader;

                const totalData = generarDatosParaChart(rowHeaders, dataAll);
                totalDataGeneral = totalData.length;
                config.data.labels = headers;
                config.data.datasets = totalData;
                config.type = chartType;
            
                config.options.plugins.legend.display = showLegend;
                config.options.plugins.title.text = "modelo de leyenda";
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
/*
            const ch = ['2', '3'];
            const rh = ['juan', 'maria', 'jose'];
            const dt = [
                ['12', '12', '12', '12.50', '13.50', '14'],
                ['210', '220', '230', '240', '250', '260'],
                ['300', '310', '320', '330', '340', '350'],
                ['400', '410', '420', '430', '440', '450'],
                ['500', '510', '520', '530', '540', '550']
            ];
            
            updateChart(ch, rh, dt);
*/
            Livewire.on('loadChart', (data) => {
                const columnsHeader = data.columnsHeader;
                const rowHeader = data.rowHeader;
                const dataAll = data.data;
                
                updateChart(columnsHeader, rowHeader, dataAll);
            });
        </script>
    @endscript
</div>
