<?php

namespace App\Livewire\Pages\Admin;

use App\Livewire\Forms\UserForm;
use Livewire\Component;
use App\Models\User;
use Mary\Traits\Toast;

use Livewire\WithPagination;

class Employee extends Component
{
    use WithPagination;
    use Toast;
    public UserForm $form;
    public $showModal = false;
    public $showDelete = false;
    public $showUpdate = false;

    public $userIdToDelete;
    public $userIdToUpdate;

    public $name;
    public $email;
    public $role;
    public $password;
    public function render()
    {
        $selectedRole = [
            ['key' => 'admin', 'name' => 'Admin',],
            ['key' => 'user', 'name' => 'User',]
        ];
        $users = User::paginate(10);
        $headers = [
            ['key' => 'id', 'label' => 'ID', 'class' => 'text-black'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'text-black'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'text-black'],
            ['key' => 'role', 'label' => 'Role', 'class' => 'text-black'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'text-black'],
        ];
        return view(
            'livewire.pages.admin.employee',
            [
                'users' => $users,
                'headers' => $headers,
                'selectedRole' => $selectedRole,
            ]
        );
    }

    public function save()
    {
        $this->form->store();
        $this->success('User created.', css: 'bg-green-500 text-white');
        $this->showModal = false;
    }

    public function delete()
    {
        if ($this->userIdToDelete) {
            User::find($this->userIdToDelete)->delete();
            $this->showDelete = false;
            $this->success('User has deleted. ', css: 'bg-green-500 text-white');

            $this->userIdToDelete = null;
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'required',
        ]);

        $user = User::find($this->userIdToUpdate);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => bcrypt($this->password),
        ]);

        $this->success('User updated.', css: 'bg-green-500 text-white');

        $this->showUpdate = false;
        $this->userIdToUpdate = null;
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->password = '';

        session()->flash('message', 'User updated.');
    }

    public function modalDelete($userId)
    {
        $this->userIdToDelete = $userId;
        $this->showDelete = true;
    }

    public function modalUpdate($userId)
    {
        $this->userIdToUpdate = $userId;
        $user = User::find($this->userIdToUpdate);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->showUpdate = true;
    }
}
