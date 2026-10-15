@extends('layouts.app')

@section('title', 'Editar Paciente - SICAPS Web')
@section('header_title', 'Editar Paciente')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form action="{{ route('patients.update', $patient['id'] ?? 0) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Datos Personales</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" value="{{ $patient['user']['name'] ?? '' }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="lastName" value="{{ $patient['user']['lastName'] ?? '' }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" name="email" value="{{ $patient['user']['email'] ?? '' }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="phone" value="{{ $patient['user']['phone'] ?? '' }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
        
        <h3 class="text-lg font-medium text-gray-800 border-b pb-2 mt-6">Contacto de Emergencia</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre Contacto</label>
                <input type="text" name="emergencyContactName" value="{{ $patient['emergencyContactName'] ?? '' }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono Contacto</label>
                <input type="text" name="emergencyContactPhone" value="{{ $patient['emergencyContactPhone'] ?? '' }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <div class="pt-4 flex justify-end space-x-2">
            <a href="{{ route('patients.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancelar</a>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">Actualizar Datos</button>
        </div>
    </form>
</div>
@endsection
