<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = DB::table('users')
            ->join('municipalities', 'users.municipality_id', '=', 'municipalities.id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('municipalities.nombre as municipio'), 'users.phone')
            ->orderBy('users.id')
            ->get();
 
        return response()->json([
            'status' => true,
            'data' => $users
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = User::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Nuevo usuario creado',
                'id_actividad' => $user->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Error en parametros',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::find($id);
            $user->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Usuario modificada',
                'id_actividad' => $user->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Error',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = user::find($id);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'Usuario eliminado',
                'id_actividad' => $user->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Usuario no encontrado',
            ], 404);
        }
    }
}
