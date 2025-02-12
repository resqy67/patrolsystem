<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\User;

class UserForm extends Form
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = '';

    public function store() {
        // dd($this->role);
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => $this->role,
        ]);

        session()->flash('message', 'User created.');
    }



    public function resetPassword($id) {
        $this->validate([
            'password' => 'required',
        ]);

        $user = User::find($id);
        $user->update([
            'password' => bcrypt($this->password),
        ]);

        session()->flash('message', 'Password updated.');
    }

    public function delete($id) {
        $user = User::find($id);
        $user->delete();
        session()->flash('message', 'User deleted.');
    }
}
