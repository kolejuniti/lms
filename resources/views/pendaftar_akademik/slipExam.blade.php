
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
      size: A4 portrait;
      margin: 50;
   }
   @media print {
      body {
         margin: 10;
         padding: 0;
      }
      .container {
         width: 100%;
         height: 100%;
         margin: 0;
         padding: 0;
         transform: scale(1);
         transform-origin: top left;
      }
      hr {
         border-top: 1px solid #000; /* make sure the color is dark enough */
      }
   }

   * {
      margin: 0;
      padding: 0;
      border: 0;
      outline: 0;
      vertical-align: baseline;
      background: transparent;
      font-size: 9px; /* reduce font-size */
   }
   h2, h3, p {
      margin: 0;
      padding: 0;
      border: 0;
      outline: 0;
      vertical-align: baseline;
      background: transparent;
      font-size: 9px; /* reduce font-size */
   }
   .container {
      width: 100%;
      height: 100%;
   }
   table {
      width: 100%; /* or a fixed width */
      table-layout: fixed;
      border-collapse: collapse; /* Ensure borders collapse */
   }
   td, th {
      padding: 2px; /* Reduce padding */
   }
   thead th,tbody, tfoot td {
      border-bottom: 2px solid #000; /* Add thicker border to header and footer */
   }
   .d-flex {
      display: flex;
      flex-wrap: wrap;
   }
   .form-group {
      margin-bottom: 5px; /* Reduce space between form groups */
   }
</style>



 </head>
 
 
 
<body>
<div class="container">
      <!-- BEGIN INVOICE -->
   <div class="col-12">
      <div class="grid invoice">
         <div class="grid-body">
            <div class="invoice-title">
               <div class="row mb-2">
                  <div class="col-12 d-flex">
                     <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="" height="50">
                     <address>
                        <strong>KOLEJ UNITI</strong><br>
                        PERSIARAN UNITI VILLAGE, TANJUNG AGAS<br>
                        71250, PORT DICKSON, NEGERI SEMBILAN.<br>
                        <abbr title="Phone">Tel:</abbr> 06-649 0350 | <abbr title="Phone">Fax:</abbr> 06-661 0509<br>
                        http://www.uniti.edu.my | <abbr title="Email">Email:</abbr> info@uniti.edu.my
                     </address>
                  </div>
               </div>
               {{-- <div class="row">
                  <div class="col-12">
                     <h2>Resit<br>
                     <span class="small">No. Resit : {{ $data['payment']->ref_no }}</span></h2>
                  </div>
               </div> --}}
            </div>
            <hr>
            <div class="row">
               <div class="col-md-12 text-center" style="font-size: 20px;">
                  <h1>
                     <b>SLIP MENDUDUKI PEPERIKSAAN</b>
                  </h1>
               </div>
               <div class="col-md-12 d-flex p-2">
                  <div class="col-md-6" style="margin-right: 10px">
                     <div class="form-group">
                           <p>Name &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                           <p>Program &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                           <p>No. KP / No. Passport &thinsp;&thinsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                           <p>No. Matriks &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                           <p>Status &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                           <p>Sesi Kemasukan &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake_name }}</p>
                           <p>Sesi Semasa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->session_name }}</p>
                           <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->semester }}</p>
                     </div>
                  </div>
               </div>

               <div class="col-md-12">
                  <table>
                     <thead>
                        <tr class="line">
                           <th class="text-center"><strong>KOD</strong></th>
                           <th class="text-center"><strong>NAMA SUBJEK</strong></th>
                           <th class="text-center"><strong>KREDIT</strong></th>
                           <th class="text-center"><strong>TARAF</strong></th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $total_level = 0;
                        @endphp
                        @foreach ($data['course'] as $key => $crs)
                        <tr>
                           <td>{{ $crs->course_code }}</td>
                           <td>{{ $crs->course_name }}</td>
                           <td class="text-center">{{ $crs->course_credit }}</td>
                           <td class="text-center">{{ $crs->level }}</td>
                        </tr>
                        @php
                        $total_level += $crs->course_credit;
                        @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <tr>
                           <td class="text-center">
                              JUMLAH :
                           </td>
                           <td></td>
                           <td class="text-center">
                              {{ $total_level }}
                           </td>
                           <td></td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
               <div class="col-md-12 text-center">
                  <p>
                     Cetakan Komputer. Tiada Tandatangan Diperlukan
                  </p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 text-right identity">
                  {{-- <p>Received By :<br><strong>{{ $data['staff']->name }}</strong></p> --}}
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- END INVOICE -->
   </div>
</body>
<script type="text/javascript">

window.onload = function() {
      var contentHeight = document.querySelector('.container').offsetHeight;
      var contentWidth = document.querySelector('.container').offsetWidth;
      var pageHeight = 1122; // height of an A4 page in pixels
      var pageWidth = 2000; // width of an A4 page in pixels
      var scaleFactorHeight = pageHeight / contentHeight;
      var scaleFactorWidth = pageWidth / contentWidth;
      var scaleFactor = Math.min(scaleFactorHeight, scaleFactorWidth);

      var container = document.querySelector('.container');
      container.style.transform = 'scale(' + scaleFactor + ')';
      container.style.transformOrigin = 'top left';
      container.style.width = (pageWidth / scaleFactor) + 'px';
      container.style.height = (pageHeight / scaleFactor) + 'px';
   };

   $(document).ready(function () {
       window.print();
   });
   
   </script>