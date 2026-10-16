<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController
{
    public function showLoginForm()
    {
        if (Session::has('jwt_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $apiUrl = config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));

        $email = strtolower(trim($request->email));
        $password = trim($request->password);

        try {
            $response = Http::timeout(12)->post($apiUrl . '/auth/login', [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Guardamos el token JWT y los datos básicos en la sesión de Laravel
                Session::put('jwt_token', $data['token']);
                Session::put('user_email', $data['email']);
                Session::put('user_role', $data['role']);
                Session::put('user_name', $data['name']);
                
                return redirect()->intended('/dashboard');
            }

            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no son válidas o el usuario está inactivo. Verifica el correo y la contraseña ingresada.',
            ])->onlyInput('email');

        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Error de conexión con el servidor de autenticación.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Has cerrado sesión exitosamente.');
    }

    public function showRegisterForm()
    {
        if (Session::has('jwt_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastName' => 'required',
            'documentType' => 'required',
            'documentNumber' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $apiUrl = config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));

        try {
            $response = Http::timeout(12)->post($apiUrl . '/auth/register', [
                'name' => trim($request->name),
                'lastName' => trim($request->lastName),
                'documentType' => trim($request->documentType),
                'documentNumber' => trim($request->documentNumber),
                'email' => strtolower(trim($request->email)),
                'password' => trim($request->password),
            ]);

            if ($response->successful()) {
                return redirect('/login')->with('success', 'Registro exitoso. Ya puedes iniciar sesión.');
            }

            $errorMsg = $response->json('message') ?? 'Error en el registro';
            return back()->withErrors(['email' => $errorMsg])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error de conexión con el servidor.'])->withInput();
        }
    }
}
