<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->orderBy('created_at', 'desc')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6',
            'cpf'            => 'required|string|unique:employees,cpf',
            'position'       => 'required|string|max:255',
            'address'        => 'required|string',
            'admission_date' => 'required|date',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'employee',
        ]);

        Employee::create([
            'user_id'        => $user->id,
            'cpf'            => $request->cpf,
            'position'       => $request->position,
            'address'        => $request->address,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Funcionario cadastrado com sucesso!');
    }

    public function show(Employee $employee)
    {
        $employee->load('user', 'workLogs');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'cpf'            => 'required|string|size:14|unique:employees,cpf,' . $employee->id,
            'position'       => 'required|string|max:255',
            'address'        => 'required|string',
            'admission_date' => 'required|date',
        ]);

        $employee->user->update(['name' => $request->name]);

        $employee->update([
            'cpf'            => $request->cpf,
            'position'       => $request->position,
            'address'        => $request->address,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Funcionario atualizado com sucesso!');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->delete();
        return redirect()->route('employees.index')
            ->with('success', 'Funcionario removido com sucesso!');
    }
}