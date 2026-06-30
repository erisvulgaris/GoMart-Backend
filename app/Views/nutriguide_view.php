<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriGuide - AI Nutrition Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg mb-6 p-6">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-r from-green-500 to-blue-500 w-12 h-12 rounded-full flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">NutriGuide</h1>
                    <p class="text-gray-600">Your AI Nutrition Assistant</p>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-green-500 to-blue-500 p-4 text-white">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-300 rounded-full animate-pulse"></div>
                    <span class="font-medium">Online - Ready to help with your nutrition needs</span>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-4 bg-gray-50">
                <!-- Welcome Message -->
                <div class="flex items-start space-x-3 animate-fadeIn">
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <div class="bg-white rounded-lg rounded-tl-none p-3 shadow-sm max-w-md">
                        <p class="text-gray-800">👋 Hello! I'm your AI nutrition assistant. Ask me anything about:</p>
                        <ul class="text-sm text-gray-600 mt-2 space-y-1">
                            <li>• Nutritional deficiencies</li>
                            <li>• Healthy meal suggestions</li>
                            <li>• Dietary recommendations</li>
                            <li>• Product recommendations</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Products Section (Initially Hidden) -->
            <div id="productsSection" class="hidden border-t bg-white p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-shopping-cart mr-2 text-green-500"></i>
                    Recommended Products
                </h3>
                <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Products will be loaded here -->
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t bg-white p-4">
                <div class="flex space-x-3">
                    <div class="flex-1">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="messageInput" 
                                placeholder="Ask about nutrition, deficiencies, healthy foods..."
                                class="w-full p-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                maxlength="500"
                            >
                            <div class="absolute right-3 top-3 text-gray-400">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        </div>
                    </div>
                    <button 
                        id="sendButton"
                        class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white px-6 py-3 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                
                <!-- Quick Suggestions -->
                <div class="mt-3">
                    <div class="text-sm text-gray-600 mb-2">Quick suggestions:</div>
                    <div class="flex flex-wrap gap-2">
                        <button class="suggestion-btn bg-gray-100 hover:bg-green-100 text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                            I have iron deficiency, what should I eat?
                        </button>
                        <button class="suggestion-btn bg-gray-100 hover:bg-green-100 text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                            Healthy snacks for kids
                        </button>
                        <button class="suggestion-btn bg-gray-100 hover:bg-green-100 text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                            Foods high in protein
                        </button>
                        <button class="suggestion-btn bg-gray-100 hover:bg-green-100 text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                            Best vitamins for immunity
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
                <span class="text-gray-700 font-medium">Getting nutritional advice...</span>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Custom scrollbar */
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }
        #chatMessages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        #chatMessages::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        #chatMessages::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    <script>
        class NutriGuideChat {
            constructor() {
                this.conversationId = null;
                this.isLoading = false;
                this.apiEndpoint = '/nutriguide/chat'; // Adjust this to your CI4 route
                this.initializeElements();
                this.bindEvents();
            }

            initializeElements() {
                this.messageInput = document.getElementById('messageInput');
                this.sendButton = document.getElementById('sendButton');
                this.chatMessages = document.getElementById('chatMessages');
                this.productsSection = document.getElementById('productsSection');
                this.productsGrid = document.getElementById('productsGrid');
                this.loadingOverlay = document.getElementById('loadingOverlay');
                this.suggestionButtons = document.querySelectorAll('.suggestion-btn');
            }

            bindEvents() {
                // Send button click
                this.sendButton.addEventListener('click', () => this.sendMessage());
                
                // Enter key press
                this.messageInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter' && !this.isLoading) {
                        this.sendMessage();
                    }
                });

                // Suggestion buttons
                this.suggestionButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        this.messageInput.value = btn.textContent.trim();
                        this.sendMessage();
                    });
                });
            }

            async sendMessage() {
                const message = this.messageInput.value.trim();
                if (!message || this.isLoading) return;

                this.setLoading(true);
                this.addUserMessage(message);
                this.messageInput.value = '';

                try {
                    const response = await this.callAPI(message);
                    this.handleResponse(response);
                } catch (error) {
                    console.error('API Error:', error);
                    this.addBotMessage('Sorry, I encountered an error. Please try again.', [], 'error');
                } finally {
                    this.setLoading(false);
                }
            }

            async callAPI(message) {
                const payload = {
                    message: message,
                    conversation_id: this.conversationId
                };

                const response = await fetch(this.apiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                return await response.json();
            }

            handleResponse(data) {
                if (data.conversation_id) {
                    this.conversationId = data.conversation_id;
                }

                this.addBotMessage(data.reply || 'No response received', data.products || []);
                
                // Show products section if products exist
                if (data.products && data.products.length > 0) {
                    this.displayProducts(data.products);
                    this.productsSection.classList.remove('hidden');
                }
            }

            addUserMessage(message) {
                const messageElement = this.createMessageElement(message, 'user');
                this.chatMessages.appendChild(messageElement);
                this.scrollToBottom();
            }

            addBotMessage(message, products = [], type = 'bot') {
                const messageElement = this.createMessageElement(message, type);
                this.chatMessages.appendChild(messageElement);
                this.scrollToBottom();
            }

            createMessageElement(message, type) {
                const div = document.createElement('div');
                div.className = `flex items-start space-x-3 animate-fadeIn ${type === 'user' ? 'flex-row-reverse space-x-reverse' : ''}`;

                const avatar = document.createElement('div');
                if (type === 'user') {
                    avatar.className = 'bg-gradient-to-r from-blue-500 to-purple-500 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0';
                    avatar.innerHTML = '<i class="fas fa-user text-white text-sm"></i>';
                } else if (type === 'error') {
                    avatar.className = 'bg-red-500 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0';
                    avatar.innerHTML = '<i class="fas fa-exclamation-triangle text-white text-sm"></i>';
                } else {
                    avatar.className = 'bg-gradient-to-r from-green-500 to-blue-500 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0';
                    avatar.innerHTML = '<i class="fas fa-robot text-white text-sm"></i>';
                }

                const messageDiv = document.createElement('div');
                messageDiv.className = `rounded-lg p-3 shadow-sm max-w-md ${
                    type === 'user' 
                        ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-tr-none' 
                        : type === 'error'
                        ? 'bg-red-100 text-red-800 rounded-tl-none border border-red-200'
                        : 'bg-white text-gray-800 rounded-tl-none'
                }`;

                // Convert line breaks to HTML breaks
                const formattedMessage = message.replace(/\n/g, '<br>');
                messageDiv.innerHTML = `<p>${formattedMessage}</p>`;

                div.appendChild(avatar);
                div.appendChild(messageDiv);

                return div;
            }

            displayProducts(products) {
                this.productsGrid.innerHTML = '';
                
                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer border hover:border-green-200';
                    
                    productCard.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <img 
                                src="${product.image}" 
                                alt="${product.name}" 
                                class="w-12 h-12 rounded-lg object-cover flex-shrink-0"
                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yMCAyMkM5LjUwNjU5IDIyIDEgMTYuNDkzNCAxIDZDMSA1LjQ0NzcyIDEuNDQ3NzIgNSAyIDVIMzhDMzguNTUyMyA1IDM5IDUuNDQ3NzIgMzkgNkMzOSAxNi40OTM0IDMwLjQ5MzQgMjIgMjAgMjJaTTIwIDIyVjM4IiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiIvPgo8L3N2Zz4K'"
                            >
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-800 text-sm truncate">${product.name}</h4>
                                <button class="text-green-600 hover:text-green-700 text-xs font-medium mt-1 focus:outline-none">
                                    View Product →
                                </button>
                            </div>
                        </div>
                    `;

                    // Add click handler to redirect to product page
                    productCard.addEventListener('click', () => {
                        // Adjust this URL pattern to match your site structure
                        window.open(`/product/${product.slug}`, '_blank');
                    });

                    this.productsGrid.appendChild(productCard);
                });
            }

            setLoading(loading) {
                this.isLoading = loading;
                this.sendButton.disabled = loading;
                this.messageInput.disabled = loading;
                
                if (loading) {
                    this.loadingOverlay.classList.remove('hidden');
                    this.sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                } else {
                    this.loadingOverlay.classList.add('hidden');
                    this.sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
                }
            }

            scrollToBottom() {
                setTimeout(() => {
                    this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
                }, 100);
            }
        }

        // Initialize chat when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new NutriGuideChat();
        });
    </script>
</body>
</html>