$(document).ready(function() {
    var conversationList = $('#conversation-list');
    var messages = $('#messages');
    var messageInput = $('#message-input');
    var sendButton = $('#send-button');
    var currentConversationId = null;

    function loadConversations() {
        $.get('api.php?action=get_conversations', function(data) {
            conversationList.empty();
            data.forEach(function(conversation) {
                var listItem = $('<li>').text('Conversation #' + conversation.id);
                listItem.click(function() {
                    loadMessages(conversation.id);
                });
                conversationList.append(listItem);
            });
        });
    }

    function loadMessages(conversationId) {
        currentConversationId = conversationId;
        $.get('api.php?action=get_messages&conversation_id=' + conversationId, function(data) {
            messages.empty();
            data.forEach(function(message) {
                var messageElement = $('<div>').text(message.sender + ': ' + message.message);
                messages.append(messageElement);
            });
        });
    }

    function sendMessage() {
        var message = messageInput.val();
        if (message.trim() !== '' && currentConversationId) {
            $.post('api.php?action=send_message', {
                conversation_id: currentConversationId,
                message: message,
                sender: 'admin'
            }, function() {
                messageInput.val('');
                loadMessages(currentConversationId);
            });
        }
    }

    sendButton.click(sendMessage);
    messageInput.keypress(function(e) {
        if (e.which === 13) {
            sendMessage();
        }
    });

    loadConversations();
    setInterval(function() {
        if (currentConversationId) {
            loadMessages(currentConversationId);
        }
    }, 3000);
});
