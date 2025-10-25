<?php
// Session is now started and managed by the router.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-gray-900 text-white flex h-screen font-sans">

    <!-- Sidebar for Conversations -->
    <div id="conversations" class="w-1/3 bg-gray-800 p-6 overflow-y-auto shadow-lg flex flex-col">
        <h2 class="text-2xl font-bold mb-6 border-b-2 border-gray-700 pb-4 flex items-center">
            <i data-feather="message-square" class="mr-3"></i>
            Conversations
        </h2>
        <ul id="conversation-list" class="space-y-2 flex-grow">
            <!-- Conversations will be loaded here via JavaScript -->
            <li class="p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-indigo-600 transition duration-300">
                <p class="font-semibold">Loading conversations...</p>
            </li>
        </ul>
        <a href="/admin/logout" class="text-gray-400 hover:text-white mt-4 flex items-center justify-center">
            <i data-feather="log-out" class="mr-2"></i>
            Logout
        </a>
    </div>

    <!-- Main Chat Window -->
    <div id="chat-window" class="w-2/3 flex flex-col">
        <!-- Messages Display -->
        <div id="messages" class="flex-grow p-8 overflow-y-auto">
            <!-- Messages will be loaded here -->
            <div class="flex items-center justify-center h-full">
                <i class="bi bi-chat-dots text-5xl text-gray-500"></i>
                <p class="text-gray-400 text-lg ml-4">Select a conversation to start chatting.</p>
            </div>
        </div>

        <!-- Message Input Form -->
        <div id="chat-form" class="p-6 bg-gray-800 shadow-md">
            <div class="flex items-center bg-gray-700 rounded-full px-4 py-2">
                <input type="text" id="message-input" placeholder="Type your message..."
                       class="w-full bg-transparent text-white focus:outline-none placeholder-gray-400">
                <button id="send-button"
                        class="ml-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold p-3 rounded-full transition duration-300 ease-in-out transform hover:scale-110">
                    <i data-feather="send"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/admin/chat.js"></script>
    <script>
      feather.replace()
    </script>
</body>
</html>
