@extends('layouts.app')

@section('title', 'Historias Clínicas - SICAPS Web')
@section('header_title', 'Historias Clínicas')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-medium text-gray-800">Buscador de Historias Clínicas</h3>
        <p class="text-sm text-gray-500 mt-1">Seleccione un paciente para gestionar su historia clínica, evoluciones y medicamentos.</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="p-4 font-medium">Documento</th>
                    <th class="p-4 font-medium">Paciente</th>
                    <th class="p-4 font-medium">Contacto</th>
                    <th class="p-4 font-medium text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($patients as $patient)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-sm text-gray-800">{{ $patient['user']['documentType'] ?? '' }} {{ $patient['user']['documentNumber'] ?? '' }}</td>
                    <td class="p-4 text-sm text-gray-800 font-medium">{{ $patient['user']['name'] ?? '' }} {{ $patient['user']['lastName'] ?? '' }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $patient['user']['email'] ?? '' }}</td>
                    <td class="p-4 text-sm text-right">
                        <a href="{{ route('patients.show', $patient['id'] ?? 0) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium">
                            Abrir Historia
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-500">
                        No hay pacientes registrados para consultar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
