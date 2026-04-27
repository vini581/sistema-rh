<x-app-layout>
    <x-slot name="header">Funcionários</x-slot>

    <div class="card fade-up">
        {{-- Header com busca e botão --}}
        <div class="card-header" style="flex-wrap:wrap; gap:12px;">
            <span class="card-title">Lista de Funcionários</span>
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <form method="GET" action="{{ route('employees.index') }}" style="display:flex; gap:8px;">
                    <div class="input-group" style="width:240px;">
                        <span class="input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" class="form-input" placeholder="Buscar funcionário..." id="search-input" style="height:38px; font-size:13px;" value="{{ $search ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm">Buscar</button>
                    @if($search)
                        <a href="{{ route('employees.index') }}" class="btn btn-ghost btn-sm">Limpar</a>
                    @endif
                </form>
                <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Novo Funcionário
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        <div style="padding: 0 22px;">
            @if(session('success'))
            <div class="alert alert-success" style="margin-top:16px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger" style="margin-top:16px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
            @endif
        </div>

        {{-- Table --}}
        <div class="table-wrap">
            <table id="employees-table">
                <thead>
                    <tr>
                        <th>Funcionário</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th>Cargo</th>
                        <th>Admissão</th>
                        <th>Jornada</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr class="employee-row">
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <img src="{{ $employee->user->avatar_url }}" style="width:36px; height:36px; border-radius:10px; object-fit:cover; border:1px solid var(--border);" alt="{{ $employee->user->name }}">
                                <div>
                                    <div style="font-weight:600; font-size:13.5px;">{{ $employee->user->name }}</div>
                                    <div style="font-size:11px; color:var(--text-muted);">ID #{{ $employee->id }}</div>
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
                            <div style="display:flex; align-items:center; justify-content:flex-end; gap:4px;">
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-ghost btn-sm" title="Ver detalhes">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-ghost btn-sm" title="Editar">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <a href="{{ route('schedule.edit', $employee) }}" class="btn btn-ghost btn-sm" title="Configurar jornada">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('employees.destroy', $employee) }}"
                                      onsubmit="return confirm('Tem certeza que deseja excluir {{ $employee->user->name }}?')" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" title="Excluir" style="color:var(--danger);">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:48px; color:var(--text-muted);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;display:block;opacity:.25"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Nenhum funcionário cadastrado.
                            <br>
                            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px; display:inline-flex;">Adicionar seu primeiro funcionário</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
        <div style="padding:16px 22px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:13px; color:var(--text-muted);">
                Mostrando {{ $employees->firstItem() }}–{{ $employees->lastItem() }} de {{ $employees->total() }} funcionários
            </span>
            <div class="pagination">
                @if($employees->onFirstPage())
                    <span class="page-btn" style="opacity:.4; cursor:not-allowed;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $employees->previousPageUrl() }}" class="page-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif
                @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn {{ $page == $employees->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($employees->hasMorePages())
                    <a href="{{ $employees->nextPageUrl() }}" class="page-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="page-btn" style="opacity:.4; cursor:not-allowed;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>


</x-app-layout>