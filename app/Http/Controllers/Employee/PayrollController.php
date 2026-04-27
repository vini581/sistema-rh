<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect('/dashboard')->with('error', 'Perfil de funcionário não encontrado.');
        }

        $payrolls = Payroll::where('employee_id', $employee->id)
            ->where('status', '!=', 'draft')
            ->orderBy('reference_month', 'desc')
            ->paginate(12);

        return view('employee.payroll.index', compact('payrolls'));
    }

    public function show(Payroll $payroll)
    {
        $employee = Auth::user()->employee;

        if (!$employee || $payroll->employee_id !== $employee->id) {
            abort(403);
        }

        return view('employee.payroll.show', compact('payroll'));
    }
}
