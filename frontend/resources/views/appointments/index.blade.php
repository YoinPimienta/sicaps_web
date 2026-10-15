@extends('layouts.app')

@section('title', 'Citas - SICAPS Web')
@section('header_title', 'Gestión de Citas')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">Citas Programadas</h3>
        <a href="{{ route('appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            + Agendar Cita
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="p-4 font-medium">Fecha y Hora</th>
                    <th class="p-4 font-medium">Paciente</th>
                    <th class="p-4 font-medium">Profesional</th>
                    <th class="p-4 font-medium">Motivo</th>
                    <th class="p-4 font-medium">Estado</th>
                    <th class="p-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($appointments as $apt)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-sm text-gray-800 font-medium">{{ $apt['date'] }} <span class="text-gray-500">{{ substr($apt['time'], 0, 5) }}</span></td>
                    <td class="p-4 text-sm text-gray-800">{{ $apt['patient']['user']['name'] ?? 'N/A' }} {{ $apt['patient']['user']['lastName'] ?? '' }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $apt['professional']['user']['name'] ?? 'N/A' }} {{ $apt['professional']['user']['lastName'] ?? '' }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($apt['reason'], 30) }}</td>
                    <td class="p-4 text-sm">
                        @if($apt['status'] == 'PROGRAMADA' || $apt['status'] == 'SCHEDULED')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Programada</span>
                        @elseif($apt['status'] == 'COMPLETADA' || $apt['status'] == 'COMPLETED')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completada</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                        @endif
                    </td>
                    <td class="p-4 text-sm text-right space-x-2">
                        @if($apt['status'] == 'PROGRAMADA' || $apt['status'] == 'SCHEDULED')
                            <form action="{{ route('appointments.updateStatus', $apt['id']) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="COMPLETADA">
                                <button type="submit" class="text-green-600 hover:text-green-800 font-medium" title="Marcar como Completada">✅</button>
                            </form>
                            <form action="{{ route('appointments.updateStatus', $apt['id']) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="CANCELADA">
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium ml-2" title="Cancelar Cita">❌</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">
                        No hay citas agendadas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
