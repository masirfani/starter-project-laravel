<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get data table ajax
        if ($request->ajax()) {
            $data = Permission::latest()->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($see){
                $data = "
                <form action='". route('permission.destroy', $see->id) ."' method='DELETE' data-action='permission' data-id='$see->id'>
                    <div class='d-flex gap-1'>
                        <button type='button' class='btn btn-info btn-sm btn-detail'><i class='bi bi-info'></i></button>
                        <button type='button' class='btn btn-warning btn-sm btn-edit'><i class='bi bi-pencil'></i></button>
                        <button type='button' class='btn btn-danger btn-sm btn-delete'><i class='bi bi-trash'></i></button>
                    </div>
                </form>
                ";
                return $data;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        $config_table = [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'searchable' => false],
            ['data' => 'name', 'name' => 'name'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
        ];

        return view('backend.user-management.permission',[
            "route" => "permission",
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

        $data = Permission::create($validate->valid());

        return response()->json([
            'message' => "Permission <b><i>$data->name</i></b> berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Permission::find($id);
        $data->created_on = $data->created_at->diffForHumans();
        $data->updated_on = $data->updated_at->diffForHumans();
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

        $data = Permission::create($validate->valid());

        return response()->json([
            'message' => "Permission <b><i>$data->name</i></b> berhasil dirubah"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Permission::find($id);
        $data->destroy($id);

        return response()->json([
            'message' => "Permission <b><i>$data->name</i></b> berhasil dihapus"
        ], 200);
    }
}
