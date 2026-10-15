@extends('layouts.app')

@section('title', 'Agendar Cita — SICAPS')
@section('header_title', 'Agendar Cita Psicológica')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{ 
    selectedTime: '{{ old('time', '09:00') }}', 
    selectedDate: '{{ old('date', date('Y-m-d')) }}',
    modality: 'Presencial',
    reasonText: '{{ old('reason', '') }}',
    setTime(t) { this.selectedTime = t; }
}">
    
    <!-- Enlace de retorno y encabezado -->
    <div class="flex items-center justify-between">
        <a 
            href="{{ route('appointments.index') }}" 
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-sicaps-muted hover:text-sicaps-text transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            <span>Volver a la Agenda</span>
        </a>

        <span class="inline-flex items-center gap-1.5 text-xs text-sicaps-muted">
            <svg class="w-3.5 h-3.5 text-sicaps-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            Bloque estándar de sesión: 45 minutos
        </span>
    </div>

    <!-- CUADRÍCULA: FORMULARIO (7 cols) + RESUMEN PREVIO (5 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- COLUMNA PRINCIPAL: FORMULARIO (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-card border border-sicaps-border shadow-soft overflow-hidden">
            
            <div class="p-6 border-b border-sicaps-border bg-[#FAF9F6]">
                <h2 class="text-xl font-bold text-sicaps-text">Detalles de la Cita</h2>
                <p class="text-xs text-sicaps-muted mt-1">
                    Selecciona el paciente, especialista y horario para la sesión.
                </p>
            </div>

            <form action="{{ route('appointments.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- 1. Paciente -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Paciente <span class="text-sicaps-danger">*</span>
                    </label>
                    <select 
                        name="patientId" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                    >
                        <option value="">Seleccione un paciente de la lista...</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}" {{ old('patientId') == $patient['id'] ? 'selected' : '' }}>
                                {{ $patient['user']['name'] ?? '' }} {{ $patient['user']['lastName'] ?? '' }} — {{ $patient['user']['documentType'] ?? 'CC' }}: {{ $patient['user']['documentNumber'] ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Profesional -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Profesional / Especialista Asignado <span class="text-sicaps-danger">*</span>
                    </label>
                    <select 
                        name="professionalId" 
                        required 
                        class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                    >
                        @if(empty($professionals) || count($professionals) === 0)
                            <option value="1">Dra. Camila Santos (Psicología Clínica y TCC)</option>
                        @else
                            <option value="">Seleccione un especialista...</option>
                            @foreach($professionals as $prof)
                                <option value="{{ $prof['id'] }}" {{ old('professionalId') == $prof['id'] ? 'selected' : '' }}>
                                    {{ $prof['user']['name'] ?? 'Dr.' }} {{ $prof['user']['lastName'] ?? '' }} ({{ $prof['specialty'] ?? 'Psicología' }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- 3. Fecha y Hora -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Fecha de Atención <span class="text-sicaps-danger">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="date" 
                            required 
                            min="{{ date('Y-m-d') }}"
                            x-model="selectedDate"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                            Hora de Inicio <span class="text-sicaps-danger">*</span>
                        </label>
                        <input 
                            type="time" 
                            name="time" 
                            required 
                            x-model="selectedTime"
                            class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                        >
                    </div>
                </div>

                <!-- Franjas horarias sugeridas (Chips interactivos) -->
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-sicaps-muted mb-2">
                        Franjas Horarias Disponibles
                    </label>
                    <div class="flex flex-wrap gap-2 text-xs">
                        @foreach(['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'] as $slot)
                            <button 
                                type="button" 
                                @click="setTime('{{ $slot }}')" 
                                :class="selectedTime === '{{ $slot }}' ? 'bg-sicaps-primary text-white border-sicaps-primary shadow-sm' : 'bg-sicaps-bg hover:bg-sicaps-sand-light text-sicaps-text border-sicaps-border'"
                                class="px-3 py-1.5 rounded-lg border font-medium transition"
                            >
                                {{ $slot }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Motivo de Consulta -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Motivo de Consulta <span class="text-sicaps-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="reason" 
                        required 
                        x-model="reasonText"
                        placeholder="Ej. Control de Ansiedad, Evaluación Inicial, Terapia de Pareja..."
                        class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                    >
                </div>

                <!-- 5. Observaciones adicionales -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-sicaps-text mb-1.5">
                        Observaciones Clínicas / Modalidad (Opcional)
                    </label>
                    <textarea 
                        name="observations" 
                        rows="2" 
                        placeholder="Indica si es modalidad presencial (Consultorio) o virtual (Teleconsulta), o notas previas..."
                        class="w-full px-3.5 py-2.5 bg-white border border-sicaps-border rounded-[10px] text-sicaps-text placeholder-sicaps-muted/60 text-sm focus:outline-none focus:ring-2 focus:ring-sicaps-primary/30 focus:border-sicaps-primary transition"
                    >{{ old('observations') }}</textarea>
                </div>

                <!-- Botones -->
                <div class="pt-4 border-t border-sicaps-border flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('appointments.index') }}" 
                        class="px-5 py-2.5 rounded-[10px] border border-sicaps-border hover:bg-sicaps-sand-light text-sicaps-text font-medium text-sm transition"
                    >
                        Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-[10px] bg-sicaps-primary hover:bg-sicaps-primary-hover active:scale-[0.98] text-white font-medium text-sm shadow-sm transition flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/>
                        </svg>
                        <span>Confirmar y Agendar Cita</span>
                    </button>
                </div>

            </form>

        </div>

        <!-- COLUMNA LATERAL: RESUMEN Y RECOMENDACIÓN (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- TARJETA DE RESUMEN PREVIO -->
            <div class="bg-white rounded-card p-6 border border-sicaps-border shadow-soft">
                <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-sicaps-border">
                    <div class="w-8 h-8 rounded-lg bg-sicaps-primary-soft text-sicaps-primary flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-sicaps-text">Resumen de la Sesión</h3>
                        <span class="text-[11px] text-sicaps-muted">Vista previa de la reserva</span>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div class="flex items-center justify-between py-1.5 border-b border-sicaps-border/50">
                        <span class="text-sicaps-muted font-medium">Fecha:</span>
                        <span class="font-bold text-sicaps-text" x-text="selectedDate"></span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-b border-sicaps-border/50">
                        <span class="text-sicaps-muted font-medium">Horario:</span>
                        <span class="font-bold text-sicaps-primary" x-text="selectedTime + ' (45 minutos)'"></span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-b border-sicaps-border/50">
                        <span class="text-sicaps-muted font-medium">Motivo:</span>
                        <span class="font-bold text-sicaps-text truncate max-w-[180px]" x-text="reasonText || 'Por definir'"></span>
                    </div>

                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-sicaps-muted font-medium">Estado inicial:</span>
                        <span class="px-2 py-0.5 rounded-full font-semibold bg-sicaps-primary-soft text-sicaps-primary">
                            Programada
                        </span>
                    </div>
                </div>
            </div>

            <!-- NOTA DE BUENAS PRÁCTICAS CLÍNICAS -->
            <div class="bg-gradient-to-br from-sicaps-primary-soft/40 to-[#E9F3F1] rounded-card p-5 border border-sicaps-primary/20">
                <div class="flex items-start gap-3">
                    <div class="p-1 rounded-full bg-sicaps-primary text-white flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-sicaps-text">Recomendación Terapéutica</h4>
                        <p class="text-[11px] text-sicaps-muted mt-1 leading-relaxed">
                            Procura mantener un intervalo de 10 a 15 minutos entre pacientes para revisar notas previas, asentar la evolución SOAP en el sistema y asegurar un ambiente de calma en la consulta.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
