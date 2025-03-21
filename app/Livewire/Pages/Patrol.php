<?php

namespace App\Livewire\Pages;

use App\Livewire\Forms\PatrolForm;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Reports;
use App\Models\ReportImages;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Carbon\Carbon;

class Patrol extends Component
{
    use Toast;
    public PatrolForm $form;
    use WithFileUploads;
    use WithPagination;

    public bool $showModal = false;
    public bool $showDelete = false;
    public bool $showFinish = false;
    public bool $showExport = false;
    public bool $showImage = false;


    public $reportIdToDelete;
    public $reportIdToFinish;
    public $is_before;
    public $image_path;

    public $date_resolved;

    public $date_range_start;
    public $date_range_end;

    public $selectedImage;


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
        // $reports = Reports::with('user', 'images')->paginate(10);
        if ( Auth::user()->role === 'admin') {
            $reports = Reports::with('user', 'images')->paginate(10);
        } else {
            $reports = Reports::with('user', 'images')->where('user_id', Auth::user()->id)->paginate(10);
        }
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
        $this->success('Report submitted successfully.', css: 'bg-green-500 text-white');
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
                'date_resolved' => Carbon::parse($this->date_resolved)->format('Y-m-d'),
            ]);
            Log::info('Uploading image for resolved report:', ['report_id' => $report->id]);
            $this->uploadImage($report->id, $this->image_path, false);
            $this->image_path = null;
            $this->showFinish = false;
            $this->success(
                'Report resolved successfully.',
                css: 'bg-green-500 text-white'
            );
        } else {
            $this->error('Report resolved failed.');
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
        $dateStart = Carbon::parse($this->date_range_start)->format('Y-m-d');
        $dateEnd = Carbon::parse($this->date_range_end)->format('Y-m-d');
        if ($dateStart > $dateEnd) {
            $this->error(
                // tanggal awal tidak boleh lebih besar dari tanggal akhir dalam bahasa inggris
                'Start date cannot be greater than end date.',
                css: 'bg-red-500 text-white'
            );
            $this->showExport = false;
            return;
        }

        $reports = Reports::with('user', 'images')
        ->whereBetween('date_reported', [$dateStart, $dateEnd])
        ->get();
        if ($reports->isEmpty()) {
            $this->error(
                // tidak ada data yang ditemukan dengan range tanggal tersebut dalam bahasa inggris
                'No data found for date range ' . $this->date_range_start . ' - ' . $this->date_range_end,
                css: 'bg-red-500 text-white'
            );
            $this->showExport = false;
            return;
        }

        $pdf = PDF::loadView('pdf.patrol-pdf', ['reports' => $reports])->setPaper('a4', 'landscape');
        //jika pdf berhasil dibuat, maka menampilkan toast message
        $this->success('PDF generated successfully.', css: 'text-white bg-green-500');
        $this->showExport = false;

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'patrol_report_' . $this->date_range_start . '-' . $this->date_range_end . '.pdf');
    }

    public function confirmExport()
    {
        $this->showExport = false;
    }

    public function showImages($imagePath)
    {
        $this->selectedImage = $imagePath;
        $this->showImage = true;
    }
}
