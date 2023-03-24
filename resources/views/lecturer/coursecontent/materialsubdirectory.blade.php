
@extends('layouts.lecturer.lecturer')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }
</style>

<style>
    .modal-body-centered-text p {
        text-align: center;
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
                            <div class="row mb-3 d-flex">
                                <div class="col-md-12 mb-3">
                                    <div class="pull-right">
                                        <button id="newFolder" class="waves-effect waves-light btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i> <i class="fa fa-folder"></i> &nbsp New Folder
                                        </button>

                                        <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                                            <i class="fa fa-plus"></i> <i class="fa fa-folder"></i> &nbsp Upload File
                                        </a>

                                        <a type="button" class="waves-effect waves-light btn btn-light btn-sm" data-toggle="modal" data-target="#uploadLink">
                                            <i class="fa fa-plus"></i> <i class="fa fa-link"></i> &nbsp Link
                                        </a>
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
                                            Chapter {{ $fold->SubChapterNo }} : {{ ($fold->newDrName != null) ? $fold->newDrName : $fold->DrName }}  &nbsp <i class="{{ ($fold->Password != null) ? 'fa fa-lock' : '' }}"></i>
                                        </div>
                                    </a>

                                    <form method="post" name="form-rename" id="form-rename"> 
                                        <div id="rename-material-{{ $fold->DrID }}" class="collapse input-group mb-3" data-bs-parent="#material-directory">
                                            <button class="btn btn-link btn-circle btn-xs " data-bs-toggle="collapse" data-bs-target="#rename-material-{{ $fold->DrID }}" aria-expanded="false" aria-controls="rename-material-{{ $fold->DrID }}">
                                                <i class="mdi mdi-close text-dark"></i>
                                            </button> 
                                            <input type="text" class="form-control" id="test-{{ $fold->DrID }}"> 
                                            <button class="btn btn-link btn-circle btn-xs" type="button" onclick="renameMaterial('{{ $fold->DrID }}')">
                                                <i class="fa fa-save text-dark"></i>
                                            </button>  
                                        </div>
                                    </form>
                        
                                    <button data-bs-toggle="collapse" data-bs-target="#rename-material-{{ $fold->DrID }}" aria-expanded="false" aria-controls="rename-material-{{ $fold->DrID }}"
                                        class="btn btn-secondary btn-sm">
                                            <i class="mdi mdi-pencil"></i>
                                    </button>
                        
                                    <a  onclick="deleteMaterial('{{ $fold->DrID }}')" class="btn btn-secondary btn-sm">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </div>
                                @endforeach

                                
                                @foreach ( $classmaterial as $key => $mats)
                                <div class="col-md-3 text-center mb-3">
                                    <a href="{{ Storage::disk('linode')->url($mats) }}" target="_blank">
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
                                            {{ basename($mats)}} / {{ pathinfo(storage_path($mats), PATHINFO_EXTENSION); }}
                                        </div>
                                    </a>

                                    <form method="post" name="form-rename" id="form-rename"> 
                                        <div id="rename-material-{{ $key }}" class="collapse input-group mb-3" data-bs-parent="#material-directory">
                                            <button class="btn btn-link btn-circle btn-xs " data-bs-toggle="collapse" data-bs-target="#rename-material-{{ $key }}" aria-expanded="false" aria-controls="rename-material-{{ $key }}">
                                                <i class="mdi mdi-close text-dark"></i>
                                            </button> 
                                            <input type="text" class="form-control" id="test-{{ $key }}"> 
                                            <button class="btn btn-link btn-circle btn-xs" type="button" onclick="renameFile('{{ basename($mats)}}','{{ $key }}','{{ pathinfo(storage_path($mats), PATHINFO_EXTENSION); }}')">
                                                <i class="fa fa-save text-dark"></i>
                                            </button>  
                                        </div>
                                    </form>
                        
                                    <button data-bs-toggle="collapse" data-bs-target="#rename-material-{{ $key }}" aria-expanded="false" aria-controls="rename-material-{{ $key }}"
                                        class="btn btn-secondary btn-sm">
                                            <i class="mdi mdi-pencil"></i>
                                    </button>
                        
                                    <a  onclick="deleteMaterialfile('{{ $mats }}')" class="btn btn-secondary btn-sm">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </div>
                                @endforeach

                                @if($url != null)
                                    @foreach($url as $key => $ul)
                                        @php
                                            $originalURL = $ul->url;
                                            $search = 'https://www.youtube.com/watch?v=';
                                            $replace = 'https://www.youtube.com/embed/';

                                            $newURL = str_replace($search, $replace, $originalURL);
                                        @endphp     
                                        <div class="col-md-3 text-center">
                                            <iframe style="width:100%; height:75%;" src="{{ $newURL }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            <button type="button" class="btn btn-info btn-sm" id="infoButton{{ $key }}">
                                                i
                                            </button>

                                            <div id="descriptionModal{{ $key }}" class="modal" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <!-- modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-body modal-body-centered-text">
                                                            <p>{!! $ul->description !!}</p>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteUrl('{{ $ul->DrID }}')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                        <script>
                                            $(document).ready(function(){
                                                $('#infoButton{{ $key }}').click(function() {
                                                    $('#descriptionModal{{ $key }}').show();
                                                });

                                                $(document).click(function(event) {
                                                    if ($(event.target).closest('#descriptionModal{{ $key }} .custom-modal-content').length === 0 && !$(event.target).is('#infoButton{{ $key }}')) {
                                                        $('#descriptionModal{{ $key }}').hide();
                                                    }
                                                });
                                            });
                                        </script>
                                    @endforeach
                                @endif


                                <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- modal content-->
                                        <div class="modal-content">
                                            <form action="/lecturer/content/material/sub/storefile/{{ $dirid }}" method="post" role="form" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="modal-header">
                                                <div class="">
                                                    <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>File Upload</label>
                                                    <input type="file" name="fileUpload" id="fileUpload" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group pull-right">
                                                    <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="uploadLink" class="modal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- modal content-->
                                        <div class="modal-content">
                                            <form action="/lecturer/content/material/sub/storefile/{{ $dirid }}" method="post" role="form" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="modal-header">
                                                <div class="">
                                                    <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Link Upload</label>
                                                    <input type="url" name="url" id="url" placeholder="https://example.com" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Description</label>
                                                    <textarea id="classdescriptiontxt" name="description" class="mt-2" rows="10" cols="80">
                                                    </textarea>
                                                    <span class="text-danger">@error('description')
                                                      {{ $message }}
                                                    @enderror</span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group pull-right">
                                                    <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>

<script src="{{ asset('assets/assets/vendor_components/dropzone/dropzone.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){

    "use strict";
        ClassicEditor
        .create( document.querySelector( '#classdescriptiontxt' ),{ height: '25em' } )
        .then(newEditor =>{editor = newEditor;})
        .catch( error => { console.log( error );});

    })

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

    function deleteUrl(id){     
        Swal.fire({
			title: "Are you sure?",
			text: "This will be permanent",
			showCancelButton: true,
			confirmButtonText: "Yes, delete it!"
		}).then(function(res){
			
			if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/content/folder/subfolder/material/url/delete') }}",
                    method   : 'DELETE',
                    data 	 : {id:id},
                    error:function(err){
                        alert("Error");
                        console.log(err);
                    },
                    success  : function(data){
                        alert("success");
                        window.location.reload();
                    }
                });
            }
        });
    }

    function deleteMaterialfile(mats){     
        Swal.fire({
			title: "Are you sure?",
			text: "This will be permanent",
			showCancelButton: true,
			confirmButtonText: "Yes, delete it!"
		}).then(function(res){
			
			if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/content/folder/subfolder/deletefile') }}",
                    method   : 'DELETE',
                    data 	 : {mats:mats},
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
                    url      : "{{ url('lecturer/content/folder/subfolder/rename') }}",
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

    function renameFile(file,key,ext)
    {
        var name = document.getElementById('test-'+key).value;

        var dir = '{{ request()->dir }}';

        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/content/folder/subfolder/renameFile') }}",
            method   : 'POST',
            data 	 : {file:file,name:name,dir:dir,ext:ext},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                if(data == 1)
                {
                    window.location.reload();
                    alert("success");
                }else{
                    alert("Please insert valid name to proceed");
                }

            }
        });
    }

</script>
@stop