@extends('layouts.hep')

@section('title', 'Vehicle Registration')

@section('main')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Search Staff / Student for Vehicle Registration</h4>
                    </div>
                    <div class="box-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('hep.vehicle_sticker.search') }}" method="GET">
                            <div class="form-group row">
                                <label for="search" class="col-sm-2 col-form-label">Name / IC / Sticker Number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Enter Name, IC or Sticker Number" value="{{ $search ?? '' }}">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($results) && !$results->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Search Results</h4>
                    </div>
                    <div class="box-body">
                        @foreach($results as $user)
                        <div class="card mb-4 border border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $user->name }} ({{ strtoupper($user->user_type) }})</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>IC:</strong> {{ $user->ic }}</p>
                                        @if($user->user_type == 'staff')
                                            <p><strong>No. Staff:</strong> {{ $user->id_number }}</p>
                                            <p><strong>No. Tel:</strong> {{ $user->no_tel }}</p>
                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                        @else
                                            <p><strong>No. Matric:</strong> {{ $user->id_number }}</p>
                                            <p><strong>Program:</strong> {{ $user->program_name }}</p>
                                            <p>
                                                <span class="mr-3"><strong>Session:</strong> {{ $user->session }}</span> |
                                                <span class="mx-3"><strong>Semester:</strong> {{ $user->semester }}</span> |
                                                <span class="ml-3"><strong>Status:</strong> {{ $user->status }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <h6>Vehicle Registration Applications</h6>
                                @if(isset($applicationsByIc[$user->ic]) && $applicationsByIc[$user->ic]->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Plate Number</th>
                                                <th>Type</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Color</th>
                                                <th>Sticker Number</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applicationsByIc[$user->ic] as $app)
                                            <tr>
                                                <td>{{ $app->plate_number }}</td>
                                                <td>{{ $app->type }}</td>
                                                <td>{{ $app->brand }}</td>
                                                <td>{{ $app->model }}</td>
                                                <td>{{ $app->color }}</td>
                                                <td>{{ $app->sticker_number }}</td>
                                                <td>
                                                    @if(strtoupper($app->status) == 'SAH')
                                                        <span class="badge badge-success">{{ $app->status }}</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ $app->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(strtolower($app->status) == 'baru')
                                                    <form action="{{ route('hep.vehicle_sticker.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="sticker_id" value="{{ $app->id }}">
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to update the status to SAH?')">Sahkan (SAH)</button>
                                                    </form>
                                                    @else
                                                        <span class="text-muted">No Action</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">No applications found for this user.</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
</div>
@endsection
