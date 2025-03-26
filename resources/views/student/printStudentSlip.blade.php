<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Hostel Registration Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .logo {
            width: 80px;
            margin-right: 15px;
        }
        .header-text {
            flex-grow: 1;
        }
        .slip-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
        .note {
            font-style: italic;
            font-size: 10px;
            margin-top: 5px;
        }
        @media print {
            @page {
                size: portrait;
                margin: 1;
            }
            body {
                padding: 10mm;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Logo -->
        <div class="header">
            <img src="{{ asset('assets/images/logo/Kolej-UNITI.png') }}" alt="Kolej UNITI Logo" class="logo">
            <div class="header-text">
                <p style="margin: 0; font-weight: bold;">Kolej UNITI Sdn Bhd (418694-K)</p>
                <p style="margin: 0;">Persiaran Uniti Village</p>
                <p style="margin: 0;">Tanjung Agas, Pasir Panjang, 71250, Port Dickson, Negeri Sembilan</p>
                <p style="margin: 0;">No. Telefon: 06-661 0519, 06-661 0520</p>
                <p style="margin: 0;">No. Faks: 06-661 0509</p>
                <p style="margin: 0;">Email: info@uniti.edu.my</p>
            </div>
        </div>

        <!-- Slip Title -->
        <div class="slip-title">
            SLIP PENDAFTARAN ASRAMA
        </div>

        <!-- Student Copy Section -->
        <div class="section-title">SALINAN PELAJAR</div>
        <table class="info-table">
            <tr>
                <td width="150">NAMA PELAJAR</td>
                <td width="5">:</td>
                <td width="380">{{ $data['student']->name }}</td>
                <td width="100">PROGRAM</td>
                <td width="5">:</td>
                <td>{{ $data['student']->program_code }}</td>
            </tr>
            <tr>
                <td>NO. KP / PASSPORT</td>
                <td>:</td>
                <td>{{ $data['student']->ic }}</td>
                <td>KEMASUKAN</td>
                <td>:</td>
                <td>{{ $data['student']->intake_name }}</td>
            </tr>
            <tr>
                <td>NO. MATRIKS</td>
                <td>:</td>
                <td>{{ $data['student']->no_matric }}</td>
                <td>SEMESTER SEMASA</td>
                <td>:</td>
                <td>{{ $data['student']->semester }}</td>
            </tr>
            <tr>
                <td>SESI SEMASA</td>
                <td>:</td>
                <td>{{ $data['student']->session_name }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <hr>

        <table class="info-table">
            <tr>
                <td width="150">BLOK</td>
                <td width="5">:</td>
                <td>{{ $data['hostel']->name ?? 'N/A' }} - {{ $data['hostel']->location ?? 'N/A' }}</td>
                <td width="100">NO. BILIK</td>
                <td width="5">:</td>
                <td>{{ $data['hostel']->no_unit ?? 'N/A' }}</td>
            </tr>
        </table>

        <p class="note">*Sila bawa resit pendaftaran ini semasa mendaftar asrama.</p>
        <p class="note">*Sila pastikan kad matrik anda bagi pengesahan semasa mendaftar asrama.</p>
        <p class="note">*Sebarang masalah berkaitan dengan asrama, sila hubungi pegawai asrama ditalian 06 661 0517.</p>

        <hr style="border: 1px dashed #000; margin: 30px 0;">

        <!-- Admin Copy Section -->
        <div class="header">
            <img src="{{ asset('assets/images/logo/Kolej-UNITI.png') }}" alt="Kolej UNITI Logo" class="logo">
            <div class="header-text">
                <p style="margin: 0; font-weight: bold;">Kolej UNITI Sdn Bhd (418694-K)</p>
                <p style="margin: 0;">Persiaran Uniti Village</p>
                <p style="margin: 0;">Tanjung Agas, Pasir Panjang, 71250, Port Dickson, Negeri Sembilan</p>
                <p style="margin: 0;">No. Telefon: 06-661 0519, 06-661 0520</p>
                <p style="margin: 0;">No. Faks: 06-661 0509</p>
                <p style="margin: 0;">Email: info@uniti.edu.my</p>
            </div>
        </div>

        <div class="slip-title">
            SLIP PENDAFTARAN ASRAMA
        </div>

        <div class="section-title">SALINAN PENTADBIRAN</div>
        <table class="info-table">
            <tr>
                <td width="150">NAMA PELAJAR</td>
                <td width="5">:</td>
                <td width="380">{{ $data['student']->name }}</td>
                <td width="100">PROGRAM</td>
                <td width="5">:</td>
                <td>{{ $data['student']->program_code }}</td>
            </tr>
            <tr>
                <td>NO. KP / PASSPORT</td>
                <td>:</td>
                <td>{{ $data['student']->ic }}</td>
                <td>KEMASUKAN</td>
                <td>:</td>
                <td>{{ $data['student']->intake_name }}</td>
            </tr>
            <tr>
                <td>NO. MATRIKS</td>
                <td>:</td>
                <td>{{ $data['student']->no_matric }}</td>
                <td>SEMESTER SEMASA</td>
                <td>:</td>
                <td>{{ $data['student']->semester }}</td>
            </tr>
            <tr>
                <td>SESI SEMASA</td>
                <td>:</td>
                <td>{{ $data['student']->session_name }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <hr>

        <table class="info-table">
            <tr>
                <td width="150">BLOK</td>
                <td width="5">:</td>
                <td>{{ $data['hostel']->name ?? 'N/A' }} - {{ $data['hostel']->location ?? 'N/A' }}</td>
                <td width="100">NO. BILIK</td>
                <td width="5">:</td>
                <td>{{ $data['hostel']->no_unit ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>TARIKH DAFTAR</td>
                <td>:</td>
                <td colspan="4">{{ isset($data['hostel']->entry_date) ? date('d/m/Y', strtotime($data['hostel']->entry_date)) : 'N/A' }}</td>
            </tr>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">TANDATANGAN PELAJAR</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">TANDATANGAN PEGAWAI ASRAMA</div>
            </div>
        </div>
    </div>

    <div class="print-button" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Print Slip</button>
    </div>

    <script>
        // Auto-print when the page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 