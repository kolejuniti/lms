@extends('layouts.student')

@section('title', 'Tic Tac Toe')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Select2 Custom Styling - High Specificity Override */
#opponentSelection .select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--single {
    height: 45px !important;
    border: 2px solid #e1e5e9 !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    background: #ffffff !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04) !important;
    line-height: 27px !important;
}

#opponentSelection .select2-container--default .select2-selection--single:hover,
.select2-container--default .select2-selection--single:hover {
    border-color: #c3d1e4 !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
}

#opponentSelection .select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #4f84e7 !important;
    box-shadow: 0 0 0 3px rgba(79, 132, 231, 0.1) !important;
    outline: none !important;
}

#opponentSelection .select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057 !important;
    line-height: 27px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    padding-left: 0 !important;
    padding-right: 20px !important;
}

#opponentSelection .select2-container--default .select2-selection--single .select2-selection__placeholder,
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #868e96 !important;
    font-weight: 400 !important;
}

#opponentSelection .select2-container--default .select2-selection--single .select2-selection__arrow,
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 43px !important;
    right: 12px !important;
    width: 20px !important;
}

#opponentSelection .select2-container--default .select2-selection--single .select2-selection__arrow b,
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #6c757d transparent transparent transparent !important;
    border-width: 6px 6px 0 6px !important;
    border-style: solid !important;
}

.select2-dropdown {
    border: 2px solid #e1e5e9 !important;
    border-radius: 8px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    border-top: 2px solid #4f84e7 !important;
    z-index: 9999 !important;
}

.select2-search--dropdown .select2-search__field {
    border: 2px solid #e1e5e9 !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    font-size: 14px !important;
}

.select2-search--dropdown .select2-search__field:focus {
    border-color: #4f84e7 !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(79, 132, 231, 0.1) !important;
}

.select2-results__option {
    padding: 12px 16px !important;
    font-size: 14px !important;
    transition: all 0.2s ease !important;
}

.select2-results__option--highlighted {
    background-color: #f8f9fa !important;
    color: #495057 !important;
    border-left: 3px solid #4f84e7 !important;
}

.select2-results__option--selected {
    background-color: #4f84e7 !important;
    color: white !important;
}

.select2-results__message {
    padding: 12px 16px !important;
    color: #6c757d !important;
    font-style: italic !important;
    font-size: 13px !important;
}

/* Opponent Selection Styling */
#opponentSelection {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
    border-radius: 12px !important;
    padding: 32px !important;
    margin: 24px auto !important;
    max-width: 500px !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08) !important;
    border: 1px solid #e9ecef !important;
}

#opponentSelection h4 {
    color: #2c3e50 !important;
    font-weight: 600 !important;
    margin-bottom: 24px !important;
    font-size: 18px !important;
    text-align: center !important;
}

#opponentSelection .form-group {
    margin-bottom: 24px !important;
}

#opponentSelection .select2-container {
    width: 100% !important;
}

#startGameBtn {
    background: linear-gradient(135deg, #4f84e7 0%, #3a6dcf 100%) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 12px 32px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    color: white !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 12px rgba(79, 132, 231, 0.3) !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    display: block !important;
    margin: 0 auto !important;
}

#startGameBtn:hover {
    background: linear-gradient(135deg, #3a6dcf 0%, #2952b3 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(79, 132, 231, 0.4) !important;
}

#startGameBtn:active {
    transform: translateY(0) !important;
    box-shadow: 0 2px 8px rgba(79, 132, 231, 0.3) !important;
}

#startGameBtn:disabled {
    background: #6c757d !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Override any conflicting styles */
.select2-container .select2-selection--single {
    height: auto !important;
}

.select2-container .select2-selection--single .select2-selection__rendered {
    padding-left: 12px !important;
    padding-right: 20px !important;
}

/* Mobile responsive */
@media (max-width: 768px) {
    #opponentSelection {
        margin: 16px !important;
        padding: 24px 16px !important;
    }
    
    .select2-container--default .select2-selection--single {
        height: 50px !important;
        padding: 12px 16px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
    }
}
</style>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tic Tac Toe
            <small>Play with your friends</small>
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('studentDashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.games.lobby') }}">Game Lobby</a></li>
            <li class="breadcrumb-item active">Tic Tac Toe</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="gameTitle">Tic Tac Toe</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-primary" id="newGameBtn">New Game</button>
                            <a href="{{ route('student.games.lobby') }}" class="btn btn-secondary">Back to Lobby</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- Game Status -->
                        <div class="game-status mb-4 text-center">
                            <h4 id="gameStatus">Select an opponent to start playing</h4>
                            <div id="playerInfo" style="display: none;">
                                <p><strong>Player 1 (X):</strong> <span id="player1Name"></span></p>
                                <p><strong>Player 2 (O):</strong> <span id="player2Name"></span></p>
                                <p><strong>Current Turn:</strong> <span id="currentTurn"></span></p>
                            </div>
                        </div>

                        <!-- Game Board -->
                        <div class="game-container text-center">
                            <div class="tic-tac-toe-board" id="gameBoard" style="display: none;">
                                <div class="board-row">
                                    <div class="board-cell" data-position="0"></div>
                                    <div class="board-cell" data-position="1"></div>
                                    <div class="board-cell" data-position="2"></div>
                                </div>
                                <div class="board-row">
                                    <div class="board-cell" data-position="3"></div>
                                    <div class="board-cell" data-position="4"></div>
                                    <div class="board-cell" data-position="5"></div>
                                </div>
                                <div class="board-row">
                                    <div class="board-cell" data-position="6"></div>
                                    <div class="board-cell" data-position="7"></div>
                                    <div class="board-cell" data-position="8"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Opponent Selection -->
                        <div id="opponentSelection">
                            <h4>Select an opponent:</h4>
                            <div class="form-group">
                                <select class="form-control select2" id="opponentSelect">
                                    <option value="">Search and select a student...</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" id="startGameBtn">Start Game</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.tic-tac-toe-board {
    display: inline-block;
    margin: 20px auto;
}

.board-row {
    display: flex;
    justify-content: center;
}

.board-cell {
    width: 80px;
    height: 80px;
    border: 3px solid #333;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: bold;
    cursor: pointer;
    background-color: #f8f9fa;
    transition: background-color 0.3s;
}

.board-cell:hover {
    background-color: #e9ecef;
}

.board-cell.disabled {
    cursor: not-allowed;
    background-color: #f5f5f5;
}

.board-cell:nth-child(1), .board-cell:nth-child(2) {
    border-right: none;
}

.board-cell:nth-child(3) {
    border-right: 3px solid #333;
}

.board-row:nth-child(1) .board-cell, .board-row:nth-child(2) .board-cell {
    border-bottom: none;
}

.board-row:nth-child(3) .board-cell {
    border-bottom: 3px solid #333;
}

.winner-cell {
    background-color: #d4edda !important;
    color: #155724;
}

.game-status {
    min-height: 60px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
console.log('SCRIPT STARTED - Testing if JavaScript works');

let currentGame = null;
let gameId = null;
let isMyTurn = false;
let playerSymbol = '';
let gameInterval = null;

$(document).ready(function() {
    console.log('Document ready - initializing Select2');
    
    // Simple test function
    window.testSelect2 = function() {
        console.log('Testing Select2 manually');
        $('#opponentSelect').select2({
            placeholder: 'Search and select a student...',
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: '{{ route("student.search") }}',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    console.log('AJAX data function called with params:', params);
                    return {
                        search: params.term,
                        _token: '{{ csrf_token() }}'
                    };
                },
                processResults: function (data) {
                    console.log('Search results received:', data);
                    return {
                        results: data.map(function(student) {
                            return {
                                id: student.ic,
                                text: student.name + ' (' + student.no_matric + ')'
                            };
                        })
                    };
                }
            }
        });
    };
    
    // Call the test function
    testSelect2();

    // Check if there's a game_id in URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const gameIdFromUrl = urlParams.get('game_id');
    
    if (gameIdFromUrl) {
        loadGame(gameIdFromUrl);
    }

    // Start new game
    $('#startGameBtn').click(function() {
        const opponentIc = $('#opponentSelect').val();
        if (!opponentIc) {
            alert('Please select an opponent');
            return;
        }

        $.ajax({
            url: '{{ route("student.games.create") }}',
            method: 'POST',
            data: {
                game_type: 'tic_tac_toe',
                opponent_ic: opponentIc,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                gameId = response.game_id;
                $('#gameStatus').text('Game invitation sent! Waiting for opponent to accept...');
                $('#opponentSelection').hide();
                startGamePolling();
            },
            error: function(xhr) {
                const error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred';
                alert('Error: ' + error);
            }
        });
    });

    // Board cell click
    $(document).on('click', '.board-cell', function() {
        if (!isMyTurn || $(this).text() !== '' || !gameId) {
            return;
        }

        const position = parseInt($(this).data('position'));
        makeMove(position);
    });

    // New game button
    $('#newGameBtn').click(function() {
        location.reload();
    });
});

function loadGame(id) {
    gameId = id;
    
    $.ajax({
        url: `/student/games/${gameId}`,
        method: 'GET',
        success: function(game) {
            currentGame = game;
            displayGame(game);
            if (game.status === 'active') {
                startGamePolling();
            }
        },
        error: function(xhr) {
            alert('Game not found or access denied');
            window.location.href = '{{ route("student.games.lobby") }}';
        }
    });
}

function displayGame(game) {
    $('#opponentSelection').hide();
    $('#gameBoard').show();
    $('#playerInfo').show();
    
    $('#player1Name').text(game.player1_name);
    $('#player2Name').text(game.player2_name);
    
    playerSymbol = game.player_symbol;
    isMyTurn = game.is_current_player;
    
    updateGameStatus(game);
    updateBoard(game.board_state);
}

function updateGameStatus(game) {
    let status = '';
    
    if (game.status === 'waiting') {
        status = 'Waiting for opponent to accept...';
    } else if (game.status === 'active') {
        if (game.winner_ic) {
            if (game.winner_ic === 'draw') {
                status = "It's a draw!";
            } else {
                const winnerName = game.winner_ic === game.player1_ic ? game.player1_name : game.player2_name;
                status = `${winnerName} wins!`;
            }
        } else {
            const currentPlayerName = game.current_turn === game.player1_ic ? game.player1_name : game.player2_name;
            status = `${currentPlayerName}'s turn`;
            
            if (isMyTurn) {
                status += ' (Your turn)';
            }
        }
         } else if (game.status === 'completed') {
         if (game.winner_ic === 'draw') {
             status = "Game completed - It's a draw!";
         } else {
             const winnerName = game.winner_ic === game.player1_ic ? game.player1_name : game.player2_name;
             status = `Game completed - ${winnerName} wins!`;
         }
     }
    
    $('#gameStatus').text(status);
    $('#currentTurn').text(game.current_turn === game.player1_ic ? game.player1_name : game.player2_name);
}

function updateBoard(boardState) {
    $('.board-cell').each(function(index) {
        $(this).text(boardState[index] || '');
        $(this).removeClass('disabled');
        
        if (boardState[index] || !isMyTurn || currentGame?.status === 'completed') {
            $(this).addClass('disabled');
        }
    });
}

function makeMove(position) {
    $.ajax({
        url: '{{ route("student.games.move") }}',
        method: 'POST',
        data: {
            game_id: gameId,
            position: position,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            // Update the local board immediately
            $(`.board-cell[data-position="${position}"]`).text(playerSymbol);
            isMyTurn = false;
            $('.board-cell').addClass('disabled');
            
            if (response.winner) {
                $('#gameStatus').text(response.winner === playerSymbol ? 'You win!' : 'You lose!');
                stopGamePolling();
            } else if (response.is_draw) {
                $('#gameStatus').text("It's a draw!");
                stopGamePolling();
            } else {
                $('#gameStatus').text("Opponent's turn");
            }
        },
        error: function(xhr) {
            const error = xhr.responseJSON ? xhr.responseJSON.error : 'An error occurred';
            alert('Error: ' + error);
        }
    });
}

function startGamePolling() {
    gameInterval = setInterval(function() {
        if (gameId) {
            loadGame(gameId);
        }
    }, 2000); // Poll every 2 seconds
}

function stopGamePolling() {
    if (gameInterval) {
        clearInterval(gameInterval);
        gameInterval = null;
    }
}

// Clean up interval when leaving page
$(window).on('beforeunload', function() {
    stopGamePolling();
});
</script>
@endsection 