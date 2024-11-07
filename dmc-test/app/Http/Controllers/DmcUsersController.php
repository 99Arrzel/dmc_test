<?php

namespace App\Http\Controllers;

use App\Models\DmcUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//No se si los comentarios tambien en ingles o en español
class DmcUsersController extends Controller
{
    /**
     * Listar todos los usuarios
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function ListUsers()
    {
        return DmcUsers::all();
    }
    /**
     *
     * Crea un usuario siguiendo los campos minimos de dmc_users
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\DmcUsers
     */
    public function CreateUser(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'uid' => 'nullable|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'required|unique:dmc_users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'birth_date' => 'nullable|string',
            'gender' => 'nullable|string|max:1',
        ]);
        if ($valid->fails()) {
            return response()->json($valid->errors(), 422);
        }
        $request->merge(['password' => bcrypt($request->password)]);
        return DmcUsers::create($request->all());
    }
    /**
     *
     * Actualiza un usuario segun los parametros enviados en request
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\DmcUsers
     */
    public function UpdateUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:dmc_users,id,deleted_at,NULL',
            'uid' => 'nullable|string',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'required|unique:dmc_users,email,' . $request->id,
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'birth_date' => 'nullable|string',
            'gender' => 'nullable|string|max:1',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        if ($request->password) {
            $request->merge(['password' => bcrypt($request->password)]);
        }
        $user = DmcUsers::find($request->id);
        $user->update($request->all());
        return $user;
    }

    /**
     * Elimina un usuario dmc, es un softdelete.
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\DmcUsers
     */
    public function DeleteUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|exists:dmc_users,id,deleted_at,NULL',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        $user = DmcUsers::find($request->id);
        $user->delete();
        return $user;
    }
}
