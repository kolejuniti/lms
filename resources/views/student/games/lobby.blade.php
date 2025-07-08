@extends('layouts.student')

@section('title', 'Game Lobby')

@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Game Lobby
            <small>Play games with your friends</small>
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('studentDashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">Game Lobby</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Game Invitations -->
            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Game Invitations</h3>
                    </div>
                    <div class="box-body">
                        @if($gameInvitations->count() > 0)
                            @foreach($gameInvitations as $invitation)
                                <div class="invitation-item mb-3 p-3" style="border: 1px solid #ddd; border-radius: 5px;">
                                    <h5>{{ $invitation->sender_name }}</h5>
                                    <p class="mb-1">wants to play <strong>{{ ucfirst($invitation->game_type) }}</strong></p>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($invitation->created_at)->diffForHumans() }}</small>
                                    <div class="mt-2">
                                        <button class="btn btn-success btn-sm accept-invitation" data-id="{{ $invitation->id }}">
                                            Accept
                                        </button>
                                        <button class="btn btn-danger btn-sm decline-invitation" data-id="{{ $invitation->id }}">
                                            Decline
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No game invitations</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Available Students -->
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Available Students</h3>
                    </div>
                    <div class="box-body">
                        @if($onlineStudents->count() > 0)
                            @foreach($onlineStudents as $student)
                                <div class="student-item mb-2 p-2" style="border: 1px solid #ddd; border-radius: 3px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $student->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $student->no_matric }}</small>
                                        </div>
                                        <button class="btn btn-primary btn-xs invite-player" data-ic="{{ $student->ic }}" data-name="{{ $student->name }}">
                                            Invite
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No available students</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Active Games -->
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Active Games</h3>
                    </div>
                    <div class="box-body">
                        @if($activeGames->count() > 0)
                            @foreach($activeGames as $game)
                                <div class="game-item mb-3 p-3" style="border: 1px solid #ddd; border-radius: 5px;">
                                    <h5>{{ ucfirst($game->game_type) }}</h5>
                                    <p class="mb-1">
                                        <strong>{{ $game->player1_name }}</strong> vs <strong>{{ $game->player2_name }}</strong>
                                    </p>
                                    <small class="text-muted">Started {{ \Carbon\Carbon::parse($game->created_at)->diffForHumans() }}</small>
                                    <div class="mt-2">
                                        <a href="{{ route('student.games.tictactoe') }}?game_id={{ $game->id }}" class="btn btn-success btn-sm">
                                            Continue Game
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No active games</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Game Buttons -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Quick Games</h3>
                    </div>
                    <div class="box-body text-center">
                        <a href="{{ route('student.games.tictactoe') }}" class="btn btn-lg btn-primary">
                            <i data-feather="grid-3x3"></i> Play Tic Tac Toe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Game Invitation Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Invite Player</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="inviteForm">
                    <input type="hidden" id="opponent_ic" name="opponent_ic">
                    <div class="form-group">
                        <label>Player:</label>
                        <p id="player_name"></p>
                    </div>
                    <div class="form-group">
                        <label for="game_type">Game Type:</label>
                        <select class="form-control" id="game_type" name="game_type" required>
                            <option value="tic_tac_toe">Tic Tac Toe</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sendInvite">Send Invitation</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Invite player
    $('.invite-player').click(function() {
        var ic = $(this).data('ic');
        var name = $(this).data('name');
        
        $('#opponent_ic').val(ic);
        $('#player_name').text(name);
        $('#inviteModal').modal('show');
    });

    // Send invitation
    $('#sendInvite').click(function() {
        var formData = {
            opponent_ic: $('#opponent_ic').val(),
            game_type: $('#game_type').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("student.games.create") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                alert('Invitation sent successfully!');
                $('#inviteModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                var error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred';
                alert('Error: ' + error);
            }
        });
    });

    // Accept invitation
    $('.accept-invitation').click(function() {
        var invitationId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("student.games.accept") }}',
            method: 'POST',
            data: {
                invitation_id: invitationId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Game started!');
                location.reload();
            },
            error: function(xhr) {
                var error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred';
                alert('Error: ' + error);
            }
        });
    });

    // Auto-refresh every 10 seconds
    setInterval(function() {
        location.reload();
    }, 10000);
});
</script>
@endsection 