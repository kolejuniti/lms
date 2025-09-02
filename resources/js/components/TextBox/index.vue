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
              <!-- Reassign button for admin-to-student chats -->
              <button 
                v-if="showReassignButton()" 
                class="action-btn reassign" 
                @click="toggleReassignDropdown"
                title="Reassign conversation to another department"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m3 16 4 4 4-4"></path>
                  <path d="M7 20V4"></path>
                  <path d="m21 8-4-4-4 4"></path>
                  <path d="M17 4v16"></path>
                </svg>
              </button>
              
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
                  :isMine="isMessageMine(message)"
                  :showDateHeader="shouldShowDateHeader(message, index)"
                  @delete-message="handleDeleteMessage"
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

      <!-- Reassign Modal -->
      <div v-if="showReassignDropdown" class="reassign-modal-overlay" @click="toggleReassignDropdown">
        <div class="reassign-modal" @click.stop>
          <div class="reassign-modal-header">
            <h3>Reassign Conversation</h3>
            <button @click="toggleReassignDropdown" class="close-modal">×</button>
          </div>
          <div class="reassign-modal-body">
            <div class="form-group">
              <label class="form-label">From:</label>
              <div class="current-department">{{ getDepartmentName(state.messageType) }}</div>
            </div>
            <div class="form-group">
              <label for="to-department" class="form-label">To Department:</label>
              <select id="to-department" v-model="reassignToDepartment" class="form-select">
                <option value="">Select Department</option>
                <option v-for="dept in getAvailableDepartments()" :key="dept.code" :value="dept.code">
                  {{ dept.name }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label for="reassign-reason" class="form-label">Reason (Optional):</label>
              <textarea 
                id="reassign-reason" 
                v-model="reassignReason" 
                placeholder="Enter reason for reassignment..."
                class="form-textarea"
                rows="4"
              ></textarea>
            </div>
          </div>
          <div class="reassign-modal-footer">
            <button @click="toggleReassignDropdown" class="btn-cancel">Cancel</button>
            <button @click="handleReassign" :disabled="!reassignToDepartment || isReassigning" class="btn-reassign">
              {{ isReassigning ? 'Reassigning...' : 'Reassign' }}
            </button>
          </div>
        </div>
      </div>
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
        studentName: '',
        matricNumber: '',
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
      
      // Reassignment refs
      const showReassignDropdown = ref(false);
      const reassignDropdown = ref(null);
      const reassignToDepartment = ref('');
      const reassignReason = ref('');
      const isReassigning = ref(false);
  
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
        
        // If it's a student chat, use the student name
        if (state.messageType === 'STUDENT_TO_STUDENT') {
          return state.studentName ? `Chat with ${state.studentName}` : 'Student Chat';
        }
        
        // For admin-to-student chats, show student name with matric number if available
        if (state.studentName && (state.messageType === 'FN' || state.messageType === 'RGS' || state.messageType === 'HEP' || state.messageType === 'AR' || state.messageType === 'ADM')) {
          const matricDisplay = state.matricNumber ? ` (${state.matricNumber})` : '';
          return `Chat with ${state.studentName}${matricDisplay}`;
        }
        
        const titleMap = {
          'FN': 'Chat with UKP',
          'RGS': 'Chat with KRP', 
          'HEP': 'Chat with HEP',
          'AR': 'Chat with Academic Registrar',
          'ADM': 'Chat with Admin'
        };
        
        return titleMap[state.messageType] || 'Chat';
      };


  
      // Method to handle the custom event
      const getMessage = (event) => {
        const newIc = event.detail.ic;
        const messageType = event.detail.messageType || newIc; // For backwards compatibility
        const studentName = event.detail.studentName || '';
        const matricNumber = event.detail.matricNumber || '';
        
        // If switching to a different user/type, clear messages and update details
        if (state.ic !== newIc) {
          state.ic = newIc;
          state.messageType = messageType;
          state.studentName = studentName;
          state.matricNumber = matricNumber;
          state.messages = [];
          
          // Use nextTick to ensure state is updated before fetching
          nextTick(() => {
            fetchMessages();
          });
        } else {
          // Same user, just refresh messages but update student info if provided
          if (studentName) {
            state.studentName = studentName;
          }
          if (matricNumber) {
            state.matricNumber = matricNumber;
          }
          fetchMessages();
        }
        
        state.isChatBoxOpen = true;
      };

      // Method to open student chat
      const openStudentChat = (studentIc, studentName) => {
        state.ic = studentIc;
        state.messageType = 'STUDENT_TO_STUDENT';
        state.studentName = studentName;
        state.messages = [];
        state.isChatBoxOpen = true;
        
        // Fetch messages for this student
        nextTick(() => {
          fetchMessages();
        });
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

      // Reassignment helper methods
      const showReassignButton = () => {
        // Only show for admin-to-student chats (not student-to-student or student-to-admin)
        return state.ic && state.messageType && 
               state.messageType !== 'STUDENT_TO_STUDENT' && 
               state.sessionUserId !== 'STUDENT' &&
               ['FN', 'RGS', 'HEP', 'AR', 'ADM'].includes(state.messageType);
      };

      const getDepartmentName = (code) => {
        const departments = {
          'FN': 'Finance (UKP)',
          'RGS': 'Registration (KRP)',
          'HEP': 'HEP',
          'AR': 'Academic Registrar',
          'ADM': 'Admin'
        };
        return departments[code] || code;
      };

      const getAvailableDepartments = () => {
        const allDepartments = [
          { code: 'FN', name: 'Finance (UKP)' },
          { code: 'RGS', name: 'Registration (KRP)' },
          { code: 'HEP', name: 'HEP' },
          { code: 'AR', name: 'Academic Registrar' },
          { code: 'ADM', name: 'Admin' }
        ];
        
        // Filter out the current department
        return allDepartments.filter(dept => dept.code !== state.messageType);
      };

      const toggleReassignDropdown = () => {
        showReassignDropdown.value = !showReassignDropdown.value;
        if (!showReassignDropdown.value) {
          // Reset form when closing
          reassignToDepartment.value = '';
          reassignReason.value = '';
        }
      };

      const handleReassign = async () => {
        if (!reassignToDepartment.value || isReassigning.value) {
          return;
        }

        // Show confirmation dialog
        if (!confirm(`Are you sure you want to reassign this conversation to ${getDepartmentName(reassignToDepartment.value)}?`)) {
          return;
        }

        isReassigning.value = true;

        try {
          const response = await axios.post('/all/massage/user/reassignConversation', {
            student_ic: state.ic,
            from_department: state.messageType,
            to_department: reassignToDepartment.value,
            reason: reassignReason.value
          });

          if (response.data.success) {
            // Show success message
            alert(`Conversation successfully reassigned to ${response.data.to_department}`);
            
            // Close the dropdown
            toggleReassignDropdown();
            
            // Close the chat box
            state.isChatBoxOpen = false;
            
            // Add a system message to the current conversation
            const systemMessage = {
              id: `system-${Date.now()}`,
              message: `This conversation has been reassigned to ${response.data.to_department}`,
              user_type: 'SYSTEM',
              sender: 'system',
              created_at: new Date().toISOString(),
              isSystemMessage: true
            };
            
            state.messages.push(systemMessage);
            
          } else {
            alert('Failed to reassign conversation: ' + (response.data.error || 'Unknown error'));
          }
        } catch (error) {
          console.error('Reassignment error:', error);
          alert('Failed to reassign conversation: ' + (error.response?.data?.error || error.message));
        } finally {
          isReassigning.value = false;
        }
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
      const isMessageMine = (message) => {
        if (state.messageType === 'STUDENT_TO_STUDENT') {
          // For student-to-student messaging, check if sender IC matches current user
          const currentStudentIc = window.Laravel?.currentStudentIc;
          // Check both sender and user_type fields
          return message.sender === currentStudentIc || 
                 (message.user_type === 'STUDENT_TO_STUDENT' && message.sender === currentStudentIc);
        }
        return message.user_type === state.sessionUserId;
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
      const fetchMessages = (forceUpdate = false) => {
        if (!state.ic) {
          console.log('No IC set, skipping fetch');
          return;
        }
        
        // Skip fetching if user is actively typing to avoid input disruption (unless forced)
        if (!forceUpdate && isTyping.value) {
          console.log('User is typing, skipping fetch to avoid input disruption');
          return;
        }
        
        console.log(`Fetching messages for IC: ${state.ic}${forceUpdate ? ' (FORCED)' : ''}`);
        
        let fetchPromise;
        
        if (state.messageType === 'STUDENT_TO_STUDENT') {
          // Use student messaging endpoint
          fetchPromise = axios.post('/all/student/getMessages', { 
            recipient_ic: state.ic
          });
        } else {
          // Use regular department messaging endpoint
          fetchPromise = axios.post('/all/massage/user/getMassage', { 
            ic: state.ic,
            type: state.sessionUserId
          });
        }
        
        fetchPromise
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
          
          // Don't update messages if user is typing to prevent visual disruption (unless forced)
          if (!forceUpdate && isTyping.value) {
            console.log('User is typing, skipping message update to prevent visual disruption');
            return;
          }
          
          // Check if this is genuinely new data to avoid unnecessary updates
          const hasChanged = JSON.stringify(processedMessages.map(m => `${m.id}-${m.status}`)) !== JSON.stringify(state.messages.map(m => `${m.id}-${m.status}`));
          
          if (hasChanged || previousLength === 0 || forceUpdate) {
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
          pollingInterval = setInterval(() => fetchMessages(), 2000); // More frequent polling for better real-time updates
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
        // Note: Modal handles its own click outside via the overlay
      };
  
      // Handle message deletion
      const handleDeleteMessage = (messageData) => {
        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this message?')) {
          return;
        }
        
        console.log('Deleting message:', messageData);
        
        // Optimistically update UI - mark message as deleted immediately
        const messageIndex = state.messages.findIndex(msg => 
          msg.id === messageData.id || 
          (msg.message === messageData.message && msg.created_at === messageData.created_at)
        );
        
        if (messageIndex !== -1) {
          // Create a copy of the message with deleted status
          const updatedMessages = [...state.messages];
          updatedMessages[messageIndex] = {
            ...updatedMessages[messageIndex],
            status: 'DELETED',
            is_deleted: true
          };
          state.messages = updatedMessages;
        }
        
        // Send delete request to server
        const deleteData = {
          message_id: messageData.id || messageData.message_id,
          ic: state.ic,
          type: state.sessionUserId
        };
        
        axios.post('/all/massage/user/deleteMassage', deleteData)
        .then(response => {
          console.log('Message deleted successfully:', response.data);
          
          // Force immediate update to get the latest state from server
          fetchMessages(true);
          
          // Set up aggressive polling for the next 10 seconds to ensure all clients see the update
          const aggressivePolling = setInterval(() => {
            fetchMessages(true);
          }, 1000); // Every 1 second
          
          // Stop aggressive polling after 10 seconds and return to normal polling
          setTimeout(() => {
            clearInterval(aggressivePolling);
            console.log('Stopped aggressive polling after delete operation');
          }, 10000);
        })
        .catch(error => {
          console.error('Error deleting message:', error);
          
          // Revert the optimistic update if deletion failed
          if (messageIndex !== -1) {
            const revertedMessages = [...state.messages];
            revertedMessages[messageIndex] = messageData; // Restore original message
            state.messages = revertedMessages;
          }
          
          alert('Failed to delete message. Please try again.');
        });
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
          user_type: state.messageType === 'STUDENT_TO_STUDENT' ? 'STUDENT_TO_STUDENT' : state.sessionUserId,
          sender: state.messageType === 'STUDENT_TO_STUDENT' ? window.Laravel?.currentStudentIc : state.sessionUserId,
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
        let endpoint;
        
        if (state.messageType === 'STUDENT_TO_STUDENT') {
          // Student-to-student messaging
          endpoint = '/all/student/sendMessage';
          
          if (imageToSend) {
            // Use FormData for image upload
            requestData = new FormData();
            requestData.append('message', messageToSend);
            requestData.append('recipient_ic', state.ic);
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
              recipient_ic: state.ic
            };
          }
        } else {
          // Department messaging
          endpoint = '/all/massage/user/sendMassage';
          
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
        }
        
        // Send to server
        axios.post(endpoint, requestData, config)
        .then(response => {
          console.log('Message sent successfully:', response.data);
          
          // Force immediate update to replace temp message
          setTimeout(() => {
            fetchMessages(true);
            
            // Set up brief aggressive polling to ensure recipient sees the message quickly
            const sendPolling = setInterval(() => {
              fetchMessages(true);
            }, 1000);
            
            // Stop after 5 seconds
            setTimeout(() => {
              clearInterval(sendPolling);
            }, 5000);
          }, 500);
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
        
        // Expose component methods globally
        window.textBoxComponent = {
          openStudentChat
        };
        
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
        handleDeleteMessage,
        openStudentChat,
        // Image upload related
        imageInput,
        imageButton,
        selectedImage,
        triggerImageUpload,
        handleImageSelect,
        removeSelectedImage,
        // Reassignment related
        showReassignDropdown,
        reassignDropdown,
        reassignToDepartment,
        reassignReason,
        isReassigning,
        showReassignButton,
        getDepartmentName,
        getAvailableDepartments,
        toggleReassignDropdown,
        handleReassign
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

  .action-btn.reassign {
    margin-right: 0.5rem;
  }

  .action-btn.reassign:hover {
    background-color: rgba(255, 255, 255, 0.3);
  }
  
  /* Body styles */
  .chat-body {
    flex: 1;
    overflow: hidden;
    position: relative;
    border-radius: 0 0 var(--radius) var(--radius);
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
  
  /* Reassignment Modal Styles */
  .reassign-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease;
  }

  .reassign-modal {
    background-color: white;
    border-radius: 0.75rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    width: 90%;
    max-width: 480px;
    max-height: 90vh;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease;
  }

  @keyframes modalSlideIn {
    from {
      opacity: 0;
      transform: scale(0.95) translateY(-20px);
    }
    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  .reassign-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background-color: #f8fafc;
  }

  .reassign-modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-color);
  }

  .close-modal {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--light-text);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    transition: background-color 0.2s;
  }

  .close-modal:hover {
    background-color: var(--border-color);
  }

  .reassign-modal-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
  }

  .reassign-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    background-color: #f8fafc;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-color);
  }

  .current-department {
    padding: 0.75rem;
    background-color: #f1f5f9;
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-weight: 500;
    color: var(--primary-color);
  }

  .form-select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background-color: white;
    color: var(--text-color);
    transition: border-color 0.2s;
  }

  .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px var(--secondary-color);
  }

  .form-textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background-color: white;
    color: var(--text-color);
    resize: vertical;
    min-height: 60px;
    transition: border-color 0.2s;
    font-family: inherit;
  }

  .form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px var(--secondary-color);
  }

  .form-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
  }

  .btn-reassign {
    padding: 0.75rem 1.5rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    min-width: 100px;
  }

  .btn-reassign:hover:not(:disabled) {
    background-color: #3730a3;
  }

  .btn-reassign:disabled {
    background-color: var(--border-color);
    cursor: not-allowed;
    opacity: 0.6;
  }

  .btn-cancel {
    padding: 0.75rem 1.5rem;
    background-color: transparent;
    color: var(--light-text);
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 100px;
  }

  .btn-cancel:hover {
    background-color: #f9fafb;
    border-color: var(--text-color);
    color: var(--text-color);
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

    .reassign-dropdown {
      position: fixed;
      left: 1rem;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      width: auto;
    }
  }
  </style>