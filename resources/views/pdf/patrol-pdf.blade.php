<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Patrol</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #0a0a0a;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        th {
            background-color: #7CD14BFF;
        }

        img {
            width: 100px;
            height: 100px;
        }

    </style>
</head>

<body>
    <table width="100%">
        <tr >
            <td width="20%" align="left" style="border: 0px;">
                <img src="{{ public_path('agraLogo.jpg') }}" width="100">
            </td>
            <td width="60%" align="center" style="border: 0px;">
                <h2 style="font-size: 12px">HSSE PATROL WALKTROUGHT EPCC AGPA REFINERY COMPLEX PROJECT</h2>
            </td>
            <td width="20%" align="right" style="border: 0px;">
                <img src="{{ public_path('tripatrLogo.png') }}" width="100">
            </td>
        </tr>
    </table>
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
                        @if ($beforeImage = $report->images->where('is_before', 1)->first())
                            @php
                                $imageDataBefore = base64_encode(
                                    file_get_contents(public_path('storage/' . $beforeImage->image_path)),
                                );
                            @endphp
                            <img src="data:image/png;base64,{{ $imageDataBefore }}" alt="Gambar Sebelum">
                        @endif
                    </td>
                    <td>{{ $report->date_reported }}</td>
                    <td>{{ $report->date_to_be_resolved }}</td>
                    <td>
                        @if ($afterImage = $report->images->where('is_before', 0)->first())
                            @php
                                $imageDataAfter = base64_encode(
                                    file_get_contents(public_path('storage/' . $afterImage->image_path)),
                                );
                            @endphp
                            <img src="data:image/png;base64,{{ $imageDataAfter }}" alt="Gambar Sesudah" width="100">
                        @endif
                    </td>
                    <td>{{ $report->date_resolved }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>
                        @if ($report->status === 'open')
                            <span
                                style="background-color: #FC4545FF; color: #fff; padding: 5px 10px; border-radius: 5px;">Open</span>
                        @else
                            <span
                                style="background-color: #68d391; color: #fff; padding: 5px 10px; border-radius: 5px;">Close</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="8" rowspan="4" style="padding: 0px; margin-top: 2px">Summary</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #cecece; text-align: right;" align="right">Total Finding</td>
                <td style="">{{ $totalFinding = $reports->count() }}</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #FC4545FF; text-align: right;" align="right">Open</td>
                <td style="">{{ $totalOpen = $reports->where('status', 'open')->count() }}</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #68d391; text-align: right;" align="right">Close</td>
                <td style="">{{ $totalClose = $reports->where('status', 'resolved')->count() }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
