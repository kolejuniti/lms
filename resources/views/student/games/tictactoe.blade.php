@extends('layouts.student')

@section('title', 'Tic Tac Toe')

@section('main')
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
                        <div id="opponentSelection" class="text-center">
                            <h4>Select an opponent:</h4>
                            <div class="form-group">
                                <select class="form-control" id="opponentSelect" style="width: 300px; margin: 0 auto;">
                                    <option value="">Choose a student...</option>
                                    @foreach($availableStudents as $student)
                                        <option value="{{ $student->ic }}">{{ $student->name }} ({{ $student->no_matric }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary" id="startGameBtn">Start Game</button>
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

<script>
let currentGame = null;
let gameId = null;
let isMyTurn = false;
let playerSymbol = '';
let gameInterval = null;

$(document).ready(function() {
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