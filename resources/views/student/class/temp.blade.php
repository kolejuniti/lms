<head>
    <title>How to Use Bootstrap Datetimepicker in Laravel - NiceSnippets.com</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
</head>
<body>
    <div class="container">
        <form method="post">
          <div class="row form-group">
            <div class="col-md-2">
                Appointment Time 
            </div>
            <div class="col-md-8">
              <input type="text" class="form-control datetimepicker" name="Appointment_time"> 
            </div>
          </div>
        </div>
      </div>
    <script type="text/javascript">
       $(function () {
    $('.datetimepicker').datetimepicker();
});
    </script>
</body>