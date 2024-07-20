<x-app-layout>
    <x-slot name="title">
        Gráficos
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de Gráficos estadisticos
        </h2>
    </x-slot>
    <livewire:chat :popup="true"/>

    <livewire:chart-group/>
    
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2 md:p-10 mt-2 md:mt-5">

        
        @if (session('error'))
            <div class="border shadow-md mb-5">
                <div class="bg-red-600 text-white p-2 md:p-3 rounded border">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        @if ($chart != null)
            <div class="border shadow-md mb-5">
                <h2 class="p-2 font-bold bg-green-200">Editando Gráfico: {{ $chart->title }}</h2>
            </div>
        @endif
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
                            <option value="bar" {{($chart&&$chart->type=='bar'?'selected':'')}}>Bar </option>
                            <option value="line" {{($chart&&$chart->type=='line'?'selected':'')}}>Line </option>
                            <option value="bubble" {{($chart&&$chart->type=='bubble'?'selected':'')}}>Bubble </option>
                            <option value="doughnut" {{($chart&&$chart->type=='doughnut'?'selected':'')}}>Doughnut </option>
                            <option value="pie" {{($chart&&$chart->type=='pie'?'selected':'')}}>Pie </option>
                            <option value="polarArea" {{($chart&&$chart->type=='polarArea'?'selected':'')}}>Polar area</option>
                            <option value="radar" {{($chart&&$chart->type=='radar'?'selected':'')}}>Radar </option>
                            <option value="scatter" {{($chart&&$chart->type=='scatter'?'selected':'')}}>Scatter </option>
                        </x-select>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <x-label for="chart_height" value="Alto" />
                        <x-input name="chart_height" id="chart_height" value="{{($chart)?$chart->height:'100'}}" />
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/2 p-2">
                        <x-label for="chart_legend" value="Titulo" />
                        <x-input name="chart_legend" id="chart_legend" value="{{($chart)?$chart->title:'mMi título'}}" />
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <x-label for="parse_in" value="Analizar datos en" />
                        <x-select name="parse_in" id="parse_in">
                            <option value="columns" {{($chart&&$chart->order_by=='columns'?'selected':'')}}>Columnas </option>
                            <option value="rows" {{($chart&&$chart->order_by=='rows'?'selected':'')}}>Filas </option>
                        </x-select>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <div class="flex items-center mb-4">
                            <input name="showlabels" id="showlabels" type="checkbox" {{($chart&&$chart->showlabels=='0'?'':'checked')}}
                                class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500 dark:focus:ring-cyan-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="showlabels"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mostrar
                                etiquetas</label>
                        </div>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 p-2">
                        <div class="flex items-center mb-4">
                            <input name="showlegend" id="showlegend" type="checkbox" {{($chart&&$chart->showlegend=='0'?'':'checked')}}
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
                        @if ($chart == null)
                            <x-button id="guardar_grafico" class="ml-2" style="display:none">
                                Guardar Gráfico
                            </x-button>
                        @else
                            <x-button id="guardar_grafico" class="ml-2" style="display:none">
                                Editar Gráfico
                            </x-button>
                            <x-secondary-a href="{{ route('charts') }}" class="ml-2">
                                Cancelar Edición
                            </x-secondary-a>
                        @endif
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
                    @foreach ($charts as $chartdata)
                        <x-tr class="{{ $chart && $chartdata->chart_id == $chart->chart_id ? 'bg-cyan-100' : '' }}">
                            <x-th value="{{ $chartdata->title }}" />
                            <x-th value="{{ $chartdata->type }}" />
                            <x-th value="{{ $chartdata->height }}" />
                            <x-th value="{{ $chartdata->order_by }}" />
                            <x-th value="{{ $chartdata->showlabels == '0' ? 'No' : 'Si' }}" />
                            <x-th value="{{ $chartdata->showlegend == '0' ? 'No' : 'Si' }}" />
                            <td>
                                
                                    <x-secondary-a href="{{ route('charts', $chartdata->chart_id) }}">
                                        <i class="icon-pencil"></i>
                                    </x-secondary-a>
                                    <x-success-button>
                                        <i class="icon-share"></i>
                                    </x-success-button>
                                    <x-danger-button data-chart="{{ $chartdata->chart_id }}" class="show_confirm_delete ml-1">
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
   
</x-app-layout>
