<x-app-layout>
    <x-slot name="header">Atestados Médicos</x-slot>
    <div class="card fade-up" style="margin-bottom:18px;">
        <div class="card-body" style="padding:18px 22px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <form method="GET" action="{{ route('certificates.index') }}" style="display:flex;gap:8px;">
                <select name="status" class="form-input" style="width:180px;">
                    <option value="">Todos os Status</option>
                    <option value="pending" {{ $status==='pending'?'selected':'' }}>Pendente</option>
                    <option value="approved" {{ $status==='approved'?'selected':'' }}>Aprovado</option>
                    <option value="rejected" {{ $status==='rejected'?'selected':'' }}>Recusado</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
            </form>
            <a href="{{ route('certificates.create') }}" class="btn btn-primary btn-sm" style="margin-left:auto;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Novo Atestado
            </a>
        </div>
    </div>
    <div class="card fade-up" style="animation-delay:.1s;opacity:0;">
        <div class="card-header"><span class="card-title">Lista de Atestados</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Funcionário</th><th>Tipo</th><th>Período</th><th>Dias</th><th>Status</th><th>Abonado</th><th>Ações</th></tr></thead>
                <tbody>
                    @forelse($certificates as $c)
                    <tr>
                        <td><div style="display:flex;align-items:center;gap:10px;"><div style="width:32px;height:32px;border-radius:8px;background:var(--primary-lt);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:var(--primary);">{{ substr($c->employee->user->name,0,1) }}</div><span style="font-weight:600;font-size:13px;">{{ $c->employee->user->name }}</span></div></td>
                        <td><span style="font-size:12px;">{{ $c->type_label }}</span></td>
                        <td style="font-family:'JetBrains Mono',monospace;font-size:12px;">{{ $c->start_date->format('d/m') }} — {{ $c->end_date->format('d/m/Y') }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $c->days }}</td>
                        <td>
                            @if($c->status==='pending')<span class="badge badge-warning"><span class="badge-dot" style="background:#f59e0b;"></span>Pendente</span>
                            @elseif($c->status==='approved')<span class="badge badge-success"><span class="badge-dot" style="background:#10b981;"></span>Aprovado</span>
                            @else<span class="badge badge-danger"><span class="badge-dot" style="background:#ef4444;"></span>Recusado</span>@endif
                        </td>
                        <td style="text-align:center;">@if($c->excused)<span style="color:var(--success);font-weight:700;">Sim</span>@else<span style="color:var(--danger);font-weight:700;">Não</span>@endif</td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                @if($c->status==='pending')
                                <form method="POST" action="{{ route('certificates.approve',$c) }}" style="margin:0;">@csrf @method('PATCH')<button type="submit" class="btn btn-ghost btn-sm" style="color:var(--success);" title="Aprovar"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button></form>
                                <form method="POST" action="{{ route('certificates.reject',$c) }}" style="margin:0;">@csrf @method('PATCH')<button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);" title="Recusar"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></form>
                                @endif
                                <form method="POST" action="{{ route('certificates.destroy',$c) }}" style="margin:0;" onsubmit="return confirm('Excluir atestado?')">@csrf @method('DELETE')<button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);" title="Excluir"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">Nenhum atestado cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($certificates->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--border);text-align:center;">{{ $certificates->withQueryString()->links() }}</div>
        @endif
    </div>
</x-app-layout>
