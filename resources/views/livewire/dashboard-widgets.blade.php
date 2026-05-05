<div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <div 
        x-data="{
            widgets: JSON.parse(localStorage.getItem('dashboard_widgets')) || ['employees', 'vacations', 'certificates', 'holidays'],
            initSortable() {
                var el = document.getElementById('dashboard-grid');
                Sortable.create(el, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'opacity-50',
                    onEnd: (evt) => {
                        let newOrder = [];
                        el.querySelectorAll('.widget-item').forEach(item => {
                            newOrder.push(item.dataset.id);
                        });
                        this.widgets = newOrder;
                        localStorage.setItem('dashboard_widgets', JSON.stringify(newOrder));
                    }
                });
            }
        }"
        x-init="
            initSortable();
            $nextTick(() => {
                const grid = document.getElementById('dashboard-grid');
                this.widgets.forEach(id => {
                    const el = grid.querySelector(`[data-id='${id}']`);
                    if(el) grid.appendChild(el);
                });
            });
        "
    >
        <div class="mb-8 flex justify-between items-center fade-up">
            <div>
                <h3 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-50">Visão Geral do RH</h3>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Gerencie indicadores chave arrastando os cartões.</p>
            </div>
            <button @click="localStorage.removeItem('dashboard_widgets'); location.reload();" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-slate-50 transition-colors">
                Restaurar Ordem Original
            </button>
        </div>

        <div id="dashboard-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Widget: Funcionários --}}
            <div class="widget-item fade-up-1 relative group" data-id="employees">
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden h-full">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-400 to-brand-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-5 right-5 text-slate-300 dark:text-slate-700 cursor-move drag-handle hover:text-slate-900 dark:hover:text-slate-300 transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    </div>
                    
                    <div class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 dark:text-brand-400 mb-6">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    
                    <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ $stats['employees_count'] }}</div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Funcionários Ativos</div>
                    
                    <a href="{{ route('employees.index') }}" class="absolute inset-0 z-10" style="opacity: 0;">Link</a>
                </div>
            </div>

            {{-- Widget: Férias --}}
            <div class="widget-item fade-up-2 relative group" data-id="vacations">
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden h-full">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-5 right-5 text-slate-300 dark:text-slate-700 cursor-move drag-handle hover:text-slate-900 dark:hover:text-slate-300 transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    </div>
                    
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m18 4v-4M10 21V7a2 2 0 012-2h4a2 2 0 012 2v14M5 21h14M5 21V7a2 2 0 012-2h3M5 21h3"/></svg>
                    </div>
                    
                    <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ $stats['pending_vacations'] }}</div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Férias p/ Aprovar</div>
                </div>
            </div>

            <div class="widget-item fade-up-3 relative group" data-id="certificates">
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden h-full">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-rose-400 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-5 right-5 text-slate-300 dark:text-slate-700 cursor-move drag-handle hover:text-slate-900 dark:hover:text-slate-300 transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    </div>
                    
                    <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400 mb-6">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    
                    <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ $stats['pending_certificates'] }}</div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Atestados Pendentes</div>
                </div>
            </div>

            <div class="widget-item fade-up-4 relative group" data-id="holidays">
                <div class="bg-white dark:bg-[#09090b] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden h-full">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-5 right-5 text-slate-300 dark:text-slate-700 cursor-move drag-handle hover:text-slate-900 dark:hover:text-slate-300 transition-colors">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    </div>
                    
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    
                    <div class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-50 mb-1">{{ count($stats['upcoming_holidays']) }}</div>
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Próximos Feriados</div>
                    
                    @if(count($stats['upcoming_holidays']) > 0)
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/60">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 truncate">
                            <span class="text-amber-500">{{ \Carbon\Carbon::parse($stats['upcoming_holidays'][0]->date)->format('d/m') }}</span> — {{ $stats['upcoming_holidays'][0]->name }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
