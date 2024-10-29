<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Transcript Pelajar</title>
        <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <style>
          @page {
            size: A4;
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
            font-size: 13px;
          }

          h2, h3, p {
            margin: 0;
            padding: 0;
            font-size: 13px;
          }

          h1 {
            font-size: 30px;
          }

          .b2 {
            font-weight: bold;
            font-size: 10px;
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

          .text-center {
            text-align: center;
          }

          /* Ensure the columns stay side-by-side in print */
          @media print {
            .flex-container {
              display: flex;
            }
            .col-md-6 {
              width: 48%;
              margin-right: 2%;
            }
            .col-md-12 {
              width: 100%;
            }
          }

          /* Border line style for print */
          .border-line {
            width: 100%;
            border-top: 1px solid black;
            margin: 15px 0;
          }

          .fixed-bottom-container {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: white;
          }

          .fixed-bottom-container .flex-container {
            display: flex;
            justify-content: space-between;
          }
        </style>
    </head>

    <body>
      <div class="row">
        <div class="col-12 d-flex justify-content-center align-items-center">
          <div class="me-2">
            <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="Kolej Uniti Logo" height="35">
          </div>
          <div>
            <h1 class="mb-0">KOLEJ UNITI</h1>
          </div>
        </div>
        <div>
          <div class="b2 text-center">Maklumat Pelajar</div>
        </div>
      </div>

      <div class="flex-container col-12 mt-1">
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">Full Name</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">IC</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->ic ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">No. Passport</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->passport ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Place Of Birth</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->place_birth ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Date Of Birth</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->date_birth ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Gender</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->sex_name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">Race</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->nationality_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Religion</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->religion_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Citizenship Level</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->citizenshiplevel_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Citizen</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->citizenship_name ?? '-' }}</td>
                </tr>
                <tr>
                  <td style="padding-right: 10px;">Status</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->marriage_name ?? '-' }}</td>
              </tr>
                <tr>
                    <td style="padding-right: 10px;">Phone Number 1</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->no_tel ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Phone Number 2</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->no_tel2 ?? '-' }}</td>
                </tr>
            </table>
        </div>
      </div>

      <div class="flex-container col-12 mt-1">
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">DUN</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->dun ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Parlimen</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->parlimen ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Qualification</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->qualification ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">No. Matric</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->no_matric ?? '-' }}</td>
                </tr>
            </table>
        </div>
    
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">Email</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->email ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Education Advisor</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->advisor ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">OKU</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->oku ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">JKM</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->no_jkm ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- Divider -->
    <div class="border-line"></div>
    
    {{-- <div class="flex-container col-12 mt-1">
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">Bank Name</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->bank_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Bank Account No.</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->bank_no ?? '-' }}</td>
                </tr>
            </table>
        </div>
    
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">PTPTN Pin No.</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->ptptn_no ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Date/Time</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->datetime ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    
    <!-- Divider -->
    <div class="border-line"></div> --}}
    
    <!-- Visa / Student Pass Information Section -->
    {{-- <div class="flex-container col-12 mt-1">
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">Visa Pass Type</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->pass_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Student Pass No.</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->pass_no ?? '-' }}</td>
                </tr>
            </table>
        </div>
    
        <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
            <table>
                <tr>
                    <td style="padding-right: 10px;">Date Issued</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->date_issued ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Date Expired</td>
                    <td>:</td>
                    <td style="padding-left: 10px;">{{ $student->date_expired ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div> --}}

    <div class="flex-container col-12 mt-1">
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">Address 1</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->address1 ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Address 2</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->address2 ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Address 3</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->address3 ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Postcode</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->postcode ?? '-' }}</td>
              </tr>
          </table>
      </div>
  
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">City</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->city ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">State</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->state_name2 ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Country</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->country ?? '-' }}</td>
              </tr>
          </table>
      </div>
  </div>
  
  <!-- Divider -->
  <div class="border-line"></div>
  
  <div class="flex-container col-12 mt-1">
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">Program</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->progname ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Intake</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->SessionName ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Session</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->session ?? '-' }}</td>
              </tr>
          </table>
      </div>
  
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">Date of Offer Letter</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->date_offer ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Semester</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->semester ?? '-' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Status</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->status ? 'Yes' : 'No' }}</td>
              </tr>
              {{-- <tr>
                  <td style="padding-right: 10px;">Complete Form</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->complete_form ? 'Yes' : 'No' }}</td>
              </tr> --}}
          </table>
      </div>
  </div>

  <div class="border-line"></div>
  
  <div class="flex-container col-12 mt-1">
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">Block</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $data['hostel']->block_name ?? '-' }}</td>
              </tr>
          </table>
      </div>
  
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">Unit</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $data['hostel']->no_unit ?? '-' }}</td>
              </tr>
          </table>
      </div>
  </div>

     
  <div class="row col-md-12">
    <div class="col-md-12 mt-3">
        <table class="custom-table">
            <thead>
                <tr>
                    <th class="text-center">Heir (Waris) Name</th>
                    <th class="text-center">Phone Number</th>
                    <th class="text-center">Relationship</th>
                    <th class="text-center">Occupation</th>
                    <th class="text-center">Salary (kasar)</th>
                    <th class="text-center">Salary (Bersih)</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['waris'] as $waris)
                <tr>
                    <td class="text-center">{{ $waris->name ?? '-' }}</td>
                    <td class="text-center">{{ $waris->phone_tel ?? '-' }}</td>
                    <td class="text-center">{{ $waris->relationship ?? '-' }}</td>
                    <td class="text-center">{{ $waris->occupation ?? '-' }}</td>
                    <td class="text-center">{{ $waris->kasar ?? '-' }}</td>
                    <td class="text-center">{{ $waris->bersih ?? '-' }}</td>
                    <td class="text-center">{{ $waris->status ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
  
  <!-- Divider -->
  {{-- <div class="border-line"></div> --}}
  
  <!-- Missing/Incomplete Form Section -->
  {{-- <div class="flex-container col-12 mt-1">
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">Copy of Student's Identification Card</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->copy_ic ? 'Yes' : 'No' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Copy of Student's Birth Certificate</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->copy_birth ? 'Yes' : 'No' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Copy of SPM Certificate</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->copy_spm ? 'Yes' : 'No' }}</td>
              </tr>
          </table>
      </div>
  
      <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
          <table>
              <tr>
                  <td style="padding-right: 10px;">Copy of School Certificate</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->copy_school ? 'Yes' : 'No' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Copy of Parent's Identification Card</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->copy_pic ? 'Yes' : 'No' }}</td>
              </tr>
              <tr>
                  <td style="padding-right: 10px;">Copy of Parent's Payslip/Income Confirmation</td>
                  <td>:</td>
                  <td style="padding-left: 10px;">{{ $student->copy_pincome ? 'Yes' : 'No' }}</td>
              </tr>
          </table>
      </div>
  </div> --}}
  
    

      {{-- <div class="fixed-bottom-container">
        <div class="flex-container">
          <div class="col-md-6">
            <p class="text-center"><b>{{ $data['date'] }}</b></p>
          </div>
        </div>
      </div> --}}
    </body>
</html>

<script type="text/javascript">
  $(document).ready(function () {
      window.print();
  });
</script>


