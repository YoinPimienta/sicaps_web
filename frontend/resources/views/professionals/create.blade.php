@extends('layouts.app')

@section('title', 'Habilitar Nuevo Profesional — SICAPS')

@section('header_title')
    <div class="flex items-center gap-3">
        <a href="{{ route('professionals.index') }}" class="p-2 rounded-xl border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-muted hover:text-sicaps-text transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-sicaps-text tracking-tight flex items-center gap-2">
                <span>Habilitación y Registro de Profesional</span>
                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-sicaps-primary/10 text-sicaps-primary">Admin</span>
            </h2>
            <p class="text-xs text-sicaps-muted mt-0.5">
                Alta institucional de especialista en psicología, validación de tarjeta profesional y emisión de credenciales.
            </p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    name: '{{ old('name', '') }}',
    lastName: '{{ old('lastName', '') }}',
    docType: '{{ old('documentType', 'CC') }}',
    docNum: '{{ old('documentNumber', '') }}',
    specialty: '{{ old('specialty', 'Psicología Clínica y de la Salud') }}',
    customSpecialty: '',
    regNumber: '{{ old('medicalRegistrationNumber', '') }}',
    email: '{{ old('email', '') }}',
    phone: '{{ old('phone', '') }}',
    password: '',
    showPass: false,
    generateSecurePassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
        let pass = '';
        for (let i = 0; i < 10; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        this.password = pass;
    },
    get initials() {
        const n = this.name.trim();
        const l = this.lastName.trim();
        return ((n ? n[0] : 'D') + (l ? l[0] : 'R')).toUpperCase();
    }
}">

    <!-- ALERTAS DE ERROR -->
    @if ($errors->any())
        <div class="mb-6 bg-sicaps-danger-soft border border-sicaps-danger/30 rounded-2xl p-4 flex items-start gap-3">
            <div class="p-1 rounded-full bg-sicaps-danger text-white flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-sm text-sicaps-danger">Por favor verifica los campos requeridos:</p>
                <ul class="list-disc list-inside text-xs text-sicaps-text mt-1 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- COLUMNA PRINCIPAL: FORMULARIO LEGAL Y CLÍNICO (8 COLUMNAS) -->
        <div class="lg:col-span-8 bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
            
            <div class="p-6 border-b border-sicaps-border/70 bg-gradient-to-r from-sicaps-bg/60 to-white">
                <span class="text-[11px] font-bold uppercase tracking-wider text-sicaps-primary flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    Ficha de Acreditación Sanitaria y Deontológica
                </span>
                <h3 class="text-lg font-bold text-sicaps-text mt-1">Datos del Especialista para Habilitación</h3>
                <p class="text-xs text-sicaps-muted mt-0.5">
                    El registro creará el expediente profesional y el usuario de acceso para redactar historias clínicas y notas SOAP.
                </p>
            </div>

            <form action="{{ route('professionals.store') }}" method="POST" class="p-6 sm:p-8 space-y-8">
                @csrf

                <!-- SECCIÓN 1: ACREDITACIÓN PROFESIONAL Y LEGAL (RETHUS / COLEGIO) -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-sicaps-border/60">
                        <span class="w-6 h-6 rounded-lg bg-sicaps-primary text-white font-bold text-xs flex items-center justify-center">1</span>
                        <h4 class="text-sm font-bold text-sicaps-text uppercase tracking-wide">Acreditación Legal en Salud</h4>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <!-- Tarjeta Profesional / Registro ReTHUS -->
                        <div>
                            <label for="medicalRegistrationNumber" class="block text-xs font-semibold text-sicaps-text mb-1.5 flex items-center justify-between">
                                <span>Tarjeta Profesional / ReTHUS <span class="text-sicaps-danger">*</span></span>
                                <span class="text-[10px] text-sicaps-muted font-normal">Obligatorio por ley</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="medicalRegistrationNumber" 
                                    name="medicalRegistrationNumber" 
                                    required 
                                    x-model="regNumber"
                                    placeholder="Ej: TP-142857 / Colpsic" 
                                    value="{{ old('medicalRegistrationNumber') }}"
                                    class="w-full pl-3.5 pr-4 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text font-mono placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                            </div>
                            <p class="text-[10px] text-sicaps-muted mt-1 leading-tight">
                                Registro del Colegio Profesional o Ministerio de Salud necesario para suscribir actos clínicos.
                            </p>
                        </div>

                        <!-- Especialidad / Enfoque Clínico -->
                        <div>
                            <label for="specialty" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                Especialidad o Enfoque Clínico <span class="text-sicaps-danger">*</span>
                            </label>
                            <select 
                                id="specialty" 
                                name="specialty" 
                                required
                                x-model="specialty"
                                class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                                <option value="Psicología Clínica y de la Salud">Psicología Clínica y de la Salud</option>
                                <option value="Neuropsicología Clínica">Neuropsicología Clínica</option>
                                <option value="Psicología Infantil y de la Adolescencia">Psicología Infantil y de la Adolescencia</option>
                                <option value="Terapia Cognitivo-Conductual (TCC)">Terapia Cognitivo-Conductual (TCC)</option>
                                <option value="Terapia Familiar y de Pareja">Terapia Familiar y de Pareja</option>
                                <option value="Psicología Forense y Peritaje">Psicología Forense y Peritaje</option>
                                <option value="Psicoterapia Humanista e Integrativa">Psicoterapia Humanista e Integrativa</option>
                                <option value="Psicología General y Evaluación">Psicología General y Evaluación</option>
                            </select>
                            <p class="text-[10px] text-sicaps-muted mt-1">
                                Área principal de consulta y asignación de casos clínicos.
                            </p>
                        </div>

                    </div>
                </div>

                <!-- SECCIÓN 2: DATOS PERSONALES Y CIUDADANOS -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-sicaps-border/60">
                        <span class="w-6 h-6 rounded-lg bg-sicaps-primary text-white font-bold text-xs flex items-center justify-center">2</span>
                        <h4 class="text-sm font-bold text-sicaps-text uppercase tracking-wide">Identificación y Datos Personales</h4>
                    </div>

                    <div class="space-y-4">
                        
                        <!-- Nombres y Apellidos -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Nombres Completos <span class="text-sicaps-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    required 
                                    x-model="name"
                                    placeholder="Ej: Laura Sofía" 
                                    value="{{ old('name') }}"
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                            </div>

                            <div>
                                <label for="lastName" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Apellidos <span class="text-sicaps-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="lastName" 
                                    name="lastName" 
                                    required 
                                    x-model="lastName"
                                    placeholder="Ej: Restrepo Mendoza" 
                                    value="{{ old('lastName') }}"
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                            </div>
                        </div>

                        <!-- Documento de Identidad -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="documentType" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Tipo Documento <span class="text-sicaps-danger">*</span>
                                </label>
                                <select 
                                    id="documentType" 
                                    name="documentType" 
                                    required
                                    x-model="docType"
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                                    <option value="CC">Cédula de Ciudadanía (CC)</option>
                                    <option value="CE">Cédula de Extranjería (CE)</option>
                                    <option value="PASSPORT">Pasaporte</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="documentNumber" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Número de Identificación <span class="text-sicaps-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="documentNumber" 
                                    name="documentNumber" 
                                    required 
                                    x-model="docNum"
                                    placeholder="Número de documento sin puntos" 
                                    value="{{ old('documentNumber') }}"
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento y Género -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="birthDate" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Fecha de Nacimiento
                                </label>
                                <input 
                                    type="date" 
                                    id="birthDate" 
                                    name="birthDate" 
                                    value="{{ old('birthDate') }}"
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                            </div>

                            <div>
                                <label for="gender" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Género
                                </label>
                                <select 
                                    id="gender" 
                                    name="gender" 
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                                    <option value="">Seleccionar género</option>
                                    <option value="FEMENINO" {{ old('gender') === 'FEMENINO' ? 'selected' : '' }}>Femenino</option>
                                    <option value="MASCULINO" {{ old('gender') === 'MASCULINO' ? 'selected' : '' }}>Masculino</option>
                                    <option value="OTRO" {{ old('gender') === 'OTRO' ? 'selected' : '' }}>Otro / No binario</option>
                                </select>
                            </div>
                        </div>

                        <!-- Teléfono y Dirección -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Teléfono / Móvil Profesional
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    x-model="phone"
                                    placeholder="Ej: +57 300 123 4567" 
                                    value="{{ old('phone') }}"
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                    Dirección / Consultorio Asignado
                                </label>
                                <input 
                                    type="text" 
                                    id="address" 
                                    name="address" 
                                    placeholder="Ej: Consultorio 302, Sede Principal" 
                                    value="{{ old('address') }}"
                                    class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SECCIÓN 3: ACCESO AL SISTEMA Y CREDENCIALES INSTITUCIONALES -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-sicaps-border/60">
                        <span class="w-6 h-6 rounded-lg bg-sicaps-primary text-white font-bold text-xs flex items-center justify-center">3</span>
                        <h4 class="text-sm font-bold text-sicaps-text uppercase tracking-wide">Credenciales de Inicio de Sesión</h4>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Correo Electrónico Institucional -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-sicaps-text mb-1.5">
                                Correo Electrónico Institucional <span class="text-sicaps-danger">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required 
                                x-model="email"
                                placeholder="profesional@sicaps.com" 
                                value="{{ old('email') }}"
                                class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                            >
                            <p class="text-[10px] text-sicaps-muted mt-1">
                                Servirá como usuario único para acceder al SICAPS.
                            </p>
                        </div>

                        <!-- Contraseña de Acceso -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-semibold text-sicaps-text">
                                    Contraseña Inicial <span class="text-sicaps-danger">*</span>
                                </label>
                                <button 
                                    type="button" 
                                    @click="generateSecurePassword()" 
                                    class="text-[11px] font-semibold text-sicaps-primary hover:text-sicaps-primary-hover hover:underline"
                                >
                                    Generar aleatoria
                                </button>
                            </div>
                            <div class="relative">
                                <input 
                                    :type="showPass ? 'text' : 'password'" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    x-model="password"
                                    placeholder="Mínimo 6 caracteres" 
                                    class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-sicaps-border rounded-xl text-xs sm:text-sm text-sicaps-text placeholder-sicaps-muted/60 focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                                >
                                <button 
                                    type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sicaps-muted hover:text-sicaps-text"
                                    tabindex="-1"
                                >
                                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                    <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SECCIÓN 4: DECLARACIÓN LEGAL Y DEONTOLÓGICA (OBLIGATORIA) -->
                <div class="p-4 rounded-xl bg-sicaps-sand-light/60 border border-sicaps-border space-y-3">
                    <span class="text-xs font-bold text-sicaps-text block uppercase tracking-wider">
                        Verificación Deontológica de Habilitación Sanitaria
                    </span>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="legal_confirmation" 
                            required 
                            value="1" 
                            class="mt-1 h-4 w-4 rounded border-sicaps-border text-sicaps-primary focus:ring-sicaps-primary"
                        >
                        <span class="text-xs text-sicaps-muted leading-relaxed">
                            Certifico como Administrador que he verificado el título profesional y la tarjeta profesional / ReTHUS vigente del especialista, y que no registra sanciones disciplinarias activas ante el Tribunal Deontológico de Psicología para el ejercicio clínico.
                        </span>
                    </label>
                </div>

                <!-- BOTONES DE ACCIÓN -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-sicaps-border/70">
                    <a 
                        href="{{ route('professionals.index') }}" 
                        class="py-2.5 px-4 bg-white border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-muted hover:text-sicaps-text text-xs font-semibold rounded-xl transition"
                    >
                        Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="py-2.5 px-6 bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.99] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span>Habilitar y Registrar Profesional</span>
                    </button>
                </div>

            </form>

        </div>

        <!-- COLUMNA LATERAL: VISTA PREVIA EN TIEMPO REAL DEL CARNÉ DIGITAL (4 COLUMNAS) -->
        <div class="lg:col-span-4 space-y-5">
            
            <div class="bg-white rounded-card p-5 border border-sicaps-border shadow-soft">
                <span class="text-[11px] font-bold uppercase tracking-wider text-sicaps-muted block mb-3">
                    Carné Digital de Habilitación
                </span>

                <!-- Tarjeta / Carné Visual del Profesional -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-white via-sicaps-sand-light/40 to-sicaps-primary-soft/30 border border-sicaps-border/80 shadow-soft relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full bg-sicaps-primary/10 blur-xl pointer-events-none"></div>

                    <!-- Header carné con isotipo SICAPS -->
                    <div class="flex items-center justify-between pb-3 border-b border-sicaps-border/60">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-md bg-sicaps-primary text-white flex items-center justify-center text-[10px] font-bold">S</div>
                            <span class="text-xs font-bold text-sicaps-text">SICAPS</span>
                        </div>
                        <span class="text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full bg-sicaps-success-soft text-sicaps-success">
                            Habilitado
                        </span>
                    </div>

                    <!-- Avatar e Información -->
                    <div class="mt-4 flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-sicaps-primary text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-md">
                            <span x-text="initials"></span>
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-sm font-bold text-sicaps-text truncate" x-text="(name || lastName) ? `${name} ${lastName}` : 'Nombre del Profesional'"></h5>
                            <p class="text-xs text-sicaps-primary font-medium truncate" x-text="specialty"></p>
                        </div>
                    </div>

                    <!-- Datos Legales del carné -->
                    <div class="mt-4 pt-3 border-t border-sicaps-border/50 space-y-1.5 text-[11px]">
                        <div class="flex justify-between">
                            <span class="text-sicaps-muted">Registro / ReTHUS:</span>
                            <span class="font-mono font-semibold text-sicaps-text" x-text="regNumber || 'TP-000000'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sicaps-muted">Documento:</span>
                            <span class="font-semibold text-sicaps-text" x-text="`${docType}: ${docNum || '—'}`"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sicaps-muted">Acceso:</span>
                            <span class="text-sicaps-text truncate max-w-[150px]" x-text="email || 'correo@sicaps.com'"></span>
                        </div>
                    </div>

                    <!-- Código de seguridad simulado -->
                    <div class="mt-4 pt-3 border-t border-dashed border-sicaps-border/80 flex items-center justify-between text-[10px] text-sicaps-muted">
                        <span>SICAPS HEALTH ID</span>
                        <span class="font-mono">VERIFICADO</span>
                    </div>
                </div>

                <p class="text-[11px] text-sicaps-muted text-center mt-3 leading-relaxed">
                    Al confirmar, el profesional podrá ingresar de inmediato a su agenda personal e historias clínicas.
                </p>
            </div>

            <!-- GUÍA LEGAL Y DEONTOLÓGICA -->
            <div class="bg-sicaps-sand-light/40 rounded-card p-5 border border-sicaps-border/70 text-xs text-sicaps-muted space-y-2.5">
                <h5 class="font-bold text-sicaps-text flex items-center gap-1.5 text-xs">
                    <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                    Requisitos para el Ejercicio Clínico
                </h5>
                <p class="leading-relaxed">
                    1. <strong>Título Universitario:</strong> Psicólogo graduado de institución legalmente reconocida.
                </p>
                <p class="leading-relaxed">
                    2. <strong>Tarjeta Profesional:</strong> Expedida por el Colegio Profesional acreditado.
                </p>
                <p class="leading-relaxed">
                    3. <strong>Custodia de Historia Clínica:</strong> Las notas de evolución SOAP y diagnósticos emitidos quedan bajo secreto profesional y reserva legal.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
