<?php

namespace App\Livewire;

use App\Models\Chart;
use DateInterval;
use DateTime;
use Livewire\Component;

class DialogDataInversion extends Component
{
    public $mostrarDialogo = false;
    public $chart;

    public $capitalInicial = 20000;
    public $tasaInteres = 5;
    public $plazo = 12;
    public $fechaInicio;
    public $tipoInteres = false;
    public $frecuenciaPago = 'mensual';
    public $chartData = [];

    protected $listeners = ['dialogDataInversion'];
    public function dialogDataInversion($chartId)
    {
        $this->chart = Chart::where('chart_id', $chartId)->first();
        if ($this->chart) {
            $this->mostrarDialogo = true;
        }
    }
    public function calcularInversion()
    {
        // Asegúrate de que $this->fechaInicio tenga un valor válido
        if (empty($this->fechaInicio)) {
            return; // Manejo de error si es necesario
        }
    
        // Convertir la fecha de inicio a un objeto DateTime
        $fechaInicio = new DateTime($this->fechaInicio);
        
        $tasaMensual = $this->tasaInteres / 100 / 12;
        $data = [];
        $capital = (float) $this->capitalInicial;
        $intervaloFrecuencia = $this->obtenerIntervaloFrecuencia($this->frecuenciaPago);
    
        // Array de meses en español
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
        // Cálculo de intereses
        for ($i = 0; $i <= $this->plazo; $i++) {
            // Fecha de pago según frecuencia seleccionada
            $fechaPago = (clone $fechaInicio)->add(new DateInterval("P" . ($intervaloFrecuencia * $i) . "M"));
            $mesIndex = $fechaPago->format('n') - 1; // Obtener el índice del mes (0 a 11)
            $mes = $meses[$mesIndex]; // Obtener el nombre del mes en español
            $año = $fechaPago->format('Y');
    
            // Cálculo de capital acumulado
            if ($this->tipoInteres === false) {
                // Interés Simple
                $capital = $this->capitalInicial * (1 + ($tasaMensual * $i));
            } else {
                // Interés Compuesto
                $capital = $this->capitalInicial * pow(1 + $tasaMensual, $i);
            }
    
            // Añadir el resultado formateado al arreglo de datos
            $data[] = [
                'mes' => $mes . ' ' . $año,
                'capital' => round($capital, 2),
            ];
        }
    
        // Asignar datos para el gráfico
        $this->chartData = $data;
        $this->dispatch('generarChart', $this->chartData);
    }
    
    protected function obtenerIntervaloFrecuencia($frecuenciaPago)
    {
        switch ($frecuenciaPago) {
            case 'trimestral':
                return 3;  // Cada 3 meses
            case 'semestral':
                return 6;  // Cada 6 meses
            case 'anual':
                return 12; // Cada 12 meses
            case 'mensual':
            default:
                return 1;  // Cada mes
        }
    }
    public function volver()
    {
        $this->chartData = [];
        $this->dispatch('generarChart', $this->chartData);
    }
    public function render()
    {
        return view('livewire.dialog-data-inversion');
    }
}
