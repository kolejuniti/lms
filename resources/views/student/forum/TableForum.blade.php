<?php
$user = Auth::guard('student')->user();

if(isset($TopicID) && $TopicID != '') {
    $forum = DB::table('tblforum')
        ->where([['TopicID', $TopicID]])
        ->orderBy('DateTime')
        ->get();
    
    if($forum->isEmpty()) {
        // No messages in this topic yet
        ?>
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="far fa-comments fa-4x text-muted"></i>
            </div>
            <h5 class="text-muted">No messages in this topic yet</h5>
            <p class="text-muted">Be the first to start the conversation!</p>
        </div>
        <?php
    } else {
        foreach($forum as $frm) {
            // Format date and time
            $datetime = "";
            $content = nl2br($frm->Content);
            $dt = strtotime($frm->DateTime);
            $ystd = strtotime("yesterday");
            
            if(date("dmY", $dt) == date("dmY")) {
                $datetime = "Today, " . date("h:i a", $dt);
            } elseif(date("dmY", $dt) == date("dmY", $ystd)) {
                $datetime = "Yesterday, " . date("h:i a", $dt);
            } else {
                $datetime = date("d/m/Y, h:i a", $dt);
            }
            
            // Determine user details
            $isSelfMessage = ($user->ic == $frm->UpdatedBy);
            
            if($isSelfMessage) {
                $usrnm = $user->name;
                $avatarBg = "bg-success";
                $messageClass = "self-message";
            } else {
                $getlect = DB::table('students')->where('ic', $frm->UpdatedBy)->first();
                
                if($getlect == null) {
                    $getlect = DB::table('users')->where('ic', $frm->UpdatedBy)->first();
                }
                
                $usrnm = $getlect->name;
                
                // Check if it's a lecturer (user from 'users' table)
                $isLecturer = ($getlect == null || isset($getlect->role_id));
                
                $avatarBg = $isLecturer ? "bg-info" : "bg-primary";
                $messageClass = $isLecturer ? "lecturer-message" : "other-message";
            }
            
            // Get user initials for avatar
            $initials = '';
            $nameParts = explode(' ', $usrnm);
            if(count($nameParts) >= 2) {
                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
            } else {
                $initials = strtoupper(substr($usrnm, 0, 2));
            }
            ?>
            
            <div class="forum-message <?php echo $messageClass; ?> p-3 border-bottom">
                <div class="d-flex">
                    <!-- User avatar -->
                    <div class="message-avatar me-3">
                        <div class="avatar-circle <?php echo $avatarBg; ?> text-white">
                            <?php echo $initials; ?>
                        </div>
                    </div>
                    
                    <!-- Message content -->
                    <div class="message-content flex-grow-1">
                        <div class="message-header d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold <?php echo $isSelfMessage ? 'text-success' : ($isLecturer ?? false ? 'text-info' : 'text-primary'); ?>">
                                <?php echo wordwrap($usrnm, 20, "<br>\n", TRUE); ?>
                                <?php if(isset($isLecturer) && $isLecturer): ?>
                                    <span class="badge bg-info text-white ms-2 fs-xs">Lecturer</span>
                                <?php endif; ?>
                            </h6>
                            <div class="message-actions">
                                <?php if($isSelfMessage): ?>
                                <a href="ForumDB.php?crsid=&frmid=&tpcID=&DEL" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Message body -->
                        <div class="message-body mb-2 text-dark">
                            <?php echo $content; ?>
                        </div>
                        
                        <!-- Message timestamp -->
                        <div class="message-footer text-end">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i><?php echo $datetime; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
} else {
    // No topic selected
    ?>
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="fa fa-hand-point-right fa-4x text-muted"></i>
        </div>
        <h5 class="text-muted">Select a topic from the sidebar</h5>
        <p class="text-muted">Choose a topic to join the discussion</p>
    </div>
    <?php
}
?>

<style>
    /* Forum message styling */
    .forum-message {
        transition: all 0.2s ease;
    }
    
    .forum-message:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
    
    .message-body {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
    }
    
    .self-message .message-body {
        background-color: #e8f8e8;
    }
    
    .lecturer-message .message-body {
        background-color: #e8f4fc;
    }
    
    .badge.fs-xs {
        font-size: 0.65rem;
    }
    
    .forum-messages {
        max-height: 600px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    
    .forum-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .forum-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .forum-messages::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    
    .forum-messages::-webkit-scrollbar-thumb:hover {
        background: #999;
    }
    
    /* Animation for new messages */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .forum-message {
        animation: fadeIn 0.3s ease-out;
    }
</style>