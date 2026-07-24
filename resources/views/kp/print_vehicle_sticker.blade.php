<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Permohonan Daftar Kenderaan</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        .page-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: middle;
        }

        .no-border-table {
            border: none;
            margin-bottom: 15px;
        }
        .no-border-table th, .no-border-table td {
            border: none;
            padding: 2px 5px;
        }

        .section-title {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .logo-container {
            width: 60px;
            text-align: center;
        }
        .logo-container img {
            max-width: 50px;
            height: auto;
        }

        .header-title {
            font-weight: bold;
            font-size: 14px;
        }
        .header-subtitle {
            font-weight: bold;
            font-size: 12px;
        }

        .ref-info {
            font-size: 9px;
            text-align: right;
            vertical-align: top;
        }

        .checkbox-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            text-align: center;
            line-height: 12px;
            font-size: 10px;
            margin-right: 5px;
            vertical-align: middle;
        }

        .terms-text {
            font-size: 10.5px;
            line-height: 1.4;
            text-align: justify;
            margin-bottom: 10px;
        }

        .terms-list {
            margin: 0;
            padding-left: 20px;
            font-size: 10.5px;
        }
        .terms-list li {
            margin-bottom: 3px;
        }

        .signature-box {
            height: 60px;
        }

        .line-input {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 100%;
            min-height: 16px;
        }

        .w-30 { width: 30%; }
        .w-20 { width: 20%; }
        .w-50 { width: 50%; }
        .w-70 { width: 70%; }
        .w-100 { width: 100%; }

        @media print {
            body {
                padding: 0;
            }
            .page-container {
                padding: 0;
            }
            .section-title {
                background-color: #d9d9d9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        
        <!-- HEADER -->
        <table class="no-border-table">
            <tr>
                <td class="logo-container">
                    <!-- Placeholder for logo. Add your logo path here -->
                    <img src="https://learn.uniticms.edu.my/assets/images/logo/Kolej-UNITI.png" alt="Logo" style="width: 50px; height: 50px; border: 1px solid #ccc; display: inline-block;">
                </td>
                <td>
                    <div class="header-title">BORANG PERMOHONAN DAFTAR KENDERAAN PELAJAR & STAF</div>
                    <div class="header-subtitle">BAHAGIAN HAL EHWAL PELAJAR (HEP) KOLEJ UNITI PORT DICKSON (KUPD)</div>
                </td>
                <td class="ref-info">
                    <strong>NO. RUJUKAN</strong> &nbsp;: KUSB/HEP/KENDERAAN-2024/<br>
                    <strong>TARIKH KEMASKINI</strong> : 01.10.2024
                </td>
            </tr>
        </table>
        
        <div style="border-top: 3px solid #000; margin-bottom: 10px;"></div>

        <!-- BUTIRAN PEMOHON -->
        <table>
            <tr>
                <td colspan="4" class="section-title">BUTIRAN PEMOHON</td>
            </tr>
            <tr>
                <td class="w-20"><strong>Nama Pemohon</strong></td>
                <td colspan="3">: <strong style="text-transform: uppercase;">{{ $student->name }}</strong></td>
            </tr>
            <tr>
                <td><strong>No. Kad Pengenalan</strong></td>
                <td class="w-30">: <strong>{{ $student->ic }}</strong></td>
                <td class="w-20"><strong>No. Matrik / Staf</strong></td>
                <td class="w-30">: <strong>{{ $student->no_staf ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td><strong>Program Diploma</strong></td>
                <td>: <strong>{{ $student->program_name ?? '-' }}</strong></td>
                <td><strong>Fakulti</strong></td>
                <td>: 
                    @if($student->facultycode == 'FPIH')
                        <strong>FPIH</strong> &nbsp;/&nbsp; <span style="text-decoration: line-through;">FPPM</span> &nbsp;/&nbsp; <span style="text-decoration: line-through;">FTK</span>
                    @elseif($student->facultycode == 'FPPM')
                        <span style="text-decoration: line-through;">FPIH</span> &nbsp;/&nbsp; <strong>FPPM</strong> &nbsp;/&nbsp; <span style="text-decoration: line-through;">FTK</span>
                    @elseif($student->facultycode == 'FTK')
                        <span style="text-decoration: line-through;">FPIH</span> &nbsp;/&nbsp; <span style="text-decoration: line-through;">FPPM</span> &nbsp;/&nbsp; <strong>FTK</strong>
                    @else
                        <strong>{{ $student->facultycode ?? '-' }}</strong>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Semester Semasa</strong></td>
                <td>: <strong>-</strong></td>
                <td><strong>No. Telefon</strong></td>
                <td>: <strong>{{ $student->no_tel ?? '-' }}</strong></td>
            </tr>
        </table>

        <!-- BUTIRAN KENDERAAN & STATUS PEMILIKAN -->
        <table>
            <tr>
                <td colspan="4" class="section-title">BUTIRAN KENDERAAN & STATUS PEMILIKAN</td>
            </tr>
            <tr>
                <td class="w-20"><strong>No. Kenderaan</strong></td>
                <td class="w-30">: <strong>{{ $vehicle->plate_number }}</strong></td>
                <td class="w-20"><strong>Model</strong></td>
                <td class="w-30">: <strong>{{ $vehicle->brand }} - {{ $vehicle->model }}</strong></td>
            </tr>
            <tr>
                <td><strong>Warna Kenderaan</strong></td>
                <td>: <strong>{{ $vehicle->color }}</strong></td>
                <td><strong>Jenis Kenderaan</strong></td>
                <td>: 
                    @if(strtoupper($vehicle->type) == 'MOTORSIKAL' || strtoupper($vehicle->type) == 'MOTOR')
                        <strong>2 RODA</strong> &nbsp;/&nbsp; <span style="text-decoration: line-through;">4 RODA</span>
                    @elseif(strtoupper($vehicle->type) == 'KERETA')
                        <span style="text-decoration: line-through;">2 RODA</span> &nbsp;/&nbsp; <strong>4 RODA</strong>
                    @else
                        2 RODA &nbsp;/&nbsp; 4 RODA
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Status Pemilikan</strong></td>
                <td>: PEMILIK &nbsp;/&nbsp; BUKAN PEMILIK</td>
                <td><strong>Hubungan dengan Pemilik</strong></td>
                <td>:</span></td>
            </tr>
            <tr>
                <td><strong>Nama Pemilik</strong></td>
                <td colspan="3">:</td>
            </tr>
            <tr>
                <td><strong>No. Telefon Pemilik</strong></td>
                <td>:</td>
                <td><strong>No. Kad Pengenalan Pemilik</strong></td>
                <td>:</td>
            </tr>
        </table>

        <!-- SYARAT & AKUJANJI -->
        <table>
            <tr>
                <td class="section-title">SYARAT & AKUJANJI PENDAFTARAN KENDERAAN</td>
            </tr>
            <tr>
                <td style="padding: 10px;">
                    <div class="terms-text">
                        Saya, pemohon seperti yang dinyatakan di atas, dengan ini mengakui dan mengesahkan, untuk kegunaan harian saya dalam kawasan Kolej UNITI Port Dickson (KUPD), bahawa (sila tandakan (√) pada yang berkenaan) :-
                    </div>
                    
                    <div style="margin-bottom: 15px; margin-left: 20px;">
                        <span style="display: inline-block; width: 30%;">
                            [&nbsp;&nbsp;&nbsp;&nbsp;] Kenderaan ini adalah milik saya.
                        </span>
                        <span style="display: inline-block; width: 60%;">
                            [&nbsp;&nbsp;&nbsp;&nbsp;] Saya telah diberi kebenaran oleh pemilik kenderaan ini untuk menggunakannya.
                        </span>
                    </div>

                    <div class="terms-text">
                        Dan, saya dengan ini mengaku dan berjanji bahawasanya:-
                    </div>

                    <ol class="terms-list">
                        <li>Saya faham bahawa permohonan ini hanya untuk staf dan pelajar KUPD yang aktif sahaja, dan hanya permohonan lengkap sahaja yang akan diproses.</li>
                        <li>Saya akan tampalkan dan pamerkan pelekat kenderaan pada tempat yang ditetapkan, iaitu:-
                            <ol type="a" style="margin-top: 3px;">
                                <li>Bagi kenderaan jenis kereta, pelekat tersebut hendaklah ditampal dan dipamerkan di cermin depan, sebelah kiri atas.</li>
                                <li>Bagi kenderaan jenis motorsikal, pelekat tersebut hendaklah ditampal dan dipamerkan di sebelah kiri motorsikal menghadap depan.</li>
                            </ol>
                        </li>
                        <li>Saya akan menjaga pelekat kenderaan, tidak akan mengubah rupa dan bentuk pelekat kenderaan, dan tidak akan menanggalkannya selepas ditampal atas tanpa sebarang alasan kecuali ia rosak atau tamat tempoh sah.</li>
                        <li>Saya tidak akan menyalahgunakan pelekat kenderaan bagi kegunaan kereta sewa, kereta sapu, atau teksi. Saya juga tidak akan meminjamkan atau menjual pelekat kenderaan tersebut kepada pelajar atau staf lain, juga untuk kegunaan individu yang tiada kaitan atau tiada urusan dengan KUPD;</li>
                        <li>Saya akan patuhi segala aturan dan ketetapan tempat meletak kenderaan dan parkir di tempat yang dibenarkan dalam kawasan KUPD</li>
                        <li>Saya akan patuhi segala peraturan lalulintas dan lain-lain peraturan keselamatan yang dikuatkuasa oleh KUPD.</li>
                        <li>Saya faham bahawa saya boleh diambil tindakan tatatertib sekiranya saya mengingkari sebarang polisi dan undang-undang berkenaan pendaftaraan kenderaan ini.</li>
                    </ol>
                </td>
            </tr>
        </table>

        <!-- SIGNATURE AND CHECKLIST -->
        <table class="no-border-table" style="margin-bottom: 0;">
            <tr>
                <!-- Applicant Signature Box -->
                <td style="width: 48%; padding: 0; vertical-align: top;">
                    <table style="border: solid;">
                        <tr style="height: 60px;">
                            <td style="width: 20%; font-weight: bold;">Tandatangan</td>
                            <td style="width: 1%;">:</td>
                            <td style="width: 79%;"></td>
                        </tr>
                        <tr style="height: 60px;">
                            <td style="font-weight: bold;">Nama Penuh</td>
                            <td>:</td>
                            <td>{{ $student->name }}</td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="font-weight: bold;">Tarikh</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($vehicle->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </td>
                
                <td style="width: 4%;"></td>

                <!-- Office Checklist Box -->
                <td style="width: 48%; padding: 0; vertical-align: top;">
                    <table style="border: solid;">
                        <tr style="height: 30px;">
                            <td rowspan="5" style="width: 35%; align-items: center; vertical-align: middle; text-align: center; font-weight: bold; border: solid 1px;">
                                SENARAI DOKUMEN<br>LAMPIRAN<br>(KEGUNAAN PEJABAT)<br><br>
                                ADA (√) / TIADA (x)<br>
                                (POTONG YANG<br>TAK BERKENAAN)
                            </td>
                            <td style="width: 1%; border: solid 1px;">1.</td>
                            <td style="width: 34%; border: solid 1px;">Salinan Kad Staf Pemohon</td>
                            <td style="width: 10%; border: solid 1px;"></td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="border: solid 1px;">2.</td>
                            <td style="border: solid 1px;">Salinan Kad Pengenalan Pemohon</td>
                            <td style="border: solid 1px;"></td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="border: solid 1px;">3.</td>
                            <td style="border: solid 1px;">Salinan Cukai Jalan Kenderaan</td>
                            <td style="border: solid 1px;"></td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="border: solid 1px;">4.</td>
                            <td style="border: solid 1px;">Surat Kebenaran dari Pemilik</td>
                            <td style="border: solid 1px;"></td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="border: solid 1px;">5.</td>
                            <td style="border: solid 1px;">Salinan Kad Pengenalan Pemilik</td>
                            <td style="border: solid 1px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- UNTUK KEGUNAAN PEJABAT -->
        <table>
            <tr>
                <td colspan="2" class="section-title">UNTUK KEGUNAAN PEJABAT</td>
            </tr>
            <tr>
                <td class="w-50" style="text-align: center; font-weight: bold; background-color: #f2f2f2; -webkit-print-color-adjust: exact; print-color-adjust: exact;">BAHAGIAN HAL EHWAL PELAJAR</td>
                <td class="w-50" style="text-align: center; font-weight: bold; background-color: #f2f2f2; -webkit-print-color-adjust: exact; print-color-adjust: exact;">UNIT PENTADBIRAN & KEWANGAN</td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none;"><strong>Status Dokumen Lengkap :</strong> LENGKAP &nbsp;/&nbsp; TIDAK LENGKAP</td>
                <td style="border-bottom: none; border-top: none;"><strong>No. Siri Pelekat Kenderaan :</strong></td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none;"><strong>Status Pemilikan :</strong> PEMILIK &nbsp;/&nbsp; BUKAN PEMILIK</td>
                <td style="border-bottom: none; border-top: none;"><strong>Jumlah Bayaran Diterima :</strong></td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none;"><strong>Tarikh Sahkan Dokumen :</strong></td>
                <td style="border-bottom: none; border-top: none;"><strong>Tarikh Daftar Masuk Sistem :</strong></td>
            </tr>
            <tr>
                <td style="padding: 10px; vertical-align: top; height: 100px;">                    
                    <table class="no-border-table" style="margin-top: 15px;">
                        <tr>
                            <td style="width: 15%; vertical-align: top;"><strong>Ulasan</strong></td>
                            <td style="width: 35%; vertical-align: top;">
                                <span class="line-input" style="width: 90%; margin-bottom: 10px;"></span><br>
                                <span class="line-input" style="width: 90%; margin-bottom: 10px;"></span><br>
                                <span class="line-input" style="width: 90%;"></span>
                            </td>
                            <td style="width: 50%; vertical-align: top; border-left: 1px solid #000; padding-left: 10px;">
                                <strong>Tandatangan & Cop :</strong><br><br><br><br>
                                <strong>Tarikh :</strong> <span class="line-input" style="width: 60%;"></span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="padding: 10px; vertical-align: top; height: 100px;">                    
                    <table class="no-border-table" style="margin-top: 15px;">
                        <tr>
                            <td style="width: 15%; vertical-align: top;"><strong>Ulasan</strong></td>
                            <td style="width: 35%; vertical-align: top;">
                                <span class="line-input" style="width: 90%; margin-bottom: 10px;"></span><br>
                                <span class="line-input" style="width: 90%; margin-bottom: 10px;"></span><br>
                                <span class="line-input" style="width: 90%;"></span>
                            </td>
                            <td style="width: 50%; vertical-align: top; border-left: 1px solid #000; padding-left: 10px;">
                                <strong>Tandatangan & Cop :</strong><br><br><br><br>
                                <strong>Tarikh :</strong> <span class="line-input" style="width: 60%;"></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Print immediately on load (optional) -->
    <!--
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    -->
</body>
</html>
