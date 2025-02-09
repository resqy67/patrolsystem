<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Patrol</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Laporan Patrol - {{ date('F Y', strtotime(request('selectedMonth'))) }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>Lokasi</th>
                <th>Deskripsi Masalah</th>
                <th>Gambar Sebelum</th>
                <th>Tanggal Dilaporkan</th>
                <th>Tanggal Close</th>
                <th>Gambar Setelah</th>
                <th>Tanggal Selesai</th>
                <th>Nama Pelapor</th>
                <th>Status</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->company_name }}</td>
                    <td>{{ $report->location }}</td>
                    <td>{{ $report->description_of_problem }}</td>
                    <td>
                        @if($report->images->where('is_before', 1)->first())
                            <img src="{{ $report->images->where('is_before', 1)->first()->image_path }}" width="100">
                        @endif
                    </td>
                    <td></td>{{ $report->date_reported }}</td>
                    <td>{{ $report->date_to_be_resolved }}</td>
                    <td>
                        @if($report->images->where('is_before', 0)->first())
                            <img src="{{ $report->images->where('is_before', 0)->first()->image_path }}" alt="Gambar Sesudah" width="100">
                        @endif
                    </td>
                    <td>{{ $report->date_resolved }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
