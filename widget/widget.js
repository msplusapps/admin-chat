var ChatWidget = {
    init: function(options) {
        this.container = $(options.container);
        this.apiUrl = options.apiUrl;
        this.conversationId = null;
        this.isOpen = false;
        this.render();
        this.attachEvents();
        this.startConversation();
    },

    render: function() {
        const widgetHtml = `
            <div id="chat-widget" class="fixed bottom-5 right-5 font-sans">
                <!-- Icon Libraries -->
                <script src="https://unpkg.com/feather-icons"></script>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

                <!-- Chat Bubble Toggle -->
                <div id="chat-bubble" class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg transform hover:scale-110 transition-transform duration-300">
                    <i class="bi bi-chat-dots-fill text-white text-3xl"></i>
                </div>
                <!-- Chat Window -->
                <div id="chat-window-widget" class="hidden absolute bottom-20 right-0 w-80 bg-gray-800 rounded-2xl shadow-2xl flex-col" style="height: 30rem;">
                    <!-- Header -->
                    <div class="bg-indigo-600 p-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-white text-lg font-bold flex items-center"><i data-feather="message-circle" class="mr-2"></i> Chat with Us</h3>
                        <button id="close-chat" class="text-white"><i data-feather="x"></i></button>
                    </div>
                    <!-- Messages -->
                    <div id="chat-widget-messages" class="flex-grow p-4 overflow-y-auto"></div>
                    <!-- Form -->
                    <div class="p-4 bg-gray-900 rounded-b-2xl">
                        <div class="flex items-center">
                            <input type="text" id="chat-widget-input" placeholder="Type a message..." class="w-full bg-gray-700 text-white rounded-full py-2 px-4 focus:outline-none">
                            <button id="chat-widget-send" class="ml-3 bg-indigo-600 p-2 rounded-full">
                                <i data-feather="send" class="text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        this.container.html(widgetHtml);
        feather.replace();
        this.messages = $('#chat-widget-messages');
        this.messageInput = $('#chat-widget-input');
    },

    attachEvents: function() {
        $('#chat-bubble, #close-chat').on('click', () => this.toggleChatWindow());
        $('#chat-widget-send').on('click', () => this.sendMessage());
        this.messageInput.on('keypress', (e) => {
            if (e.which === 13) {
                e.preventDefault();
                this.sendMessage();
            }
        });
    },

    toggleChatWindow: function() {
        this.isOpen = !this.isOpen;
        $('#chat-window-widget').toggleClass('hidden');
    },

    startConversation: function() {
        $.post(`${this.apiUrl}?action=start_conversation`)
            .done(data => {
                this.conversationId = data.conversation_id;
                this.loadMessages();
                setInterval(() => this.loadMessages(), 5000);
            });
    },

    loadMessages: function() {
        if (!this.conversationId) return;
        $.getJSON(`${this.apiUrl}?action=get_messages`)
            .done(data => {
                this.messages.empty();
                data.forEach(message => {
                    const isUser = message.sender === 'user';
                    const messageHtml = `
                        <div class="flex ${isUser ? 'justify-end' : 'justify-start'} mb-2">
                            <div class="py-2 px-4 rounded-2xl ${isUser ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-gray-700 text-white rounded-bl-none'}">
                                ${message.message}
                            </div>
                        </div>`;
                    this.messages.append(messageHtml);
                });
                this.messages.scrollTop(this.messages[0].scrollHeight);
            });
    },

    sendMessage: function() {
        const message = this.messageInput.val().trim();
        if (message === '' || !this.conversationId) return;

        $.post(`${this.apiUrl}?action=send_message`, {
            message: message,
            sender: 'user'
        })
        .done(() => {
            this.messageInput.val('');
            this.loadMessages();
        });
    }
};
