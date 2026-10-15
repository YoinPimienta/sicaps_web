@extends('layouts.app')

@section('title', 'Pacientes - SICAPS Web')
@section('header_title', 'Gestión de Pacientes')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">Listado de Pacientes</h3>
        <a href="{{ route('patients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            + Nuevo Paciente
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="p-4 font-medium">Documento</th>
                    <th class="p-4 font-medium">Nombre Completo</th>
                    <th class="p-4 font-medium">Correo Electrónico</th>
                    <th class="p-4 font-medium">Teléfono</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($patients as $patient)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-sm text-gray-800">{{ $patient['user']['documentType'] ?? '' }} {{ $patient['user']['documentNumber'] ?? '' }}</td>
                    <td class="p-4 text-sm text-gray-800 font-medium">{{ $patient['user']['name'] ?? '' }} {{ $patient['user']['lastName'] ?? '' }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $patient['user']['email'] ?? '' }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $patient['user']['phone'] ?? 'No registrado' }}</td>
                    <td class="p-4 text-sm text-right space-x-2">
                        <a href="{{ route('patients.show', $patient['id'] ?? 0) }}" class="text-blue-600 hover:text-blue-800 font-medium">Ver</a>
                        @if(session('user_role') === 'ROLE_ADMIN' || session('user_role') === 'ROLE_PROFESSIONAL')
                        <a href="{{ route('patients.edit', $patient['id'] ?? 0) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">Editar</a>
                        <form action="{{ route('patients.destroy', $patient['id'] ?? 0) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este paciente?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        No hay pacientes registrados en el sistema.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
