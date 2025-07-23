
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>EduHub - @yield('title')</title>
   <!-- Vendors Style-->
	<link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
  <!-- Style-->  
  <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
  {{-- <link rel="stylesheet" media="screen, print" href="{{ asset('assets/src/css/datagrid/datatables/datatables.bundle.css') }}"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('assets/assets/vendor_components/datatable/datatables.css') }}"> --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/css-skeletons@1.0.3/css/css-skeletons.min.css"/> --}}
  <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <style>
      @page {
         size: A4 ; 
       
      }
      
      * {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         font-size: 100%;
         vertical-align: baseline;
         background: transparent;
         font-size: 12px; /* reduce font-size */
      }
      h2,h3,p {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         font-size: 100%;
         vertical-align: baseline;
         background: transparent;
         font-size: 10px; /* reduce font-size */
      }
      h1 {
         font-size: 18px; /* increase font-size */
      }
      b {
         font-weight: bold;
         font-size: 18px; /* increase font-size */
      }

      .b2 {
         font-weight: bold;
         font-size: 16px; /* increase font-size */
      }

      .b3 {
         font-weight: bold;
         font-size: 10px; /* increase font-size */
      }

      .container {
         transform: scale(1.0); /* scale down everything */
      }
      .container table{
         transform: scaleY(1.0); /* Scale vertically down */
         transform-origin: top left;
         margin: 0;
         padding: 0;
            }

      .container table + table {
         margin-top: 5px; /* Adjust this value to reduce the gap */
      }
   
      .form-group {
            page-break-inside: avoid;
      }
      .custom-table, .custom-table th, .custom-table td {
            border: 1px solid black;
      }

      .custom-table {
            width: 100%;
            border-collapse: collapse;
      }

      /* Use border instead of background */
      .border-line {
            width: 100%;
            border-top: 1px solid black; /* Border as the line */
            margin: 0px 0; /* Space around the line */
      }
   </style>

 </head>
 
 
 
<body>
      <!-- BEGIN INVOICE -->
   <div class="col-12">
      <div class="grid invoice">
         <div class="grid-body">
            <div class="invoice-title">
               {{-- @if(!isset(request()->std)) --}}
               <div class="row">
                  <div class="col-12 d-flex">
                     <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="" height="50">
                     <address>
                        <div class="b3">
                        <strong>KOLEJ UNITI</strong><br>
                        PERSIARAN UNITI VILLAGE, TANJUNG AGAS<br>
                        71250, PORT DICKSON, NEGERI SEMBILAN.<br>
                        <abbr title="Phone">Tel:</abbr> 06-649 0350 | <abbr title="Phone">Fax:</abbr> 06-661 0509<br>
                        http://www.uniti.edu.my | <abbr title="Email">Email:</abbr> info@uniti.edu.my
                        </div>
                     </address>
                  </div>
               </div>
               <br>
               {{-- @endif --}}
               {{-- <div class="row">
                  <div class="col-12">
                     <h2>Resit<br>
                     <span class="small">No. Resit : {{ $data['payment']->ref_no }}</span></h2>
                  </div>
               </div> --}}
            </div>
            <div class="row">
               {{-- <div class="col-md-12 d-flex p-2">
                  <div class="col-md-6" style="margin-right: 10px">
                     <div class="form-group">
                           <p>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                           <p>No. KP / No. Passport &thinsp;&thinsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                           <p>Sesi Kemasukan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake }}</p>
                           <p>Sesi Semasa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['transcript']->session }}</p>
                           <p>No. Matriks &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                           <p>Program &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                           <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                           <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['transcript']->semester }}</p>
                     </div>
                  </div>
               </div> --}}

               <div class="col-12">
                  <div class="row">
                     <div class="col-12">
                        <h1><b>PEJABAT PENDAFTAR BAHAGIAN AKADEMIK</b></h1>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-12">
                           <h3><div class="b2">KEPUTUSAN PEPERIKSAAN</div></h3>
                     </div>
                  </div>
                  <div class="row mt-2">
                     <div class="col-12">
                           <h3><div class="b2">PROGRAM : {{ $data['student']->code }}-{{ $data['student']->program }}</div></h3>
                     </div>
                  </div>
               </div>
               <!-- Black Line Divider using Border -->
               <div class="border-line mt-2"></div>

               <div class="col-6 mb-1 mt-1">  
                  <div style="border: 1px solid white; padding: 10px;">
                      <table>
                          <tr>
                              <td style="padding-right: 10px;">Nama</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['student']->name }}</td>
                          </tr>
                          <tr>
                              <td style="padding-right: 10px;">No. Matriks</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['student']->no_matric }}</td>
                          </tr>
                          <tr>
                              <td style="padding-right: 10px;">Sesi Kemasukan</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['student']->intake }}</td>
                          </tr>
                          {{-- <tr>
                              <td style="padding-right: 10px;">Status</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['student']->status }}</td>
                          </tr> --}}
                          {{-- <tr>
                              <td style="padding-right: 10px;">Program</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['student']->program }}</td>
                          </tr> --}}
                      </table>
                  </div> 
               </div>
               <div class="col-6 mb-1 mt-1">  
                  <div style="border: 1px solid white; padding: 10px;">
                      <table>
                          <tr>
                              <td style="padding-right: 10px;">No. KP / No. Passport</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['student']->ic }}</td>
                          </tr>
                          <tr>
                              <td style="padding-right: 10px;">Semester</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['transcript']->semester }}</td>
                          </tr>
                          <tr>
                              <td style="padding-right: 10px;">Sesi Semasa</td>
                              <td>:</td>
                              <td style="padding-left: 10px;">{{ $data['transcript']->session }}</td>
                          </tr>
                       </tr>
                      </table>
                  </div> 
               </div>

               <div class="col-md-12">
                  <table class="custom-table">
                     <thead>
                        <tr class="line">
                           <td class="text-center" style="width: 2%"><strong>BIL</strong></td>
                           <td class="text-center" style="width: 5%"><strong>KOD KURSUS</strong></td>
                           <td class="text-center" style="width: 20%"><strong>NAMA KURSUS</strong></td>
                           <td class="text-center" style="width: 5%"><strong>GRED</strong></td>
                           <td class="text-center" style="width: 5%"><strong>NILAI MATA</strong></td>
                           <td class="text-center" style="width: 5%"><strong>KREDIT</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                     @foreach($data['subject'] as $key => $subject)
                     <tr>
                        <td class="text-center">
                           {{ $key + 1 }}
                        </td>
                        <td class="text-center">
                           {{ $subject->course_code }}
                        </td>
                        <td style="padding-left: 5px;">
                           {{ $subject->course_name }}
                        </td>
                        <td class="text-center">
                           {{ $subject->grade }}
                        </td>
                        <td class="text-center">
                           {{ $subject->pointer }}
                        </td>
                        <td class="text-center">
                           {{ $subject->credit }}
                        </td>
                     </tr>
                     @endforeach
                     </tbody>
                  </table>
               </div>

               <div class="col-md-12 mt-10">
                  <table class="custom-table">
                     <thead>
                        <tr>
                           <td><strong></strong></td>
                           <td class="text-center"><strong>KREDIT AMBIL</strong></td>
                           <td class="text-center"><strong>JUMLAH NILAI GRED</strong></td>
                           <td class="text-center" style="width: 12.4%"><strong>PNG</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                       <tr>
                           <td class="text-center">
                              SEMESTER SEMASA
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->passed_credit_s }}
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->grade_pointer_s }}
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->gpa }}
                           </td>
                       </tr>
                       <tr>
                           <td class="text-center">
                              KESELURUHAN SEMESTER
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->passed_credit_c }}
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->grade_pointer_c }}
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->cgpa }}
                           </td>
                        </tr>
                        <tr>
                           <td class="text-center">
                              KEPUTUSAN
                           </td>
                           <td colspan="3" class="text-center">
                              {{ $data['transcript']->transcript_status_id }} {{ ($data['transcript']->count_credit_c >= $data['student']->limit_credit && $data['student']->status == 'TAMAT PENGAJIAN') ? '- Tamat Pengajian' : '' }}
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>

               <p class="text-center mt-2">* Penyata ini dicetak olek komputer, oleh itu tandatangan Pendaftar tidak diperlukan.</p>
            </div>
         </div>
      </div>
   </div>
</body>
<script type="text/javascript">

   window.onload = function() {
      var contentHeight = document.querySelector('.container').offsetHeight;
      var contentWidth = document.querySelector('.container').offsetWidth;
      var pageHeight = 1122; // height of an A4 page in pixels
      var pageWidth = 1300; // width of an A4 page in pixels
      var scaleFactorHeight = pageHeight / contentHeight;
      var scaleFactorWidth = pageWidth / contentWidth;
      var scaleFactor = Math.min(scaleFactorHeight, scaleFactorWidth);
      document.querySelector('.container').style.transform = 'scale(' + scaleFactor + ')';
      document.querySelector('.container').style.transformOrigin = 'top left';
   };

   $(document).ready(function () {
       window.print();
   });
   
   </script>