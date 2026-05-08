
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
         size: 21cm 14cm landscape; 
         margin: 0.5cm;
      }
      @media print {
         * {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            page-break-after: avoid;
         }
         body {
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
         }
         .container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
            page-break-after: avoid;
         }
         .grid, .invoice, .grid-body {
            page-break-after: avoid;
         }
         hr {
            border-top: 1px solid #000;
         }
      }

      * {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 12px;
         font-family: 'Courier New', Courier, monospace;
         table-layout: fixed;
      }
      h2,h3,p {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 12px;
         font-family: 'Courier New', Courier, monospace;
         font-weight: normal;
      }
      h3 {
         font-size: 13px;
         font-weight: bold;
         margin: 5px 0 3px 0;
      }
      body {
         margin: 0;
         padding: 0;
         text-align: center;
      }
      .container {
         width: 90%;
         margin: 0 auto;
         padding: 0;
         display: inline-block;
      }
      table {
         width: 100%;
         table-layout: fixed;
         font-family: 'Courier New', Courier, monospace;
         border-collapse: collapse;
      }
      td, th {
         padding: 3px 4px;
         font-family: 'Courier New', Courier, monospace;
         font-size: 12px;
         line-height: 1.3;
      }
      address {
         font-family: 'Courier New', Courier, monospace;
         font-size: 11px;
         line-height: 1.4;
         font-style: normal;
         text-align: left;
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
                     <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="" height="80">
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
               <div class="col-md-12 p-2">
                  <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                     <!-- Row 1: Name -->
                     <tr>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>NAMA</strong></td>
                        <td colspan="3" style="width: 75%; padding: 2px 5px;">{{ $data['student']->name }}</td>
                     </tr>
                     <!-- Row 2: No. KP / No. Passport and No. TIN -->
                     <tr>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>NO. KP / NO. PASSPORT</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['student']->ic }}</td>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>NO. TIN</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['student']->tin_number }}</td>
                     </tr>
                     <!-- Row 3: Program and Sesi Kemasukan -->
                     <tr>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>PROGRAM</strong></td>
                        <td colspan="3" style="width: 75%; padding: 2px 5px;">{{ $data['payment']->program }}</td>
                     </tr>
                     <tr>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>SESI KEMASUKAN</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['student']->intake }}</td>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>NO. MATRIKS</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['student']->no_matric }}</td>
                     </tr>
                     <!-- Row 4: Sesi Semasa and Semester -->
                     <tr>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>SESI SEMASA</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['payment']->session }}</td>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>SEMESTER</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['payment']->semester_id }}</td>
                     </tr>
                     <!-- Row 5: Tarikh and No. Resit -->
                     <tr>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>TARIKH</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['date'] }}</td>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>NO. RESIT</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['payment']->ref_no }}</td>
                     </tr>
                  </table>
               </div>

               <div class="col-md-12">
                  {{-- <h3>KAEDAH</h3> --}}
                  <table class="table table-bordered">
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
                        @php
                        $sum = 0;
                        @endphp
                        @foreach ($data['method'] as $key => $dtl)
                        <tr>
                           <td style="width: 10px;">{{ $key+1 }}</td>
                           <td>{{ $dtl->method }}</td>
                           <td>{{ $dtl->bank }}</td>
                           @if ($dtl->no_document == null)
                           <td>TIADA</td>
                           @else
                           <td >{{ $dtl->no_document }}</td>
                           @endif
                           <td>RM{{ number_format($dtl->amount, 2, '.', ',') }}</td>
                           @php
                           $sum += $dtl->amount;
                           @endphp
                        </tr>
                        @endforeach
                        <tr>
                           <td colspan="3"></td>
                           <td class="text-center; width: 50px;"><strong>JUMLAH BAYARAN</strong></td>
                           <td class="text-center; width: 50px;"><strong>RM{{ number_format($sum, 2, '.', ',') }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>

               <div class="col-md-12">
                  {{-- <h3>BAYARAN</h3> --}}
                  <table class="table table-bordered">
                     <thead>
                        <tr class="line">
                           <td style="width: 10px;"><strong>#</strong></td>
                           <td class="text-center; width: 50px;"><strong>MAKLUMAT BAYARAN</strong></td>
                           <td class="text-center; width: 50px;"><strong>SEMESTER</strong></td>
                           <td class="text-center; width: 50px;"><strong>AMAUN</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($data['detail'] as $keys => $dtl)
                        @if ($dtl->total_amount != 0)
                        <tr>
                           <td>{{ $keys+1 }}</td>
                           <td>{{ $dtl->name }}</td>
                           <td>{{ $data['payment']->semester_id }}</td>
                           <td>RM{{ number_format($dtl->total_amount, 2, '.', ',') }}</td>
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                           <td colspan="2">
                           </td><td class="text-center; width: 50px;"><strong>JUMLAH KESELURUHAN</strong></td>
                           <td class="text-center; width: 50px;"><strong>RM{{ number_format($data['total'], 2, '.', ',') }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 text-right identity">
                  <p>Diterima Oleh :<br><strong>{{ $data['staff']->name ?? '' }}</strong></p>
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