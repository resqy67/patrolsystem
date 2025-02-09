<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use App\Models\User;

class Employee extends Component
{
    public function render()
    {
        $users = User::all();
        $headers = [
            ['key' => 'id', 'label' => 'ID', 'class' => 'text-black'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'text-black'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'text-black'],
            ['key' => 'role', 'label' => 'Role', 'class' => 'text-black'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'text-black'],
        ];
        return view('livewire.pages.admin.employee',
        [
            'users' => $users,
            'headers' => $headers,
        ]);
    }

}
