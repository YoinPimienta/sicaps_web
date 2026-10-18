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
            $response = Http::timeout(65)->retry(2, 500)->post($apiUrl . '/auth/login', [
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

            // Distinguir error real de credenciales vs servidor despertando
            if ($response->status() === 401 || $response->status() === 403) {
                return back()->withErrors([
                    'email' => 'Las credenciales proporcionadas no son válidas o el usuario está inactivo. Verifica el correo y la contraseña ingresada.',
                ])->onlyInput('email');
            }

            if ($response->status() === 502 || $response->status() === 503 || $response->status() === 504) {
                return back()->withErrors([
                    'email' => 'El servidor clínico se está reanudando tras un periodo de inactividad en la nube. Por favor espera unos segundos e intenta nuevamente.',
                ])->onlyInput('email');
            }

            return back()->withErrors([
                'email' => 'El servidor respondió con un error temporal (' . $response->status() . '). Por favor intenta de nuevo.',
            ])->onlyInput('email');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->withErrors([
                'email' => 'El servidor clínico se está reanudando tras reposo. Por favor espera 30 segundos y vuelve a presionar Ingresar.',
            ])->onlyInput('email');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Error de conexión con el servidor de autenticación. Verifica tu conexión a internet o espera unos instantes.',
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
            'name' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'documentType' => 'required|in:CC,TI,CE,PASSPORT,PPT',
            'documentNumber' => 'required|string|min:5|max:30',
            'email' => 'required|email|max:150',
            'password' => 'required|min:6',
            'birthDate' => 'nullable|date',
            'gender' => 'nullable|in:MASCULINO,FEMENINO,OTRO',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'eps' => 'nullable|string|max:100',
            'emergencyContactName' => 'nullable|string|max:150',
            'emergencyContactPhone' => 'nullable|string|max:20',
            'emergencyContactRelationship' => 'nullable|string|max:50',
        ], [
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'documentNumber.required' => 'El número de identificación es obligatorio.',
            'documentNumber.min' => 'El documento de identificación debe tener al menos 5 dígitos.',
        ]);

        $apiUrl = config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));

        try {
            $payload = [
                'name' => trim($request->name),
                'lastName' => trim($request->lastName),
                'documentType' => trim($request->documentType),
                'documentNumber' => trim($request->documentNumber),
                'email' => strtolower(trim($request->email)),
                'password' => trim($request->password),
                'phone' => trim($request->phone ?? ''),
                'address' => trim($request->address ?? ''),
                'birthDate' => $request->birthDate,
                'gender' => $request->gender,
                'eps' => trim($request->eps ?? ''),
                'emergencyContactName' => trim($request->emergencyContactName ?? ''),
                'emergencyContactPhone' => trim($request->emergencyContactPhone ?? ''),
                'emergencyContactRelationship' => trim($request->emergencyContactRelationship ?? ''),
                'informedConsentAccepted' => true,
            ];

            $response = Http::timeout(65)->retry(2, 500)->post($apiUrl . '/auth/register', $payload);

            if ($response->successful()) {
                return redirect('/login')->with('success', 'Registro exitoso. Ya puedes iniciar sesión.');
            }

            if ($response->status() === 502 || $response->status() === 503 || $response->status() === 504) {
                return back()->withErrors(['email' => 'El servidor clínico se está reanudando tras reposo. Por favor espera 30 segundos y vuelve a intentar.'])->withInput();
            }

            $errorMsg = $response->json('message') ?? 'Error en el registro';
            return back()->withErrors(['email' => $errorMsg])->withInput();

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->withErrors(['email' => 'El servidor clínico se está reanudando tras reposo. Por favor espera unos momentos e intenta de nuevo.'])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error de conexión con el servidor.'])->withInput();
        }
    }
}
