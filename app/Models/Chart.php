<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chart extends Model
{
    protected $fillable = [
        'chart_id',
        'data',
        'user_id',
        'type',
        'chart_type',
        'height',
        'title',
        'order_by',
        'showlabels',
        'showlegend',
        'project_id'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $model->chart_id = Str::random(10);
            } while (self::where('chart_id', $model->chart_id)->exists());
        });
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function columns()
    {
        return $this->hasMany(ColumnChart::class);
    }
    public function rows()
    {
        return $this->hasMany(RowChart::class);
    }
    public function getHasDataAttribute()
    {
        if ($this->chart_type == 1) {
            return $this->getHasGeneralChartData();
        } elseif ($this->chart_type == 2) {
            return $this->getHasPartnerChartData();
        }

        return false;
    }

    private function getHasGeneralChartData()
    {
        $rows = $this->rows;
        $columns = $this->columns;

        foreach ($rows as $row) {
            foreach ($columns as $column) {
                $rowColumnData = RowColumn::where('row_id', $row->id)
                    ->where('column_id', $column->id)
                    ->first();
                if ($rowColumnData && $rowColumnData->data) {
                    return true;
                }
            }
        }

        return false;
    }
    private function getHasPartnerChartData()
    {
        $project = $this->project;
        $columns = $this->columns;
        $partners = $project->partners;

        foreach ($partners as $partner) {
            foreach ($columns as $column) {
                $partnerColumnData = PartnerColumn::where('partner_id', $partner->id)
                    ->where('column_chart_id', $column->id)
                    ->first();
                if ($partnerColumnData && $partnerColumnData->data) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Obtiene los datos del gráfico basados en el tipo de gráfico.
     * 
     * Esta función es un accesor personalizado que devuelve los datos 
     * del gráfico dependiendo del tipo de gráfico definido en el atributo 
     * 'chart_type'. Si el tipo de gráfico es 1, se obtienen los datos generales 
     * utilizando la función `getGeneralChartData`. Si el tipo de gráfico es 2, 
     * se obtienen los datos específicos de cada socio utilizando la función 
     * `getPartnerChartData`.
     *
     * @return array Los datos del gráfico en formato de matriz. El formato de los 
     *               datos varía según el tipo de gráfico.
     */
    public function getDataAttribute()
    {
        if ($this->chart_type == 1) {
            return $this->getGeneralChartData();
        } elseif ($this->chart_type == 2) {
            return $this->getPartnerChartData();
        }

        return [];
    }

    private function getGeneralChartData()
    {
        $data = [];
        $columns = $this->columns;
        $rows = $this->rows;

        $columnsHeader = $columns->pluck('name')->toArray();
        $rowHeader = $rows->pluck('name')->toArray();

        $dataAll = [];
        foreach ($rows as $row) {
            $rowData = [];
            foreach ($columns as $column) {
                $rowColumnData = RowColumn::where('row_id', $row->id)
                    ->where('column_id', $column->id)
                    ->first();
                $rowData[] = $rowColumnData ? $rowColumnData->data : null;
            }
            $dataAll[] = $rowData;
        }

        $data['columnsHeader'] = $columnsHeader;
        $data['rowHeader'] = $rowHeader;
        $data['data'] = $dataAll;

        return $data;
    }

    private function getPartnerChartData()
    {
        $data = [];
        $columns = $this->columns;
        $project = $this->project;
        $partners = $project->partners;

        foreach ($partners as $partner) {
            $columnsHeader = $columns->pluck('name')->toArray();
            $rowHeader = [$partner->name];  // Asumimos que solo hay una fila por socio

            $dataAll = [];
            foreach ($columns as $column) {
                $partnerColumnData = PartnerColumn::where('partner_id', $partner->id)
                    ->where('column_chart_id', $column->id)
                    ->first();
                $dataAll[] = $partnerColumnData ? $partnerColumnData->data : null;
            }

            $data[$partner->id] = [
                'columnsHeader' => $columnsHeader,
                'rowHeader' => $rowHeader,
                'data' => [$dataAll]
            ];
        }

        return $data;
    }
}
