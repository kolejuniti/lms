
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
         font-size: 10px; /* reduce font-size */
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
                     <h2>Resit<br>
                     <span class="small">No. Resit : {{ $data['payment']->ref_no }}</span></h2>
                  </div>
               </div> --}}
            </div>
            <div class="row">
               <div class="col-md-12 d-flex p-2">
                  <div class="col-md-6" style="margin-right: 10px">
                     <div class="form-group">
                           <p>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                           <p>No. KP / No. Passport &thinsp;&thinsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                           <p>Sesi Kemasukan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake }}</p>
                           <p>Sesi Semasa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->session }}</p>
                           <p>No. Matriks &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                           <p>Program &thinsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                           <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                           <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->semester }}</p>
                     </div>
                  </div>
               </div>

               <div class="col-md-12">
                  <table class="w-100 table table-bordered display margin-top-10 w-p100">
                     <thead>
                        <tr class="line">
                           <td><strong>#</strong></td>
                           <td class="text-center"><strong>KOD KURSUS</strong></td>
                           <td class="text-center"><strong>NAMA KURSUS</strong></td>
                           <td class="text-center"><strong>GRED</strong></td>
                           <td class="text-center"><strong>NILAI MATA</strong></td>
                           <td class="text-center"><strong>KREDIT</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                     @foreach($data['subject'] as $key => $subject)
                     <tr>
                        <td>
                           {{ $key + 1 }}
                        </td>
                        <td>
                           {{ $subject->course_code }}
                        </td>
                        <td>
                           {{ $subject->course_name }}
                        </td>
                        <td>
                           {{ $subject->grade }}
                        </td>
                        <td>
                           {{ $subject->pointer }}
                        </td>
                        <td>
                           {{ $subject->credit }}
                        </td>
                     </tr>
                     @endforeach
                     </tbody>
                  </table>
               </div>

               <div class="col-md-12 mt-10">
                  <table class="w-100 table table-bordered display margin-top-10 w-p100">
                     <thead>
                        <tr>
                           <td><strong></strong></td>
                           <td class="text-center"><strong>KREDIT AMBIL</strong></td>
                           <td class="text-center"><strong>JUMLAH NILAI GRED</strong></td>
                           <td class="text-center"><strong>PNG</strong></td>
                        </tr>
                     </thead>
                     <tbody>
                       <tr>
                           <td class="text-center">
                              SEMESTER
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->total_credit_s }}
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
                              KUMULATIF
                           </td>
                           <td class="text-center">
                              {{ $data['transcript']->total_credit_c }}
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
                              {{ $data['transcript']->transcript_status_id }}
                           </td>
                        </tr>
                     </tbody>
                  </table>
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