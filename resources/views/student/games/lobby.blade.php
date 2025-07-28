@extends('layouts.student')

@section('title', 'Game Lobby')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    * {
        font-family: 'Poppins', sans-serif;
    }
    
    .content-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
    }
    
    .lobby-header {
        text-align: center;
        color: white;
        margin-bottom: 40px;
        animation: fadeInDown 1s ease-out;
    }
    
    .lobby-header h1 {
        font-size: 3rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        margin-bottom: 10px;
    }
    
    .lobby-header p {
        font-size: 1.2rem;
        opacity: 0.9;
        font-weight: 300;
    }
    
    .game-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .game-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s;
    }
    
    .game-card:hover::before {
        left: 100%;
    }
    
    .game-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-right: 15px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    }
    
    .card-icon.invitations { background: linear-gradient(135deg, #4ecdc4, #44a08d); }
    .card-icon.players { background: linear-gradient(135deg, #45b7d1, #96c93d); }
    .card-icon.games { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .card-icon.stats { background: linear-gradient(135deg, #ffecd2, #fcb69f); color: #333; }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .card-subtitle {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }
    
    .invitation-item, .student-item, .game-item {
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .invitation-item:hover, .student-item:hover, .game-item:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #007bff;
    }
    
    .player-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 18px;
        margin-right: 15px;
    }
    
    .player-info {
        flex: 1;
    }
    
    .player-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .player-details {
        font-size: 0.85rem;
        color: #666;
    }
    
    .online-indicator {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #4CAF50;
        animation: pulse 2s infinite;
    }
    
    .btn-modern {
        border: none;
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-accept {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
    }
    
    .btn-decline {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
    }
    
    .btn-invite {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
    }
    
    .btn-continue {
        background: linear-gradient(135deg, #FF9800, #F57C00);
        color: white;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }
    
    .quick-games {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }
    
    .game-button {
        display: inline-block;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px 40px;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 10px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    
    .game-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.6s;
    }
    
    .game-button:hover::before {
        left: 100%;
    }
    
    .game-button:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    
    .stat-item {
        text-align: center;
        padding: 15px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #667eea;
        display: block;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .empty-state h4 {
        margin-bottom: 10px;
        color: #333;
    }
    
    .empty-state p {
        margin: 0;
        font-size: 0.9rem;
    }
    
    .notification-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ff4757;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        animation: bounce 1s infinite;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(76, 175, 80, 0); }
        100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    .modal-modern .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    }
    
    .modal-modern .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 20px 20px 0 0;
        border-bottom: none;
    }
    
    .modal-modern .modal-body {
        padding: 30px;
    }
    
    .game-type-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .game-type-option:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .game-type-option.selected {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }
    
    .game-type-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    @media (max-width: 768px) {
        .lobby-header h1 {
            font-size: 2rem;
        }
        
        .game-card {
            margin-bottom: 20px;
            padding: 20px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('main')
<div class="content-wrapper">
    <!-- Lobby Header -->
    <div class="lobby-header">
        <h1><i class="fas fa-gamepad"></i> Game Lobby</h1>
        <p>Connect, compete, and have fun with your classmates!</p>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Game Invitations -->
            <div class="col-lg-4 col-md-6">
                <div class="game-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header">
                        <div class="card-icon invitations">
                            <i class="fas fa-envelope"></i>
                            @if($gameInvitations->count() > 0)
                                <span class="notification-badge">{{ $gameInvitations->count() }}</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="card-title">Invitations</h3>
                            <p class="card-subtitle">Pending game requests</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if($gameInvitations->count() > 0)
                            @foreach($gameInvitations as $invitation)
                                <div class="invitation-item">
                                    <div class="d-flex align-items-center">
                                        <div class="player-avatar">
                                            {{ substr($invitation->sender_name, 0, 1) }}
                                        </div>
                                        <div class="player-info">
                                            <div class="player-name">{{ $invitation->sender_name }}</div>
                                            <div class="player-details">
                                                <i class="fas fa-gamepad"></i> {{ ucfirst(str_replace('_', ' ', $invitation->game_type)) }}
                                                <br>
                                                <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($invitation->created_at)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <button class="btn btn-modern btn-accept me-2 accept-invitation" data-id="{{ $invitation->id }}">
                                            <i class="fas fa-check"></i> Accept
                                        </button>
                                        <button class="btn btn-modern btn-decline decline-invitation" data-id="{{ $invitation->id }}">
                                            <i class="fas fa-times"></i> Decline
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h4>No Invitations</h4>
                                <p>You have no pending game invitations</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Available Players -->
            <div class="col-lg-4 col-md-6">
                <div class="game-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header">
                        <div class="card-icon players">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="card-title">Online Players</h3>
                            <p class="card-subtitle">{{ $onlineStudents->count() }} students available</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if($onlineStudents->count() > 0)
                            @foreach($onlineStudents as $student)
                                <div class="student-item">
                                    <div class="online-indicator"></div>
                                    <div class="d-flex align-items-center">
                                        <div class="player-avatar">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div class="player-info">
                                            <div class="player-name">{{ $student->name }}</div>
                                            <div class="player-details">
                                                <i class="fas fa-id-card"></i> {{ $student->no_matric }}
                                                <br>
                                                <i class="fas fa-circle text-success"></i> Online now
                                            </div>
                                        </div>
                                        <button class="btn btn-modern btn-invite invite-player" data-ic="{{ $student->ic }}" data-name="{{ $student->name }}">
                                            <i class="fas fa-paper-plane"></i> Invite
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-user-slash"></i>
                                <h4>No Players Online</h4>
                                <p>No other students are currently online</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Active Games -->
            <div class="col-lg-4 col-md-6">
                <div class="game-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header">
                        <div class="card-icon games">
                            <i class="fas fa-play"></i>
                        </div>
                        <div>
                            <h3 class="card-title">Active Games</h3>
                            <p class="card-subtitle">Your ongoing matches</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if($activeGames->count() > 0)
                            @foreach($activeGames as $game)
                                <div class="game-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="player-name">
                                                <i class="fas fa-gamepad"></i> {{ ucfirst(str_replace('_', ' ', $game->game_type)) }}
                                            </div>
                                            <div class="player-details">
                                                {{ $game->player1_name }} <span class="text-primary">vs</span> {{ $game->player2_name }}
                                                <br>
                                                <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($game->created_at)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        @php
                                            $gameRoute = $game->game_type === 'connect_four' 
                                                ? route('student.games.connectfour') 
                                                : route('student.games.tictactoe');
                                        @endphp
                                        <a href="{{ $gameRoute }}?game_id={{ $game->id }}" class="btn btn-modern btn-continue">
                                            <i class="fas fa-play"></i> Continue Game
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-gamepad"></i>
                                <h4>No Active Games</h4>
                                <p>Start a new game with your friends!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Player Stats -->
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="game-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-header">
                        <div class="card-icon stats">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h3 class="card-title">Your Stats</h3>
                            <p class="card-subtitle">Game performance overview</p>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-number">{{ $playerStats['total_games'] ?? 0 }}</span>
                            <span class="stat-label">Total Games</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $playerStats['wins'] ?? 0 }}</span>
                            <span class="stat-label">Wins</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $playerStats['losses'] ?? 0 }}</span>
                            <span class="stat-label">Losses</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $playerStats['draws'] ?? 0 }}</span>
                            <span class="stat-label">Draws</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Games -->
            <div class="col-lg-6">
                <div class="quick-games" data-aos="fade-up" data-aos-delay="500">
                    <h3 style="margin-bottom: 30px; color: #333; font-weight: 600;">
                        <i class="fas fa-rocket"></i> Quick Start
                    </h3>
                    <a href="{{ route('student.games.tictactoe') }}" class="game-button">
                        <i class="fas fa-th"></i> Tic Tac Toe
                    </a>
                    <a href="{{ route('student.games.connectfour') }}" class="game-button">
                        <i class="fas fa-circle"></i> Connect Four
                    </a>
                    <div style="margin-top: 20px; color: #666; font-size: 0.9rem;">
                        More games coming soon!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Game Invitation Modal -->
<div class="modal fade modal-modern" id="inviteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-paper-plane"></i> Send Game Invitation</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="inviteForm">
                    <input type="hidden" id="opponent_ic" name="opponent_ic">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Inviting Player:</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded-3">
                            <div class="player-avatar me-3">
                                <span id="player_initial"></span>
                            </div>
                            <div>
                                <div class="fw-bold" id="player_name"></div>
                                <small class="text-muted">Ready to play!</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Choose Game Type:</label>
                        <div>
                            <div class="game-type-option selected" data-game="tic_tac_toe">
                                <div class="game-type-icon">
                                    <i class="fas fa-th"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Tic Tac Toe</div>
                                    <small class="text-muted">Classic 3x3 strategy game</small>
                                </div>
                            </div>
                            <div class="game-type-option" data-game="connect_four">
                                <div class="game-type-icon">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Connect Four</div>
                                    <small class="text-muted">Drop pieces to get four in a row</small>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="game_type" name="game_type" value="tic_tac_toe">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-modern" style="background: #6c757d; color: white;" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-modern" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;" id="sendInvite">
                    <i class="fas fa-paper-plane"></i> Send Invitation
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Invite player
    $('.invite-player').click(function() {
        var ic = $(this).data('ic');
        var name = $(this).data('name');
        
        $('#opponent_ic').val(ic);
        $('#player_name').text(name);
        $('#player_initial').text(name.charAt(0));
        
        // Reset game type selection to default (tic_tac_toe)
        $('.game-type-option').removeClass('selected');
        $('.game-type-option[data-game="tic_tac_toe"]').addClass('selected');
        $('#game_type').val('tic_tac_toe');
        
        var modal = new bootstrap.Modal(document.getElementById('inviteModal'));
        modal.show();
    });

    // Game type selection
    $('.game-type-option').click(function() {
        $('.game-type-option').removeClass('selected');
        $(this).addClass('selected');
        $('#game_type').val($(this).data('game'));
    });

    // Send invitation with better feedback
    $('#sendInvite').click(function() {
        var btn = $(this);
        var originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Sending...');
        btn.prop('disabled', true);
        
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
                // Success animation
                btn.html('<i class="fas fa-check"></i> Sent!');
                btn.css('background', 'linear-gradient(135deg, #4CAF50, #45a049)');
                
                setTimeout(function() {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('inviteModal'));
                    modal.hide();
                    
                    // Show success notification
                    showNotification('Invitation sent successfully!', 'success');
                    
                    // Reset button after modal closes
                    setTimeout(function() {
                        btn.html(originalText);
                        btn.css('background', 'linear-gradient(135deg, #667eea, #764ba2)');
                        btn.prop('disabled', false);
                    }, 500);
                    
                    // Refresh page data
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                var error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred';
                btn.html(originalText);
                btn.prop('disabled', false);
                showNotification('Error: ' + error, 'error');
            }
        });
    });

    // Accept invitation with animation
    $('.accept-invitation').click(function() {
        var btn = $(this);
        var invitationId = $(this).data('id');
        var originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("student.games.accept") }}',
            method: 'POST',
            data: {
                invitation_id: invitationId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                btn.html('<i class="fas fa-check"></i> Accepted!');
                showNotification('Game started! Redirecting...', 'success');
                
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                var error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred';
                btn.html(originalText);
                btn.prop('disabled', false);
                showNotification('Error: ' + error, 'error');
            }
        });
    });

    // Decline invitation
    $('.decline-invitation').click(function() {
        var btn = $(this);
        var invitationId = $(this).data('id');
        var originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("student.games.decline") }}',
            method: 'POST',
            data: {
                invitation_id: invitationId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                btn.html('<i class="fas fa-check"></i> Declined');
                showNotification('Invitation declined', 'info');
                
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                var error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred';
                btn.html(originalText);
                btn.prop('disabled', false);
                showNotification('Error: ' + error, 'error');
            }
        });
    });

    // Auto-refresh with visual indicator
    var refreshInterval = 15000; // 15 seconds
    var refreshTimer;
    
    function startRefreshTimer() {
        refreshTimer = setTimeout(function() {
            // Add subtle loading indicator
            $('body').append('<div id="autoRefreshIndicator" style="position: fixed; top: 20px; right: 20px; background: rgba(102, 126, 234, 0.9); color: white; padding: 10px 15px; border-radius: 8px; z-index: 9999; font-size: 0.9rem;"><i class="fas fa-sync fa-spin"></i> Refreshing...</div>');
            
            setTimeout(function() {
                location.reload();
            }, 1000);
        }, refreshInterval);
    }
    
    startRefreshTimer();
    
    // Reset timer on user interaction
    $(document).on('click keypress', function() {
        clearTimeout(refreshTimer);
        startRefreshTimer();
    });
});

// Enhanced notification system
function showNotification(message, type) {
    var bgColor = type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3';
    var icon = type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info';
    
    var notification = $(`
        <div style="
            position: fixed; 
            top: 20px; 
            right: 20px; 
            background: ${bgColor}; 
            color: white; 
            padding: 15px 20px; 
            border-radius: 8px; 
            z-index: 9999; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-weight: 500;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        ">
            <i class="fas fa-${icon}"></i> ${message}
        </div>
    `);
    
    $('body').append(notification);
    
    // Animate in
    setTimeout(function() {
        notification.css({
            'opacity': '1',
            'transform': 'translateX(0)'
        });
    }, 100);
    
    // Animate out
    setTimeout(function() {
        notification.css({
            'opacity': '0',
            'transform': 'translateX(100%)'
        });
        setTimeout(function() {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection 