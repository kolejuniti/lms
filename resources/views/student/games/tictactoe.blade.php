@extends('layouts.student')

@section('title', 'Tic Tac Toe')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        position: relative;
        overflow-x: hidden;
    }
    
    .game-header {
        text-align: center;
        color: white;
        margin-bottom: 30px;
        animation: fadeInDown 1s ease-out;
    }
    
    .game-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        margin-bottom: 10px;
    }
    
    .game-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        margin: 0 auto;
        max-width: 800px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        overflow: hidden;
    }
    
    .game-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(102, 126, 234, 0.1), transparent);
        animation: rotate 20s linear infinite;
        z-index: -1;
    }
    
    .game-status {
        text-align: center;
        margin-bottom: 30px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .game-status h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }
    
    .player-info {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 20px;
        align-items: center;
        margin-top: 20px;
    }
    
    .player-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px;
        border-radius: 15px;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .player-card.active {
        animation: pulse-glow 2s infinite;
        transform: scale(1.05);
    }
    
    .player-card.winner {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        animation: celebrate 1s ease-out;
    }
    
    .player-avatar-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        margin: 0 auto 10px;
    }
    
    .vs-divider {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    
    .tic-tac-toe-board {
        display: inline-block;
        margin: 20px auto;
        padding: 20px;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(5px);
    }
    
    .board-row {
        display: flex;
        justify-content: center;
    }
    
    .board-cell {
        width: 90px;
        height: 90px;
        border: 4px solid #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .board-cell::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
        transition: left 0.6s;
    }
    
    .board-cell:hover::before {
        left: 100%;
    }
    
    .board-cell:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .board-cell.disabled {
        cursor: not-allowed;
        background: #f5f5f5;
        opacity: 0.6;
    }
    
    .board-cell.disabled:hover {
        transform: none;
        box-shadow: none;
        background: #f5f5f5;
    }
    
    .board-cell.winner {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        animation: winner-cell 1s ease-out;
    }
    
    .board-cell.x-symbol {
        color: #e74c3c;
        text-shadow: 2px 2px 4px rgba(231, 76, 60, 0.3);
    }
    
    .board-cell.o-symbol {
        color: #3498db;
        text-shadow: 2px 2px 4px rgba(52, 152, 219, 0.3);
    }
    
    .board-cell:nth-child(1), .board-cell:nth-child(2) {
        border-right: none;
    }
    
    .board-cell:nth-child(3) {
        border-right: 4px solid #667eea;
    }
    
    .board-row:nth-child(1) .board-cell, .board-row:nth-child(2) .board-cell {
        border-bottom: none;
    }
    
    .board-row:nth-child(3) .board-cell {
        border-bottom: 4px solid #667eea;
    }
    
    .opponent-selection {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 20px;
        padding: 40px;
        margin: 30px auto;
        max-width: 600px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
        text-align: center;
    }
    
    .opponent-selection h4 {
        color: #333;
        font-weight: 600;
        margin-bottom: 30px;
        font-size: 1.5rem;
    }
    
.select2-container--default .select2-selection--single {
        height: 50px !important;
    border: 2px solid #e1e5e9 !important;
        border-radius: 12px !important;
    background: #ffffff !important;
    transition: all 0.3s ease !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
}

.select2-container--default .select2-selection--single:hover {
        border-color: #667eea !important;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15) !important;
}

.select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #667eea !important;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 46px !important;
        font-size: 16px !important;
    font-weight: 500 !important;
        color: #495057 !important;
        padding-left: 15px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        right: 15px !important;
    }
    
    .start-game-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 15px;
        padding: 15px 40px;
        font-size: 18px;
        font-weight: 600;
        color: white;
        transition: all 0.4s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 25px;
    }
    
    .start-game-btn:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }
    
    .start-game-btn:active {
        transform: translateY(-1px);
    }
    
    .start-game-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .game-controls {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
        flex-wrap: wrap;
    }
    
    .btn-modern {
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
    }
    
    .btn-secondary-modern {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
        box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    
    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 350px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        z-index: 1000;
        transition: all 0.3s ease;
        transform: translateY(calc(100% - 60px));
    }
    
    .chat-container.expanded {
        transform: translateY(0);
    }
    
    .chat-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 15px 20px;
        border-radius: 20px 20px 0 0;
        cursor: pointer;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    
    .chat-body {
        height: 250px;
        overflow-y: auto;
        padding: 15px;
    }
    
    .chat-message {
        margin-bottom: 10px;
        padding: 8px 12px;
        border-radius: 12px;
        max-width: 80%;
        animation: slideInMessage 0.3s ease-out;
    }
    
    .chat-message.own {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        margin-left: auto;
        text-align: right;
    }
    
    .chat-message.other {
        background: #f1f3f4;
        color: #333;
    }
    
    .chat-input-container {
        padding: 15px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 10px;
    }
    
    .chat-input {
        flex: 1;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .chat-input:focus {
        border-color: #667eea;
        outline: none;
    }
    
    .chat-send-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 8px 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .chat-send-btn:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
    }
    
    .winner-celebration {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 9999;
    }
    
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        animation: confetti-fall 3s linear infinite;
    }
    
    .sound-controls {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
    
    .sound-btn {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #667eea;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .sound-btn:hover {
        background: white;
        transform: scale(1.1);
    }
    
    .sound-btn.muted {
        color: #dc3545;
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
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes pulse-glow {
        0%, 100% { 
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
            transform: scale(1.05);
        }
        50% { 
            box-shadow: 0 0 0 20px rgba(102, 126, 234, 0);
            transform: scale(1.1);
        }
    }
    
    @keyframes celebrate {
        0% { transform: scale(1.05); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1.05); }
    }
    
    @keyframes winner-cell {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1.1); }
    }
    
    @keyframes slideInMessage {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes confetti-fall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }
    
@media (max-width: 768px) {
        .game-container {
            margin: 0 10px;
            padding: 20px;
        }
        
        .board-cell {
            width: 70px;
            height: 70px;
            font-size: 36px;
        }
        
        .chat-container {
            width: calc(100% - 40px);
            bottom: 20px;
            right: 20px;
            left: 20px;
        }
        
        .player-info {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .vs-divider {
            display: none;
    }
}
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Sound Controls -->
    <div class="sound-controls">
        <button class="sound-btn" id="soundToggle" title="Toggle Sound">
            <i class="fas fa-volume-up"></i>
        </button>
    </div>

    <!-- Game Header -->
    <div class="game-header">
        <h1><i class="fas fa-th"></i> Tic Tac Toe</h1>
        <p>Strategic gameplay at its finest!</p>
                        </div>

    <!-- Game Container -->
    <div class="game-container">
                        <!-- Game Status -->
        <div class="game-status" id="gameStatusContainer">
            <h2 id="gameStatus">Select an opponent to start playing</h2>
            
                            <div id="playerInfo" style="display: none;">
                <div class="player-info">
                    <div class="player-card" id="player1Card">
                        <div class="player-avatar-large">
                            <span id="player1Initial"></span>
                        </div>
                        <div>
                            <strong id="player1Name"></strong>
                            <div style="font-size: 0.9rem; opacity: 0.8;" id="player1Symbol">X</div>
                        </div>
                    </div>
                    
                    <div class="vs-divider">VS</div>
                    
                    <div class="player-card" id="player2Card">
                        <div class="player-avatar-large">
                            <span id="player2Initial"></span>
                        </div>
                        <div>
                            <strong id="player2Name"></strong>
                            <div style="font-size: 0.9rem; opacity: 0.8;" id="player2Symbol">O</div>
                        </div>
                    </div>
                </div>
                            </div>
                        </div>

                        <!-- Game Board -->
        <div class="text-center">
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

        <!-- Game Controls -->
        <div class="game-controls">
            <button class="btn-modern btn-primary-modern" id="newGameBtn" style="display: none;">
                <i class="fas fa-redo"></i> New Game
            </button>
            <a href="{{ route('student.games.lobby') }}" class="btn-modern btn-secondary-modern">
                <i class="fas fa-arrow-left"></i> Back to Lobby
            </a>
        </div>

                        <!-- Opponent Selection -->
        <div class="opponent-selection" id="opponentSelection">
            <h4><i class="fas fa-users"></i> Choose Your Opponent</h4>
                            <div class="form-group">
                                <select class="form-control select2" id="opponentSelect">
                                    <option value="">Search and select a student...</option>
                                </select>
                            </div>
            <button class="start-game-btn" id="startGameBtn">
                <i class="fas fa-play"></i> Start Game
            </button>
                            </div>
                        </div>

    <!-- Chat Container -->
    <div class="chat-container" id="chatContainer" style="display: none;">
        <div class="chat-header" id="chatToggle">
            <div>
                <i class="fas fa-comments"></i>
                <span>Game Chat</span>
                    </div>
            <i class="fas fa-chevron-up" id="chatArrow"></i>
                </div>
        <div class="chat-body" id="chatBody">
            <!-- Messages will be dynamically added here -->
            </div>
        <div class="chat-input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="Type a message..." maxlength="100">
            <button class="chat-send-btn" id="chatSendBtn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
</div>

    <!-- Winner Celebration -->
    <div class="winner-celebration" id="winnerCelebration"></div>
</div>

<!-- Audio Elements -->
<audio id="moveSound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEaBDuJ0fLQgywGJW/A7+ORUQ0PVqzn77RfGAg+ltryxnkpBSl+zPLaizsIGGS57OWhTgwOUarm7bdjHgU2jdXzzn0vBSF1xe/glEILElyx6OyrWBUIQ5zd8sFiGAU7k9n7u" type="audio/wav">
</audio>
<audio id="winSound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmEaBDuJ0fLQgywGJW/A7+ORUQ0PVqzn77RfGAg+ltryxnkpBSl+zPLaizsIGGS57OWhTgwOUarm7bdjHgU2jdXzzn0vBSF1xe/glEILElyx6OyrWBUIQ5zd8sFiGAU7k9n7u" type="audio/wav">
</audio>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
let currentGame = null;
let gameId = null;
let isMyTurn = false;
let playerSymbol = '';
let gameInterval = null;
let chatInterval = null;
let soundEnabled = true;
let lastChatId = 0;

$(document).ready(function() {
    initializeSelect2();
    initializeChat();
    initializeSoundControls();
    
    // Check for game_id in URL
    const urlParams = new URLSearchParams(window.location.search);
    const gameIdFromUrl = urlParams.get('game_id');
    
    if (gameIdFromUrl) {
        loadGame(gameIdFromUrl);
    }

    // Event Listeners
    $('#startGameBtn').click(startNewGame);
    $(document).on('click', '.board-cell', handleCellClick);
    $('#newGameBtn').click(() => location.reload());
    $('#chatToggle').click(toggleChat);
    $('#chatSendBtn').click(sendChatMessage);
    $('#chatInput').keypress(function(e) {
        if (e.which === 13) sendChatMessage();
    });
});

function initializeSelect2() {
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
                    return {
                        search: params.term,
                        _token: '{{ csrf_token() }}'
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(student) {
                            return {
                                id: student.ic,
                                text: student.name + ' (' + student.no_matric + ')'
                            };
                        })
                    };
                }
        },
        templateResult: formatPlayerOption,
        templateSelection: formatPlayerSelection
    });
}

function formatPlayerOption(player) {
    if (!player.id) return player.text;
    
    const name = player.text.split(' (')[0];
    const matric = player.text.split(' (')[1]?.replace(')', '') || '';
    
    return $(`
        <div style="display: flex; align-items: center; padding: 8px 0;">
            <div style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-weight: 600;">
                ${name.charAt(0)}
            </div>
            <div>
                <div style="font-weight: 600; color: #333;">${name}</div>
                <div style="font-size: 0.85rem; color: #666;">${matric}</div>
            </div>
        </div>
    `);
}

function formatPlayerSelection(player) {
    return player.text;
}

function initializeChat() {
    $('#chatContainer').hide();
}

function initializeSoundControls() {
    $('#soundToggle').click(function() {
        soundEnabled = !soundEnabled;
        $(this).find('i').removeClass().addClass(soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute');
        $(this).toggleClass('muted', !soundEnabled);
        
        if (soundEnabled) {
            showNotification('Sound enabled', 'info');
        } else {
            showNotification('Sound disabled', 'info');
        }
    });
}

function playSound(type) {
    if (!soundEnabled) return;
    
    const audio = document.getElementById(type + 'Sound');
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(() => {}); // Ignore audio play errors
    }
}

function startNewGame() {
        const opponentIc = $('#opponentSelect').val();
        if (!opponentIc) {
        showNotification('Please select an opponent', 'error');
            return;
        }

    const btn = $('#startGameBtn');
    const originalText = btn.html();
    
    btn.html('<i class="fas fa-spinner fa-spin"></i> Creating Game...').prop('disabled', true);

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
            
            btn.html('<i class="fas fa-check"></i> Invitation Sent!').css('background', 'linear-gradient(135deg, #4CAF50, #45a049)');
            
            setTimeout(() => {
                btn.html(originalText).css('background', '').prop('disabled', false);
            }, 2000);
            },
            error: function(xhr) {
            const error = xhr.responseJSON?.error || 'An error occurred';
            showNotification('Error: ' + error, 'error');
            btn.html(originalText).prop('disabled', false);
        }
    });
}

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
                startChatPolling();
                $('#chatContainer').show();
            }
        },
        error: function(xhr) {
            showNotification('Game not found or access denied', 'error');
            setTimeout(() => {
            window.location.href = '{{ route("student.games.lobby") }}';
            }, 2000);
        }
    });
}

function displayGame(game) {
    $('#opponentSelection').hide();
    $('#gameBoard').show();
    $('#playerInfo').show();
    $('#newGameBtn').show();
    
    // Set player information
    $('#player1Name').text(game.player1_name);
    $('#player2Name').text(game.player2_name);
    $('#player1Initial').text(game.player1_name.charAt(0));
    $('#player2Initial').text(game.player2_name.charAt(0));
    
    playerSymbol = game.player_symbol;
    isMyTurn = game.is_current_player;
    
    updateGameStatus(game);
    updateBoard(game.board_state);
    updatePlayerCards(game);
}

function updateGameStatus(game) {
    let status = '';
    let statusClass = '';
    
    if (game.status === 'waiting') {
        status = '<i class="fas fa-clock"></i> Waiting for opponent to accept...';
        statusClass = 'waiting';
    } else if (game.status === 'active') {
        if (game.winner_ic) {
            if (game.winner_ic === 'draw') {
                status = '<i class="fas fa-handshake"></i> It\'s a draw!';
                statusClass = 'draw';
            } else {
                const winnerName = game.winner_ic === game.player1_ic ? game.player1_name : game.player2_name;
                const isWinner = game.winner_ic === '{{ auth()->guard("student")->user()->ic }}';
                status = `<i class="fas fa-trophy"></i> ${winnerName} wins!`;
                statusClass = isWinner ? 'winner' : 'loser';
                
                if (isWinner) {
                    playSound('win');
                    triggerCelebration();
                }
            }
        } else {
            const currentPlayerName = game.current_turn === game.player1_ic ? game.player1_name : game.player2_name;
            if (isMyTurn) {
                status = `<i class="fas fa-hand-pointer"></i> Your turn, ${currentPlayerName}!`;
                statusClass = 'your-turn';
            } else {
                status = `<i class="fas fa-hourglass-half"></i> ${currentPlayerName}'s turn`;
                statusClass = 'waiting-turn';
            }
        }
         } else if (game.status === 'completed') {
         if (game.winner_ic === 'draw') {
            status = '<i class="fas fa-handshake"></i> Game completed - It\'s a draw!';
            statusClass = 'draw';
         } else {
             const winnerName = game.winner_ic === game.player1_ic ? game.player1_name : game.player2_name;
            status = `<i class="fas fa-trophy"></i> Game completed - ${winnerName} wins!`;
            statusClass = 'completed';
         }
     }
    
    $('#gameStatus').html(status);
    $('#gameStatusContainer').removeClass().addClass('game-status ' + statusClass);
}

function updateBoard(boardState) {
    $('.board-cell').each(function(index) {
        const cell = $(this);
        const value = boardState[index];
        
        cell.text(value || '');
        cell.removeClass('disabled x-symbol o-symbol winner');
        
        if (value === 'X') {
            cell.addClass('x-symbol');
        } else if (value === 'O') {
            cell.addClass('o-symbol');
        }
        
        if (value || !isMyTurn || currentGame?.status === 'completed') {
            cell.addClass('disabled');
        }
    });
    
    // Highlight winning combination
    if (currentGame && currentGame.winner_ic && currentGame.winner_ic !== 'draw') {
        highlightWinningCombination(boardState);
    }
}

function updatePlayerCards(game) {
    $('#player1Card, #player2Card').removeClass('active winner');
    
    if (game.status === 'active' && !game.winner_ic) {
        if (game.current_turn === game.player1_ic) {
            $('#player1Card').addClass('active');
        } else {
            $('#player2Card').addClass('active');
        }
    } else if (game.winner_ic && game.winner_ic !== 'draw') {
        if (game.winner_ic === game.player1_ic) {
            $('#player1Card').addClass('winner');
        } else {
            $('#player2Card').addClass('winner');
        }
    }
}

function highlightWinningCombination(boardState) {
    const winningCombinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // columns
        [0, 4, 8], [2, 4, 6] // diagonals
    ];
    
    for (let combo of winningCombinations) {
        const [a, b, c] = combo;
        if (boardState[a] && boardState[a] === boardState[b] && boardState[a] === boardState[c]) {
            combo.forEach(pos => {
                $(`.board-cell[data-position="${pos}"]`).addClass('winner');
            });
            break;
        }
    }
}

function handleCellClick() {
    if (!isMyTurn || $(this).text() !== '' || !gameId) {
        return;
    }

    const position = parseInt($(this).data('position'));
    makeMove(position);
}

function makeMove(position) {
    playSound('move');
    
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
            const cell = $(`.board-cell[data-position="${position}"]`);
            cell.text(playerSymbol);
            cell.addClass(playerSymbol === 'X' ? 'x-symbol' : 'o-symbol');
            
            isMyTurn = false;
            $('.board-cell').addClass('disabled');
            
            if (response.winner) {
                const isWinner = response.winner === playerSymbol;
                $('#gameStatus').html(isWinner ? 
                    '<i class="fas fa-trophy"></i> You win!' : 
                    '<i class="fas fa-trophy"></i> You lose!'
                );
                
                if (isWinner) {
                    playSound('win');
                    triggerCelebration();
                }
                
                stopGamePolling();
            } else if (response.is_draw) {
                $('#gameStatus').html('<i class="fas fa-handshake"></i> It\'s a draw!');
                stopGamePolling();
            } else {
                $('#gameStatus').html('<i class="fas fa-hourglass-half"></i> Opponent\'s turn');
            }
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'An error occurred';
            showNotification('Error: ' + error, 'error');
        }
    });
}

function triggerCelebration() {
    const celebration = $('#winnerCelebration');
    celebration.empty();
    
    // Create confetti
    for (let i = 0; i < 50; i++) {
        const confetti = $('<div class="confetti"></div>');
        confetti.css({
            left: Math.random() * 100 + '%',
            background: `hsl(${Math.random() * 360}, 70%, 60%)`,
            animationDelay: Math.random() * 3 + 's',
            animationDuration: (Math.random() * 2 + 2) + 's'
        });
        celebration.append(confetti);
    }
    
    // Remove confetti after animation
    setTimeout(() => {
        celebration.empty();
    }, 5000);
}

function startGamePolling() {
    gameInterval = setInterval(() => {
        if (gameId) {
            loadGame(gameId);
        }
    }, 2000);
}

function stopGamePolling() {
    if (gameInterval) {
        clearInterval(gameInterval);
        gameInterval = null;
    }
}

function toggleChat() {
    const container = $('#chatContainer');
    const arrow = $('#chatArrow');
    
    container.toggleClass('expanded');
    arrow.toggleClass('fa-chevron-up fa-chevron-down');
}

function startChatPolling() {
    chatInterval = setInterval(loadChatMessages, 3000);
}

function loadChatMessages() {
    if (!gameId) return;
    
    $.ajax({
        url: `/student/games/${gameId}/chat`,
        method: 'GET',
        success: function(messages) {
            displayChatMessages(messages);
        },
        error: function() {
            // Silently fail for chat
        }
    });
}

function displayChatMessages(messages) {
    const chatBody = $('#chatBody');
    const currentUserIc = '{{ auth()->guard("student")->user()->ic }}';
    
    messages.forEach(message => {
        if (message.id > lastChatId) {
            const isOwn = message.user_ic === currentUserIc;
            const messageDiv = $(`
                <div class="chat-message ${isOwn ? 'own' : 'other'}">
                    ${isOwn ? '' : '<strong>' + message.user_name + ':</strong> '}
                    ${message.message}
                </div>
            `);
            
            chatBody.append(messageDiv);
            lastChatId = message.id;
        }
    });
    
    // Auto-scroll to bottom
    chatBody.scrollTop(chatBody[0].scrollHeight);
}

function sendChatMessage() {
    const input = $('#chatInput');
    const message = input.val().trim();
    
    if (!message || !gameId) return;
    
    $.ajax({
        url: `/student/games/${gameId}/chat`,
        method: 'POST',
        data: {
            message: message,
            _token: '{{ csrf_token() }}'
        },
        success: function() {
            input.val('');
            loadChatMessages(); // Refresh chat immediately
        },
        error: function() {
            showNotification('Failed to send message', 'error');
        }
    });
}

// Enhanced notification system
function showNotification(message, type) {
    const colors = {
        success: '#4CAF50',
        error: '#f44336',
        info: '#2196F3',
        warning: '#FF9800'
    };
    
    const icons = {
        success: 'check',
        error: 'exclamation-triangle',
        info: 'info',
        warning: 'exclamation'
    };
    
    const notification = $(`
        <div style="
            position: fixed; 
            top: 20px; 
            left: 50%;
            transform: translateX(-50%);
            background: ${colors[type]}; 
            color: white; 
            padding: 15px 25px; 
            border-radius: 12px; 
            z-index: 9999; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            font-weight: 500;
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
            transition: all 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
        ">
            <i class="fas fa-${icons[type]}"></i> ${message}
        </div>
    `);
    
    $('body').append(notification);
    
    // Animate in
    setTimeout(() => {
        notification.css({
            'opacity': '1',
            'transform': 'translateX(-50%) translateY(0)'
        });
    }, 100);
    
    // Animate out
    setTimeout(() => {
        notification.css({
            'opacity': '0',
            'transform': 'translateX(-50%) translateY(-20px)'
        });
        setTimeout(() => notification.remove(), 400);
    }, 3000);
}

// Clean up intervals when leaving page
$(window).on('beforeunload', function() {
    stopGamePolling();
    if (chatInterval) {
        clearInterval(chatInterval);
    }
});
</script>
@endsection 