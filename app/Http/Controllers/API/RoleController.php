<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:papel-listar|papel-criar|papel-editar|papel-deletar', ['only' => ['index']]);
        $this->middleware('permission:papel-criar', ['only' => ['store']]);
        $this->middleware('permission:papel-editar', ['only' => ['update']]);
        $this->middleware('permission:papel-deletar', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_papeis = Role::all();
        foreach ($list_papeis as $key => $value) {
            $value->permissions;
        }
        $list_permissions = Permission::get();
        return response()->json(
            [
                'list_papeis' => $list_papeis,
                'list_permissions' => $list_permissions,
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], 422);       
        }

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        $lists = $this->index()->original;

        return response()->json([
                'message' => 'Papel criado com sucesso!',
                'lists' => $lists
            ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'permission' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], 422);       
        }

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        $lists = $this->index()->original;

        return response()->json([
                'message' => 'Papel atualizado com sucesso!',
                'lists' => $lists
            ], 201);
    }

}
