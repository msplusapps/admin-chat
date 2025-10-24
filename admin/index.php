<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="chat-container">
        <div id="conversations">
            <h2>Conversations</h2>
            <ul id="conversation-list">
                <!-- Conversations will be loaded here -->
            </ul>
        </div>
        <div id="chat-window">
            <div id="messages">
                <!-- Messages will be loaded here -->
            </div>
            <div id="chat-form">
                <input type="text" id="message-input" placeholder="Type your message...">
                <button id="send-button">Send</button>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="chat.js"></script>
</body>
</html>
