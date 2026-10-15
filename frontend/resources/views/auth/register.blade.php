<!DOCTYPE html>
<html lang="es" class="h-full bg-sicaps-bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Paciente — SICAPS | Sistema de Atención Psicológica</title>
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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased text-sicaps-text bg-sicaps-bg">

<div class="min-h-screen grid lg:grid-cols-12">
    
    <!-- PANEL LATERAL: Ilustración botánica y mensaje de confianza (5 columnas) -->
    <div class="relative hidden lg:flex lg:col-span-5 flex-col justify-between p-12 bg-gradient-to-br from-[#E3F0EE] via-[#E9F3F1] to-[#D5E7E4] border-r border-sicaps-border overflow-hidden">
        
        <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-white/40 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-sicaps-primary/10 blur-3xl pointer-events-none"></div>
        
        <!-- Logo -->
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-sicaps-primary text-white flex items-center justify-center shadow-md">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                </svg>
            </div>
            <div>
                <span class="text-2xl font-bold tracking-tight text-sicaps-text">SICAPS</span>
                <span class="text-xs block text-sicaps-muted font-medium uppercase tracking-wider">Atención Psicológica</span>
            </div>
        </div>

        <!-- Contenido Central inspirador -->
        <div class="relative z-10 my-auto py-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/80 border border-sicaps-primary/20 text-xs font-semibold text-sicaps-primary mb-6">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Tu espacio de salud mental
            </div>

            <h2 class="text-3xl font-bold text-sicaps-text tracking-tight leading-snug">
                Comienza tu proceso con tranquilidad.
            </h2>
            <p class="mt-4 text-sm text-sicaps-muted leading-relaxed">
                El registro te permitirá agendar citas con nuestros profesionales, acceder a tu historial de consultas y recibir atención personalizada bajo total reserva y respeto.
            </p>

            <div class="mt-8 space-y-3">
                <div class="flex items-center gap-3 text-sm text-sicaps-text">
                    <div class="w-6 h-6 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center flex-shrink-0">
                        ✓
                    </div>
                    <span>Privacidad médica y secreto profesional garantizado.</span>
                </div>
                <div class="flex items-center gap-3 text-sm text-sicaps-text">
                    <div class="w-6 h-6 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center flex-shrink-0">
                        ✓
                    </div>
                    <span>Recordatorios oportunos de tus sesiones terapéuticas.</span>
                </div>
                <div class="flex items-center gap-3 text-sm text-sicaps-text">
                    <div class="w-6 h-6 rounded-full bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center flex-shrink-0">
                        ✓
                    </div>
                    <span>Atención presencial o en línea con psicólogos certificados.</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 pt-6 border-t border-sicaps-border/60 text-xs text-sicaps-muted">
            SICAPS • Centro Especializado de Bienestar Psicológico
        </div>
    </div>

    <!-- PANEL DERECHO: Formulario de Registro (7 columnas) -->
    <div class="lg:col-span-7 flex flex-col justify-center px-6 sm:px-12 lg:px-16 xl:px-20 py-12 bg-sicaps-bg">
        <div class="w-full max-w-xl mx-auto">
            
            <!-- Mobile Header -->
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-sicaps-primary text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-sicaps-text">SICAPS</span>
                    <span class="text-xs block text-sicaps-muted">Atención Psicológica</span>
                </div>
            </div>

            <!-- Título de Registro -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-sicaps-text tracking-tight">Registro de Paciente</h1>
                <p class="mt-2 text-sm text-sicaps-muted">
                    Completa tus datos personales para crear tu ficha clínica y habilitar el agendamiento.
                </p>
            </div>

            <!-- Alertas -->
            @if ($errors->any())
                <div class="mb-6 bg-sicaps-danger-soft border border-sicaps-danger/30 rounded-xl p-4">
                    <p class="font-semibold text-sm text-sicaps-danger mb-1">Por favor verifica los siguientes campos:</p>
                    <ul class="list-disc list-inside text-xs text-sicaps-text space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-sicaps-success-soft border border-sicaps-success/30 rounded-xl p-4 flex items-center gap-3">
                    <span class="text-sicaps-success font-bold text-lg">✓</span>
                    <p class="text-sm text-sicaps-text font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Formulario -->
            <form 
                action="{{ route('register.post') }}" 
                method="POST" 
                class="space-y-4"
                x-data="{ submitting: false, showPass: false }"
                @submit="submitting = true"
            >
                @csrf

                <!-- Fila 1: Nombre y Apellido -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Nombre
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            required 
                            value="{{ old('name') }}"
                            placeholder="Ej. Juan"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Apellido
                        </label>
                        <input 
                            type="text" 
                            name="lastName" 
                            required 
                            value="{{ old('lastName') }}"
                            placeholder="Ej. Pérez"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>

                <!-- Fila 2: Tipo de Documento y Número -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Tipo Doc.
                        </label>
                        <select 
                            name="documentType" 
                            required 
                            class="w-full px-3 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                            <option value="CC" {{ old('documentType') == 'CC' ? 'selected' : '' }}>CC (Cédula)</option>
                            <option value="TI" {{ old('documentType') == 'TI' ? 'selected' : '' }}>TI (Tarjeta Identidad)</option>
                            <option value="CE" {{ old('documentType') == 'CE' ? 'selected' : '' }}>CE (Extranjería)</option>
                            <option value="PAS" {{ old('documentType') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Número de Documento
                        </label>
                        <input 
                            type="text" 
                            name="documentNumber" 
                            required 
                            value="{{ old('documentNumber') }}"
                            placeholder="Ej. 1020304050"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>

                <!-- Campo Correo Electrónico -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        value="{{ old('email') }}"
                        placeholder="tu-correo@ejemplo.com"
                        class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                    >
                </div>

                <!-- Campo Contraseña -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Contraseña (mínimo 6 caracteres)
                    </label>
                    <div class="relative">
                        <input 
                            :type="showPass ? 'text' : 'password'" 
                            name="password" 
                            required 
                            minlength="6"
                            placeholder="••••••••"
                            class="w-full px-3.5 pr-11 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                        <button 
                            type="button" 
                            @click="showPass = !showPass" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-sicaps-muted hover:text-sicaps-text transition focus:outline-none"
                            tabindex="-1"
                        >
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="pt-4">
                    <button 
                        type="submit" 
                        :disabled="submitting"
                        class="w-full py-3 px-4 bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.99] text-white font-medium rounded-[10px] shadow-sm transition flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sicaps-primary disabled:opacity-75 disabled:cursor-not-allowed"
                    >
                        <svg x-show="submitting" x-cloak class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="submitting ? 'Creando tu cuenta...' : 'Crear Cuenta de Paciente'"></span>
                    </button>
                </div>
            </form>

            <!-- Enlace para volver a iniciar sesión -->
            <div class="mt-6 text-center text-sm text-sicaps-muted">
                ¿Ya tienes una cuenta registrada? 
                <a href="{{ route('login') }}" class="font-semibold text-sicaps-primary hover:text-sicaps-primary-hover hover:underline ml-1">
                    Inicia sesión aquí
                </a>
            </div>

            <div class="mt-8 pt-4 border-t border-sicaps-border/70 text-center">
                <span class="inline-flex items-center gap-1.5 text-xs text-sicaps-muted">
                    <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                    </svg>
                    Tus datos personales y de salud están estrictamente resguardados.
                </span>
            </div>

        </div>
    </div>

</div>

</body>
</html>
