<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;

class EmployeeTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortAsc = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
            $this->sortField = $field;
        }
    }

    public function render()
    {
        $query = Employee::with(['user', 'workSchedule']);

        if (!empty($this->search)) {
            $query->where(function($subQuery) {
                $subQuery->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('cpf', 'like', '%' . $this->search . '%')
                ->orWhere('position', 'like', '%' . $this->search . '%');
            });
        }

        if (in_array($this->sortField, ['name', 'email'])) {
            $query->join('users', 'employees.user_id', '=', 'users.id')
                  ->select('employees.*')
                  ->orderBy('users.' . $this->sortField, $this->sortAsc ? 'asc' : 'desc');
        } else {
            $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
        }

        return view('livewire.employee-table', [
            'employees' => $query->paginate(10)
        ]);
    }
}
