<template>
    <div class="chat-widget">
      <!-- Chat Toggle Button (hidden for students - they access chat via sidebar) -->
      <button 
        v-if="!state.isChatBoxOpen && !isStudent" 
        @click="toggleChatBox" 
        class="chat-toggle-btn"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chat-icon">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="pulse-dot"></span>
      </button>
  
      <!-- Chat Container -->
      <transition name="slide">
        <div v-if="state.isChatBoxOpen" class="chat-container">
          <div class="chat-header">
            <div class="chat-header-info">
              <div class="status-indicator" :class="{ 'online': true }"></div>
              <h2>{{ getChatTitle() }}</h2>
            </div>
            <div class="chat-actions">
              <button class="action-btn minimize" @click="toggleChatBox">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
              </button>
            </div>
          </div>
  
          <div class="chat-body">
            <div class="chat-messages" ref="chatMessages">
              <div v-if="state.messages.length === 0" class="empty-state">
                <div class="empty-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                  </svg>
                </div>
                <p>No messages yet. Start a conversation!</p>
              </div>
              
              <div v-if="state.messages && state.messages.length > 0" class="messages-container" v-memo="[state.messages, state.ic, isTyping]">
                <message-bubble
                  v-for="(message, index) in state.messages" 
                  :key="`${state.ic}-${message.id || index}`"
                  :data="message"
                  :isMine="isMessageMine(message.user_type)"
                  :showDateHeader="shouldShowDateHeader(message, index)"
                />
              </div>
            </div>
          </div>
  
          <!-- Image Preview (above the input area) -->
          <div v-if="selectedImage" class="image-preview-container">
            <div class="image-preview">
              <img :src="selectedImage.preview" alt="Image preview" class="preview-image">
              <button @click="removeSelectedImage" class="remove-image-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
              </button>
            </div>
          </div>

          <div class="chat-footer">
            <div class="chat-input-container">
              <input 
                type="text" 
                v-model="message" 
                @keyup.enter="submitMessage"
                @focus="handleFocus"
                @blur="handleBlur"
                placeholder="Type a message..." 
                class="chat-input"
                ref="messageInput"
              >
              <div class="input-actions">
                <!-- Image Upload Button -->
                <button class="image-button" @click="triggerImageUpload" ref="imageButton">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="9" cy="9" r="2"></circle>
                    <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                  </svg>
                </button>
                
                <!-- Hidden File Input -->
                <input 
                  type="file" 
                  ref="imageInput" 
                  @change="handleImageSelect" 
                  accept="image/*" 
                  style="display: none"
                >
                
                <button class="emoji-button" @click="toggleEmojiPicker" ref="emojiButton">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                  </svg>
                </button>
                
                <!-- Emoji Picker -->
                <div v-if="showEmojiPicker" class="emoji-picker" ref="emojiPicker">
                  <div class="emoji-picker-header">
                    <span>Emojis</span>
                    <button @click="toggleEmojiPicker" class="close-picker">×</button>
                  </div>
                  <div class="emoji-categories">
                    <button 
                      v-for="(category, index) in emojiCategories" 
                      :key="index"
                      @click="selectCategory(index)"
                      :class="{ 'active': selectedCategory === index }"
                      class="category-btn"
                    >
                      {{ category.icon }}
                    </button>
                  </div>
                  <div class="emoji-list">
                    <button 
                      v-for="emoji in currentCategoryEmojis" 
                      :key="emoji"
                      @click="insertEmoji(emoji)"
                      class="emoji-btn"
                    >
                      {{ emoji }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <button 
              @click="submitMessage" 
              class="send-button" 
              :class="{ 'active': message.trim().length > 0 || selectedImage }"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
              </svg>
            </button>
          </div>
        </div>
      </transition>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import MessageBubble from '../MessageBubble/index.vue';
  import { watch, reactive, onMounted, onBeforeUnmount, ref, nextTick, computed } from 'vue';
  
  export default {
    components: {
      MessageBubble
    },
    setup() {
      // Reactive state
      const state = reactive({
        isChatBoxOpen: false,
        messages: [],
        ic: null,
        messageType: null,
        sessionUserId: window.Laravel.sessionUserId,
        status: 'online',
        isTyping: false
      });
      
      const chatMessages = ref(null);
      const message = ref('');
      const messageInput = ref(null);
      const emojiButton = ref(null);
      const emojiPicker = ref(null);
      const showEmojiPicker = ref(false);
      const selectedCategory = ref(0);
      const isTyping = ref(false);
      const typingTimeout = ref(null);
      
      // Image upload refs
      const imageInput = ref(null);
      const imageButton = ref(null);
      const selectedImage = ref(null);
  
      // Emoji categories and their emojis
      const emojiCategories = [
        { 
          icon: '😀', 
          emojis: ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘']
        },
        { 
          icon: '👋', 
          emojis: ['👋', '👌', '✌️', '🤞', '👍', '👎', '👏', '🙌', '🤝', '💪', '🤲', '🤟', '🤙', '👈', '👉']
        },
        { 
          icon: '❤️', 
          emojis: ['❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '💔', '❣️', '💕', '💞', '💓', '💗', '💖', '💘']
        },
        { 
          icon: '🔥', 
          emojis: ['🔥', '✨', '🌟', '💫', '⭐', '🌈', '☀️', '🌤️', '⛅', '🌥️', '☁️', '🌦️', '🌧️', '⛈️', '🌩️']
        }
      ];
      
      // Compute current category's emojis
      const currentCategoryEmojis = computed(() => {
        return emojiCategories[selectedCategory.value].emojis;
      });

      // Check if current user is a student
      const isStudent = computed(() => {
        return state.sessionUserId === 'STUDENT';
      });

      // Get chat title based on message type
      const getChatTitle = () => {
        if (!state.messageType) return 'Chat';
        
        const titleMap = {
          'FN': 'Chat with UKP',
          'RGS': 'Chat with KRP', 
          'HEP': 'Chat with HEP'
        };
        
        return titleMap[state.messageType] || 'Chat';
      };


  
      // Method to handle the custom event
      const getMessage = (event) => {
        const newIc = event.detail.ic;
        const messageType = event.detail.messageType || newIc; // For backwards compatibility
        
        // If switching to a different user/type, clear messages and update details
        if (state.ic !== newIc) {
          state.ic = newIc;
          state.messageType = messageType;
          state.messages = [];
          
          // Use nextTick to ensure state is updated before fetching
          nextTick(() => {
            fetchMessages();
          });
        } else {
          // Same user, just refresh messages
          fetchMessages();
        }
        
        state.isChatBoxOpen = true;
      };
  
      // Function to toggle emoji picker
      const toggleEmojiPicker = () => {
        showEmojiPicker.value = !showEmojiPicker.value;
      };
      
      // Function to select emoji category
      const selectCategory = (index) => {
        selectedCategory.value = index;
      };
      
      // Function to insert emoji into message
      const insertEmoji = (emoji) => {
        message.value += emoji;
        messageInput.value.focus();
      };

      // Image handling methods
      const triggerImageUpload = () => {
        imageInput.value.click();
      };

      const handleImageSelect = (event) => {
        const file = event.target.files[0];
        if (file) {
          // Validate file type
          const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
          if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, JPG, GIF, or WebP)');
            return;
          }

          // Validate file size (5MB max)
          if (file.size > 5 * 1024 * 1024) {
            alert('Image size must be less than 5MB');
            return;
          }

          // Create preview
          const reader = new FileReader();
          reader.onload = (e) => {
            selectedImage.value = {
              file: file,
              preview: e.target.result,
              name: file.name,
              size: file.size
            };
          };
          reader.readAsDataURL(file);
        }
        
        // Reset the input value so the same file can be selected again
        event.target.value = '';
      };

      const removeSelectedImage = () => {
        selectedImage.value = null;
      };

      // Handle typing detection to pause polling
      const handleTyping = () => {
        // Only set isTyping to true if it's not already true (avoid unnecessary re-renders)
        if (!isTyping.value) {
          isTyping.value = true;
        }
        
        // Clear existing timeout
        if (typingTimeout.value) {
          clearTimeout(typingTimeout.value);
        }
        
        // Set user as not typing after 3 seconds of inactivity (longer delay)
        typingTimeout.value = setTimeout(() => {
          isTyping.value = false;
          // Fetch messages once when user stops typing to get any updates
          setTimeout(() => {
            fetchMessages();
          }, 500);
        }, 3000);
      };

      // Handle input focus
      const handleFocus = () => {
        // Set typing state only once when focused
        if (!isTyping.value) {
          isTyping.value = true;
          console.log('Input focused, stopping updates');
        }
      };

      // Handle input blur
      const handleBlur = () => {
        // Resume normal polling after a longer delay
        console.log('Input blurred, will resume updates in 2 seconds');
        setTimeout(() => {
          isTyping.value = false;
          // Fetch messages once when input loses focus
          setTimeout(() => {
            fetchMessages();
          }, 500);
        }, 2000);
      };
  
      // Function to toggle chat box visibility
      const toggleChatBox = () => {
        state.isChatBoxOpen = !state.isChatBoxOpen;
        
        if (state.isChatBoxOpen) {
          // Fetch latest messages when chat opens
          if (state.ic) {
            fetchMessages();
          }
          
          // Focus on input when chat opens
          nextTick(() => {
            if (messageInput.value) messageInput.value.focus();
          });
        }
      };
  
      // Function to determine if the message is from the current user
      const isMessageMine = (messageSender) => {
        return messageSender === state.sessionUserId;
      };

      // Function to determine if a date header should be shown
      const shouldShowDateHeader = (message, index) => {
        // Skip computation when user is typing to avoid re-renders
        if (isTyping.value) return false;
        
        // Safety checks
        if (!message || !state.messages || state.messages.length === 0) return false;
        
        // Always show date header for the first message
        if (index === 0) return true;
        
        // Safety check for previous message
        if (index <= 0 || index >= state.messages.length) return false;
        
        // Use cached date strings instead of creating new Date objects
        const currentDateStr = message.datetime || message.created_at;
        const previousMessage = state.messages[index - 1];
        if (!previousMessage) return false;
        
        const previousDateStr = previousMessage.datetime || previousMessage.created_at;
        
        // Simple string comparison for same day (much faster)
        if (!currentDateStr || !previousDateStr) return false;
        
        try {
          const currentDate = currentDateStr.split(' ')[0] || currentDateStr.split('T')[0];
          const previousDate = previousDateStr.split(' ')[0] || previousDateStr.split('T')[0];
          return currentDate !== previousDate;
        } catch (e) {
          return false;
        }
      };

      // Helper function to get date from timestamp
      const getMessageDate = (timestamp) => {
        if (!timestamp) return new Date();
        try {
          return new Date(timestamp);
        } catch (e) {
          return new Date();
        }
      };

      // Helper function to check if two dates are the same day
      const isSameDate = (date1, date2) => {
        return date1.getDate() === date2.getDate() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getFullYear() === date2.getFullYear();
      };
  
      // Function to fetch messages
      const fetchMessages = () => {
        if (!state.ic) {
          console.log('No IC set, skipping fetch');
          return;
        }
        
        // Skip fetching if user is actively typing to avoid input disruption
        if (isTyping.value) {
          console.log('User is typing, skipping fetch to avoid input disruption');
          return;
        }
        
        console.log(`Fetching messages for IC: ${state.ic}`);
        
        axios.post('/all/massage/user/getMassage', { 
          ic: state.ic,
          type: state.sessionUserId
        })
        .then(response => {
          const newMessages = response.data || [];
          console.log(`Received ${newMessages.length} messages from server`);
          
          // Ensure each message has a unique ID for Vue's key tracking
          const processedMessages = newMessages.map((msg, index) => {
            // Create a more stable ID that won't change between fetches
            const stableId = msg.id || msg.message_id || `${msg.message}-${msg.created_at || msg.datetime}-${index}`;
            return {
              ...msg,
              id: stableId
            };
          });
          
          const previousLength = state.messages.length;
          console.log(`Previous messages: ${previousLength}, New messages: ${processedMessages.length}`);
          
          // Don't update messages if user is typing to prevent visual disruption
          if (isTyping.value) {
            console.log('User is typing, skipping message update to prevent visual disruption');
            return;
          }
          
          // Check if this is genuinely new data to avoid unnecessary updates
          const hasChanged = JSON.stringify(processedMessages.map(m => m.id)) !== JSON.stringify(state.messages.map(m => m.id));
          
          if (hasChanged || previousLength === 0) {
            // Update messages
            state.messages = processedMessages;
            
            // Scroll to bottom if new messages were added or if this is the first load
            if (processedMessages.length > previousLength || previousLength === 0) {
              nextTick(() => {
                scrollToBottom();
              });
            }
          }
        })
        .catch(error => {
          console.error('Error fetching messages:', error);
        });
      };
  
      // Function to scroll to the bottom of messages
      const scrollToBottom = () => {
        if (chatMessages.value) {
          chatMessages.value.scrollTop = chatMessages.value.scrollHeight;
        }
      };
  
      let pollingInterval = null;
  
      const startPolling = () => {
        // Start polling only if the chat box is open
        if (state.isChatBoxOpen && !pollingInterval) {
          pollingInterval = setInterval(fetchMessages, 3000); // Much less aggressive polling
        }
      };
  
      const stopPolling = () => {
        // Clear the polling interval if it exists
        if (pollingInterval) {
          clearInterval(pollingInterval);
          pollingInterval = null;
        }
      };
  
      // Watch for changes in state.isChatBoxOpen
      watch(() => state.isChatBoxOpen, (newValue) => {
        if (newValue) {
          startPolling();
          fetchMessages(); // Fetch immediately when opened
        } else {
          stopPolling();
          showEmojiPicker.value = false; // Close emoji picker when chat is closed
        }
      }, { immediate: true });
      
      // Close emoji picker when clicking outside
      const handleClickOutside = (event) => {
        if (showEmojiPicker.value && 
            emojiPicker.value && 
            !emojiPicker.value.contains(event.target) &&
            emojiButton.value && 
            !emojiButton.value.contains(event.target)) {
          showEmojiPicker.value = false;
        }
      };
  
      // Define your methods here
      const submitMessage = () => {
        if (message.value.trim() === '' && !selectedImage.value) {
          return; // Don't send empty messages without image
        }
        
        // Close emoji picker when sending a message
        showEmojiPicker.value = false;
        
        const messageToSend = message.value.trim();
        const imageToSend = selectedImage.value;
        
        // Create a temporary message to show immediately
        const tempId = `temp-${state.ic}-${Date.now()}`;
        const tempMessage = {
          id: tempId,
          message: messageToSend,
          user_type: state.sessionUserId,
          created_at: new Date().toISOString(),
          isTemporary: true,
          image_url: imageToSend ? imageToSend.preview : null // Show preview temporarily
        };
        
        // Add temp message to UI immediately (safer approach)
        const currentMessages = [...state.messages];
        currentMessages.push(tempMessage);
        state.messages = currentMessages;
        
        // Clear input and image
        message.value = '';
        selectedImage.value = null;
        
        // Scroll to bottom
        nextTick(() => {
          scrollToBottom();
        });
        
        console.log('Sending message:', messageToSend);
        
        // Prepare data for submission
        let requestData;
        let config = {};
        
        if (imageToSend) {
          // Use FormData for image upload
          requestData = new FormData();
          requestData.append('message', messageToSend);
          requestData.append('ic', state.ic);
          requestData.append('type', state.sessionUserId);
          requestData.append('image', imageToSend.file);
          
          config = {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          };
        } else {
          // Use regular JSON for text-only message
          requestData = {
            message: messageToSend,
            ic: state.ic,
            type: state.sessionUserId
          };
        }
        
        // Send to server
        axios.post('/all/massage/user/sendMassage', requestData, config)
        .then(response => {
          console.log('Message sent successfully:', response.data);
          
          // Wait a bit for server to process, then fetch messages to replace temp message
          setTimeout(() => {
            console.log('Fetching messages after send - attempt 1');
            fetchMessages();
            
            // Second attempt after another delay to ensure we get the update
            setTimeout(() => {
              console.log('Fetching messages after send - attempt 2');
              fetchMessages();
            }, 1500);
          }, 1000);
        })
        .catch(error => {
          console.error("Error sending message:", error);
          
          // Remove temp message if sending failed
          state.messages = state.messages.filter(msg => msg.id !== tempMessage.id);
          
          // Restore the message to input if sending failed
          message.value = messageToSend;
          if (imageToSend) {
            selectedImage.value = imageToSend;
          }
          
          // Show error message to user
          alert('Failed to send message. Please try again.');
        });
      };
  
      // Lifecycle hooks for event listener
      onMounted(() => {
        window.addEventListener('message-requested', getMessage);
        window.addEventListener('click', handleClickOutside);
        
        if (state.isChatBoxOpen) {
          fetchMessages();
          startPolling();
        }
      });
  
      onBeforeUnmount(() => {
        window.removeEventListener('message-requested', getMessage);
        window.removeEventListener('click', handleClickOutside);
        stopPolling();
        
        // Clear typing timeout
        if (typingTimeout.value) {
          clearTimeout(typingTimeout.value);
        }
      });
  
      // Return everything that needs to be accessible in the template
      return { 
        state, 
        message, 
        chatMessages,
        messageInput,
        emojiButton,
        emojiPicker,
        showEmojiPicker,
        emojiCategories,
        selectedCategory,
        currentCategoryEmojis,
        isStudent,
        getChatTitle,
        toggleChatBox, 
        isMessageMine, 
        submitMessage,
        scrollToBottom,
        toggleEmojiPicker,
        selectCategory,
        insertEmoji,
        shouldShowDateHeader,
        handleFocus,
        handleBlur,
        // Image upload related
        imageInput,
        imageButton,
        selectedImage,
        triggerImageUpload,
        handleImageSelect,
        removeSelectedImage
      };
    }
  }
  </script>
  
  <style scoped>
  /* Base styles */
  .chat-widget {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --primary-color: #4f46e5;
    --secondary-color: #e0e7ff;
    --text-color: #1f2937;
    --light-text: #6b7280;
    --border-color: #e5e7eb;
    --success-color: #10b981;
    --error-color: #ef4444;
    --radius: 1rem;
  }
  
  /* Toggle button styles */
  .chat-toggle-btn {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: var(--primary-color);
    color: white;
    border: none;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .chat-toggle-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }
  
  .chat-icon {
    width: 24px;
    height: 24px;
  }
  
  .pulse-dot {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--success-color);
    border: 2px solid white;
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
  }
  
  /* Chat container styles */
  .chat-container {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 350px;
    height: 500px;
    display: flex;
    flex-direction: column;
    border-radius: var(--radius);
    overflow: hidden;
    background-color: white;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    z-index: 1000;
  }
  
  /* Header styles */
  .chat-header {
    background-color: var(--primary-color);
    color: white;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  
  .chat-header-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .chat-header h2 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
  }
  
  .status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #9CA3AF;
  }
  
  .status-indicator.online {
    background-color: var(--success-color);
  }
  
  .chat-actions {
    display: flex;
    gap: 0.5rem;
  }
  
  .action-btn {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background-color 0.2s;
  }
  
  .action-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
  }
  
  /* Body styles */
  .chat-body {
    flex: 1;
    overflow: hidden;
    position: relative;
  }
  
  .chat-messages {
    height: 100%;
    padding: 1rem;
    overflow-y: auto;
    scroll-behavior: smooth;
  }
  
  .empty-state {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--light-text);
    text-align: center;
    padding: 1rem;
  }
  
  .empty-icon {
    color: var(--border-color);
    margin-bottom: 1rem;
  }
  
  /* Footer styles */
  .chat-footer {
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-top: 1px solid var(--border-color);
  }
  
  .chat-input-container {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    background-color: #f9fafb;
    border-radius: 1.5rem;
    transition: box-shadow 0.3s ease;
  }
  
  .chat-input-container:focus-within {
    box-shadow: 0 0 0 2px var(--secondary-color);
  }
  
  .chat-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    background: transparent;
    font-size: 0.9rem;
    color: var(--text-color);
    outline: none;
  }
  
  .input-actions {
    display: flex;
    padding-right: 0.5rem;
    position: relative;
  }
  
  .image-button {
    background: transparent;
    border: none;
    color: var(--light-text);
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: color 0.2s;
    margin-right: 0.25rem;
  }
  
  .image-button:hover {
    color: var(--primary-color);
  }
  
  .emoji-button {
    background: transparent;
    border: none;
    color: var(--light-text);
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: color 0.2s;
  }
  
  .emoji-button:hover {
    color: var(--primary-color);
  }

  /* Image Preview Styles */
  .image-preview-container {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border-color);
    background-color: #f9fafb;
  }

  .image-preview {
    position: relative;
    display: inline-block;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 2px solid var(--border-color);
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .preview-image {
    width: auto;
    height: 120px;
    max-width: 200px;
    object-fit: cover;
    display: block;
    border-radius: 0.5rem;
  }

  .remove-image-btn {
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
  }

  .remove-image-btn:hover {
    background-color: var(--error-color);
  }
  
  /* Emoji Picker Styles */
  .emoji-picker {
    position: absolute;
    bottom: calc(100% + 10px);
    right: 0;
    width: 250px;
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    z-index: 1000;
    animation: fadeIn 0.2s ease;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .emoji-picker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    border-bottom: 1px solid var(--border-color);
    background-color: #f9fafb;
  }
  
  .close-picker {
    background: transparent;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: var(--light-text);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    transition: background-color 0.2s;
  }
  
  .close-picker:hover {
    background-color: var(--border-color);
  }
  
  .emoji-categories {
    display: flex;
    overflow-x: auto;
    padding: 0.5rem;
    background-color: #f9fafb;
    border-bottom: 1px solid var(--border-color);
  }
  
  .category-btn {
    background: transparent;
    border: none;
    font-size: 1.25rem;
    margin: 0 0.25rem;
    padding: 0.25rem;
    cursor: pointer;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
  }
  
  .category-btn.active {
    background-color: var(--secondary-color);
  }
  
  .emoji-list {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    padding: 0.5rem;
    gap: 0.25rem;
    max-height: 150px;
    overflow-y: auto;
  }
  
  .emoji-btn {
    font-size: 1.5rem;
    background: transparent;
    border: none;
    padding: 0.25rem;
    cursor: pointer;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 36px;
  }
  
  .emoji-btn:hover {
    background-color: var(--secondary-color);
  }
  
  .send-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e5e7eb;
    color: var(--light-text);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    flex-shrink: 0;
  }
  
  .send-button.active {
    background-color: var(--primary-color);
    color: white;
    transform: scale(1.05);
  }
  
  .send-button:hover {
    transform: scale(1.1);
  }
  
  /* Animation transitions */
  .slide-enter-active,
  .slide-leave-active {
    transition: all 0.3s ease;
  }
  
  .slide-enter-from,
  .slide-leave-to {
    opacity: 0;
    transform: translateY(20px);
  }
  
  .messages-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .messages-container > * {
    animation: messageSlideIn 0.3s ease-out;
  }
  

  
  @keyframes messageSlideIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Responsive adjustments */
  @media (max-width: 640px) {
    .chat-container {
      width: 100%;
      height: 100%;
      bottom: 0;
      right: 0;
      border-radius: 0;
    }
    
    .chat-toggle-btn {
      bottom: 1rem;
      right: 1rem;
    }
    
    .emoji-picker {
      position: fixed;
      left: 0;
      right: 0;
      bottom: 80px;
      width: auto;
      max-width: 100%;
      margin: 0 1rem;
    }
  }
  </style>