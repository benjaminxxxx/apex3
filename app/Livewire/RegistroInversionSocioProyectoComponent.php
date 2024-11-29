<?php

namespace App\Livewire;

use App\Models\Inversion;
use DateInterval;
use DateTime;
use Illuminate\Database\QueryException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegistroInversionSocioProyectoComponent extends Component
{
    use LivewireAlert;
    public $mostrarFormulario = false;
    public $chart;
    public $inversionId;

    public $capital_invertido = 20000;
    public $tasa_interes_anual = 5;
    public $plazo = 12;
    public $fecha_inicio;
    public $tipo_interes = false;
    public $frecuencia_pago = 'mensual';
    public $chartData = [];
    public $inversor_id,$projecto_id,$grupo_id;
    protected $listeners = ['crearInversion'];
    public function crearInversion($inversor_id,$projecto_id,$grupo_id){
        
        $this->resetForm();
        $this->inversor_id = $inversor_id;
        $this->projecto_id = $projecto_id;
        $this->grupo_id = $grupo_id;

        $this->mostrarFormulario = true;
    }
    public function resetForm(){
        $this->resetErrorBag();
        $this->reset(['capital_invertido','tasa_interes_anual','plazo','fecha_inicio','inversor_id','projecto_id','grupo_id']);
        $this->tipo_interes = false;
        $this->frecuencia_pago = 'mensual';
        $this->chartData = [];
    }
    public function guardarInformacion()
    {
        try {

            $data = [
                'inversor_id' => $this->inversor_id,
                'projecto_id' => $this->projecto_id,
                'grupo_id' => $this->grupo_id,
                'capital_invertido' => $this->capital_invertido,
                'tasa_interes_anual' => $this->tasa_interes_anual,
                'tipo_interes' => $this->tipo_interes?'compuesto':'simple',
                'frecuencia_pago' => $this->frecuencia_pago,
                'fecha_inicio' => $this->fecha_inicio,
                'plazo' => $this->plazo,
                'fecha_vencimiento' => now()->addMonths($this->plazo), // Calcula la fecha de vencimiento
                'capital_final' => $this->calcularCapitalFinal(), // Agrega lógica para calcular el capital final
            ];

            if ($this->inversionId) {
                // Actualizar si existe inversión
                $inversion = Inversion::findOrFail($this->inversionId);
                $inversion->update($data);
                $this->alert('success', 'Inversión actualizada exitosamente.');
            } else {
                // Crear nueva inversión
                Inversion::create($data);
                $this->alert('success', 'Inversión creada exitosamente.');
            }
            $this->resetForm();
            $this->mostrarFormulario = false;
            $this->dispatch('inversionRegistrada');
        } catch (QueryException $e) {
            $this->alert('error', 'Ocurrió un error al guardar la inversión. Inténtalo nuevamente.');
        } catch (\Throwable $th) {
            $this->alert('error', 'Error: ' . $th->getMessage());
        }
    }
    private function calcularCapitalFinal()
    {
        if ($this->tipo_interes === false) {
            return $this->capital_invertido * (1 + ($this->tasa_interes_anual / 100) * ($this->plazo / 12));
        } elseif ($this->tipo_interes === true) {
            return $this->capital_invertido * pow(1 + ($this->tasa_interes_anual / 100), ($this->plazo / 12));
        }
        return $this->capital_invertido; // Por defecto regresa el capital inicial si no se define el tipo de interés
    }
    public function calcularInversion()
    {

        $this->validate([
            'capital_invertido' => 'required|numeric|min:0.01',
            'tipo_interes' => 'required', // Validación para el tipo de interés
            'tasa_interes_anual' => 'required|numeric|min:0.01',
            'plazo' => 'required|integer|min:1', // Plazo en meses debe ser al menos 1
            'frecuencia_pago' => 'required|in:mensual,trimestral,semestral,anual', // Validación de valores aceptados
            'fecha_inicio' => 'required|date',
        ], [
            'capital_invertido.required' => 'El capital inicial es obligatorio.',
            'capital_invertido.numeric' => 'El capital inicial debe ser un número.',
            'capital_invertido.min' => 'El capital inicial debe ser mayor a 0.',
            'tipo_interes.required' => 'El tipo de interés es obligatorio.',
            'tasa_interes_anual.required' => 'La tasa de interés es obligatoria.',
            'tasa_interes_anual.numeric' => 'La tasa de interés debe ser un número.',
            'tasa_interes_anual.min' => 'La tasa de interés debe ser mayor a 0.',
            'plazo.required' => 'El plazo es obligatorio.',
            'plazo.integer' => 'El plazo debe ser un número entero.',
            'plazo.min' => 'El plazo debe ser al menos 1 mes.',
            'frecuencia_pago.required' => 'La frecuencia de pagos es obligatoria.',
            'frecuencia_pago.in' => 'La frecuencia de pagos debe ser Mensual, Trimestral, Semestral o Anual.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
        ]);

        // Convertir la fecha de inicio a un objeto DateTime
        $fecha_inicio = new DateTime($this->fecha_inicio);

        $tasaMensual = $this->tasa_interes_anual / 100 / 12;
        $data = [];
        $capital = (float) $this->capital_invertido;
        $intervaloFrecuencia = $this->obtenerIntervaloFrecuencia($this->frecuencia_pago);

        // Array de meses en español
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Cálculo de intereses
        for ($i = 0; $i <= $this->plazo; $i++) {
            // Fecha de pago según frecuencia seleccionada
            $fechaPago = (clone $fecha_inicio)->add(new DateInterval("P" . ($intervaloFrecuencia * $i) . "M"));
            $mesIndex = $fechaPago->format('n') - 1; // Obtener el índice del mes (0 a 11)
            $mes = $meses[$mesIndex]; // Obtener el nombre del mes en español
            $año = $fechaPago->format('Y');

            // Cálculo de capital acumulado
            if ($this->tipo_interes === false) {
                // Interés Simple
                $capital = $this->capital_invertido * (1 + ($tasaMensual * $i));
            } else {
                // Interés Compuesto
                $capital = $this->capital_invertido * pow(1 + $tasaMensual, $i);
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

    protected function obtenerIntervaloFrecuencia($frecuencia_pago)
    {
        switch ($frecuencia_pago) {
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
        return view('livewire.registro-inversion-socio-proyecto-component');
    }
}
