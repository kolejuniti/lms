@extends('layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Barcode Generate</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Examination</li>
                <li class="breadcrumb-item active" aria-current="page">Barcode Generate</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Generate Barcodes</h3>
        </div>
        <div class="card-body">
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              {{ session('error') }}
            </div>
          @endif

          <form action="{{ route('pendaftar_akademik.barcodeGenerate.submit') }}" method="POST" target="_blank">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="from">From Number</label>
                  <input type="text" class="form-control" id="from" name="from" placeholder="e.g., 0001" required>
                  <small class="form-text text-muted">Enter the starting number (e.g., 0001, 0010, 1000)</small>
                  @error('from')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="to">To Number</label>
                  <input type="text" class="form-control" id="to" name="to" placeholder="e.g., 0030" required>
                  <small class="form-text text-muted">Enter the ending number (e.g., 0030, 0050, 1100)</small>
                  @error('to')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <div class="alert alert-info">
                  <strong>Note:</strong>
                  <ul class="mb-0">
                    <li>The barcodes will be generated in a 5 column x 10 row layout suitable for A4 paper</li>
                    <li>Each page will contain 50 barcodes (5 x 10)</li>
                    <li>Leading zeros will be preserved (e.g., 0001 to 0030)</li>
                    <li>The result will open in a new window ready for printing</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-barcode"></i> Generate Barcodes
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection
