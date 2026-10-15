@extends('layouts.app')

@section('title', 'Nuevo Paciente — SICAPS')
@section('header_title', 'Registrar Nuevo Paciente')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Enlace de retorno y encabezado -->
    <div class="flex items-center justify-between">
        <a 
            href="{{ route('patients.index') }}" 
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-sicaps-muted hover:text-sicaps-text transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            <span>Volver al Directorio de Pacientes</span>
        </a>

        <span class="inline-flex items-center gap-1.5 text-xs text-sicaps-muted">
            <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
            </svg>
            Confidencialidad médica asegurada
        </span>
    </div>

    <!-- Tarjeta del Formulario -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
        
        <div class="p-6 border-b border-sicaps-border bg-[#FAF9F6]">
            <h2 class="text-xl font-bold text-sicaps-text">Ficha de Admisión de Paciente</h2>
            <p class="text-xs text-sicaps-muted mt-1">
                Ingresa los datos personales y de salud requeridos para la apertura de la historia clínica psicológica.
            </p>
        </div>

        <form action="{{ route('patients.store') }}" method="POST" class="p-6 sm:p-8 space-y-8">
            @csrf

            <!-- SECCIÓN 1: DATOS PERSONALES -->
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-sicaps-border">
                    <span class="w-6 h-6 rounded-full bg-sicaps-primary-soft text-sicaps-primary text-xs font-bold flex items-center justify-center">1</span>
                    <h3 class="text-sm font-bold text-sicaps-text uppercase tracking-wider">Identificación y Contacto</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Nombre <span class="text-sicaps-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            required 
                            value="{{ old('name') }}"
                            placeholder="Ej. Sofía"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Apellido <span class="text-sicaps-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="lastName" 
                            required 
                            value="{{ old('lastName') }}"
                            placeholder="Ej. Gómez Valderrama"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Tipo Documento <span class="text-sicaps-danger">*</span>
                        </label>
                        <select 
                            name="documentType" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                            <option value="CC" {{ old('documentType') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía (CC)</option>
                            <option value="TI" {{ old('documentType') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad (TI)</option>
                            <option value="CE" {{ old('documentType') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería (CE)</option>
                            <option value="PAS" {{ old('documentType') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
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
                            value="{{ old('documentNumber') }}"
                            placeholder="Ej. 1098765432"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Correo Electrónico <span class="text-sicaps-danger">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            required 
                            value="{{ old('email') }}"
                            placeholder="paciente@correo.com"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Teléfono Móvil
                        </label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            placeholder="+57 300 123 4567"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: CONTACTO DE EMERGENCIA -->
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-sicaps-border">
                    <span class="w-6 h-6 rounded-full bg-sicaps-sand text-sicaps-text text-xs font-bold flex items-center justify-center">2</span>
                    <h3 class="text-sm font-bold text-sicaps-text uppercase tracking-wider">Contacto de Emergencia</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Nombre del Contacto
                        </label>
                        <input 
                            type="text" 
                            name="emergencyContactName" 
                            value="{{ old('emergencyContactName') }}"
                            placeholder="Ej. Roberto Gómez (Padre/Pareja)"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Teléfono de Emergencia
                        </label>
                        <input 
                            type="text" 
                            name="emergencyContactPhone" 
                            value="{{ old('emergencyContactPhone') }}"
                            placeholder="+57 311 987 6543"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: ANTECEDENTES PREVIOS -->
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-sicaps-border">
                    <span class="w-6 h-6 rounded-full bg-sicaps-primary-soft text-sicaps-primary text-xs font-bold flex items-center justify-center">3</span>
                    <h3 class="text-sm font-bold text-sicaps-text uppercase tracking-wider">Antecedentes Clínicos Iniciales</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Antecedentes Médicos Relevantes
                        </label>
                        <textarea 
                            name="medicalBackground" 
                            rows="2" 
                            placeholder="Enfermedades crónicas, tratamientos farmacológicos actuales, alergias..."
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >{{ old('medicalBackground') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Antecedentes Psicológicos / Motivo Inicial
                        </label>
                        <textarea 
                            name="psychologicalBackground" 
                            rows="2" 
                            placeholder="Procesos psicoterapéuticos anteriores, hospitalizaciones, motivos de consulta previos..."
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >{{ old('psychologicalBackground') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="pt-4 border-t border-sicaps-border flex items-center justify-end gap-3">
                <a 
                    href="{{ route('patients.index') }}" 
                    class="px-5 py-2.5 rounded-[10px] border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-text font-medium text-sm transition"
                >
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-[10px] bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.98] text-white font-medium text-sm shadow-sm transition flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                    <span>Guardar Paciente</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
