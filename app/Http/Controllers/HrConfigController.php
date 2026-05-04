<?php

namespace App\Http\Controllers;

use App\Models\HrConfig;
use Illuminate\Http\Request;

class HrConfigController extends Controller
{
    public function index()
    {
        $configs = HrConfig::whereNull('employee_id')->orderBy('vigencia_inicio', 'desc')->get();
        $employees = \App\Models\Employee::with('user')->get();
        return view('hr-config.index', compact('configs', 'employees'));
    }

    public function create()
    {
        $config = HrConfig::current();
        return view('hr-config.edit', compact('config'));
    }

    public function edit(HrConfig $hrConfig)
    {
        $config = $hrConfig;
        return view('hr-config.edit', compact('config'));
    }

    public function store(Request $request)
    {
        return $this->save($request, new HrConfig());
    }

    public function update(Request $request, HrConfig $hrConfig)
    {
        return $this->save($request, $hrConfig);
    }

    protected function save(Request $request, HrConfig $config)
    {
        $validated = $request->validate([
            'vigencia_inicio'        => 'required|date',
            'default_hourly_rate'    => 'required|numeric|min:0',
            'monthly_hours'          => 'required|integer|min:1|max:744',
            'payment_type'           => 'required|in:monthly,biweekly',
            'overtime_weekday_pct'   => 'required|integer|min:0|max:200',
            'overtime_saturday_pct'  => 'required|integer|min:0|max:200',
            'overtime_sunday_pct'    => 'required|integer|min:0|max:200',
            'overtime_holiday_pct'   => 'required|integer|min:0|max:200',
            'night_shift_pct'        => 'required|integer|min:0|max:100',
            'overtime_min_minutes'   => 'required|integer|min:0|max:120',
            'saturday_is_overtime'   => 'boolean',
            'use_hour_bank'          => 'boolean',
            'biweekly_first_start'   => 'required|integer|min:1|max:31',
            'biweekly_first_end'     => 'required|integer|min:1|max:31|gte:biweekly_first_start',
            'biweekly_second_start'  => 'required|integer|min:1|max:31|gt:biweekly_first_end',
            'biweekly_second_end'    => 'required|integer|min:1|max:31|gte:biweekly_second_start',
            'biweekly_first_pct'     => 'required|integer|min:0|max:100',
            'biweekly_second_pct'    => 'required|integer|min:0|max:100',
            'vacation_bonus_pct'     => 'required|numeric|min:0|max:100',
            'vacation_include_overtime_avg' => 'boolean',
            'vacation_allow_split'   => 'boolean',
            'certificate_excuses_absence'    => 'boolean',
            'certificate_counts_as_worked'   => 'boolean',
            'certificate_discount_after_days' => 'required|integer|min:0',
            'certificate_discount_transport' => 'boolean',
            'certificate_discount_food'      => 'boolean',
            'certificate_company_paid_days'  => 'required|integer|min:0',
            'fixed_discount_pct'             => 'required|integer|min:0|max:100',
        ]);

        // Validação de soma das quinzenas = 100%
        if (((int) $validated['biweekly_first_pct'] + (int) $validated['biweekly_second_pct']) !== 100) {
            return back()->withInput()->withErrors([
                'biweekly_first_pct' => 'A soma das % da 1ª e 2ª quinzena deve ser exatamente 100%.',
            ]);
        }

        $booleans = [
            'saturday_is_overtime', 'use_hour_bank',
            'vacation_include_overtime_avg', 'vacation_allow_split',
            'certificate_excuses_absence', 'certificate_counts_as_worked',
            'certificate_discount_transport', 'certificate_discount_food',
        ];
        foreach ($booleans as $field) {
            $validated[$field] = $request->boolean($field);
        }

        // Converte para centavos
        $validated['default_hourly_rate'] = (int) round($request->default_hourly_rate * 100);

        $config->fill($validated)->save();

        return redirect()->route('hr-config.index')
            ->with('success', 'Tudo salvo! As configurações do RH foram atualizadas.');
    }

    public function destroy(HrConfig $hrConfig)
    {
        $hrConfig->delete();

        return redirect()->route('hr-config.index')
            ->with('success', 'Vigência removida.');
    }
}
