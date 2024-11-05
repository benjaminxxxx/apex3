<?php

namespace App\Livewire;

use Illuminate\Database\QueryException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Auth;
use App\Models\Project;
use App\Models\Group;
use App\Models\Chart;
use App\Models\ColumnChart;
use App\Models\RowChart;
use App\Models\PartnerColumn;
use App\Models\RowColumn;
use App\Models\Chartpublish;
use Illuminate\Support\Str;
use DB;


class ChartGroup extends Component
{
    use LivewireAlert;
    public $chart;
    public $chartId;
    public $selectedChartType;
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
    public $chart_rows;
    public $chart_column;
    public $chart_row;
    public $data = [];
    public $isFormData = false;
    public $isChartSelected = false;
    public $chartToShow;
    public $chartType = "bar";
    public $chart_title = "";
    public $showlegend = true;
    public $showlabels = true;
    public $chartToPublish;
    public $chart_description;
    public $projectCountPartners = 0;
    public function mount()
    {
        $this->projects = Project::all();
    }
    public function render()
    {
        $this->charts = Chart::all();
        if ($this->selectedProject) {
            $project = Project::find($this->selectedProject);
            $partners = $project->partners;
            $this->projectCountPartners = $partners->count();
        } else {
            $this->projectCountPartners = 0;
        }
        return view('livewire.chart-group');
    }

    public function showchart($chartId)
    {
        try {

            $chart = Chart::where('chart_id', $chartId)->first();
            if ($chart) {
                $this->selectedChart = $chart;
                $this->chartToPublish = $chart->id;
                $this->loadChartData();
                //$this->chartToShow = $chart;
                $project = Project::find($chart->project_id);
                $partners = $project->partners;
                $chart_columns = $this->selectedChart->columns;

                $columnsHeader = $chart_columns->pluck('name')->toArray();
                $rowHeader = [];

                if ($this->selectedChart->chart_type == 1) {
                    //general
                    $rows = $this->selectedChart->rows;
                    $rowHeader = $rows->pluck('name')->toArray();
                } else {

                    $rows = $partners;
                    $rowHeader = $rows->pluck('name')->toArray();
                }
                $data = [];
                foreach ($rows as $row) {
                    $rowData = [];
                    foreach ($chart_columns as $column) {
                        $rowData[] = $this->data[$row->id][$column->id] ?? null;
                    }
                    $data[] = $rowData;
                }

                $this->dispatch("loadChart", columnsHeader: $columnsHeader, rowHeader: $rowHeader, data: $data);

            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al tratar de visualizar el gráfico: ' . $e->getMessage());
        }
    }
    public function loadChartData()
    {
        $chart = Chart::find($this->selectedChart->id);
        $this->chart_columns = $chart->columns;
        $this->chart_rows = $chart->rows;
        $this->data = [];

        $project = Project::find($chart->project_id);

        if ($project) {

            switch ($chart->chart_type) {
                case 1:
                    # general

                    foreach ($this->chart_rows as $row) {
                        foreach ($this->chart_columns as $column) {
                            $existingData = RowColumn::where('row_id', $row->id)
                                ->where('column_id', $column->id)
                                ->first();
                            $this->data[$row->id][$column->id] = $existingData ? $existingData->data : '';
                        }
                    }
                    break;

                default:
                    # socio
                    $this->partners = $project->partners;

                    foreach ($this->partners as $partner) {
                        foreach ($this->chart_columns as $column) {
                            $existingData = PartnerColumn::where('partner_id', $partner->id)
                                ->where('column_chart_id', $column->id)
                                ->first();
                            $this->data[$partner->id][$column->id] = $existingData ? $existingData->data : '';
                        }
                    }
                    break;
            }

        }
    }
    public function updateChartType()
    {

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
    public function publishChart()
    {


        try {
            $data = $this->selectedChart->data;
            $code = Str::random(10);
            $user_id = Auth::id();

            Chartpublish::create([
                'code' => $code,
                'data' => json_encode($data), // Asegurarse de codificar los datos como JSON
                'chart_type' => $this->selectedChart->chart_type,
                'type' => $this->chartType,
                'title' => $this->chart_title,
                'description' => $this->chart_description,
                'showlabels' => $this->showlabels ? '1' : '0',
                'showlegend' => $this->showlegend ? '1' : '0',
                'chart_id' => $this->selectedChart->id,
                'user_id' => $user_id,
                'project_id' => $this->selectedChart->project_id,
            ]);
            $this->dispatch("hideChartView");
            session()->flash('message', '¡Gráfico publicado correctamente!');
        } catch (QueryException $e) {
            session()->flash('error', 'Error al publicar el gráfico: ' . $e->getMessage());
        }
    }
    public function selectChart()
    {
        /*
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

            $this->dispatch("loadChart", columnsHeader: $columnsHeader, rowHeader: $rowHeader, data: $data);
        }*/
    }
    public function removeColumn($columnId)
    {

        $this->updateInformation();

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
    public function edit($chartId)
    {

        try {
            $chart = Chart::where('chart_id', $chartId)->first();
            if ($chart) {
                $this->chartId = $chart->id;
                $this->selectedChartType = $chart->chart_type;
                $this->chart_name = $chart->title;
                $this->selectedProject = $chart->project_id;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al tratar de editar el gráfico: ' . $e->getMessage());
        }
    }
    public function cancelar()
    {
        $this->chartId = null;
        $this->selectedChartType = null;
        $this->chart_name = null;
        $this->selectedProject = null;
    }
    public function addChart()
    {
        // Definir las reglas de validación
        $rules = [
            'selectedChartType' => 'required',
            'chart_name' => 'required',
            'selectedProject' => 'required',
        ];

        // Definir los mensajes de validación en español
        $messages = [
            'selectedChartType.required' => 'El tipo de gráfico es obligatorio.',
            'chart_name.required' => 'El nombre del gráfico es obligatorio.',
            'selectedProject.required' => 'El proyecto seleccionado es obligatorio.',
        ];

        // Validar los datos
        $this->validate($rules, $messages);

        try {
            $data = [
                'user_id' => Auth::id(),
                'chart_type' => $this->selectedChartType,
                'title' => $this->chart_name,
                'project_id' => $this->selectedProject,
            ];

            if ($this->chartId) {
                // Actualizar el gráfico existente
                $chart = Chart::find($this->chartId);
                if ($chart) {
                    $chart->update($data);
                } else {
                    session()->flash('error', 'Error: El gráfico no existe.');
                    return;
                }
            } else {
                // Insertar un nuevo gráfico
                $data["data"] = "";
                $data["type"] = "bar";
                $data["height"] = "200";
                $data["order_by"] = "columns";
                $data["showlabels"] = 1;
                $data["showlegend"] = 1;

                Chart::create($data);
            }

            // Reiniciar los campos después de la operación
            $this->chartId = null;
            $this->selectedChartType = null;
            $this->chart_name = null;
            $this->selectedProject = null;

            $this->createGridData();

            session()->flash('message', '¡Chart guardado correctamente!');
        } catch (QueryException $e) {
            session()->flash('error', 'Error al guardar el chart: ' . $e->getMessage());
        }
    }
    public function addColumn()
    {
        $this->updateInformation();

        $rules = [
            'chart_column' => 'required|string|max:255',
        ];

        $messages = [
            'chart_column.required' => 'El nombre de la columna es obligatorio.',
            'chart_column.string' => 'El nombre de la columna debe ser una cadena de texto.',
            'chart_column.max' => 'El nombre de la columna no puede tener más de 255 caracteres.',
        ];

        $this->validate($rules, $messages);

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
    public function addRow()
    {
        $this->updateInformation();

        $rules = [
            'chart_row' => 'required|string|max:255',
        ];

        $messages = [
            'chart_row.required' => 'El nombre de la columna es obligatorio.',
            'chart_row.string' => 'El nombre de la columna debe ser una cadena de texto.',
            'chart_row.max' => 'El nombre de la columna no puede tener más de 255 caracteres.',
        ];

        $this->validate($rules, $messages);

        try {
            RowChart::create([
                'name' => $this->chart_row,
                'chart_id' => $this->selectedChart->id,
            ]);

            $this->chart_row = null;
            $this->chart_rows = $this->selectedChart->rows;

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
    public function updateAndClose()
    {
        $this->updateInformation();
        $this->isFormData = false;
    }
    public function updateInformation()
    {
        try {
            $insertData = [];

            if ($this->data && count($this->data) > 0) {
                foreach ($this->data as $partnerId => $columns) {
                    foreach ($columns as $columnId => $value) {

                        switch ($this->selectedChart->chart_type) {
                            case 1:
                                $insertData[] = [
                                    'row_id' => $partnerId,
                                    'column_id' => $columnId,
                                    'data' => $value,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                break;

                            default:
                                $insertData[] = [
                                    'partner_id' => $partnerId,
                                    'column_chart_id' => $columnId,
                                    'data' => $value,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                break;
                        }
                    }
                }

                if ($this->selectedChart->chart_type == 1) {
                    //general
                    RowColumn::whereIn('row_id', array_keys($this->data))
                        ->whereIn('column_id', array_keys(current($this->data)))
                        ->delete();

                    // Insert new data
                    RowColumn::insert($insertData);
                } else {
                    //socio
                    // Delete existing data for the selected chart's partners and columns
                    PartnerColumn::whereIn('partner_id', array_keys($this->data))
                        ->whereIn('column_chart_id', array_keys(current($this->data)))
                        ->delete();

                    // Insert new data
                    PartnerColumn::insert($insertData);
                }
            }




            session()->flash('message', '¡Información guardada correctamente!');
        } catch (QueryException $e) {
            session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
        }

    }
    public function showdata($chartId)
    {
        try {
            // Buscar el gráfico por su ID
            $chart = Chart::where('chart_id', $chartId)->firstOrFail();

            // Verificar si el gráfico existe
            if ($chart) {
                $this->selectedChart = $chart;

                $this->loadChartData();
                // Inicializar los datos


                /*$columnsHeader = $this->chart_columns->pluck('name')->toArray();
                $rowHeader = $this->partners->pluck('name')->toArray();
                $data = [];
                foreach ($this->partners as $partner) {
                    $rowData = [];
                    foreach ($this->chart_columns as $column) {
                        $rowData[] = $this->data[$partner->id][$column->id] ?? null;
                    }
                    $data[] = $rowData;
                }
*/
                $this->isFormData = true;
            } else {
                session()->flash('error', 'Error: El gráfico no existe.');
            }
        } catch (QueryException $e) {
            session()->flash('error', 'Error al eliminar el chart: ' . $e->getMessage());
        }
    }
    public function closeForm()
    {
        $this->selectedChart = null;
        $this->chart_columns = null;
        $this->data = [];
        $this->partners = null;
        $this->isFormData = false;
        $this->chart_column = null;
    }
    public function delete($chartId)
    {
        try {
            // Buscar el gráfico por su ID
            $chart = Chart::where('chart_id', $chartId)->firstOrFail();

            // Verificar si el gráfico existe
            if ($chart) {
                // Eliminar el gráfico
                $chart->delete();
                session()->flash('message', '¡Chart eliminado correctamente!');
            } else {
                session()->flash('error', 'Error: El gráfico no existe.');
            }
        } catch (QueryException $e) {
            session()->flash('error', 'Error al eliminar el chart: ' . $e->getMessage());
        }
    }
}
