<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Customer;
use App\Models\Shop;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:SeeShop')->only('index');
        $this->middleware('can:EditShop')->only('index', 'store', 'update', 'destroy');

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = DB::table('shops')
            ->join('users', 'shops.users_id', '=', 'users.id')
            ->join('municipalities', 'users.municipality_id', '=', 'municipalities.id')
            ->select(
                'users.id', DB::raw('users.name as Username'), 'users.email', 'users.phone', 'shops.nombre', 'users.email', 'shops.direccion',
                'shops.descripcion', DB::raw('municipalities.nombre as municipio'),
            )
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
    public function store(storeShopRequest $request)
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
                    'message' => "Error al crear la tienda",
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
    public function update(UpdateShopRequest $request, string $id)
    {
        try {
            $user = User::find($id);
            $user->update($request->all());

            $shops = Shop::find($id);
            $shops->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Usuario modificada',
                'id_actividad' => $user->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Error',
                'error' => throw $th
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

            $shops = Shop::find($id);
            $shops->delete();

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
            ], 400);
        }
    }
}
