
@extends('layouts.student.student')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Material Gallery</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Material Gallery</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12 col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Material Gallery
                        </h4>						
                    </div>
                    <div class="box-body">
                        <div id="material-div">
                            <div id="material-directory" class='row'> 
                                @if ($folder != null)
                                    @foreach ( $folder as $i => $fold)
                                    <div class="col-md-3 text-center mb-3">
                                        <a href="/student/content/material/{{ $fold->DrID }}" >
                                            <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                                                <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                                                <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                                                <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                                            </svg>
                                            <div class="p-3">
                                                {{ $fold->DrName }}  &nbsp <i class="{{ ($fold->Password != null) ? 'fa fa-lock' : '' }}"></i>
                                            </div>
                                        </a>
                                    </div>
                                    @endforeach
                                @else
                                <div class="box bg-danger">
                                    <div class="box-body d-flex p-0">
                                        <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                                            <div class="row">
                                                <div class="col-12 col-xl-12">
                                                    <h1 class="mb-0 fw-600">You are not yet assigned to group, please consult the person in charge.</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif        
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
    
    </div>
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">

    $(document).on('click', '#newFolder', function() {
        location.href = "/lecturer/content/{{ $course_id }}/create";
    })


    function materialDirectory(dir){
        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : '/lecturer/material',
            method   : 'POST',
            data 	 : {dir: dir},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#material-div').html(data);
            }
        });
    }

</script>
@stop