<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalhes do Funcionário
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Informações Pessoais</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nome</p>
                        <p class="font-medium">{{ $employee->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium">{{ $employee->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">CPF</p>
                        <p class="font-medium">{{ $employee->cpf }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Cargo</p>
                        <p class="font-medium">{{ $employee->position }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Endereço</p>
                        <p class="font-medium">{{ $employee->address }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Data de Admissão</p>
                        <p class="font-medium">{{ $employee->admission_date->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Histórico de Ponto</h3>
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
                        @forelse($employee->workLogs->sortByDesc('work_date') as $log)
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
            </div>

            <div class="mt-4">
                <a href="{{ route('employees.index') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Voltar
                </a>
            </div>

        </div>
    </div>
</x-app-layout>