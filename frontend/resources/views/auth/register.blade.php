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
    <script>
        // Precalentamiento proactivo: Despertar el backend en segundo plano mientras el usuario completa el registro
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

<div class="min-h-screen grid lg:grid-cols-12">
    
    <!-- PANEL LATERAL: Ilustración botánica y mensaje de confianza (5 columnas) -->
    <div class="relative hidden lg:flex lg:col-span-5 flex-col justify-between p-12 bg-gradient-to-br from-[#E3F0EE] via-[#E9F3F1] to-[#D5E7E4] border-r border-sicaps-border overflow-hidden">
        
        <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-white/40 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-sicaps-primary/10 blur-3xl pointer-events-none"></div>
        
        <!-- Logo -->
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

            <!-- Formulario Stepper Clínico -->
            <form 
                action="{{ route('register.post') }}" 
                method="POST" 
                class="space-y-6"
                x-data="{ 
                    step: 1,
                    submitting: false, 
                    showPass: false,
                    name: '{{ old('name', '') }}',
                    lastName: '{{ old('lastName', '') }}',
                    docType: '{{ old('documentType', 'CC') }}',
                    docNum: '{{ old('documentNumber', '') }}',
                    email: '{{ old('email', '') }}',
                    password: '',
                    birthDate: '{{ old('birthDate', '') }}',
                    gender: '{{ old('gender', '') }}',
                    phone: '{{ old('phone', '') }}',
                    address: '{{ old('address', '') }}',
                    eps: '{{ old('eps', '') }}',
                    emergencyContactName: '{{ old('emergencyContactName', '') }}',
                    emergencyContactPhone: '{{ old('emergencyContactPhone', '') }}',
                    emergencyContactRelationship: '{{ old('emergencyContactRelationship', '') }}',
                    showConsentModal: false,

                    get computedAge() {
                        if (!this.birthDate) return null;
                        const b = new Date(this.birthDate);
                        if (isNaN(b.getTime())) return null;
                        const today = new Date();
                        let age = today.getFullYear() - b.getFullYear();
                        const m = today.getMonth() - b.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < b.getDate())) {
                            age--;
                        }
                        return age >= 0 ? age : null;
                    },

                    get docPlaceholder() {
                        switch(this.docType) {
                            case 'CC': return 'Ej. 1020111234';
                            case 'TI': return 'Ej. 1098111222';
                            case 'CE': return 'Ej. 854921';
                            case 'PPT': return 'Ej. 9845124';
                            case 'PASSPORT': return 'Ej. PA0987654';
                            default: return 'Número de identificación';
                        }
                    },

                    get docHelper() {
                        switch(this.docType) {
                            case 'CC': return 'Cédula de Ciudadanía: 6 a 10 dígitos numéricos';
                            case 'TI': return 'Tarjeta de Identidad: Formato numérico para menores de edad (8 a 11 dígitos)';
                            case 'CE': return 'Cédula de Extranjería para residentes en Colombia';
                            case 'PPT': return 'Permiso por Protección Temporal emitido por Migración';
                            case 'PASSPORT': return 'Número oficial de pasaporte internacional';
                            default: return 'Documento válido de identificación';
                        }
                    },

                    goToStep2() {
                        if (!this.name.trim() || !this.lastName.trim() || !this.docNum.trim() || !this.email.trim() || !this.password) {
                            alert('Por favor completa todos los campos del Paso 1 antes de continuar a la ficha clínica.');
                            return;
                        }
                        if (this.password.length < 6) {
                            alert('La contraseña debe tener un mínimo de 6 caracteres.');
                            return;
                        }
                        this.step = 2;
                    }
                }"
                @submit="submitting = true"
            >
                @csrf

                <!-- Indicador de Pasos Clínicos -->
                <div class="flex items-center justify-between pb-4 border-b border-sicaps-border/70">
                    <button 
                        type="button" 
                        @click="step = 1"
                        class="flex items-center gap-2.5 text-left transition focus:outline-none"
                    >
                        <div 
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition"
                            :class="step === 1 ? 'bg-sicaps-primary text-white shadow-sm ring-2 ring-sicaps-primary/30' : 'bg-sicaps-primary/15 text-sicaps-primary'"
                        >
                            1
                        </div>
                        <div>
                            <span class="block text-xs font-bold tracking-tight text-sicaps-text">Datos de Cuenta</span>
                            <span class="block text-[10px] text-sicaps-muted">Identidad y credenciales</span>
                        </div>
                    </button>

                    <div class="flex-1 mx-4 h-0.5 bg-sicaps-border" :class="step === 2 ? 'bg-sicaps-primary' : 'bg-sicaps-border'"></div>

                    <button 
                        type="button" 
                        @click="goToStep2()"
                        class="flex items-center gap-2.5 text-left transition focus:outline-none"
                    >
                        <div 
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition"
                            :class="step === 2 ? 'bg-sicaps-primary text-white shadow-sm ring-2 ring-sicaps-primary/30' : 'bg-sicaps-border text-sicaps-muted'"
                        >
                            2
                        </div>
                        <div>
                            <span class="block text-xs font-bold tracking-tight text-sicaps-text">Ficha de Admisión</span>
                            <span class="block text-[10px] text-sicaps-muted">Salud y contacto</span>
                        </div>
                    </button>
                </div>

                <!-- PASO 1: DATOS PERSONALES Y ACCESO -->
                <div x-show="step === 1" class="space-y-4">
                    
                    <!-- Nombres y Apellidos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                                Nombres <span class="text-sicaps-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                required 
                                x-model="name"
                                value="{{ old('name') }}"
                                placeholder="Ej. Juan Andrés"
                                class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                                Apellidos <span class="text-sicaps-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="lastName" 
                                required 
                                x-model="lastName"
                                value="{{ old('lastName') }}"
                                placeholder="Ej. Pérez Gómez"
                                class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                        </div>
                    </div>

                    <!-- Tipo y Número de Documento -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                                Tipo Doc. <span class="text-sicaps-danger">*</span>
                            </label>
                            <select 
                                name="documentType" 
                                required 
                                x-model="docType"
                                class="w-full px-3 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                                <option value="CC">CC (Cédula de Ciudadanía)</option>
                                <option value="TI">TI (Tarjeta de Identidad)</option>
                                <option value="CE">CE (Cédula de Extranjería)</option>
                                <option value="PPT">PPT (Protección Temporal)</option>
                                <option value="PASSPORT">Pasaporte Internacional</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                                Número de Documento <span class="text-sicaps-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="documentNumber" 
                                required 
                                x-model="docNum"
                                inputmode="numeric"
                                value="{{ old('documentNumber') }}"
                                :placeholder="docPlaceholder"
                                class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition font-mono"
                            >
                            <p class="text-[10px] text-sicaps-muted mt-1" x-text="docHelper"></p>
                        </div>
                    </div>

                    <!-- Correo Electrónico -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Correo Electrónico <span class="text-sicaps-danger">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            required 
                            x-model="email"
                            value="{{ old('email') }}"
                            placeholder="paciente@ejemplo.com"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Contraseña de Acceso <span class="text-sicaps-danger">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPass ? 'text' : 'password'" 
                                name="password" 
                                required 
                                minlength="6"
                                x-model="password"
                                placeholder="Mínimo 6 caracteres"
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

                    <!-- Botón para avanzar al Paso 2 -->
                    <div class="pt-3">
                        <button 
                            type="button" 
                            @click="goToStep2()"
                            class="w-full py-3 px-4 bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.99] text-white font-semibold rounded-[10px] shadow-sm transition flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sicaps-primary"
                        >
                            <span>Continuar a Ficha de Admisión (Paso 2)</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </div>

                </div>

                <!-- PASO 2: FICHA DE ADMISIÓN CLÍNICA Y CONSENTIMIENTO -->
                <div x-show="step === 2" x-cloak class="space-y-4">
                    
                    <div class="p-3 bg-sicaps-sand-light/60 rounded-xl border border-sicaps-border text-xs text-sicaps-muted flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-sicaps-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                        <span>
                            Estos datos clínicos son necesarios para aperturar tu expediente bajo la <strong>Ley 1090 de 2006</strong> y contactar a tu acompañante ante cualquier eventualidad.
                        </span>
                    </div>

                    <!-- Fecha de Nacimiento y Género -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text">
                                    Fecha de Nacimiento
                                </label>
                                <template x-if="computedAge !== null">
                                    <span 
                                        class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                        :class="computedAge >= 18 ? 'bg-sicaps-success-soft text-sicaps-success' : 'bg-sicaps-warning-soft text-sicaps-warning'"
                                        x-text="computedAge + ' años • ' + (computedAge >= 18 ? 'Mayor' : 'Menor')"
                                    ></span>
                                </template>
                            </div>
                            <input 
                                type="date" 
                                name="birthDate" 
                                x-model="birthDate"
                                value="{{ old('birthDate') }}"
                                class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                                Sexo / Género
                            </label>
                            <select 
                                name="gender" 
                                x-model="gender"
                                class="w-full px-3 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                                <option value="">Seleccionar género...</option>
                                <option value="FEMENINO">Femenino</option>
                                <option value="MASCULINO">Masculino</option>
                                <option value="OTRO">Otro / Prefiero no decir</option>
                            </select>
                        </div>
                    </div>

                    <!-- Teléfono y EPS -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                                Teléfono Móvil
                            </label>
                            <input 
                                type="tel" 
                                name="phone" 
                                x-model="phone"
                                value="{{ old('phone') }}"
                                placeholder="Ej. 300 123 4567"
                                class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                                Aseguradora / EPS
                            </label>
                            <select 
                                name="eps" 
                                x-model="eps"
                                class="w-full px-3 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                                <option value="">Selecciona tu EPS o régimen...</option>
                                <option value="EPS SURA">EPS SURA</option>
                                <option value="Sanitas EPS">Sanitas EPS</option>
                                <option value="Compensar EPS">Compensar EPS</option>
                                <option value="Nueva EPS">Nueva EPS</option>
                                <option value="Salud Total EPS">Salud Total EPS</option>
                                <option value="Famisanar EPS">Famisanar EPS</option>
                                <option value="Savia Salud EPS">Savia Salud EPS</option>
                                <option value="Coosalud EPS">Coosalud EPS</option>
                                <option value="Mutual Ser EPS">Mutual Ser EPS</option>
                                <option value="Sanidad Militar / Policía">Sanidad Militar / Policía</option>
                                <option value="Particular / Prepagada">Particular / Medicina Prepagada</option>
                                <option value="Otra">Otra institución</option>
                            </select>
                        </div>
                    </div>

                    <!-- Contacto de Emergencia / Acompañante Obligatorio -->
                    <div class="p-3.5 bg-white rounded-xl border border-sicaps-border space-y-3">
                        <span class="block text-xs font-bold text-sicaps-text uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-sicaps-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                            Contacto de Emergencia / Acudiente
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-medium text-sicaps-muted mb-1">Nombre Completo</label>
                                <input 
                                    type="text" 
                                    name="emergencyContactName" 
                                    x-model="emergencyContactName"
                                    value="{{ old('emergencyContactName') }}"
                                    placeholder="Ej. Carmen Gómez"
                                    class="w-full px-3 py-2 bg-sicaps-bg/40 border border-sicaps-border rounded-lg text-xs text-sicaps-text focus:outline-none focus:ring-1 focus:ring-sicaps-primary"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-sicaps-muted mb-1">Parentesco</label>
                                <select 
                                    name="emergencyContactRelationship" 
                                    x-model="emergencyContactRelationship"
                                    class="w-full px-3 py-2 bg-sicaps-bg/40 border border-sicaps-border rounded-lg text-xs text-sicaps-text focus:outline-none focus:ring-1 focus:ring-sicaps-primary"
                                >
                                    <option value="">Seleccionar parentesco...</option>
                                    <option value="Madre">Madre</option>
                                    <option value="Padre">Padre</option>
                                    <option value="Pareja / Cónyuge">Pareja / Cónyuge</option>
                                    <option value="Hijo/a">Hijo/a</option>
                                    <option value="Hermano/a">Hermano/a</option>
                                    <option value="Familiar">Familiar cercano</option>
                                    <option value="Amigo/a">Amigo/a</option>
                                    <option value="Tutor Legal">Tutor Legal</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-sicaps-muted mb-1">Teléfono del Contacto de Emergencia</label>
                            <input 
                                type="tel" 
                                name="emergencyContactPhone" 
                                x-model="emergencyContactPhone"
                                value="{{ old('emergencyContactPhone') }}"
                                placeholder="Ej. 315 987 6543"
                                class="w-full px-3 py-2 bg-sicaps-bg/40 border border-sicaps-border rounded-lg text-xs text-sicaps-text focus:outline-none focus:ring-1 focus:ring-sicaps-primary"
                            >
                        </div>
                    </div>

                    <!-- Consentimiento Informado & Habeas Data -->
                    <div class="pt-2">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="informed_consent" 
                                required 
                                checked
                                class="w-4 h-4 mt-0.5 rounded text-sicaps-primary border-sicaps-border focus:ring-sicaps-primary"
                            >
                            <span class="text-xs text-sicaps-text leading-snug">
                                Declaro que mis datos son veraces y acepto el 
                                <button type="button" @click="showConsentModal = true" class="text-sicaps-primary font-bold hover:underline">
                                    Consentimiento Informado para Atención Psicológica
                                </button>
                                y custodia de historia clínica confidencial (Ley 1090 de 2006 y Ley 1581 de 2012).
                            </span>
                        </label>
                    </div>

                    <!-- Botones de Navegación del Paso 2 -->
                    <div class="pt-3 flex items-center gap-3">
                        <button 
                            type="button" 
                            @click="step = 1"
                            class="w-1/3 py-3 px-3 bg-white border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-muted font-medium rounded-[10px] text-xs sm:text-sm transition flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                            <span>Volver</span>
                        </button>

                        <button 
                            type="submit" 
                            :disabled="submitting"
                            class="w-2/3 py-3 px-4 bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.99] text-white font-semibold rounded-[10px] shadow-sm transition flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sicaps-primary disabled:opacity-75 disabled:cursor-not-allowed text-xs sm:text-sm"
                        >
                            <svg x-show="submitting" x-cloak class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submitting ? 'Procesando expediente...' : 'Completar Registro Clínico ✓'"></span>
                        </button>
                    </div>

                </div>

                <!-- MODAL: Consentimiento Informado Legal -->
                <div 
                    x-show="showConsentModal" 
                    x-cloak 
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                >
                    <div 
                        @click.away="showConsentModal = false"
                        class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-sicaps-border space-y-4 max-h-[85vh] overflow-y-auto"
                    >
                        <div class="flex items-center justify-between pb-3 border-b border-sicaps-border">
                            <h3 class="font-bold text-base text-sicaps-text flex items-center gap-2">
                                <span class="p-1.5 rounded-lg bg-sicaps-primary-soft text-sicaps-primary">Ψ</span>
                                Consentimiento Informado Psicológico
                            </h3>
                            <button type="button" @click="showConsentModal = false" class="text-sicaps-muted hover:text-sicaps-text text-lg font-bold">&times;</button>
                        </div>
                        <div class="text-xs text-sicaps-text space-y-2.5 leading-relaxed">
                            <p><strong>1. Secreto Profesional:</strong> Conforme al Código Deontológico y Bioético del Psicólogo (Ley 1090 de 2006 de la República de Colombia), toda la información verbal, escrita y clínica recopilada en consulta psicológica es de carácter estrictamente confidencial.</p>
                            <p><strong>2. Historia Clínica Digital:</strong> Sus notas de evolución médica y formato SOAP serán custodiadas en servidores clínicos protegidos, accesibles únicamente por el profesional asignado.</p>
                            <p><strong>3. Excepciones Éticas:</strong> El secreto profesional solo podrá levantarse ante orden judicial expresa o ante situaciones de riesgo inminente para la vida o integridad física del paciente o de terceras personas.</p>
                            <p><strong>4. Tratamiento de Datos (Ley 1581 de 2012):</strong> Autoriza el uso de su correo y teléfono para recordatorios de sesiones, confirmación de citas y seguimiento terapéutico.</p>
                        </div>
                        <div class="pt-2">
                            <button 
                                type="button" 
                                @click="showConsentModal = false"
                                class="w-full py-2 bg-sicaps-primary text-white text-xs font-semibold rounded-lg hover:bg-sicaps-primary-hover transition"
                            >
                                He leído y comprendido los términos
                            </button>
                        </div>
                    </div>
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
