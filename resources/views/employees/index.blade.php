<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Funcionários
            </h2>
            <a href="{{ route('employees.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Novo Funcionário
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Cargo</th>
                                <th class="px-4 py-2">Admissão</th>
                                <th class="px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $employee->user->name }}</td>
                                <td class="px-4 py-2">{{ $employee->user->email }}</td>
                                <td class="px-4 py-2">{{ $employee->position }}</td>
                                <td class="px-4 py-2">{{ $employee->admission_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 flex gap-2">
                                    <a href="{{ route('employees.show', $employee) }}"
                                       class="text-blue-600 hover:underline">Ver</a>
                                    <a href="{{ route('employees.edit', $employee) }}"
                                       class="text-yellow-600 hover:underline">Editar</a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}"
                                          onsubmit="return confirm('Tem certeza?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-400">
                                    Nenhum funcionário cadastrado.
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