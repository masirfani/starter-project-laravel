<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::latest()->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($see){
                $data = "
                <form action='". route('role.destroy', $see->id) ."' method='DELETE'>
                    <div class='d-flex gap-1'>
                        <button type='button' data-id='$see->id' class='btn btn-info btn-sm btn-detail'><i class='bi bi-info'></i></button>
                        <button type='button' data-id='$see->id' class='btn btn-warning btn-sm btn-edit'><i class='bi bi-pencil'></i></button>
                        <button type='button' data-id='$see->id' class='btn btn-danger btn-sm btn-delete'><i class='bi bi-trash'></i></button>
                        <button type='button' data-id='$see->id' class='btn btn-dark btn-sm btn-history'><i class='bi bi-clock-history'></i></button>
                    </div>
                </form>
                ";
                return $data;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('backend.user-management.role');
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
        $data = Role::find($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Role::find($id);
        $data->destroy($id);

        return response()->json([
            'message' => "Role <b><i>$data->name</i></b> berhasil dihapus"
        ], 200);
    }
}
