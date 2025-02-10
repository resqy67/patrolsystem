<?php

namespace App\Livewire\Pages\Admin;

use App\Livewire\Forms\UserForm;
use Livewire\Component;
use App\Models\User;

class Employee extends Component
{
    public UserForm $form;
    public $showModal = false;
    public function render()
    {
        $selectedRole = [
            ['key' => 'admin', 'name' => 'Admin',],
            ['key' => 'user', 'name' => 'User',]
        ];
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
            'selectedRole' => $selectedRole,
        ]);
    }

    public function save()
    {
        $this->form->store();
        $this->showModal = false;
    }

}
