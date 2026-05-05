<div>
    {{-- Header com busca e botão --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border-b border-slate-100 dark:border-slate-800">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Lista de Funcionários</h3>
        <div class="flex items-center gap-3 flex-wrap">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-10 py-2 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 text-sm focus:ring-2 focus:ring-brand-500 transition" placeholder="Buscar funcionário...">
                <div wire:loading wire:target="search" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            
            <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Novo Funcionário
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrap relative min-h-[300px]">
        {{-- Loader Overaly --}}
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            <table id="employees-table">
                <thead>
                    <tr>
                        <th wire:click="sortBy('name')" class="cursor-pointer hover:text-primary transition-colors">
                            <div class="flex items-center gap-1">
                                Funcionário
                                @if($sortField === 'name')
                                    <span>{!! $sortAsc ? '&uarr;' : '&darr;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('email')" class="cursor-pointer hover:text-primary transition-colors">
                            <div class="flex items-center gap-1">
                                Email
                                @if($sortField === 'email')
                                    <span>{!! $sortAsc ? '&uarr;' : '&darr;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <th>CPF</th>
                        <th wire:click="sortBy('position')" class="cursor-pointer hover:text-primary transition-colors">
                            <div class="flex items-center gap-1">
                                Cargo
                                @if($sortField === 'position')
                                    <span>{!! $sortAsc ? '&uarr;' : '&darr;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('admission_date')" class="cursor-pointer hover:text-primary transition-colors">
                            <div class="flex items-center gap-1">
                                Admissão
                                @if($sortField === 'admission_date')
                                    <span>{!! $sortAsc ? '&uarr;' : '&darr;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <th>Jornada</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr class="employee-row">
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $employee->user->avatar_url }}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 dark:border-slate-700" alt="{{ $employee->user->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($employee->user->name) }}&color=1bbfbf&background=e6f9f9&size=96&bold=true'">
                                <div>
                                    <div class="font-semibold text-sm text-slate-900 dark:text-slate-50">{{ $employee->user->name }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">ID #{{ $employee->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--text-muted); font-size:13px;">{{ $employee->user->email }}</td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:12.5px;">{{ $employee->cpf }}</td>
                        <td>
                            <span style="background:var(--surface2); border:1px solid var(--border); padding:3px 10px; border-radius:6px; font-size:12px; font-weight:500;">
                                {{ $employee->position }}
                            </span>
                        </td>
                        <td style="font-size:13px;">{{ $employee->admission_date->format('d/m/Y') }}</td>
                        <td>
                            @if($employee->workSchedule)
                                <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Configurada</span>
                            @else
                                <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Pendente</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('employees.show', $employee) }}" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-500/10 rounded-lg transition" title="Ver detalhes">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('employees.edit', $employee) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-lg transition" title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <a href="{{ route('schedule.edit', $employee) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition" title="Configurar jornada">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('employees.destroy', $employee) }}"
                                      onsubmit="return confirm('Tem certeza que deseja excluir {{ $employee->user->name }}?')" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition" title="Excluir">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:48px; color:var(--text-muted);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;display:block;opacity:.25"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @if(empty($search))
                                Nenhum funcionário cadastrado.
                                <br>
                                <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px; display:inline-flex;">Adicionar seu primeiro funcionário</a>
                            @else
                                Nenhum funcionário encontrado para a busca "{{ $search }}".
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($employees->hasPages())
    <div style="padding:16px 22px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:13px; color:var(--text-muted);">
            Mostrando {{ $employees->firstItem() }}–{{ $employees->lastItem() }} de {{ $employees->total() }} funcionários
        </span>
        <div class="pagination">
            {{ $employees->links('livewire::simple-tailwind') }}
        </div>
    </div>
    @endif
</div>
