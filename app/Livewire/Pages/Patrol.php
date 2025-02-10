<?php

namespace App\Livewire\Pages;

use App\Livewire\Forms\PatrolForm;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Reports;
use App\Models\ReportImages;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;

class Patrol extends Component
{
    public PatrolForm $form;
    use WithFileUploads;
    use WithPagination;

    public bool $showModal = false;
    public bool $showDelete = false;
    public bool $showFinish = false;
    public bool $showExport = false;


    public $reportIdToDelete;
    public $reportIdToFinish;
    public $is_before;
    public $image_path;

    public $date_resolved;

    public $date_range_start;
    public $date_range_end;


    public $library = []; // Metadata library untuk preview
    public function render()
    {

        $config1 = ['altFormat' => 'm/d/Y'];
        $config2 = [
            'plugins' => [
                [
                    'monthSelectPlugin' => [
                        'dateFormat' => 'Y-m-d',
                        'altFormat' => 'F Y',
                    ]
                ]
            ]
        ];
        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16 text-black'],
            ['key' => 'company_name', 'label' => 'Nama Perusahaan', 'class' => 'w-72 text-black'],
            ['key' => 'location', 'label' => 'Lokasi', 'class' => 'w-32 text-black'],
            ['key' => 'description_of_problem', 'label' => 'Deskripsi Masalah', 'class' => 'w-32 text-black'],
            ['key' => 'image_before', 'label' => 'Gambar Sebelum', 'class' => 'w-72 text-black'],
            ['key' => 'date_reported', 'label' => 'Tanggal Dilaporkan', 'class' => 'w-32 text-black'],
            ['key' => 'date_to_be_resolved', 'label' => 'Tanggal Close', 'class' => 'w-32 text-black'],
            ['key' => 'image_after', 'label' => 'Gambar Setelah', 'class' => 'w-72 text-black'],
            ['key' => 'date_resolved', 'label' => 'Tanggal Selesai', 'class' => 'w-32 text-black'],
            ['key' => 'name', 'label' => 'Nama Pelapor', 'class' => 'w-32 text-black'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-32 text-black'],
            ['key' => 'action', 'label' => 'Aksi', 'class' => 'w-72 text-black'],
        ];
        $reports = Reports::with('user', 'images')->paginate(10);
        // dd($reports);
        return view('livewire.pages.patrol', [
            'reports' => $reports,
            'headers' => $headers,
            'config1' => $config1,
            'config2' => $config2,
        ]);
    }

    public function save()
    {
        $this->form->store();
        $this->showModal = false;
    }

    public function delete()
    {
        if ($this->reportIdToDelete) {
            Reports::find($this->reportIdToDelete)->delete();
            $this->showDelete = false;
            $this->reportIdToDelete = null;
        }
    }

    public function confirmDelete($id)
    {
        $this->reportIdToDelete = $id;
        $this->showDelete = true;
    }

    public function resolveReport()
    {
        Log::info('Resolving report:', ['report_id' => $this->reportIdToFinish]);
        $report = Reports::find($this->reportIdToFinish);
        $this->validate([
            'image_path' => 'required|image',
            'date_resolved' => 'required',
        ]);
        if ($report) {
            $report->update([
                'status' => 'resolved',
                'date_resolved' => $this->date_resolved,
            ]);
            Log::info('Uploading image for resolved report:', ['report_id' => $report->id]);
            $this->uploadImage($report->id, $this->image_path, false);
            $this->image_path = null;
            $this->showFinish = false;
            session()->flash('message', 'Report resolved successfully.');
        } else {
            session()->flash('message', 'Report not found.');
        }
    }

    public function confirmFinish($id)
    {
        $this->reportIdToFinish = $id;
        $this->showFinish = true;
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

    public function export()
    {
        $this->validate([
            'date_range_start' => 'required',
            'date_range_end' => 'required',
        ]);
        $reports = Reports::with('user', 'images')
            ->whereBetween('date_reported', [$this->date_range_start, $this->date_range_end])
            ->get();
            // foreach ($reports as $report) {
            //     $report->image_before = $report->images->where('is_before', true)->first();
            //     $report->image_after = $report->images->where('is_before', false)->first();
            //     dd($report);
            // }
        // $report = $reports->images->where('is_before', true);
        $pdf = PDF::loadView('pdf.patrol-pdf', ['reports' => $reports])->setPaper('a4', 'landscape');
        $this->showExport = false;

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'patrol_report_' . $this->date_range_start . '-' . $this->date_range_end . '.pdf');
    }

    public function confirmExport()
    {
        $this->showExport = false;
    }
}
