<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Meu Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm">Horas trabalhadas hoje</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $todayLog?->formatted_hours ?? '00:00' }}
                    </p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm">Último registro</p>
                    <p class="text-xl font-bold text-gray-800">
                        @if($todayLog?->clock_out)
                            Saída: {{ $todayLog->clock_out->format('H:i') }}
                        @elseif($todayLog?->lunch_in)
                            Volta almoço: {{ $todayLog->lunch_in->format('H:i') }}
                        @elseif($todayLog?->lunch_out)
                            Saída almoço: {{ $todayLog->lunch_out->format('H:i') }}
                        @elseif($todayLog?->clock_in)
                            Entrada: {{ $todayLog->clock_in->format('H:i') }}
                        @else
                            Nenhum registro hoje
                        @endif
                    </p>
                </div>
            </div>

            {{-- Histórico recente --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Histórico Recente</h3>
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Data</th>
                                <th class="px-4 py-2">Entrada</th>
                                <th class="px-4 py-2">Saída</th>
                                <th class="px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $log->work_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $log->formatted_hours }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-400">Nenhum registro encontrado.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>