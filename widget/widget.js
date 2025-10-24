var ChatWidget = {
    init: function(options) {
        this.container = $(options.container);
        this.apiUrl = options.apiUrl;
        this.conversationId = null;
        this.render();
        this.attachEvents();
        this.startConversation();
    },

    render: function() {
        this.container.html('<div id="chat-widget">' +
            '<div id="chat-widget-messages"></div>' +
            '<div id="chat-widget-form">' +
            '<input type="text" id="chat-widget-input" placeholder="Type your message...">' +
            '<button id="chat-widget-send">Send</button>' +
            '</div>' +
            '</div>');
        this.messages = $('#chat-widget-messages');
        this.messageInput = $('#chat-widget-input');
        this.sendButton = $('#chat-widget-send');
    },

    attachEvents: function() {
        this.sendButton.click(this.sendMessage.bind(this));
        this.messageInput.keypress(function(e) {
            if (e.which === 13) {
                this.sendMessage();
            }
        }.bind(this));
    },

    startConversation: function() {
        $.post(this.apiUrl + '?action=start_conversation', function(data) {
            this.conversationId = data.conversation_id;
            this.loadMessages();
            setInterval(this.loadMessages.bind(this), 3000);
        }.bind(this));
    },

    loadMessages: function() {
        $.get(this.apiUrl + '?action=get_messages', function(data) {
            this.messages.empty();
            data.forEach(function(message) {
                var messageElement = $('<div>').text(message.sender + ': ' + message.message);
                this.messages.append(messageElement);
            }.bind(this));
        }.bind(this));
    },

    sendMessage: function() {
        var message = this.messageInput.val();
        if (message.trim() !== '') {
            $.post(this.apiUrl + '?action=send_message', {
                message: message,
                sender: 'user'
            }, function() {
                this.messageInput.val('');
                this.loadMessages();
            }.bind(this));
        }
    }
};
