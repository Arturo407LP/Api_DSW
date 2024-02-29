<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:SeeCustomer')->only('index');
        $this->middleware('can:EditCustomer')->only('index', 'store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listadoCustomers = DB::table('customers')
                ->join('users', 'customers.users_id', '=', 'users.id')
                ->join('municipalities', 'users.municipality_id', '=', 'municipalities.id')
                ->select(
                    'users.id',
                    DB::raw('users.name as nombre_usuario'),
                    'customers.nombre',
                    'customers.apellidos',
                    'users.email',
                    'customers.sexo',
                    DB::raw('municipalities.nombre as municipio'),
                    'users.phone',
                    'customers.fecha_nacimiento'
                )
                ->get();

        

        return response()->json([
            'status' => true,
            'data' => $listadoCustomers
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
    public function store(StoreCustomerRequest $request)
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
                $customer = Customer::create([
                    'users_id' => $user->id,
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'sexo' => $request->sexo,
                    "fecha_nacimiento" => $request->fecha_nacimiento
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Nuevo cliente creado',
                    'id_actividad' => $user->id
                ], 200);

            } catch (\Throwable $th) {

                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => "Erro al crear el cliente",
                    'error' => throw $th,
                ], 400);

            }

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
    public function update(UpdateCustomerRequest $request, string $id)
    {
        try {

            $user = User::find($id);
            $user->update($request->all());

            $customer = Customer::find($id);
            $customer->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Usuario modificado',
                'id_actividad' => $user->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
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
            $customer = Customer::find($id);

            $user->delete();
            $customer->delete();

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
