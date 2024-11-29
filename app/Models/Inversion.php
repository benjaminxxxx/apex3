<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'inversor_id',
        'projecto_id',
        'grupo_id',
        'capital_invertido',
        'tasa_interes_anual',
        'tipo_interes',
        'frecuencia_pago',
        'fecha_inicio',
        'plazo',
        'fecha_vencimiento',
        'capital_final',
    ];

    /**
     * Relación con el modelo User (inversor).
     */
    public function inversor()
    {
        return $this->belongsTo(User::class, 'inversor_id');
    }

    /**
     * Relación con el modelo Project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'projecto_id');
    }

    /**
     * Relación con el modelo Group.
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'grupo_id');
    }

    public function getChartDataAttribute()
    {
        $columnsHeader = []; // Encabezados de columnas (meses en español)
        $data = []; // Datos (montos acumulados)
        $rowHeader = ['Ganancias']; // Encabezado de fila

        $capitalInicial = $this->capital_invertido;
        $tasaInteresAnual = $this->tasa_interes_anual / 100; // Convertir porcentaje a decimal
        $tipoInteres = $this->tipo_interes; // 1 = Compuesto, 0 = Simple
        $plazo = $this->plazo; // En meses
        $fechaInicio = Carbon::parse($this->fecha_inicio);

        // Calcular montos acumulados
        for ($i = 0; $i < $plazo; $i++) {
            // Agregar el mes al encabezado (en español)
            $columnsHeader[] = $fechaInicio->copy()->addMonths($i)->translatedFormat('F');

            // Calcular el monto acumulado
            if ($tipoInteres == 'compuesto') { // Interés Compuesto
                $montoActual = $capitalInicial * pow(1 + ($tasaInteresAnual / 12), $i + 1);
            } else { // Interés Simple
                $montoActual = $capitalInicial * (1 + ($tasaInteresAnual * ($i + 1) / 12));
            }

            // Redondear el monto a 2 decimales
            $data[] = round($montoActual, 2);
        }

        // Construir el JSON codificado
        return json_encode([
            'columnsHeader' => $columnsHeader, // Encabezado de columnas (meses)
            'rowHeader' => $rowHeader, // Encabezado de fila
            'data' => [$data], // Una sola fila con los datos de los montos
        ]);
    }

}
