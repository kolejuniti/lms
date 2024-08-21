
@extends('layouts.pendaftar_akademik')

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
                    <div class="box-body">
                        <h4 class="box-title mb-0 fw-500">Learning Materials Upload</h4>	
                        <hr>
                        <div class="mb-4">
                            <div class="form-group">
                                <label class="form-label">Documents</label>
                                <form action="#" enctype="multipart/form-data" class="dropzone dz-clickable" id="materialsdocument" >
                                @csrf
                                    <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                                </form>
                                <label id="materialsdocument_error" class="text-danger small error-field"></label>
                            </div>
                        </div>
                        <div class="box-footer">
         
                        </div>
                    </div>
                </div>
                {{-- <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Schedule
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
                                @foreach ( $files as $key => $fl)
                                        <div class="col-md-3 text-center mb-3">
                                            <a href="{{ Storage::disk('linode')->url($fl) }}" target="_blank">
                                                <svg width="4em" height="4em" enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" >
                                                    <path d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" fill="#E2E5E7"/>
                                                    <path d="m384 128h96l-128-128v96c0 17.6 14.4 32 32 32z" fill="#B0B7BD"/>
                                                    <polygon points="480 224 384 128 480 128" fill="#CAD1D8"/>
                                                    <path d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16  V416z" fill="#F15642"/>
                                                    <g fill="#fff">
                                                        <path d="m101.74 303.15c0-4.224 3.328-8.832 8.688-8.832h29.552c16.64 0 31.616 11.136 31.616 32.48 0 20.224-14.976 31.488-31.616 31.488h-21.36v16.896c0 5.632-3.584 8.816-8.192 8.816-4.224 0-8.688-3.184-8.688-8.816v-72.032zm16.88 7.28v31.872h21.36c8.576 0 15.36-7.568 15.36-15.504 0-8.944-6.784-16.368-15.36-16.368h-21.36z"/>
                                                        <path d="m196.66 384c-4.224 0-8.832-2.304-8.832-7.92v-72.672c0-4.592 4.608-7.936 8.832-7.936h29.296c58.464 0 57.184 88.528 1.152 88.528h-30.448zm8.064-72.912v57.312h21.232c34.544 0 36.08-57.312 0-57.312h-21.232z"/>
                                                        <path d="m303.87 312.11v20.336h32.624c4.608 0 9.216 4.608 9.216 9.072 0 4.224-4.608 7.68-9.216 7.68h-32.624v26.864c0 4.48-3.184 7.92-7.664 7.92-5.632 0-9.072-3.44-9.072-7.92v-72.672c0-4.592 3.456-7.936 9.072-7.936h44.912c5.632 0 8.96 3.344 8.96 7.936 0 4.096-3.328 8.704-8.96 8.704h-37.248v0.016z"/>
                                                    </g>
                                                    <path d="m400 432h-304v16h304c8.8 0 16-7.2 16-16v-16c0 8.8-7.2 16-16 16z" fill="#CAD1D8"/>
                                                </svg>
                                                <div class="p-3">
                                                    {{ basename($fl)}} / {{ $extension = pathinfo(storage_path($fl), PATHINFO_EXTENSION); }}
                                                </div>
                                            </a>

                                            <form method="post" name="form-rename" id="form-rename"> 
                                                <div id="rename-material-{{ $key }}" class="collapse input-group mb-3" data-bs-parent="#material-directory">
                                                    <button class="btn btn-link btn-circle btn-xs " data-bs-toggle="collapse" data-bs-target="#rename-material-{{ $key }}" aria-expanded="false" aria-controls="rename-material-{{ $key }}">
                                                        <i class="mdi mdi-close text-dark"></i>
                                                    </button> 
                                                    <input type="text" class="form-control" id="test-{{ $key }}"> 
                                                    <button class="btn btn-link btn-circle btn-xs" type="button" onclick="renameFile('{{ basename($fl)}}','{{ $key }}','{{ pathinfo(storage_path($fl), PATHINFO_EXTENSION); }}')">
                                                        <i class="fa fa-save text-dark"></i>
                                                    </button>  
                                                </div>
                                            </form>
                                
                                            <a data-bs-toggle="collapse" data-bs-target="#rename-material-{{ $key }}" aria-expanded="false" aria-controls="rename-material-{{ $key }}"
                                                class="btn btn-secondary btn-sm">
                                                    <i class="mdi mdi-pencil"></i>
                                            </a>
                                
                                            <a  onclick="deleteMaterial('{{ $fl }}')" class="btn btn-secondary btn-sm">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </div>
                                    @endforeach
                            </div> 
                           
                        </div>
                    </div>
                </div> --}}
            </div>
    </section>
    <!-- /.content -->
    
    </div>
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">

    $(document).on('click', '#newFolder', function() {
        location.href = "";
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
                    url      : "{{ url('lecturer/content/delete') }}",
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


    function renameMaterial(dir)
    {
        var name = document.getElementById('test-'+dir).value;

        $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/content/rename') }}",
                    method   : 'POST',
                    data 	 : {dir:dir,name:name},
                    error:function(err){
                        alert("Error");
                        console.log(err);
                    },
                    success  : function(data){
                        window.location.reload();
                        alert("success");
                    }
                });

        //alert(form);

    }



    Dropzone.autoDiscover = false;
	var dropzonefiles = [];
    var editor;

    $("#materialsdocument").dropzone({ 
		url: "/AR/schedule/store",
		headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        method: "POST",
		dictDefaultMessage: "Your default message",
        //acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.xlsx",
		paramName: "dropzoneimage",
		addRemoveLinks: true,
		maxFilesize: 1,
		maxFiles: 10,
		dictRemoveFile: "<i class='fa fa-trash'></i>",
        maxfilesexceeded: function(file) {
            this.removeAllFiles();
            this.addFile(file);
        },
        init: function() {
            this.on("success", function(file, responseText) {
                dropzonefiles.push(file);
            });
            this.on('error', function(file, errorMessage) {
                if (file.accepted) {
                    var mypreview = document.getElementsByClassName('dz-error');
                    mypreview = mypreview[mypreview.length - 1];
                    mypreview.classList.toggle('dz-error');
                    mypreview.classList.toggle('dz-success');
                }
            });
        }
	});

</script>
@stop