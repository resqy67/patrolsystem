<?php

namespace App\Livewire\Pages\Admin;

use Illuminate\Container\Attributes\Auth;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        return view('livewire.pages.admin.admin-dashboard');
    }
}
