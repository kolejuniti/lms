
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
         margin: 0.5in 0.2cm 0.2cm 0.2cm;
      }
      @media print {
         body {
            margin: 0;
            padding: 0;
         }
         .container {
            transform: none;
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
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
         font-size: 12px; /* reduce font-size */
         table-layout: fixed;
      }
      h2,h3,p {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 10px; /* reduce font-size */
      }
      .container {
         transform: none;
         width: 100%;
         max-width: 100%;
         margin: 0;
         padding: 0;
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
               <div class="row mb-2">
                  <div class="col-12 d-flex">
                     <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="" height="80">
                     <address>
                        <p><strong>KOLEJ UNITI</strong></p>
                        <p>PERSIARAN UNITI VILLAGE, TANJUNG AGAS</p>
                        <p>71250, PORT DICKSON, NEGERI SEMBILAN</p>
                        <p>Tel: 06-649 0350 | Fax: 06-661 0509</p>
                        <p>https://uniti.edu.my | Email: info@uniti.edu.my</p>
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
                     <!-- Row 5: Tarikh and No. Resit -->
                     <tr>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>TARIKH</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['date'] }}</td>
                        <td style="width: 25%; padding: 2px 5px; text-align: left;"><strong>NO. RESIT</strong></td>
                        <td style="width: 25%; padding: 2px 5px;">{{ $data['payment']->ref_no }}</td>
                     </tr>
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
                  </table>
               </div>

               <div class="col-md-12">
                  {{-- <h3>KAEDAH</h3> --}}
                  <table class="table table-bordered">
                     <thead>
                        <tr class="line">
                           <td style="width: 5%; padding: 4px 4px;" class="text-center"><strong>#</strong></td>
                           <td style="width: 20%; padding: 4px 4px; text-align: left;" class="text-center"><strong>KAEDAH BAYARAN</strong></td>
                           <td style="width: 20%; padding: 4px 4px; text-align: left;" class="text-center"><strong>BANK</strong></td>
                           <td style="width: 40%; padding: 4px 4px; text-align: left;" class="text-center"><strong>NO. DOKUMEN</strong></td>
                           <td style="width: 15%; padding: 4px 4px; text-align: right;" class="text-center"><strong>AMAUN</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $sum = 0;
                        @endphp
                        @foreach ($data['method'] as $key => $dtl)
                        <tr>
                           <td style="width: 10%; padding: 3px 4px;" class="text-center">{{ $key+1 }}</td>
                           <td style="width: 20%; padding: 3px 4px;" class="text-center">{{ $dtl->method }}</td>
                           <td style="width: 20%; padding: 3px 4px;" class="text-center">{{ $dtl->bank }}</td>
                           @if ($dtl->no_document == null)
                           <td style="width: 35%; padding: 3px 4px;" class="text-center">TIADA</td>
                           @else
                           <td style="width: 35%; padding: 3px 4px;" class="text-center">{{ $dtl->no_document }}</td>
                           @endif
                           <td style="width: 15%; padding: 3px 4px; text-align: right;" class="text-center">RM{{ number_format($dtl->amount, 2, '.', ',') }}</td>
                           @php
                           $sum += $dtl->amount;
                           @endphp
                        </tr>
                        @endforeach
                        <tr>
                           <td colspan="3" style="padding: 3px 4px;"></td>
                           <td style="width: 35%; padding: 3px 4px; text-align: center;"><strong>JUMLAH BAYARAN</strong></td>
                           <td style="width: 15%; padding: 3px 4px; text-align: right;" class="text-center"><strong>RM{{ number_format($sum, 2, '.', ',') }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>

               <div class="col-md-12">
                  {{-- <h3>BAYARAN</h3> --}}
                  <table class="table table-bordered">
                     <thead>
                        <tr class="line">
                           <td style="width: 5%; padding: 4px 4px;" class="text-center"><strong>#</strong></td>
                           <td style="width: 60%; padding: 4px 4px; text-align: center;"><strong>MAKLUMAT BAYARAN</strong></td>
                           <td style="width: 20%; padding: 4px 4px; text-align: center;"><strong>SEMESTER</strong></td>
                           <td style="width: 15%; padding: 4px 4px; text-align: center;"><strong>AMAUN</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($data['detail'] as $keys => $dtl)
                        @if ($dtl->total_amount != 0)
                        <tr>
                           <td style="width: 10%; padding: 3px 4px;" class="text-center">{{ $keys+1 }}</td>
                           <td style="width: 50%; padding: 3px 4px;" class="text-center">{{ $dtl->name }}</td>
                           <td style="width: 20%; padding: 3px 4px;" class="text-center">{{ $data['payment']->semester_id }}</td>
                           <td style="width: 20%; padding: 3px 4px; text-align: right;" class="text-center">RM{{ number_format($dtl->total_amount, 2, '.', ',') }}</td>
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                           <td colspan="2" style="padding: 3px 4px;"></td>
                           <td style="width: 20%; padding: 3px 4px; text-align: center;"><strong>JUMLAH KESELURUHAN</strong></td>
                           <td style="width: 20%; padding: 3px 4px; text-align: center;"><strong>RM{{ number_format($data['total'], 2, '.', ',') }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 text-right identity">
                  <p>DITERIMA OLEH :<br><strong>{{ $data['staff']->name ?? '' }}</strong></p>
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