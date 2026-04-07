<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Histórico de Ponto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Entrada</th>
                            <th class="px-4 py-2">Saída Almoço</th>
                            <th class="px-4 py-2">Volta Almoço</th>
                            <th class="px-4 py-2">Saída</th>
                            <th class="px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $log->work_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $log->lunch_out?->format('H:i') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $log->lunch_in?->format('H:i') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $log->formatted_hours }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-400">
                                Nenhum registro encontrado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>

                <div class="mt-4">
                    <a href="{{ route('work-log.index') }}"
                       class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                        Voltar
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>