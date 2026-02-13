<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Pengaduan Masyarakat</title>
    <style>
        @page {
            margin: 2.5cm 2.5cm 2.5cm 2.5cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px; /* Base font 12 */
            margin: 0;
            padding: 0;
        }

        /* Kop Surat */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }

        .logo-cell {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center; /* Logo centered in its cell */
            padding-right: 15px;
        }

        .text-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: left; /* Align text left next to logo */
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .brand-title {
            font-family: Arial, sans-serif; /* Match image style */
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 5px;
            color: #000;
        }

        .brand-subtitle {
            font-family: Arial, sans-serif;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1;
            color: #000;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin: 20px 0;
        }

        .report-title h3 {
            text-decoration: underline;
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }

        .report-period {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Isi Surat & Tabel (Content 14px) */
        .content, table, .footer {
            font-size: 14px; 
        }

        .content {
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .content p {
            margin: 3px 0;
        }

        .indent {
            text-indent: 30px;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto; 
        }

        th, td {
            border: 1px solid #000;
            padding: 6px; /* Slightly more padding for larger font */
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        td {
            text-align: left;
        }

        .text-center { text-align: center; }

        /* Footer / Tanda Tangan */
        .footer {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        
        .footer-signature {
            float: right;
            width: 250px;
            text-align: center;
        }

        .signature-space {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Kop Surat -->
    <div class="header-container">
        <div class="logo-cell">
            @php
                $path = public_path('logo.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_exists($path) ? file_get_contents($path) : '';
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            @if($data)
                <img src="{{ $base64 }}" class="logo" alt="Logo">
            @endif
        </div>
        <div class="text-cell">
            <div class="brand-title">E-LAPORAN</div>
            <div class="brand-subtitle">CITIZEN-COMPLAINT</div>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="report-title">
        <h3>LAPORAN PENGADUAN MASYARAKAT</h3>
        <div class="report-period">
            Periode: 
            {{ $dari ? \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') : '-' }}
            s/d 
            {{ $sampai ? \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') : '-' }}
        </div>
        <div class="report-period" style="margin-top:0;">No Surat : ................................................................</div>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Kepada Yth.</p>
        <p>Kepala {{ isset($opd) ? $opd->nama_opd : 'Dinas Terkait' }}</p>
        <p>Provinsi Papua</p>
        <p>Di -</p>
        <p style="margin-left: 20px;">Tempat</p>
        <br>
        <p>Dengan hormat,</p>
        <p class="indent">
            Berikut kami sampaikan informasi pengaduan masyarakat yang telah masuk pada sistem Citizen Complaint
            untuk ditindaklanjuti sesuai ketentuan yang berlaku.
        </p>
    </div>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Aduan</th>
                <th>Tanggal Lapor</th>
                <th>Pelapor</th>
                <th>Unit</th>
                <th>Kategori</th>
                <th>OPD</th>
                <th>Lokasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $i => $r)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center">
                    {{ $r->no_aduan ?? ('ADU-' . date('Ymd', strtotime($r->tanggal_lapor)) . '-' . str_pad($r->id % 1000, 4, '0', STR_PAD_LEFT)) }}
                </td>
                <td class="text-center">{{ optional($r->tanggal_lapor)->format('Y-m-d') }}</td>
                <td>{{ $r->masyarakat?->pengguna?->nama_pengguna ?? $r->masyarakat?->nama_lengkap ?? '-' }}</td>
                <td>
                    @php
                        $latestUnit = $r->riwayatStatus->sortByDesc('id')->first();
                    @endphp
                    {{ $latestUnit && $latestUnit->unitOpd ? $latestUnit->unitOpd->nama_unit : 'Sekretariat' }}
                </td>
                <td>{{ $r->kategoriAduan?->nama_kategori ?? '-' }}</td>
                <td>
                    @if($r->kategoriAduan && $r->kategoriAduan->opd)
                        @foreach($r->kategoriAduan->opd as $o)
                            {{ $o->nama_opd }}<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($r->lokasi)
                        {{ $r->lokasi }}
                    @elseif($r->latitude && $r->longitude)
                        {{ $r->latitude }}, {{ $r->longitude }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $r->statusAduan?->nama_status ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data pengaduan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Penutup -->
    <div class="content">
        <p class="indent">
            Demikian laporan ini kami sampaikan untuk menjadi perhatian dan tindak lanjut. Atas kerja sama dan
            perhatian Bapak/Ibu, kami ucapkan terima kasih.
        </p>
    </div>

    <!-- Tanda Tangan -->
    <div class="footer">
        <div class="footer-signature">
            <p>Hormat kami,</p>
            <div class="signature-space">
                ( ................................................. )
            </div>
            <!-- Optional: NIP or Jabatan -->
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>