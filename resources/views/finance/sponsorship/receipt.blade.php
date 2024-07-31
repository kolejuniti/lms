
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
         size: A4 potrait; 
         margin: 1cm; /* reduce margin */
      }
      @media print {
         .container {
            transform: scale(1.7);
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
         font-size: 100%;
         vertical-align: baseline;
         background: transparent;
         font-size: 9px; /* reduce font-size */
      }
      h2,h3,p {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         font-size: 100%;
         vertical-align: baseline;
         background: transparent;
         font-size: 9px; /* reduce font-size */
      }
      .container {
         transform: scale(0.8); /* scale down everything */
      }
      table {
         width: 100%; /* or a fixed width */
         table-layout: fixed;
      }
      td, th {
         width: 50%; /* Adjust the width as needed */
         padding: 2px; /* Reduce padding */
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
               <div class="row">
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
               <br>
               {{-- <div class="row">
                  <div class="col-12">
                     @if (isset($invois))
                     <h2>Invoice<br>
                     @else
                     <h2>Resit<br>
                     @endif
                     <span class="small">No. Resit : {{ $data['payment']->ref_no }}</span></h2>
                  </div>
               </div> --}}
            </div>
            <hr>
            <div class="row">
               <div class="d-flex p-2">
                  <div class="col-md-6" style="margin-right: 10px">
                     <div class="form-group">
                           <p>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                           <p>No. Resit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ ($invois) ? $data['payment']->ref_no : $data['payment']->id }}</p>
                           <p>No. KP / No. Passport &thinsp;&thinsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                           <p>Sesi Kemasukan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake }}</p>
                           <p>Sesi Semasa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['payment']->session }}</p>
                           <p>No. Matriks &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                           <p>Program &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['payment']->program }}</p>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                           <p>Tarikh &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['date'] }}</p>
                           {{-- <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p> --}}
                           <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['payment']->semester_id }}</p>
                     </div>
                  </div>
               </div>

               @if(!isset($invois))
               <div class="col-md-12">
                  <h3>KAEDAH</h3>
                  <table>
                     <thead>
                        <tr class="line">
                           <td style="width: 10px;"><strong>#</strong></td>
                           <td class="text-center; width: 50px;"><strong>KAEDAH BAYARAN</strong></td>
                           <td class="text-center; width: 50px;"><strong>BANK</strong></td>
                           <td class="text-center; width: 50px;"><strong>NO. DOKUMEN</strong></td>
                           <td class="text-center; width: 50px;"><strong>AMAUN</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($data['method'] as $key => $dtl)
                        <tr>
                           <td>{{ $key+1 }}</td>
                           <td>{{ $dtl->method }}</td>
                           <td>{{ $dtl->bank }}</td>
                           @if ($dtl->no_document == null)
                           <td>TIADA</td>
                           @else
                           <td>{{ $dtl->no_document }}</td>
                           @endif
                           <td>{{ number_format($data['total'], 2, '.', ',') }}</td>
                        </tr>
                        @endforeach
                        <tr>
                           <td colspan="3">
                           </td><td class="text-center; width: 50px;"><strong>Jumlah :</strong></td>
                           <td class="text-center; width: 50px;"><strong>{{ number_format($data['total'], 2, '.', ',') }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               @endif

               <div class="col-md-12">
                  <h3>TUNTUTAN</h3>
                  {{-- class="table table-striped" --}}
                  <table>
                     <thead>
                        <tr class="line">
                           <td style="width: 10px;"><strong>#</strong></td>
                           <td class="text-center; width: 50px;"><strong>MAKLUMAT TUNTUTAN</strong></td>
                           <td class="text-center; width: 50px;"><strong>SEMESTER</strong></td>
                           <td class="text-center; width: 50px;"><strong>AMAUN</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                           $count = 0;
                        @endphp

                        @foreach ($data['detail'] as $keys => $dtl)
                        @if ($dtl->amount != 0)
                        <tr>
                           <td>{{ $count+1 }}</td>
                           <td>{{ $dtl->name }}</td>
                           <td>{{ $dtl->groupid }}</td>
                           <td>{{ number_format($dtl->total_amount, 2, '.', ',') }}</td>
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                           <td colspan="2">
                           </td><td class="text-center; width: 50px;"><strong>Jumlah Keseluruhan :</strong></td>
                           <td class="text-center; width: 50px;"><strong>{{ number_format($data['total'], 2, '.', ',') }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 text-right identity">
                  <p>Received By :<br><strong>{{ $data['staff']->name }}</strong></p>
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