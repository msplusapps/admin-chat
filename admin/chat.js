$(document).ready(function() {
    const conversationList = $('#conversation-list');
    const messagesContainer = $('#messages');
    const messageInput = $('#message-input');
    const sendButton = $('#send-button');
    let currentConversationId = null;

    // Function to fetch and display conversations
    function loadConversations() {
        $.getJSON('/admin/api?action=get_conversations')
            .done(function(data) {
                conversationList.empty();
                if (data.length === 0) {
                    conversationList.html('<li class="text-gray-400 text-center">No active conversations.</li>');
                    return;
                }
                data.forEach(function(conversation) {
                    const conversationElement = $(`
                        <li data-id="${conversation.id}" class="p-4 rounded-lg cursor-pointer transition duration-300 hover:bg-indigo-600 bg-gray-700">
                            <p class="font-bold text-lg">Conversation #${conversation.id}</p>
                            <p class="text-sm text-gray-400">User IP: ${conversation.user_ip}</p>
                            <p class="text-xs text-gray-500 mt-1">Started: ${new Date(conversation.created_at).toLocaleString()}</p>
                        </li>
                    `);
                    conversationElement.on('click', function() {
                        currentConversationId = conversation.id;
                        $('.conversation-item').removeClass('bg-indigo-600').addClass('bg-gray-700');
                        $(this).removeClass('bg-gray-700').addClass('bg-indigo-600');
                        loadMessages(conversation.id);
                    });
                    conversationList.append(conversationElement);
                });
            })
            .fail(function() {
                conversationList.html('<li class="text-red-400 text-center">Failed to load conversations.</li>');
            });
    }

    // Function to fetch and display messages for a conversation
    function loadMessages(conversationId) {
        messagesContainer.html('<div class="flex justify-center items-center h-full"><p class="text-gray-400">Loading messages...</p></div>');
        $.getJSON(`/admin/api?action=get_messages&conversation_id=${conversationId}`)
            .done(function(data) {
                messagesContainer.empty();
                if (data.length === 0) {
                    messagesContainer.html('<div class="text-center text-gray-400 mt-4">No messages yet.</div>');
                }
                data.forEach(function(message) {
                    const isAdmin = message.sender === 'admin';
                    const messageBubble = $(`
                        <div class="flex ${isAdmin ? 'justify-end' : 'justify-start'} mb-4">
                            <div class="max-w-md p-4 rounded-2xl ${isAdmin ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-gray-700 text-white rounded-bl-none'}">
                                <p class="font-bold">${isAdmin ? 'You' : 'User'}</p>
                                <p>${message.message}</p>
                                <p class="text-xs text-gray-400 mt-2 text-right">${new Date(message.timestamp).toLocaleTimeString()}</p>
                            </div>
                        </div>
                    `);
                    messagesContainer.append(messageBubble);
                });
                messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
            })
            .fail(function() {
                messagesContainer.html('<div class="text-red-400 text-center">Failed to load messages.</div>');
            });
    }

    // Function to send a message
    function sendMessage() {
        const message = messageInput.val().trim();
        if (message === '' || !currentConversationId) {
            return;
        }

        $.post('/admin/api?action=send_message', {
            conversation_id: currentConversationId,
            message: message,
            sender: 'admin'
        })
        .done(function() {
            messageInput.val('');
            loadMessages(currentConversationId);
        })
        .fail(function() {
            // You can add error handling here, e.g., an alert
            alert('Failed to send message.');
        });
    }

    sendButton.on('click', sendMessage);
    messageInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Initial load
    loadConversations();

    // Periodically check for new messages in the current conversation
    setInterval(function() {
        if (currentConversationId) {
            loadMessages(currentConversationId);
        }
    }, 5000); // Poll every 5 seconds
});
