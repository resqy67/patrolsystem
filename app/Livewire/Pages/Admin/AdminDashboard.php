<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Reports;
use Livewire\Component;
use App\Models\User;

class AdminDashboard extends Component
{
    public function render()
    {
        $users = User::all()->count();
        $reported = Reports::all()->count();
        $resolved = Reports::where('status', 'resolved')->count();
        $unresolved = Reports::where('status', 'open')->count();
        return view('livewire.pages.admin.admin-dashboard', [
            'users' => $users,
            'reported' => $reported,
            'resolved' => $resolved,
            'unresolved' => $unresolved
        ]);
    }


}
