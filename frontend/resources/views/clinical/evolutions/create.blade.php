@extends('layouts.app')

@section('title', 'Nueva Evolución - SICAPS Web')
@section('header_title', 'Registrar Nueva Evolución Clínica')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-3xl mx-auto">
    <div class="mb-6 pb-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Paciente: {{ $patient['user']['name'] ?? '' }} {{ $patient['user']['lastName'] ?? '' }}</h3>
        <p class="text-sm text-gray-500">Documento: {{ $patient['user']['documentNumber'] ?? '' }}</p>
    </div>

    <form action="{{ route('clinical.evolutions.store', $patient['id']) }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de Consulta Actual</label>
            <input type="text" name="reason" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Motivo principal de la consulta...">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción / Notas de Evolución</label>
            <textarea name="description" rows="5" required class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Escriba aquí los detalles de la evolución del paciente..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico (Opcional)</label>
            <input type="text" name="diagnosis" class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="CIE-10 o descripción...">
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('patients.show', $patient['id']) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 font-medium">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium">Guardar Evolución</button>
        </div>
    </form>
</div>
@endsection
