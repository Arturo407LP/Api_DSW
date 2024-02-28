<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Shop;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listadoCustomers = Customer::all();

        $users = [];

        foreach ($listadoCustomers as $user) {
            $data = DB::table('shops')
                ->join('users', 'customers.users_id', '=', 'users.id')
                ->join('municipalities', 'users.municipality_id', '=', 'municipalities.id')
                ->select(
                    'users.id', DB::raw('users.name as nombre_usuario'), 'customers.nombre', 'customers.apellidos',
                    'users.email',
                    'customers.sexo',
                    DB::raw('municipalities.nombre as municipio'),
                    'users.phone',
                    'customers.fecha_nacimiento'
                )
                ->get();

            array_push($users, $data);
        }

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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'municipality_id' => $request->municipality_id,
                'phone' => $request->phone
            ]);

            try {
            
                $shop = Shop::create([
                    'users_id' => $user->id,
                    'nombre' => $request->nombre,
                    'categories_id' => $request->categories_id,
                    'direccion' => $request->direccion,
                    'descripcion' => $request->descripcion,
                    'tokens_id' => $request->tokens_id
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Nueva tienda creada',
                    'id_actividad' => $shop->users_id
                ], 200);
            } catch (\Throwable $th) {

                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => "Erro al crear la tienda",
                    'error' => throw $th,
                ], 400);
            }


            return response()->json([
                'status' => true,
                'message' => 'Nueva tienda creada',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => throw $th,
            ], 404);
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
            $user = Customer::find($id);
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
            $user = User::find($id);
            $user->delete();

            $customer = DB::table('customers')->where('users_id', '=', $id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Cliente eliminado',
                'id_cliente' => $id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Cliente no encontrado',
                'error' => throw $th
            ], 404);
        }
    }
}
