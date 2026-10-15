@extends('layouts.app')

@section('title', 'Editar Paciente — SICAPS')
@section('header_title', 'Modificar Ficha de Paciente')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Enlace de retorno y encabezado -->
    <div class="flex items-center justify-between">
        <a 
            href="{{ route('patients.show', $patient['id'] ?? 0) }}" 
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-sicaps-muted hover:text-sicaps-text transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            <span>Volver al Expediente del Paciente</span>
        </a>

        <span class="inline-flex items-center gap-1.5 text-xs text-sicaps-muted">
            <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
            </svg>
            Expediente #{{ $patient['id'] ?? 0 }}
        </span>
    </div>

    <!-- Tarjeta del Formulario -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
        
        <div class="p-6 border-b border-sicaps-border bg-[#FAF9F6] flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-sicaps-text">Actualizar Datos de {{ $patient['user']['name'] ?? '' }} {{ $patient['user']['lastName'] ?? '' }}</h2>
                <p class="text-xs text-sicaps-muted mt-1">
                    Modifica los datos de contacto o información de emergencia del paciente.
                </p>
            </div>
            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-sicaps-sand-light text-sicaps-text border border-sicaps-border">
                {{ $patient['user']['documentType'] ?? 'CC' }}: {{ $patient['user']['documentNumber'] ?? '' }}
            </span>
        </div>

        <form action="{{ route('patients.update', $patient['id'] ?? 0) }}" method="POST" class="p-6 sm:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- SECCIÓN 1: IDENTIFICACIÓN Y CONTACTO -->
            <div>
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-sicaps-border">
                    <span class="w-6 h-6 rounded-full bg-sicaps-primary-soft text-sicaps-primary text-xs font-bold flex items-center justify-center">1</span>
                    <h3 class="text-sm font-bold text-sicaps-text uppercase tracking-wider">Identificación y Contacto</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Nombre
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ $patient['user']['name'] ?? '' }}" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-sicaps-sand-light/40 border border-sicaps-border rounded-[10px] text-sicaps-text text-sm cursor-not-allowed" 
                            readonly
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Apellido
                        </label>
                        <input 
                            type="text" 
                            name="lastName" 
                            value="{{ $patient['user']['lastName'] ?? '' }}" 
                            required 
                            class="w-full px-3.5 py-2.5 bg-sicaps-sand-light/40 border border-sicaps-border rounded-[10px] text-sicaps-text text-sm cursor-not-allowed" 
                            readonly
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
                            value="{{ old('email', $patient['user']['email'] ?? '') }}" 
                            required 
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
                            value="{{ old('phone', $patient['user']['phone'] ?? '') }}" 
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
                            value="{{ old('emergencyContactName', $patient['emergencyContactName'] ?? '') }}" 
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Teléfono del Contacto
                        </label>
                        <input 
                            type="text" 
                            name="emergencyContactPhone" 
                            value="{{ old('emergencyContactPhone', $patient['emergencyContactPhone'] ?? '') }}" 
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>
            </div>

            <!-- BOTONES -->
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
                    <span>Guardar Cambios</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
