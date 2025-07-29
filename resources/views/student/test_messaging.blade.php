@extends('layouts.student')

@section('title', 'Test Student Messaging')

@section('main')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Test Student Messaging</h4>
                        </div>
                        <div class="box-body">
                            <p>Use the "Student Messages" section in the sidebar to:</p>
                            <ul>
                                <li>Search for other students to start a conversation</li>
                                <li>View your existing conversations</li>
                                <li>Send text messages and images</li>
                            </ul>
                            
                            <div class="alert alert-info">
                                <h5>How to use:</h5>
                                <ol>
                                    <li>Click on "Student Messages" in the sidebar</li>
                                    <li>Type a student's name, IC, or matric number in the search box</li>
                                    <li>Click on a student from the search results to start chatting</li>
                                    <li>Your existing conversations will appear below the search box</li>
                                </ol>
                            </div>
                            
                            <div class="alert alert-success">
                                <strong>Features:</strong>
                                <ul>
                                    <li>Real-time messaging between students</li>
                                    <li>Image sharing support</li>
                                    <li>Message status indicators (sent/read)</li>
                                    <li>Conversation history</li>
                                    <li>Unread message counters</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- TextBox component for messaging -->
<text-box></text-box>
@endsection

@section('content')
<script>
    // Test function to manually trigger student chat (for debugging)
    function testStudentChat() {
        const testStudentIc = prompt("Enter student IC to test chat with:");
        const testStudentName = prompt("Enter student name:");
        
        if (testStudentIc && testStudentName) {
            startStudentChat(testStudentIc, testStudentName);
        }
    }
    
    console.log('Student messaging test page loaded');
</script>
@endsection 