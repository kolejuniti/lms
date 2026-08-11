<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Keputusan Peperiksaan — {{ $data['program']->progcode ?? '' }} | Semester {{ $data['semester'] }}</title>
  <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">

  <style>
    /* ===== Reset ===== */
    * {
      margin: 0;
      padding: 0;
      border: 0;
      outline: 0;
      font-size: 10px; /* Reduced from 12px */
      vertical-align: baseline;
      background: transparent;
    }

    body {
      font-family: Arial, sans-serif;
      background: #fff;
      color: #000;
    }

    h2, h3, p, address {
      margin: 0;
      padding: 0;
      font-size: 9px; /* Reduced from 10px */
    }

    h1 {
      font-size: 16px; /* Reduced from 18px */
      font-weight: bold;
    }

    .b2 {
      font-weight: bold;
      font-size: 14px; /* Reduced from 16px */
    }

    .b3 {
      font-weight: bold;
      font-size: 9px; /* Reduced from 10px */
    }

    /* ===== A4 Page Setup ===== */
    @page {
      size: A4 portrait;
      margin: 5mm 5mm; /* Reduced from 10mm 12mm */
    }

    /* Each student block takes up only the space it needs */
    .student-block {
      width: 100%;
      box-sizing: border-box;
      padding: 4px 0 2px 0; /* Reduced padding */
      page-break-inside: avoid;
    }

    /* After every even-numbered student (2nd, 4th …) force a page break */
    .student-block.break-after {
      page-break-after: always;
    }

    /* Thin divider between 2 students on the same page */
    .student-divider {
      border-top: 1.5px solid #000;
      margin: 4px 0;
    }

    /* ===== Tables ===== */
    .custom-table,
    .custom-table th,
    .custom-table td {
      border: 1px solid black;
    }

    .custom-table {
      width: 100%;
      border-collapse: collapse;
    }

    .info-table td {
      padding: 0px 4px 0px 0; /* Reduced padding */
      font-size: 9px; /* Reduced from 10px */
    }

    .border-line {
      width: 100%;
      border-top: 1.5px solid black;
      margin: 4px 0;
    }

    .text-center { text-align: center; }
    .text-right  { text-align: right; }

    /* ===== Header (logo + address) ===== */
    .college-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 4px;
    }

    .college-header img {
      height: 38px; /* Reduced from 42px */
    }

    .college-header address .b3 {
      font-size: 8.5px; /* Reduced from 9px */
    }

    /* ===== No records message ===== */
    .no-records {
      text-align: center;
      padding: 40px;
      font-size: 16px;
      color: #555;
    }

    /* Screen only styles (hide when printing) */
    @media screen {
      body {
        background: #e0e0e0;
        padding: 10px;
      }

      .page-wrapper {
        background: #fff;
        max-width: 210mm;
        margin: 0 auto;
        padding: 10mm 12mm;
        box-shadow: 0 2px 12px rgba(0,0,0,0.18);
      }
    }

    @media print {
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

{{-- ===== Print Controls (screen only) ===== --}}
<div class="no-print" style="background:#1a1a2e; padding:12px 20px; display:flex; align-items:center; justify-content:space-between; color:#fff; position:sticky; top:0; z-index:999;">
  <div>
    <strong style="font-size:14px;">📋 Keputusan Peperiksaan — Cetakan</strong>
    <span style="margin-left:16px; font-size:12px; opacity:0.8;">
      Program: <b>{{ $data['program']->progcode ?? '-' }}</b> &nbsp;|&nbsp;
      Session: <b>{{ $data['session']->SessionName ?? '-' }}</b> &nbsp;|&nbsp;
      Semester: <b>{{ $data['semester'] }}</b> &nbsp;|&nbsp;
      Total Students: <b>{{ count($data['studentsData']) }}</b>
    </span>
  </div>
  <button onclick="window.print()"
    style="background:#0088ff; color:#fff; border:none; padding:8px 20px; border-radius:6px; font-size:13px; cursor:pointer; font-weight:bold;">
    🖨 Print / Save PDF
  </button>
</div>

<div class="page-wrapper">

@if(count($data['studentsData']) === 0)
  <div class="no-records">
    <p>⚠️ No examination records found for the selected criteria.</p>
    <p style="margin-top:8px; font-size:13px;">Program: <b>{{ $data['program']->progname ?? '-' }}</b> | Session: <b>{{ $data['session']->SessionName ?? '-' }}</b> | Semester: <b>{{ $data['semester'] }}</b></p>
  </div>
@else

  @foreach($data['studentsData'] as $index => $item)
    @php
      $student    = $item['student'];
      $subjects   = $item['subjects'];
      $position   = $index + 1;    // 1-based
      $isOdd      = ($position % 2 !== 0);
      $isEven     = ($position % 2 === 0);
      $isLast     = ($position === count($data['studentsData']));
    @endphp

    {{-- Divider between students 1&2, 3&4 etc. (before the 2nd of each pair) --}}
    @if($isEven)
      <div class="student-divider"></div>
    @endif

    {{-- Student result block --}}
    <div class="student-block {{ ($isEven || $isLast) ? 'break-after' : '' }}">

      {{-- College Header (shown on every block) --}}
      <div class="college-header">
        <img src="{{ asset('assets/images/logo/Kolej-UNITI.png') }}" alt="Kolej UNITI Logo">
        <address>
          <div class="b3">
            <strong>KOLEJ UNITI</strong><br>
            PERSIARAN UNITI VILLAGE, TANJUNG AGAS<br>
            71250, PORT DICKSON, NEGERI SEMBILAN.<br>
            <abbr title="Phone">Tel:</abbr> 06-649 0350 | <abbr title="Fax">Fax:</abbr> 06-661 0509<br>
            http://www.uniti.edu.my | <abbr title="Email">Email:</abbr> info@uniti.edu.my
          </div>
        </address>
      </div>

      {{-- Report Title --}}
      <div style="margin-bottom:3px;">
        <h1><b>PEJABAT PENDAFTAR BAHAGIAN AKADEMIK</b></h1>
      </div>
      <div style="margin-bottom:2px;">
        <div class="b2">KEPUTUSAN PEPERIKSAAN</div>
      </div>
      <div style="margin-bottom:3px;">
        <div class="b2">PROGRAM : {{ $student->program_code ?? '' }} - {{ $student->program_name ?? '' }}</div>
      </div>

      <div class="border-line"></div>

      {{-- Student Info (2-column) --}}
      <table style="width:100%; margin:3px 0;">
        <tr>
          <td style="width:50%; vertical-align:top;">
            <table class="info-table">
              <tr>
                <td style="padding-right:8px;">Nama</td>
                <td>:</td>
                <td style="padding-left:8px;">{{ $student->name }}</td>
              </tr>
              <tr>
                <td>No. Matriks</td>
                <td>:</td>
                <td style="padding-left:8px;">{{ $student->no_matric }}</td>
              </tr>
              <tr>
                <td>Sesi Kemasukan</td>
                <td>:</td>
                <td style="padding-left:8px;">{{ $student->intake_name }}</td>
              </tr>
            </table>
          </td>
          <td style="width:50%; vertical-align:top;">
            <table class="info-table">
              <tr>
                <td style="padding-right:8px;">No. KP / No. Passport</td>
                <td>:</td>
                <td style="padding-left:8px;">{{ $student->ic }}</td>
              </tr>
              <tr>
                <td>Semester</td>
                <td>:</td>
                <td style="padding-left:8px;">{{ $student->transcript_semester }}</td>
              </tr>
              <tr>
                <td>Sesi Semasa</td>
                <td>:</td>
                <td style="padding-left:8px;">{{ $student->exam_session_name }}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      {{-- Subject Results Table --}}
      <div style="margin-top:4px;">
        <table class="custom-table">
          <thead>
            <tr>
              <td class="text-center" style="width:4%; padding:2px;"><strong>BIL</strong></td>
              <td class="text-center" style="width:12%; padding:2px;"><strong>KOD KURSUS</strong></td>
              <td class="text-center" style="width:46%; padding:2px;"><strong>NAMA KURSUS</strong></td>
              <td class="text-center" style="width:8%; padding:2px;"><strong>GRED</strong></td>
              <td class="text-center" style="width:10%; padding:2px;"><strong>NILAI MATA</strong></td>
              <td class="text-center" style="width:8%; padding:2px;"><strong>KREDIT</strong></td>
            </tr>
          </thead>
          <tbody>
            @forelse($subjects as $key => $subject)
            <tr>
              <td class="text-center" style="padding:1px 2px;">{{ $key + 1 }}</td>
              <td class="text-center" style="padding:1px 4px;">{{ $subject->course_code }}</td>
              <td style="padding:1px 5px;">{{ $subject->course_name }}</td>
              <td class="text-center" style="padding:1px 2px;">{{ $subject->grade }}</td>
              <td class="text-center" style="padding:1px 2px;">{{ $subject->pointer }}</td>
              <td class="text-center" style="padding:1px 2px;">{{ $subject->credit }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center" style="padding:4px; color:#777;">Tiada kursus didaftarkan</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- GPA / CGPA Summary Table --}}
      <div style="margin-top:3px;">
        <table class="custom-table">
          <thead>
            <tr>
              <td style="padding:2px 4px;"><strong></strong></td>
              <td class="text-center" style="padding:2px;"><strong>KREDIT AMBIL</strong></td>
              <td class="text-center" style="padding:2px;"><strong>JUMLAH NILAI GRED</strong></td>
              <td class="text-center" style="width:12%; padding:2px;"><strong>PNG</strong></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" style="padding:1px 4px;">SEMESTER SEMASA</td>
              <td class="text-center" style="padding:1px;">{{ $student->passed_credit_s }}</td>
              <td class="text-center" style="padding:1px;">{{ $student->grade_pointer_s }}</td>
              <td class="text-center" style="padding:1px;">{{ $student->gpa }}</td>
            </tr>
            <tr>
              <td class="text-center" style="padding:1px 4px;">KESELURUHAN SEMESTER</td>
              <td class="text-center" style="padding:1px;">{{ $student->passed_credit_c }}</td>
              <td class="text-center" style="padding:1px;">{{ $student->grade_pointer_c }}</td>
              <td class="text-center" style="padding:1px;">{{ $student->cgpa }}</td>
            </tr>
            <tr>
              <td class="text-center" style="padding:1px 4px;">KEPUTUSAN</td>
              <td colspan="3" class="text-center" style="padding:1px;">
                {{ $student->result_status }}
                {{ ($student->count_credit_c >= $student->limit_credit && $student->status_name == 'TAMAT PENGAJIAN') ? '- Tamat Pengajian' : '' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <p class="text-center" style="margin-top:3px; margin-bottom:8px; font-size:9px;">
        * Penyata ini dicetak oleh komputer, oleh itu tandatangan Pendaftar tidak diperlukan.
      </p>

    </div>{{-- end .student-block --}}

  @endforeach

@endif

</div>{{-- end .page-wrapper --}}

<script>
  window.onload = function () {
    window.print();
  };
</script>

</body>
</html>
