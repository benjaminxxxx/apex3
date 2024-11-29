<div>
    <x-dialog-modal wire:model="mostrarFormulario">
        <x-slot name="title">
            Registro de Nueva Inversión
        </x-slot>

        <x-slot name="content">
            <div x-data="chartJsMap">
                <div class="grid grid-cols-2 md:grid-cols1 gap-4" x-show="chartData.length == 0">
                    <div class="mt-2">
                        <x-label for="capital_invertido">Capital Inicial</x-label>
                        <x-input wire:model="capital_invertido" type="number" placeholder="0.00" class="mt-2" />
                        <x-input-error for="capital_invertido" />
                    </div>
                    <div class="mt-2">
                        <x-label for="tipo_interes">Tipo de Interés</x-label>
                        <div class="mt-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="tipo_interes" class="sr-only peer">
                                <div
                                    class="relative w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span
                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $tipo_interes ? 'Compuesto' : 'Simple' }}</span>
                            </label>
                        </div>
    
                        <x-input-error for="tipo_interes" />
                    </div>
                    <div class="mt-2">
                        <x-label for="tasa_interes_anual">Tasa de Interés Anual (%)</x-label>
                        <x-input type="number" wire:model="tasa_interes_anual" class="mt-2" />
                        <x-input-error for="tasa_interes_anual" />
                    </div>
                    <div class="mt-2">
                        <x-label for="plazo">Plazo (meses)</x-label>
                        <x-input type="number" wire:model="plazo" class="mt-2" />
                        <x-input-error for="plazo" />
                    </div>
                    <div class="mt-2">
                        <x-label for="frecuencia_pago">Frecuencia de Pagos</x-label>
                        <x-select wire:model="frecuencia_pago" class="mt-2">
                            <option value="mensual">Mensual</option>
                            <option value="trimestral">Trimestral</option>
                            <option value="semestral">Semestral</option>
                            <option value="anual">Anual</option>
                        </x-select>
                        <x-input-error for="frecuencia_pago" />
                    </div>
                    <div class="mt-2">
                        <x-label for="fecha_inicio">Fecha de Inicio</x-label>
                        <x-input type="date" wire:model="fecha_inicio" placeholder="0.00" class="mt-2" />
                        <x-input-error for="fecha_inicio" />
                    </div>
    
                </div>
    
    
                <div class="my-5"  x-show="chartData.length > 0" style="display: none;" >
                    
                    <canvas x-ref="chartJs" class="h-[300px]" wire:ignore></canvas>
    
                    <x-table class="mt-8">
                        <x-slot name="thead">
                            <x-tr>
                                <x-th>Resumen de la Inversión</x-th>
                                <x-th>Valor</x-th>
                            </x-tr>
                        </x-slot>
                        <x-slot name="tbody">
                            <x-tr>
                                <x-td>Capital Inicial</x-td>
                                <x-td>{{ $capital_invertido }}</x-td>
                            </x-tr>
                            <x-tr>
                                <x-td>Tipo de Interés</x-td>
                                <x-td>{{ $tipo_interes ? 'Compuesto' : 'Simple' }}</x-td>
                            </x-tr>
                            <x-tr>
                                <x-td>Tasa de Interés</x-td>
                                <x-td>{{ $tasa_interes_anual }}%</x-td>
                            </x-tr>
                            <x-tr>
                                <x-td>Plazo</x-td>
                                <x-td>{{ $plazo }} meses</x-td>
                            </x-tr>
                            <x-tr>
                                <x-td>Frecuencia de Pago</x-td>
                                <x-td>{{ $frecuencia_pago }}</x-td>
                            </x-tr>
                            <x-tr>
                                <x-td>Fecha de Inicio</x-td>
                                <x-td>{{ $fecha_inicio }}</x-td>
                            </x-tr>
                            <x-tr>
                                <x-td>Capital Final</x-td>
                                <x-td>
                                    {{ isset($chartData) && count($chartData)>0 ? number_format(end($chartData)['capital'], 2) : 'N/A' }}
                                </x-td>
                            </x-tr>
                        </x-slot>
        
        
        
                    </x-table>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center justify-end gap-5">
                @if(!$chartData)
                <x-secondary-button wire:click="$set('mostrarFormulario', false)" wire:loading.attr="disabled">
                    Cerrar
                </x-secondary-button>
                <x-button wire:click="calcularInversion" wire:loading.attr="disabled">
                    Calcular Inversión
                </x-button>
                @else
                <x-secondary-button wire:click="volver" wire:loading.attr="disabled">
                    Atrás
                </x-secondary-button>
                <x-button wire:click="guardarInformacion" wire:loading.attr="disabled">
                    Guardar Gráfico
                </x-button>
                @endif
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
@script
<script>
    Alpine.data('chartJsMap', () => ({
        listeners: [],
        chart: null,
        chartData: [],

        init() {
            this.initChart();

            // Escuchar eventos de Livewire para actualizar el gráfico
            this.listeners.push(
                Livewire.on('generarChart', (data) => {
              
                    this.chartData = data[0];
                    this.recreateChart();
                })
            );
        },

        initChart() {
            const container = this.$refs.chartJs;
            const ctx = container.getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.chartData.map(item => `${item.mes}`),
                    datasets: [{
                        label: 'Capital',
                        data: this.chartData.map(item => item.capital),
                        borderColor: 'hsl(var(--primary))',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Mes'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Capital'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Capital: ${context.raw.toFixed(2)}`
                            }
                        }
                    }
                }
            });

            this.chart = chart;
        },

        recreateChart() {
            // Verificar si el gráfico ya existe y destruirlo
            if (this.chart) {
                this.chart.destroy();
            }

            // Crear un nuevo gráfico con datos actualizados
            this.initChart();
        }
    }));
</script>

@endscript