@extends('layouts.student')

@section('main')

<style>
    /* Styling for the vehicle sticker application page */
    .vehicle-sticker-page {
        padding: 2rem 0;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .page-header-premium {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header-premium h4 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .premium-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #2563eb;
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .premium-card-header i {
        font-size: 1.5rem;
        color: #2563eb;
    }

    .premium-card-header b {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .premium-card-body {
        padding: 2rem;
    }

    .form-label-premium {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-control-premium, .form-select-premium {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        width: 100%;
        display: block;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .form-control-premium:focus, .form-select-premium:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        outline: none;
    }

    .form-control-premium:hover:not(:disabled), .form-select-premium:hover:not(:disabled) {
        border-color: #93c5fd;
    }

    .btn-premium {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    }

    .vehicle-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .vehicle-table th {
        background-color: #f8fafc;
        padding: 1rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0;
    }

    .vehicle-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: middle;
    }

    .badge-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-status.baru {
        background-color: #dbeafe;
        color: #1e40af;
    }
</style>

<div class="content-wrapper vehicle-sticker-page">
    <div class="container-full">
        <div class="page-header-premium">
            <h4><i class="mdi mdi-car"></i> Vehicle Sticker Application</h4>
            <p style="margin: 0; opacity: 0.9;">Register your vehicles to apply for the campus parking sticker.</p>
        </div>

        <section class="content">
            <div class="container-fluid">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                        <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('alert'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                        <i class="mdi mdi-alert-circle me-2"></i> {{ session('alert') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($vehicles->count() < 2)
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="mdi mdi-plus-circle"></i>
                        <b>Register New Vehicle</b>
                    </div>
                    <div class="premium-card-body">
                        <form action="{{ route('student.vehicle_sticker.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="plate_number">Plate Number</label>
                                        <input type="text" class="form-control-premium" id="plate_number" name="plate_number" placeholder="e.g. ABC1234" required style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="type">Vehicle Type</label>
                                        <select class="form-select-premium" id="type" name="type" required>
                                            <option value="" selected disabled>Select Vehicle Type</option>
                                            <option value="KERETA">KERETA (CAR)</option>
                                            <option value="MOTOR">MOTORSIKAL (MOTORCYCLE)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="brand">Brand</label>
                                        <select class="form-select-premium" id="brand" name="brand" required>
                                            <option value="" selected disabled>Select Brand</option>
                                            <option value="PROTON">PROTON</option>
                                            <option value="PERODUA">PERODUA</option>
                                            <option value="HONDA">HONDA</option>
                                            <option value="TOYOTA">TOYOTA</option>
                                            <option value="NISSAN">NISSAN</option>
                                            <option value="YAMAHA">YAMAHA</option>
                                            <option value="MODENAS">MODENAS</option>
                                            <option value="SYM">SYM</option>
                                            <option value="KAWASAKI">KAWASAKI</option>
                                            <option value="OTHER">OTHER</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="model">Model</label>
                                        <input type="text" class="form-control-premium" id="model" name="model" placeholder="e.g. MYVI, EX5" required style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="color">Color</label>
                                        <input type="text" class="form-control-premium" id="color" name="color" placeholder="e.g. RED, WHITE" required style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn-premium">
                                        <i class="mdi mdi-content-save"></i> Register Vehicle
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-info mb-4" style="border-radius: 12px;">
                    <i class="mdi mdi-information me-2"></i> You have reached the maximum limit of 2 registered vehicles.
                </div>
                @endif

                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        <b>My Registered Vehicles</b>
                    </div>
                    <div class="premium-card-body p-0">
                        <div class="table-responsive">
                            <table class="vehicle-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Plate Number</th>
                                        <th>Type</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Status</th>
                                        <th>Date Registered</th>
                                        <th>Sticker Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vehicles as $index => $vehicle)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $vehicle->plate_number }}</strong></td>
                                        <td>{{ $vehicle->type }}</td>
                                        <td>{{ $vehicle->brand }}</td>
                                        <td>{{ $vehicle->model }}</td>
                                        <td>{{ $vehicle->color }}</td>
                                        <td>
                                            <span class="badge-status {{ strtolower($vehicle->status) == 'baru' ? 'baru' : 'bg-primary' }}">
                                                {{ $vehicle->status }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($vehicle->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($vehicle->sticker_number)
                                                <strong>{{ $vehicle->sticker_number }}</strong>
                                            @else
                                                <span class="text-muted fst-italic">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('student/vehicle_sticker/print', $vehicle->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-print"></i> Print
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            No vehicles registered yet.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
