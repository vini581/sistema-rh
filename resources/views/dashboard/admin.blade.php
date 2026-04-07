<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard — Gestor de RH
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm">Total de Funcionários</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalEmployees }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm">Registros hoje</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $todayLogs->count() }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500 text-sm">Acesso rápido</p>
                    <a href="{{ route('employees.index') }}" class="text-blue-600 hover:underline text-sm">
                        Ver funcionários →
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Registros de Ponto — Hoje</h3>
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Funcionário</th>
                                <th class="px-4 py-2">Entrada</th>
                                <th class="px-4 py-2">Saída Almoço</th>
                                <th class="px-4 py-2">Volta Almoço</th>
                                <th class="px-4 py-2">Saída</th>
                                <th class="px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayLogs as $log)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $log->employee->user->name }}</td>
                                <td class="px-4 py-2">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $log->lunch_out?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $log->lunch_in?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $log->formatted_hours }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-400">
                                    Nenhum registro hoje.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>