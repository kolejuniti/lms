
@extends('layouts.lecturer.lecturer')

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
                        <h4 class="box-title">Material Gallery - {{  $course->course_name }} ({{ $course->course_code }})
                        </h4>						
                    </div>
                    <div class="box-body">
                        <div id="material-div">
                            <div class="row mb-3">
                                <div class="col-md-12 mb-3">
                                    <div class="pull-right">
                                        <button id="newFolder" class="waves-effect waves-light btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i> <i class="fa fa-folder"></i> &nbsp New Folder
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="material-directory" class='row'>
                                <div class="col-md-3 text-center">
                                    <a href="/lecturer/content/material/prev/{{ $prev }}" onclick="">
                                        <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                                            <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                                            <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                                            <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/></svg>
                                        <div class="p-3">
                                            <i class="ti ti-more-alt"></i> 
                                        </div>
                                    </a>
                                </div>

                                @foreach ( $mat_directory as $i => $fold)
                                    <div class="col-md-3 text-center mb-3">
                                        <a href="/lecturer/content/material/sub/content/{{ $fold->DrID }}" >
                                            <svg width="4em" height="4em" enable-background="new 0 0 309.267 309.267" version="1.1" viewBox="0 0 309.27 309.27" xml:space="preserve">
                                                <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                                                <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                                                <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                                            </svg>
                                            <div class="p-3">
                                                Chapter {{ $fold->SubChapterNo }} : {{ $fold->DrName }}  &nbsp <i class="{{ ($fold->Password != null) ? 'fa fa-lock' : '' }}"></i>
                                            </div>
                                        </a>

                                        <div id="rename-material-" class="collapse input-group mb-3" data-bs-parent="#material-directory">
                                            <button class="btn btn-link btn-circle btn-xs " data-bs-toggle="collapse" data-bs-target="#rename-material-" aria-expanded="false" aria-controls="rename-material-">
                                                <i class="mdi mdi-close text-dark"></i>
                                            </button> 
                                            <input type="text" class="form-control" data-material-name="" data-original-name="" data-original-path="" value="" >    
                                            <button class="btn btn-link btn-circle btn-xs" type="button" onclick="">
                                                <i class="fa fa-save text-dark"></i>
                                            </button>  
                                        </div>
                            
                                        <button data-bs-toggle="collapse" data-bs-target="#rename-material-" aria-expanded="false" aria-controls="rename-material-"
                                            class="btn btn-secondary btn-sm">
                                                <i class="mdi mdi-pencil"></i>
                                        </button>
                            
                                        <a  onclick="deleteMaterial('{{ $fold->DrID }}')" class="btn btn-secondary btn-sm">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </div>
                                @endforeach
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
        location.href = "/lecturer/content/material/sub/create/{{ $dirid }}";
    })

    function deleteMaterial(dir){     
        Swal.fire({
			title: "Are you sure?",
			text: "This will be permanent",
			showCancelButton: true,
			confirmButtonText: "Yes, delete it!"
		}).then(function(res){
			
			if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/content/folder/subfolder/delete') }}",
                    method   : 'DELETE',
                    data 	 : {dir:dir},
                    error:function(err){
                        alert("Error");
                        console.log(err);
                    },
                    success  : function(data){
                        window.location.reload();
                        alert("success");
                    }
                });
            }
        });
    }

</script>
@stop