@extends('layouts.app')

@section('title', 'Nueva Evolución Clínica — SICAPS')
@section('header_title', 'Registrar Nota de Evolución')

@section('content')
@php
    $pName = ($patient['user']['name'] ?? '') . ' ' . ($patient['user']['lastName'] ?? '');
    $initials = strtoupper(substr($patient['user']['name'] ?? 'P', 0, 1) . substr($patient['user']['lastName'] ?? 'P', 0, 1));
    $docType = $patient['user']['documentType'] ?? 'CC';
    $docNum = $patient['user']['documentNumber'] ?? '';
@endphp

<div class="max-w-5xl mx-auto space-y-6">
    
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
            Documento médico-legal confidencial
        </span>
    </div>

    <!-- TARJETA CONTEXTUAL DEL PACIENTE -->
    <div class="bg-white rounded-card border border-sicaps-border shadow-soft p-5 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sicaps-primary-soft text-sicaps-primary font-bold text-base flex items-center justify-center border border-sicaps-primary/20">
                {{ $initials }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-sicaps-text">{{ $pName }}</h2>
                    <span class="text-xs px-2 py-0.5 rounded bg-sicaps-sand-light text-sicaps-text border border-sicaps-border">
                        {{ $docType }}: {{ $docNum }}
                    </span>
                </div>
                <span class="text-xs text-sicaps-muted">Apertura de nueva nota de evolución psicoterapéutica</span>
            </div>
        </div>

        <span class="text-xs font-semibold text-sicaps-primary bg-sicaps-primary-soft px-3 py-1 rounded-full hidden sm:inline-block">
            Sesión en Curso
        </span>
    </div>

    <!-- FORMULARIO PRINCIPAL (7 cols) + GUÍA SOAP (5 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- FORMULARIO (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
            
            <div class="p-6 border-b border-sicaps-border bg-[#FAF9F6]">
                <h3 class="text-lg font-bold text-sicaps-text">Registro de Evolución Psicológica</h3>
                <p class="text-xs text-sicaps-muted mt-1">
                    Documenta los aspectos clínicos observados y las intervenciones aplicadas en la sesión.
                </p>
            </div>

            <form action="{{ route('clinical.evolutions.store', $patient['id']) }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Motivo de la sesión -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Motivo de la Sesión / Encuadre Terapéutico <span class="text-sicaps-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="reason" 
                        required 
                        value="{{ old('reason') }}"
                        placeholder="Ej. Sesión #5: Reestructuración Cognitiva y Manejo de Rumiación"
                        class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                    >
                </div>

                <!-- Notas de Evolución Clínicas (Estructura SOAP) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text">
                            Notas de Evolución (Formato SOAP) <span class="text-sicaps-danger">*</span>
                        </label>
                        <span class="text-[11px] text-sicaps-muted">Subjetivo, Objetivo, Análisis, Plan</span>
                    </div>
                    <textarea 
                        name="description" 
                        rows="8" 
                        required 
                        placeholder="S (Subjetivo): El paciente refiere menor frecuencia de episodios de angustia...&#10;O (Objetivo): Contacto visual adecuado, discurso coherente y afecto eutímico...&#10;A (Análisis): Buena adherencia a las técnicas de respiración diafragmática...&#10;P (Plan): Continuar autorregistro de pensamientos distorsionados..."
                        class="w-full px-3.5 py-3 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/50 text-sm font-sans focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition leading-relaxed"
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Diagnóstico / Hipótesis / CIE-10 -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Diagnóstico / CIE-10 / Hipótesis Terapéutica (Opcional)
                    </label>
                    <input 
                        type="text" 
                        name="diagnosis" 
                        value="{{ old('diagnosis') }}"
                        placeholder="Ej. F41.1 Trastorno de Ansiedad Generalizada / F32.0 Episodio Depresivo Leve"
                        class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                    >
                </div>

                <!-- Botones -->
                <div class="pt-4 border-t border-sicaps-border flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('patients.show', $patient['id'] ?? 0) }}" 
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
                        <span>Guardar y Firmar Evolución</span>
                    </button>
                </div>

            </form>

        </div>

        <!-- GUÍA CLÍNICA SOAP Y PRIVACIDAD (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- GUÍA DEL FORMATO SOAP -->
            <div class="bg-white rounded-card p-6 border border-sicaps-border shadow-soft">
                <div class="flex items-center gap-2.5 pb-3 mb-4 border-b border-sicaps-border">
                    <div class="w-8 h-8 rounded-lg bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center font-bold text-xs">
                        SOAP
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-sicaps-text">Estructura Clínica Sugerida</h4>
                        <span class="text-[11px] text-sicaps-muted">Estándar internacional de salud mental</span>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="font-bold text-sicaps-primary block">S — Subjetivo</span>
                        <p class="text-sicaps-muted mt-0.5 leading-relaxed">
                            Narrativa del paciente: cómo se sintió desde la última sesión, motivos que refiere y estado de ánimo percibido subjetivamente.
                        </p>
                    </div>

                    <div>
                        <span class="font-bold text-sicaps-primary block">O — Objetivo</span>
                        <p class="text-sicaps-muted mt-0.5 leading-relaxed">
                            Observación clínica del psicólogo: lenguaje corporal, contacto visual, higiene, coherencia verbal, afecto congruente o aplanado.
                        </p>
                    </div>

                    <div>
                        <span class="font-bold text-sicaps-primary block">A — Análisis / Evaluación</span>
                        <p class="text-sicaps-muted mt-0.5 leading-relaxed">
                            Conceptualización psicológica del progreso, respuesta a las técnicas aplicadas y dinámica conductual evidenciada.
                        </p>
                    </div>

                    <div>
                        <span class="font-bold text-sicaps-primary block">P — Plan Terapéutico</span>
                        <p class="text-sicaps-muted mt-0.5 leading-relaxed">
                            Tareas intersesión (autorregistros, lecturas, exposición), acuerdos pactados y fecha de la próxima sesión de control.
                        </p>
                    </div>
                </div>
            </div>

            <!-- VALOR ÉTICO Y LEGAL -->
            <div class="bg-[#FAF9F6] rounded-card p-5 border border-sicaps-border text-xs text-sicaps-muted">
                <div class="flex items-center gap-2 text-sicaps-text font-bold mb-1.5">
                    <svg class="w-4 h-4 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                    <span>Firma y Trazabilidad Médica</span>
                </div>
                <p class="leading-relaxed text-[11px]">
                    Cada nota queda sellada con tu usuario, fecha y hora de registro, garantizando la inmutabilidad de la historia clínica según las regulaciones sanitarias de salud mental.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
