<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    	/**
	 * Función que muestra la vista de logados o la vista con el formulario de Login
	 */
	public function index()
	{
		// Comprobamos si el usuario ya está logado
		if (Auth::check()) {

			// Si está logado le mostramos la vista de logados
			return view('actividades');
		}

		// Si no está logado le mostramos la vista con el formulario de login
		return view('login');
	}

	/**
	 * Función que se encarga de recibir los datos del formulario de login, comprobar que el usuario existe y
	 * en caso correcto logar al usuario
	 */
	public function login(Request $request)
	{
		// Comprobamos que el email y la contraseña han sido introducidos
		$request->validate([
			'email' => 'required',
			'password' => 'required',
		]);

		// Almacenamos las credenciales de email y contraseña
		$credentials = $request->only('email', 'password');

		// Si el usuario existe lo logamos y lo llevamos a la vista de "logados" con un mensaje
		if (Auth::attempt($credentials)) {
			$user = User::where('email', $request->email)->first();

			if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }

            $token = $user->createToken('my-app-token')->plainTextToken;

            $response = [
                'token' => $token
            ];
            return response($response, 201);
		}

		// Si el usuario no existe devolvemos al usuario al formulario de login con un mensaje de error
		return response([
            'message' => ['These credentials do not match our records.']
        ], 404);
	}

	/**
	 * Función que muestra la vista de logados si el usuario está logado y si no le devuelve al formulario de login
	 * con un mensaje de error
	 */
	public function logados()
	{
		if (Auth::check()) {
			return view('actividades');
		}

		return redirect("/")->withSuccess('No tienes acceso, por favor inicia sesión');
	}

	public function logout(Request $request): RedirectResponse
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}
}
