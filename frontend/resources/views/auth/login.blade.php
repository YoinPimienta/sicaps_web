<!DOCTYPE html>
<html lang="es" class="h-full bg-sicaps-bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — SICAPS | Sistema de Atención Psicológica</title>
    <link rel="icon" href="data:,">
    @if(config('app.env') === 'production' || !empty(env('RENDER')) || (request()->header('x-forwarded-proto') === 'https') || str_contains(request()->getHost(), 'onrender.com'))
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    
    <!-- Tipografía: Plus Jakarta Sans & DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN con tokens SICAPS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sicaps: {
                            bg: '#F7F5F0',
                            surface: '#FFFFFF',
                            border: '#E6E2DA',
                            primary: '#2F7F79',
                            'primary-hover': '#256A65',
                            'primary-soft': '#E3F0EE',
                            sand: '#E9DFD0',
                            'sand-light': '#F5EFE6',
                            text: '#23302F',
                            muted: '#5E6B69',
                            success: '#4C9A72',
                            'success-soft': '#EBF7F1',
                            warning: '#D9A441',
                            'warning-soft': '#FDF7E7',
                            danger: '#C65D5D',
                            'danger-soft': '#FBEBEB',
                            info: '#5B8DB8',
                            'info-soft': '#EDF5FB',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"DM Sans"', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 20px rgba(35, 48, 47, 0.05)',
                        'card': '0 8px 30px rgba(35, 48, 47, 0.07)',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script>
        // Precalentamiento proactivo: Despertar el backend en segundo plano mientras el usuario digita sus credenciales
        (function() {
            @php
                $apiUrl = config('services.backend.api_url', env('API_BASE_URL', 'http://backend:8080/api'));
                $healthUrl = str_starts_with($apiUrl, 'http') ? $apiUrl . '/health' : null;
            @endphp
            @if($healthUrl)
            try {
                fetch('{{ $healthUrl }}', { mode: 'no-cors', cache: 'no-cache' }).catch(function(){});
            } catch(e) {}
            @endif
        })();
    </script>
</head>
<body class="h-full font-sans antialiased text-sicaps-text bg-sicaps-bg">

<div class="min-h-screen grid lg:grid-cols-2">
    
    <!-- PANEL IZQUIERDO: Ilustración botánica/serena y valor de marca -->
    <div class="relative hidden lg:flex flex-col justify-between p-12 xl:p-16 bg-gradient-to-br from-[#E3F0EE] via-[#E9F3F1] to-[#D5E7E4] border-r border-sicaps-border overflow-hidden">
        
        <!-- Elementos decorativos de fondo (ondas y círculos suaves zen) -->
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/40 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-sicaps-primary/10 blur-3xl pointer-events-none"></div>
        
        <!-- Logo y Nombre de marca -->
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-sicaps-primary text-white flex items-center justify-center shadow-md">
                <!-- Isotipo de la Psique (Ψ) y Escucha Empática -->
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 6.5v3a7 7 0 0 0 14 0v-3" />
                    <path d="M12 3v17" />
                    <path d="M8.5 20h7" />
                    <circle cx="12" cy="3.5" r="1.5" fill="currentColor" stroke="none" />
                </svg>
            </div>
            <div>
                <span class="text-2xl font-bold tracking-tight text-sicaps-text">SICAPS</span>
                <span class="text-xs block text-sicaps-muted font-medium uppercase tracking-wider">Atención Psicológica</span>
            </div>
        </div>

        <!-- Ilustración Central: Diálogo Terapéutico, Psique e Insight (SVG clínico y armónico) -->
        <div class="relative z-10 my-auto py-8 flex flex-col items-center text-center">
            <div class="w-72 h-72 mb-8 relative flex items-center justify-center">
                <!-- Círculos de respiración y serenidad -->
                <div class="absolute inset-0 rounded-full bg-white/60 shadow-soft animate-pulse" style="animation-duration: 6s;"></div>
                <div class="absolute inset-6 rounded-full border border-sicaps-primary/20"></div>
                
                <!-- Arte de la Psique y el Encuentro Terapéutico -->
                <svg class="w-56 h-56 relative z-10" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="psiGrad" x1="40" y1="20" x2="200" y2="220" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#2F7F79" />
                            <stop offset="100%" stop-color="#256A65" />
                        </linearGradient>
                        <radialGradient id="insightGlow" cx="120" cy="45" r="30" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#2F7F79" stop-opacity="0.3" />
                            <stop offset="100%" stop-color="#2F7F79" stop-opacity="0" />
                        </radialGradient>
                        <linearGradient id="softArea" x1="120" y1="70" x2="120" y2="180" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#2F7F79" stop-opacity="0.08" />
                            <stop offset="100%" stop-color="#2F7F79" stop-opacity="0.02" />
                        </linearGradient>
                    </defs>

                    <!-- Ondas concéntricas de resonancia y escucha empática -->
                    <circle cx="120" cy="120" r="105" stroke="#2F7F79" stroke-width="1.2" stroke-opacity="0.18" stroke-dasharray="6 6" />
                    <circle cx="120" cy="120" r="85" stroke="#2F7F79" stroke-width="1.5" stroke-opacity="0.25" />
                    <circle cx="120" cy="120" r="62" fill="url(#softArea)" />

                    <!-- Halo de claridad / Insight -->
                    <circle cx="120" cy="45" r="26" fill="url(#insightGlow)" />

                    <!-- Cáliz de la Psique (Holding y Contención Terapéutica) -->
                    <!-- Brazo izquierdo: escucha y sostén del profesional -->
                    <path d="M60 85 C60 142 88 175 120 175" stroke="url(#psiGrad)" stroke-width="3" stroke-linecap="round" />
                    <!-- Brazo derecho: confianza y apertura del paciente -->
                    <path d="M180 85 C180 142 152 175 120 175" stroke="url(#psiGrad)" stroke-width="3" stroke-linecap="round" />

                    <!-- Líneas orgánicas internas: los dos mundos conectando en diálogo -->
                    <path d="M74 90 C72 100 75 110 79 116 C76 124 77 134 85 142" stroke="#2F7F79" stroke-width="1.8" stroke-linecap="round" stroke-opacity="0.45" />
                    <path d="M166 90 C168 100 165 110 161 116 C164 124 163 134 155 142" stroke="#2F7F79" stroke-width="1.8" stroke-linecap="round" stroke-opacity="0.45" />

                    <!-- Eje central: estabilidad, estructura y salud mental -->
                    <path d="M120 48 V195" stroke="url(#psiGrad)" stroke-width="3" stroke-linecap="round" />

                    <!-- Base de apoyo y ética profesional -->
                    <path d="M96 195 H144" stroke="url(#psiGrad)" stroke-width="3" stroke-linecap="round" />

                    <!-- Nodo de Insight (el momento de toma de consciencia y sanación) -->
                    <circle cx="120" cy="45" r="7" fill="#2F7F79" />
                    <circle cx="120" cy="45" r="3" fill="#FFFFFF" />

                    <!-- Nodos de equilibrio emocional en los extremos -->
                    <circle cx="60" cy="85" r="4.5" fill="#2F7F79" />
                    <circle cx="180" cy="85" r="4.5" fill="#2F7F79" />
                    <circle cx="60" cy="85" r="2" fill="#FFFFFF" />
                    <circle cx="180" cy="85" r="2" fill="#FFFFFF" />
                </svg>
            </div>

            <!-- Cita inspiradora de salud mental -->
            <blockquote class="max-w-md">
                <p class="text-2xl font-semibold text-sicaps-text leading-snug tracking-tight">
                    "Cuidar también requiere orden y calma."
                </p>
                <footer class="mt-3 text-sm text-sicaps-muted font-medium">
                    Gestión clínica serena, confidencial y centrada en el paciente.
                </footer>
            </blockquote>
        </div>

        <!-- Garantías de seguridad y privacidad en el pie -->
        <div class="relative z-10 pt-6 border-t border-sicaps-border/60 flex items-center justify-between text-xs text-sicaps-muted">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>
                Historias Clínicas Seguras
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
                Cifrado Profesional
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Agenda Inteligente
            </span>
        </div>
    </div>

    <!-- PANEL DERECHO: Formulario de inicio de sesión -->
    <div class="flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-24 py-12 bg-sicaps-bg">
        <div class="w-full max-w-md mx-auto">
            
            <!-- Logo para móviles -->
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-sicaps-primary text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 6.5v3a7 7 0 0 0 14 0v-3" />
                        <path d="M12 3v17" />
                        <path d="M8.5 20h7" />
                        <circle cx="12" cy="3.5" r="1.5" fill="currentColor" stroke="none" />
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-sicaps-text">SICAPS</span>
                    <span class="text-xs block text-sicaps-muted">Atención Psicológica</span>
                </div>
            </div>

            <!-- Título y bienvenida -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-sicaps-text tracking-tight">Bienvenido de nuevo</h1>
                <p class="mt-2 text-sm text-sicaps-muted">
                    Ingresa tus credenciales para acceder a tu panel profesional o consultar tus citas.
                </p>
            </div>

            <!-- Alertas de error de credenciales / validación -->
            @if ($errors->any())
                <div class="mb-6 bg-sicaps-danger-soft border border-sicaps-danger/30 rounded-xl p-4 flex items-start gap-3">
                    <div class="p-1 rounded-full bg-sicaps-danger text-white flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="text-sm text-sicaps-text font-medium">
                        <p class="font-semibold text-sicaps-danger">No fue posible iniciar sesión</p>
                        <p class="text-xs text-sicaps-muted mt-0.5">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-sicaps-danger-soft border border-sicaps-danger/30 rounded-xl p-4 flex items-start gap-3">
                    <div class="p-1 rounded-full bg-sicaps-danger text-white flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="text-sm text-sicaps-text font-medium">
                        <p class="font-semibold text-sicaps-danger">Acceso protegido</p>
                        <p class="text-xs text-sicaps-muted mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-sicaps-success-soft border border-sicaps-success/30 rounded-xl p-4 flex items-start gap-3">
                    <div class="p-1 rounded-full bg-sicaps-success text-white flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="text-sm text-sicaps-text font-medium">
                        <p class="font-semibold text-sicaps-success">Operación exitosa</p>
                        <p class="text-xs text-sicaps-muted mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Formulario interactivo con Alpine.js -->
            <form 
                action="{{ route('login') }}" 
                method="POST" 
                class="space-y-5"
                x-data="{ showPass: false, submitting: false }"
                @submit="submitting = true"
            >
                @csrf

                <!-- Campo Correo Electrónico -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sicaps-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            placeholder="ejemplo@sicaps.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>

                <!-- Campo Contraseña con Mostrar/Ocultar -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text">
                            Contraseña
                        </label>
                        <a href="javascript:void(0)" onclick="alert('Por motivos de confidencialidad médica, contacta al administrador del sistema para restablecer tu contraseña.')" class="text-xs font-medium text-sicaps-primary hover:text-sicaps-primary-hover hover:underline transition">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sicaps-muted">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            :type="showPass ? 'text' : 'password'" 
                            autocomplete="current-password" 
                            required 
                            placeholder="••••••••"
                            class="w-full pl-10 pr-11 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                        <!-- Botón ojo para alternar contraseña -->
                        <button 
                            type="button" 
                            @click="showPass = !showPass" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-sicaps-muted hover:text-sicaps-text transition focus:outline-none"
                            tabindex="-1"
                            title="Alternar visibilidad"
                        >
                            <!-- Ojo abierto -->
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <!-- Ojo tachado -->
                            <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Botón de Ingreso con estado de carga -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        :disabled="submitting"
                        class="w-full py-3 px-4 bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.99] text-white font-medium rounded-[10px] shadow-sm transition flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sicaps-primary disabled:opacity-75 disabled:cursor-not-allowed"
                    >
                        <!-- Spinner de carga -->
                        <svg x-show="submitting" x-cloak class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="submitting ? 'Ingresando al sistema...' : 'Ingresar al Sistema'"></span>
                        <svg x-show="!submitting" class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Separador elegante -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-sicaps-border"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-sicaps-bg px-3 text-sicaps-muted font-medium">¿Nuevo en el centro?</span>
                </div>
            </div>

            <!-- Botón secundario de registro de pacientes -->
            <div>
                <a 
                    href="{{ route('register') }}" 
                    class="w-full py-2.5 px-4 bg-white border border-sicaps-border hover:bg-sicaps-sand-light hover:border-sicaps-primary/30 text-sicaps-text font-medium rounded-[10px] text-center transition flex items-center justify-center gap-2 text-sm shadow-soft"
                >
                    <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/>
                    </svg>
                    Registrarme como paciente nuevo
                </a>
            </div>

            <!-- Aviso de confidencialidad y secreto profesional -->
            <div class="mt-8 pt-6 border-t border-sicaps-border/70 text-center">
                <div class="inline-flex items-center gap-1.5 text-xs text-sicaps-muted bg-white/70 py-1.5 px-3 rounded-full border border-sicaps-border">
                    <svg class="w-3.5 h-3.5 text-sicaps-primary flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                    </svg>
                    <span>Tu información es confidencial y está protegida.</span>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
