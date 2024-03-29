<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get data table ajax
        if ($request->ajax()) {
            return DataTables::of(Role::query()->orderBy('created_at', 'desc'))
            ->addIndexColumn()
            ->toJson();
        }

        $config_table = [
            ['title'=>'No',   'data' => 'DT_RowIndex', 'name' => 'id', 'searchable' => false, 'className' => 'dt-no text-center'],
            ['title'=>'Nama', 'data' => 'name',        'name' => 'name'],
        ];

        return view('backend.user-management.role',[
            "route" => "role",
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

        $data = Role::create($validate->valid());

        return response()->json([
            'message' => "Role <b><i>$data->name</i></b> berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = explode(",", $id);

        $data = Role::whereIn('id', $id)->get();
        
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

        $data = Role::find($id);
        $data->update($validate->valid());

        return response()->json([
            'message' => "Role <b><i>$data->name</i></b> berhasil dirubah"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = explode(",", $id);
        if (count($id) > 1) {
            $data  = Role::whereIn('id',$id);
            $count = $data->pluck('name')->count();
            $text  = $data->pluck('name')->implode(', ');
            $data->delete();

            return response()->json([
                'message' => "<b>$count</b> Role berhasil dihapus<p><b>$text</b></p>"
            ], 200);
        }else{
            $data = Role::find($id[0]);
            $data->destroy($id);
    
            return response()->json([
                'message' => "Role <b><i>$data->name</i></b> berhasil dihapus"
            ], 200);
        }
    }
}