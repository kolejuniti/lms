@extends('layouts.finance')

@section('main')

<style>
    /* Styling for the non-staff vehicle registration page */
    .vehicle-sticker-page {
        padding: 2rem 0;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .page-header-premium {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header-premium::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .page-header-premium::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: 80px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
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
        transform: translateY(-3px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .premium-card-header {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border-bottom: 2px solid #7c3aed;
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .premium-card-header i {
        font-size: 1.5rem;
        color: #7c3aed;
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

    .form-control-premium,
    .form-select-premium {
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

    .form-control-premium:focus,
    .form-select-premium:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
        outline: none;
    }

    .form-control-premium:hover:not(:disabled),
    .form-select-premium:hover:not(:disabled) {
        border-color: #c4b5fd;
    }

    .btn-premium {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
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
        box-shadow: 0 4px 6px -1px rgba(124, 58, 237, 0.3);
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.4);
        background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
        color: white;
    }

    .section-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1.5rem 0 1rem;
    }

    .section-divider span {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #7c3aed;
        white-space: nowrap;
    }

    .section-divider::before,
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .vehicle-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .vehicle-table th {
        background-color: #f5f3ff;
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

    .vehicle-table tr:last-child td {
        border-bottom: none;
    }

    .badge-status {
        padding: 0.3rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .badge-status.sah {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-status.baru {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #f0fdf4;
        border: 1px solid #86efac;
        color: #15803d;
        padding: 0.4rem 0.9rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>

<div class="content-wrapper vehicle-sticker-page">
    <div class="container-full">
        <div class="page-header-premium">
            <h4><i class="mdi mdi-car-multiple"></i> Vehicle Registration (Non User)</h4>
            <p style="margin: 0; opacity: 0.9;">Register vehicles for individuals who are not registered system users. Status will be automatically set to <strong>SAH</strong>.</p>
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
                        <i class="mdi mdi-alert me-2"></i> <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Registration Form --}}
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="mdi mdi-plus-circle"></i>
                        <b>Register New Vehicle (Non User)</b>
                    </div>
                    <div class="premium-card-body">
                        <form action="{{ route('non_staff.vehicle_register.store') }}" method="POST">
                            @csrf

                            {{-- Personal Info Section --}}
                            <div class="section-divider"><span>Personal Information</span></div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="staff_name">Staff Name</label>
                                        <input
                                            type="text"
                                            class="form-control-premium"
                                            id="staff_name"
                                            name="staff_name"
                                            placeholder="e.g. Ahmad bin Ali"
                                            value="{{ old('staff_name') }}"
                                            required
                                            style="text-transform: uppercase;"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="staff_ic">Staff IC Number</label>
                                        <input
                                            type="text"
                                            class="form-control-premium"
                                            id="staff_ic"
                                            name="staff_ic"
                                            placeholder="e.g. 901231045678"
                                            value="{{ old('staff_ic') }}"
                                            maxlength="20"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>

                            {{-- Vehicle Info Section --}}
                            <div class="section-divider"><span>Vehicle Information</span></div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="plate_number">Plate Number</label>
                                        <input
                                            type="text"
                                            class="form-control-premium"
                                            id="plate_number"
                                            name="plate_number"
                                            placeholder="e.g. ABC1234"
                                            value="{{ old('plate_number') }}"
                                            required
                                            style="text-transform: uppercase;"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="type">Vehicle Type</label>
                                        <select class="form-select-premium" id="type" name="type" required>
                                            <option value="" selected disabled>Select Vehicle Type</option>
                                            <option value="KERETA" {{ old('type') == 'KERETA' ? 'selected' : '' }}>KERETA (CAR)</option>
                                            <option value="MOTOR" {{ old('type') == 'MOTOR' ? 'selected' : '' }}>MOTOSIKAL (MOTORCYCLE)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="brand">Brand</label>
                                        <select class="form-select-premium" id="brand" name="brand" required>
                                            <option value="" selected disabled>Select Brand</option>
                                            <option value="PROTON" {{ old('brand') == 'PROTON' ? 'selected' : '' }}>PROTON</option>
                                            <option value="PERODUA" {{ old('brand') == 'PERODUA' ? 'selected' : '' }}>PERODUA</option>
                                            <option value="HONDA" {{ old('brand') == 'HONDA' ? 'selected' : '' }}>HONDA</option>
                                            <option value="TOYOTA" {{ old('brand') == 'TOYOTA' ? 'selected' : '' }}>TOYOTA</option>
                                            <option value="NISSAN" {{ old('brand') == 'NISSAN' ? 'selected' : '' }}>NISSAN</option>
                                            <option value="YAMAHA" {{ old('brand') == 'YAMAHA' ? 'selected' : '' }}>YAMAHA</option>
                                            <option value="MODENAS" {{ old('brand') == 'MODENAS' ? 'selected' : '' }}>MODENAS</option>
                                            <option value="SYM" {{ old('brand') == 'SYM' ? 'selected' : '' }}>SYM</option>
                                            <option value="KAWASAKI" {{ old('brand') == 'KAWASAKI' ? 'selected' : '' }}>KAWASAKI</option>
                                            <option value="SUZUKI" {{ old('brand') == 'SUZUKI' ? 'selected' : '' }}>SUZUKI</option>
                                            <option value="MITSUBISHI" {{ old('brand') == 'MITSUBISHI' ? 'selected' : '' }}>MITSUBISHI</option>
                                            <option value="MAZDA" {{ old('brand') == 'MAZDA' ? 'selected' : '' }}>MAZDA</option>
                                            <option value="KIA" {{ old('brand') == 'KIA' ? 'selected' : '' }}>KIA</option>
                                            <option value="HYUNDAI" {{ old('brand') == 'HYUNDAI' ? 'selected' : '' }}>HYUNDAI</option>
                                            <option value="OTHER" {{ old('brand') == 'OTHER' ? 'selected' : '' }}>OTHER</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="model">Model</label>
                                        <input
                                            type="text"
                                            class="form-control-premium"
                                            id="model"
                                            name="model"
                                            placeholder="e.g. MYVI, EX5"
                                            value="{{ old('model') }}"
                                            required
                                            style="text-transform: uppercase;"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="color">Color</label>
                                        <input
                                            type="text"
                                            class="form-control-premium"
                                            id="color"
                                            name="color"
                                            placeholder="e.g. RED, WHITE"
                                            value="{{ old('color') }}"
                                            required
                                            style="text-transform: uppercase;"
                                        >
                                    </div>
                                </div>
                            </div>

                            {{-- Sticker Info Section --}}
                            <div class="section-divider"><span>Sticker Information</span></div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-premium" for="sticker_number">Sticker Number</label>
                                        <input
                                            type="text"
                                            class="form-control-premium"
                                            id="sticker_number"
                                            name="sticker_number"
                                            placeholder="e.g. STK-2025-001"
                                            value="{{ old('sticker_number') }}"
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="info-badge">
                                        <i class="mdi mdi-shield-check"></i>
                                        Registration status will be set to <strong>&nbsp;SAH</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <button type="reset" class="btn btn-outline-secondary me-2" style="border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                                    <i class="mdi mdi-refresh"></i> Reset
                                </button>
                                <button type="submit" class="btn-premium">
                                    <i class="mdi mdi-content-save"></i> Register Vehicle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Recent Registrations Table --}}
                @if(isset($records) && $records->count() > 0)
                <div class="premium-card">
                    <div class="premium-card-header">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        <b>Recent Non-User Registrations</b>
                    </div>
                    <div class="premium-card-body p-0">
                        <div class="table-responsive">
                            <table class="vehicle-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Staff Name</th>
                                        <th>Staff IC</th>
                                        <th>Plate Number</th>
                                        <th>Type</th>
                                        <th>Brand</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Sticker No.</th>
                                        <th>Status</th>
                                        <th>Date Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $record->name }}</strong></td>
                                        <td>{{ $record->ic }}</td>
                                        <td><strong>{{ $record->plate_number }}</strong></td>
                                        <td>{{ $record->type }}</td>
                                        <td>{{ $record->brand }}</td>
                                        <td>{{ $record->model }}</td>
                                        <td>{{ $record->color }}</td>
                                        <td>
                                            @if($record->sticker_number)
                                                <strong>{{ $record->sticker_number }}</strong>
                                            @else
                                                <span class="text-muted fst-italic">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge-status {{ strtolower($record->status) }}">
                                                {{ $record->status }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </section>
    </div>
</div>

@endsection
