<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;


class ExperinmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get data table ajax
        if ($request->ajax()) {
            return DataTables::of(Experiment::query()->orderBy('created_at', 'desc'))
            ->addIndexColumn()
            ->editColumn('status', function($see){
                return ($see->is_active) ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-danger">No</span>';
            })
            ->rawColumns(['status'])
            ->filterColumn('is_active', function($query, $keyword) {
                if ($keyword == "active") {
                    $query->where('is_active', 1);
                }
                if ($keyword == "no") {
                    $query->where('is_active', 0);
                }
            })
            ->toJson();
        }

        $config_table = [
            ['title' => 'No',   'data'  => 'DT_RowIndex', 'name' => 'id', 'searchable' => false, 'className' => 'dt-no text-center'],
            ['title' => 'Nama', 'data'  => 'name',        'name' => 'name'],
            ['title' => 'Agama', 'data' => 'religion',    'name' => 'religion'],
            ['title' => 'Nilai', 'data' => 'nilai',       'name' => 'nilai'],
            ['title' => 'Status', 'data' => 'status',     'name' => 'is_active'],
        ];

        return view('experiment.experiment',[
            "route" => "experiment",
            "config_table" => json_encode($config_table)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            "name" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(), 
                'message' => 'Ada yang salah nih!!!'
            ], 422);
        }

        $data = Experiment::create($validate->valid());

        return response()->json([
            'message' => "Experiment <b>$data->name</b> berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = explode(",", $id);

        $data = Experiment::whereIn('id', $id)->get();
        
        $data = $data->map(function($see){
            $see->created_on = $see->created_at->diffForHumans();
            $see->updated_on = $see->updated_at->diffForHumans();
            return $see;
        });
        
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(), 
                'message' => 'Ada yang salah nih!!!'
            ], 422);
        }

        $data = Experiment::find($id);
        $data->update($validate->valid());

        return response()->json([
            'message' => "Experiment <b>$data->name</b> berhasil dirubah"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = explode(",", $id);
        if (count($id) > 1) {
            $data  = Experiment::whereIn('id',$id);
            $count = $data->pluck('name')->count();
            $text  = $data->pluck('name')->implode(', ');
            $data->delete();

            return response()->json([
                'message' => "<b>$count</b> Experiment berhasil dihapus<p><b>$text</b></p>"
            ], 200);
        }else{
            $data = Experiment::find($id[0]);
            $data->destroy($id);
    
            return response()->json([
                'message' => "Experiment <b>$data->name</b> berhasil dihapus"
            ], 200);
        }
    }
}
