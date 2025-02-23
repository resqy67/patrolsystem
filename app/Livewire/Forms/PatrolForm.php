<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Form;
use App\Models\Reports;
use App\Models\ReportImages;
// use App\Livewire\Forms\Auth;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatrolForm extends Form
{
    use WithFileUploads;
    #[Validate('required', 'string')]
    public string $company_name = "";
    #[Validate('required', 'string')]
    public string $location = "";
    #[Validate('required', 'string')]
    public string $description_of_problem = "";
    public string $date_to_be_resolved = "";
    #[Validate('required', 'image')]
    public $image_path;
    public $is_before;
    // public string $status;

    public function store()
    {
        $this->validate([
            'company_name' => 'required',
            'location' => 'required',
            'description_of_problem' => 'required',
            'date_to_be_resolved' => 'required',
            'image_path' => 'required|image',
            // 'status' => 'required',
        ]);

        $report = Reports::create([
            'company_name' => $this->company_name,
            'location' => $this->location,
            'description_of_problem' => $this->description_of_problem,
            'date_to_be_resolved' => Carbon::parse($this->date_to_be_resolved)->format('Y-m-d'),
            'status' => 'open',
            'user_id' => Auth::User()->id,
            'date_reported' => now()->toDateString(),
        ]);

        $this->uploadImage($report->id, $this->image_path, $this->is_before = true);

        session()->flash('message', 'Report submitted successfully.');

        $this->reset();
    }

    public function uploadImage($report_id, $image, $is_before)
    {
        $this->validate([
            'image_path' => 'required|image',
        ]);

        // $path = $this->image_path->store('images', 'public');
        $path = $image->store('images', 'public');
        ReportImages::create([
            'reports_id' => $report_id,
            'image_path' => $path,
            'is_before' => $is_before,
        ]);

        $this->image_path = null;
    }
}
