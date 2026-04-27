<x-app-layout>
    <x-slot name="header">Detalhes do Funcionário</x-slot>

    <div style="display:flex; flex-direction:column; gap:18px;">

        {{-- Info Card --}}
        <div class="card fade-up">
            <div class="card-header">
                <span class="card-title">Informações Pessoais</span>
                <div style="display:flex; gap:8px;">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary btn-sm">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar
                    </a>
                    <a href="{{ route('schedule.edit', $employee) }}" class="btn btn-secondary btn-sm">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Jornada
                    </a>
                    <a href="{{ route('employees.index') }}" class="btn btn-ghost btn-sm">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Voltar
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Avatar + Nome --}}
                <div style="display:flex; align-items:center; gap:16px; padding:16px; background:var(--surface2); border:1px solid var(--border); border-radius:12px; margin-bottom:24px;">
                    <img src="{{ $employee->user->avatar_url }}" style="width:56px; height:56px; border-radius:14px; object-fit:cover; border:1px solid var(--border);" alt="{{ $employee->user->name }}">
                    <div>
                        <div style="font-size:17px; font-weight:700; color:var(--text);">{{ $employee->user->name }}</div>
                        <div style="font-size:13px; color:var(--text-muted);">{{ $employee->user->email }}</div>
                    </div>
                    <div style="margin-left:auto;">
                        @if($employee->workSchedule)
                            <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Jornada configurada</span>
                        @else
                            <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Sem jornada</span>
                        @endif
                    </div>
                </div>

                {{-- Dados --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <div style="font-size:11px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">CPF</div>
                        <div style="font-family:'JetBrains Mono',monospace; font-size:14px;">{{ $employee->cpf }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Cargo</div>
                        <div style="font-size:14px; font-weight:500;">{{ $employee->position }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Data de Admissão</div>
                        <div style="font-size:14px;">{{ $employee->admission_date->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">ID</div>
                        <div style="font-size:14px; font-family:'JetBrains Mono',monospace;">#{{ $employee->id }}</div>
                    </div>
                    <div style="grid-column:1/-1;">
                        <div style="font-size:11px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Endereço</div>
                        <div style="font-size:14px; color:var(--text-muted);">{{ $employee->address }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Histórico de Ponto --}}
        <div class="card fade-up" style="animation-delay:.1s; opacity:0;">
            <div class="card-header">
                <span class="card-title">Histórico de Ponto</span>
                <span style="font-size:12px; color:var(--text-muted);">Últimos registros</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Entrada</th>
                            <th>Saída Almoço</th>
                            <th>Volta Almoço</th>
                            <th>Saída Final</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workLogs as $log)
                        <tr>
                            <td style="font-weight:600;">{{ $log->work_date->format('d/m/Y') }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->clock_in?->format('H:i') ?? '—' }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->lunch_out?->format('H:i') ?? '—' }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->lunch_in?->format('H:i') ?? '—' }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px;">{{ $log->clock_out?->format('H:i') ?? '—' }}</td>
                            <td style="font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:700;">{{ $log->formatted_hours }}</td>
                            <td>
                                @if($log->clock_out)
                                    <span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Completo</span>
                                @elseif($log->clock_in)
                                    <span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Em curso</span>
                                @else
                                    <span class="badge badge-danger"><span class="badge-dot" style="background:#ef4444;"></span>Ausente</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:48px; color:var(--text-muted);">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;display:block;opacity:.25">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Nenhum registro encontrado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($workLogs->hasPages())
            <div style="padding:16px 22px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
                <span style="font-size:13px; color:var(--text-muted);">
                    Mostrando {{ $workLogs->firstItem() }}–{{ $workLogs->lastItem() }} de {{ $workLogs->total() }} registros
                </span>
                <div class="pagination">
                    @if($workLogs->onFirstPage())
                        <span class="page-btn" style="opacity:.4; cursor:not-allowed;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $workLogs->previousPageUrl() }}" class="page-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                    @foreach($workLogs->getUrlRange(1, $workLogs->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $workLogs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($workLogs->hasMorePages())
                        <a href="{{ $workLogs->nextPageUrl() }}" class="page-btn">
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

    </div>
</x-app-layout>