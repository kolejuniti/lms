
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
      size: A5 landscape; /* reduced height for A5 size in landscape orientation */
      margin: 1cm;
    }
    * {
        margin: 0;
        padding: 0;
        border: 0;
        outline: 0;
        font-size: 100%;
        vertical-align: baseline;
        background: transparent;
        font-size: 7px;
    }
    h2,h3,p {
        margin: 0;
        padding: 0;
        border: 0;
        outline: 0;
        font-size: 100%;
        vertical-align: baseline;
        background: transparent;
        font-size: 7px;
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
                        <strong>UNITI</strong><br>
                        Kompleks UNITI, 71250 Pasir Panjang,<br>
                        Port Dickson, Negeri Sembilan Darul Khusus.<br>
                        <abbr title="Phone">Tel:</abbr> 06-661 0518 / 06-661 0520 | <abbr title="Phone">Fax:</abbr> 06-661 0509<br>
                        http://www.uniti.edu.my | <abbr title="Email">Email:</abbr> info@uniti.edu.my
                     </address>
                  </div>
               </div>
               <br>
               <div class="row">
                  <div class="col-12">
                     <h2>Resit<br>
                     <span class="small">No. Resit : {{ $data['payment']->ref_no }}</span></h2>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="d-flex p-2">
                  <div class="col-md-6" style="margin-right: 10px">
                     <div class="form-group">
                           <p>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                           <p>No. Resit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                           <p>No. KP / No. Passport &thinsp;&thinsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                           <p>Sesi Kemasukan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake }}</p>
                           <p>Sesi Semasa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->session }}</p>
                           <p>No. Matriks &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                           <p>Program &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                           <p>No. IC / No. Passport &nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                           <p>No. Matric &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                     </div>
                  </div>
               </div>

               <div class="col-md-12">
                  <h3>KAEDAH</h3>
                  <table class="table table-striped">
                     <thead>
                        <tr class="line">
                           <td><strong>#</strong></td>
                           <td class="text-center"><strong>KAEDAH BAYARAN</strong></td>
                           <td class="text-center"><strong>BANK</strong></td>
                           <td class="text-center"><strong>NO. DOKUMEN</strong></td>
                           <td class="text-center"><strong>AMAUN</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($data['method'] as $key => $dtl)
                        <tr>
                           <td>{{ $key+1 }}</td>
                           <td style="text-align: center">{{ $dtl->method }}</td>
                           <td style="text-align: center">{{ $dtl->bank }}</td>
                           @if ($dtl->no_document == null)
                           <td style="text-align: center">TIADA</td>
                           @else
                           <td style="text-align: center">{{ $dtl->no_document }}</td>
                           @endif
                           <td style="text-align: center">{{ $dtl->amount }}</td>
                        </tr>
                        @endforeach
                        <tr>
                           <td colspan="3">
                           </td><td class="text-center"><strong>Jumlah :</strong></td>
                           <td class="text-center"><strong>{{ $data['total2'] }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>

               <div class="col-md-12">
                  <h3>BAYARAN</h3>
                  <table class="table table-striped">
                     <thead>
                        <tr class="line">
                           <td><strong>#</strong></td>
                           <td class="text-center"><strong>MAKLUMAT BAYARAN</strong></td>
                           <td class="text-center"><strong>SEMESTER</strong></td>
                           <td class="text-center"><strong>AMAUN</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($data['detail'] as $keys => $dtl)
                        @if ($dtl->amount != 0)
                        <tr>
                           <td>{{ $keys+1 }}</td>
                           <td style="text-align: center">{{ $dtl->name }}</td>
                           <td style="text-align: center">{{ $dtl->groupid }}</td>
                           <td style="text-align: center">{{ $dtl->amount }}</td>
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                           <td colspan="2">
                           </td><td class="text-center"><strong>Jumlah Keseluruhan :</strong></td>
                           <td class="text-center"><strong>{{ $data['total'] }}</strong></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 text-right identity">
                  <p>This Invoice belong to the<br><strong>Kolej Uniti Sdn. Bhd</strong></p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- END INVOICE -->
   </div>
</body>
<script type="text/javascript">

   $(document).ready(function () {
       window.print();
   });
   
   </script>