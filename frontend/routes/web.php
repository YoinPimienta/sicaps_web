<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Ruta principal redirige al dashboard si hay sesion, o al login
Route::get('/', function () {
    if (session()->has('jwt_token')) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rutas de Autenticacion
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Rutas protegidas (Dashboard, Pacientes, Citas, Historias Clinicas)
Route::middleware(['web', 'auth.jwt'])->group(function () {
    Route::get('/dashboard', function () {
        $token = session('jwt_token');
        $appointments = [];
        $patients = [];

        try {
            $apiUrl = config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));
            $res = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->timeout(4)->get($apiUrl . '/appointments');

            if ($res->status() === 401) {
                session()->flush();
                return redirect()->route('login')->with('error', 'Tu sesión ha expirado. Por favor ingresa nuevamente.');
            }

            if ($res->successful()) {
                $appointments = $res->json('data') ?? [];
            }

            $pRes = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->timeout(4)->get($apiUrl . '/patients');

            if ($pRes->status() === 401) {
                session()->flush();
                return redirect()->route('login')->with('error', 'Tu sesión ha expirado. Por favor ingresa nuevamente.');
            }

            if ($pRes->successful()) {
                $patients = $pRes->json('data') ?? [];
            }
        } catch (\Exception $e) {
            // Silencioso para no romper la experiencia si el backend tarda
        }

        $today = date('Y-m-d');
        $todayAppointments = array_values(array_filter($appointments, function($a) use ($today) {
            return ($a['date'] ?? '') === $today;
        }));

        return view('dashboard', [
            'name' => session('user_name', 'Profesional'),
            'role' => session('user_role', 'ROLE_PSYCHOLOGIST'),
            'appointments' => $appointments,
            'todayAppointments' => $todayAppointments,
            'patients' => $patients,
        ]);
    })->name('dashboard');

    // Módulo de Pacientes
    Route::resource('patients', \App\Http\Controllers\PatientController::class);

    // Módulo de Citas
    Route::get('appointments', [\App\Http\Controllers\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/create', [\App\Http\Controllers\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('appointments', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.store');
    Route::patch('appointments/{id}/status', [\App\Http\Controllers\AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

    // Módulo de Historias Clínicas
    Route::get('clinical', [\App\Http\Controllers\ClinicalController::class, 'index'])->name('clinical.index');
    Route::get('clinical/evolutions/create/{patient}', [\App\Http\Controllers\ClinicalController::class, 'createEvolution'])->name('clinical.evolutions.create');
    Route::post('clinical/evolutions/{patient}', [\App\Http\Controllers\ClinicalController::class, 'storeEvolution'])->name('clinical.evolutions.store');

    // Módulo de Profesionales (Equipo Clínico)
    Route::resource('professionals', \App\Http\Controllers\ProfessionalController::class);
});
