<?php

namespace App\Livewire;

use Illuminate\Database\QueryException;
use Livewire\Component;
use Auth;
use App\Models\Project;
use App\Models\Group;
use App\Models\Chart;
use App\Models\ColumnChart;
use App\Models\PartnerColumn;
use DB;


class ChartGroup extends Component
{
    public $selectedProject;
    public $selectedGroup;
    public $projects;
    public $groups;
    public $partners;
    public $chart_name;
    public $charts;
    public $theGroup;
    public $selectedChartId;
    public $selectedChart;
    public $chart_columns;
    public $chart_column;
    public $data = [];
    public function mount()
    {
        $this->projects = Auth::user()->managedProjects()->get();
    }
    public function render()
    {
        return view('livewire.chart-group');
    }
    public function loadChartData()
    {
        $chart = Chart::find($this->selectedChartId);
        $this->chart_columns = $chart->columns;
        $this->data = [];

        // Inicializar los datos
        foreach ($this->partners as $partner) {
            foreach ($this->chart_columns as $column) {
                $existingData = PartnerColumn::where('partner_id', $partner->id)
                    ->where('column_chart_id', $column->id)
                    ->first();
                $this->data[$partner->id][$column->id] = $existingData ? $existingData->data : '';
            }
        }
    }
    public function updateProject()
    {

        $this->selectedGroup = null;
        $this->partners = null;

        if ($this->selectedProject) {
            $this->groups = Project::find($this->selectedProject)->groupsOfMe()->get();
        }
    }
    public function createGridData()
    {

        $this->partners = null;

        if ($this->selectedGroup) {
            $this->theGroup = Group::find($this->selectedGroup);
            $this->partners = $this->theGroup->partners()->get();
            $this->charts = $this->theGroup->charts;
        }
    }
    public function selectChart()
    {
        if ($this->selectedChartId) {
            $this->selectedChart = Chart::find($this->selectedChartId);
            $this->chart_columns = $this->selectedChart->columns;
            $this->loadChartData();
            
            $columnsHeader = $this->chart_columns->pluck('name')->toArray();
            $rowHeader = $this->partners->pluck('name')->toArray();
            $data = [];
            foreach ($this->partners as $partner) {
                $rowData = [];
                foreach ($this->chart_columns as $column) {
                    $rowData[] = $this->data[$partner->id][$column->id] ?? null;
                }
                $data[] = $rowData;
            }

            $this->dispatch("loadChart",columnsHeader:$columnsHeader,rowHeader:$rowHeader,data:$data);
        }
    }
    public function removeColumn($columnId)
    {
        try {
            // Inicia una transacción para garantizar la integridad de los datos
            DB::transaction(function () use ($columnId) {
                // Elimina los registros asociados en la tabla partner_columns
                PartnerColumn::where('column_chart_id', $columnId)->delete();

                // Elimina la columna de la tabla column_charts
                ColumnChart::find($columnId)->delete();
            });

            // Vuelve a cargar los datos del gráfico
            $this->loadChartData();

            session()->flash('message', '¡Columna eliminada correctamente!');
        } catch (QueryException $e) {
            session()->flash('error', 'Error al eliminar la columna: ' . $e->getMessage());
        }
    }
    public function addChart()
    {
        try {
            Chart::create([
                'data' => "",
                'user_id' => Auth::id(),
                'type' => "bar",
                'height' => "200",
                'title' => $this->chart_name,
                'order_by' => "columns",
                'showlabels' => 1,
                'showlegend' => 1,
                'group_id' => $this->selectedGroup,
            ]);
            $this->chart_name = null;
            $this->createGridData();

            session()->flash('message', '¡Chart guardado correctamente!');
        } catch (QueryException $e) {
            session()->flash('error', 'Error al guardar el chart: ' . $e->getMessage());
        }
    }
    public function addColumn()
    {
        try {
            ColumnChart::create([
                'name' => $this->chart_column,
                'chart_id' => $this->selectedChart->id,
            ]);

            $this->chart_column = null;
            $this->chart_columns = $this->selectedChart->columns;

            $this->loadChartData();

            session()->flash('message', '¡Columna creada correctamente!');
        } catch (QueryException $e) {
            session()->flash('error', 'Error al crear la columna: ' . $e->getMessage());
        }
    }
    public function updateData($partnerId, $columnId, $value)
    {
        $this->data[$partnerId][$columnId] = $value;
    }
    public function updateInformation()
    {
        try {
            $insertData = [];

            foreach ($this->data as $partnerId => $columns) {
                foreach ($columns as $columnId => $value) {
                    $insertData[] = [
                        'partner_id' => $partnerId,
                        'column_chart_id' => $columnId,
                        'data' => $value,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            // Delete existing data for the selected chart's partners and columns
            PartnerColumn::whereIn('partner_id', array_keys($this->data))
                ->whereIn('column_chart_id', array_keys(current($this->data)))
                ->delete();

            // Insert new data
            PartnerColumn::insert($insertData);

            session()->flash('message', '¡Información guardada correctamente!');
        } catch (QueryException $e) {
            session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
        }

    }
}
