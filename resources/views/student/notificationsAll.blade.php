@extends('layouts.student')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Notifications</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="#">
                    <i class="mdi mdi-home-outline"></i>
                  </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Notifications</li>
              </ol>
            </nav>
          </div>
        </div>
        <div>
          <!-- Mark All as Read Button -->
          <button id="markAllRead" class="btn btn-primary">Mark All as Read</button>
        </div>
      </div>
    </div>
    <!-- Main Content -->
    <section class="content">
      <div class="row">
        <div class="col-xl-12 col-12">
          <div class="box">
            <div class="box-body p-30">
              @if(auth()->guard('student')->check())
                @php
                  $notifications = auth()->guard('student')->user()->notifications;
                @endphp

                @if($notifications->count() > 0)
                  <div class="row" id="notificationsContainer">
                    @foreach($notifications as $notification)
                      <!-- Make each card occupy a full row (col-12) -->
                      <div class="col-12 mb-3">
                        <div class="notification-card card {{ is_null($notification->read_at) ? 'unread' : 'read' }}"
                             data-id="{{ $notification->id }}"
                             style="border-radius: 8px;">
                          <div class="card-body">
                            <h5 class="card-title">
                              <a href="{{ $notification->data['url'] ?? '#' }}" class="notification-link">
                                {{ $notification->data['message'] ?? 'No message provided.' }}
                              </a>
                            </h5>
                            <p class="card-text">
                              <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </p>
                            @if(is_null($notification->read_at))
                              <button class="btn btn-sm btn-outline-primary mark-read" data-id="{{ $notification->id }}">
                                Mark as Read
                              </button>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="text-center">
                    <p>No notifications found.</p>
                  </div>
                @endif
              @else
                <div class="text-center">
                  <p>Please log in to view your notifications.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>
@endsection

@section('styles')
<style>
  /* Notification Card Styling */
  .notification-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
  }
  .notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
  }
  .notification-card.unread {
    border-left: 4px solid #4f81c7;
    background-color: #f9f9f9;
  }
  .notification-card.read {
    background-color: #fff;
  }
</style>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mark a single notification as read via AJAX
    document.querySelectorAll('.mark-read').forEach(function(button) {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        var notificationId = this.getAttribute('data-id');
        var card = this.closest('.notification-card');

        fetch('/notifications/mark-read/' + notificationId, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        }).then(response => {
          if(response.ok) {
            card.classList.remove('unread');
            card.classList.add('read');
            this.remove(); // Remove the "Mark as Read" button
          }
        });
      });
    });

    // Mark all notifications as read via AJAX
    document.getElementById('markAllRead').addEventListener('click', function() {
      fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      }).then(response => {
        if(response.ok) {
          document.querySelectorAll('.notification-card.unread').forEach(function(card) {
            card.classList.remove('unread');
            card.classList.add('read');
            // Remove individual "Mark as Read" buttons
            card.querySelectorAll('.mark-read').forEach(btn => btn.remove());
          });
        }
      });
    });
  });
</script>
@endsection
