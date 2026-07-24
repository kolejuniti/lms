@extends('layouts.finance')

@section('title', 'Vehicle Registration Records (Staff)')

@section('main')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Search Staff for Vehicle Registration Records</h4>
                    </div>
                    <div class="box-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('finance.vehicle_sticker.staff.search') }}" method="GET">
                            <div class="form-group row">
                                <label for="search" class="col-sm-2 col-form-label">Name / IC / No. Staff</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Enter Name, IC or Staff Number"
                                        value="{{ $search ?? '' }}">
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
                                <h5 class="card-title text-primary">{{ $user->name }} <span class="badge badge-info">STAFF</span></h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>IC:</strong> {{ $user->ic }}</p>
                                        <p><strong>No. Staff:</strong> {{ $user->id_number }}</p>
                                        <p><strong>No. Tel:</strong> {{ $user->no_tel }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
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
                                                <th>Status</th>
                                                <th>Sticker Number</th>
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
                                                <td>
                                                    @if(strtoupper($app->status) == 'SAH')
                                                        <span class="badge badge-success">{{ $app->status }}</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ $app->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('finance.vehicle_sticker.staff.update') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="sticker_id" value="{{ $app->id }}">
                                                        <input type="text" name="sticker_number"
                                                            class="form-control form-control-sm d-inline-block"
                                                            style="width: 130px;"
                                                            placeholder="No. Pelekat"
                                                            value="{{ $app->sticker_number ?? '' }}"
                                                            required>
                                                        <button type="submit" class="btn btn-sm btn-success ml-1"
                                                            onclick="return confirm('Kemaskini No. Pelekat dan status kepada SAH?')">
                                                            SAH
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    @if(strtoupper($app->status) == 'SAH')
                                                        <span class="text-muted">Updated</span>
                                                    @else
                                                        <span class="text-warning">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">No vehicle registration applications found for this staff.</p>
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
