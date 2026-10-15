@extends('layouts.app')

@section('title', 'Agendar Cita - SICAPS Web')
@section('header_title', 'Agendar Nueva Cita')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form action="{{ route('appointments.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Paciente</label>
                <select name="patientId" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Seleccione un paciente...</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient['id'] }}">{{ $patient['user']['name'] ?? '' }} {{ $patient['user']['lastName'] ?? '' }} ({{ $patient['user']['documentNumber'] ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Profesional / Especialista</label>
                <select name="professionalId" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Seleccione un profesional...</option>
                    @foreach($professionals as $prof)
                        <option value="{{ $prof['id'] }}">{{ $prof['user']['name'] }} {{ $prof['user']['lastName'] }} ({{ $prof['specialty'] }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Hora</label>
                <input type="time" name="time" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Motivo de Consulta</label>
            <input type="text" name="reason" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ej. Control, Ansiedad, Primera vez">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Observaciones (Opcional)</label>
            <textarea name="observations" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
        </div>

        <div class="pt-4 flex justify-end space-x-2">
            <a href="{{ route('appointments.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Confirmar Cita</button>
        </div>
    </form>
</div>
@endsection
