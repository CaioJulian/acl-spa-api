<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Spatie\Permission\Models\Role;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:usuario-listar|usuario-criar|usuario-editar|usuario-deletar', ['only' => ['index']]);
        $this->middleware('permission:usuario-criar', ['only' => ['store']]);
        $this->middleware('permission:usuario-editar', ['only' => ['update']]);
        $this->middleware('permission:usuario-deletar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_usuarios = User::all();
        foreach ($list_usuarios as $key => $value) {
            $value->roles;
        }
        $list_roles = Role::all();
        return response()->json(
            [
                'list_usuarios' => $list_usuarios,
                'list_roles' => $list_roles,
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required'
        ]);   
        if($validator->fails()){
            return response()->json(['message'=> $validator->errors()], 422);       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $lists = $this->index()->original;

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'string|min:8',
            'roles' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], 422);       
        }

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = bcrypt($input['password']);
        }

        $user = User::find($id);
        $user->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        $lists = $this->index()->original;

        return response()->json([
                'message' => 'Usuário atualizado com sucesso!',
                'lists' => $lists
            ], 201);
    }   
}