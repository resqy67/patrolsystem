<?php

namespace App\Livewire\Pages\User;

use Livewire\Component;
use App\Models\User;
use App\Models\Reports;

class UserDashboard extends Component
{
    public function render()
    {
        $reported = Reports::all()->count();
        $resolved = Reports::where('status', 'resolved')->count();
        $unresolved = Reports::where('status', 'open')->count();
        return view('livewire.pages.user.user-dashboard', [
            'reported' => $reported,
            'resolved' => $resolved,
            'unresolved' => $unresolved
        ]);
    }
}
