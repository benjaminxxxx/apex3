<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Chart;

class GraficosController extends Controller
{
    public function index(){
        $charts = Chart::all();
        $data = [
            'charts'=>$charts
        ];
        return view('admin.graficos',$data);
    }
    public function import_document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'required|mimes:csv,txt' 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $csvData = $this->processCSV($file);
            return response()->json($csvData);
        }

        return response()->json(['error' => 'No se ha subido ningún archivo.'], 400);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'data' => 'required|json',
            'type' => 'required|string',
            'height' => 'required|integer',
            'title' => 'required|string',
            'order_by' => 'required|in:columns,rows',
            'showlabels' => 'required|in:0,1',
            'showlegend' => 'required|in:0,1',
        ]);
    
        try {
            $chart = Chart::create([
                'data' => $validatedData['data'],
                'user_id' => Auth::id(),
                'type' => $validatedData['type'],
                'height' => $validatedData['height'],
                'title' => $validatedData['title'],
                'order_by' => $validatedData['order_by'],
                'showlabels' => $validatedData['showlabels'],
                'showlegend' => $validatedData['showlegend'],
            ]);
    
            return response()->json(['message' => '¡Chart guardado correctamente!', 'chart' => $chart], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error al guardar el gráfico: ' . $e->getMessage()], 500);
        }
    }
    private function processCSV($file)
    {
        $csvData = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        } else {
            return null;
        }
        return $csvData;
    }

    
}
