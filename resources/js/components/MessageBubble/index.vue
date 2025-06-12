<template>
  <!-- Date Header (like WhatsApp) -->
  <div v-if="showDateHeader" class="date-header">
    <div class="date-separator">
      <span class="date-text">{{ formatDateHeader(data.datetime || data.created_at) }}</span>
    </div>
  </div>

  <div class="message-container" :class="{ 'mine': isMine, 'others': !isMine }">
    <div class="avatar" v-if="!isMine">
      <div class="avatar-circle">
        {{ getInitial() }}
      </div>
    </div>
    
    <div class="message-bubble" :class="{ 'mine': isMine, 'others': !isMine, 'temporary': data.isTemporary }">
      <div class="message-content">
        <p class="message-text">{{ data.message }}</p>
        
        <div class="message-metadata">
          <span class="message-time">{{ formatTime(data.datetime || data.created_at) }}</span>
          
          <!-- Single tick for NEW messages -->
          <svg v-if="isMine && !data.isTemporary && data.status === 'NEW'" class="status-icon new-message" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          
          <!-- Double green ticks for READ messages -->
          <svg v-if="isMine && !data.isTemporary && data.status === 'READ'" class="status-icon read-message" xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
            <polyline points="16 6 5 17 0 12"></polyline>
          </svg>
          
          <!-- Fallback single tick for messages without status -->
          <svg v-if="isMine && !data.isTemporary && !data.status" class="status-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          
          <!-- Clock icon for temporary messages -->
          <svg v-if="isMine && data.isTemporary" class="status-icon sending" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12,6 12,12 16,14"></polyline>
          </svg>
        </div>
      </div>
    </div>
    
    <div class="avatar avatar-placeholder" v-if="isMine"></div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true
    },
    isMine: {
      type: Boolean,
      required: true
    },
    showDateHeader: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    formatTime(timestamp) {
      if (!timestamp) return '';
      
      // Handle both ISO strings and custom datetime formats
      let date;
      try {
        date = new Date(timestamp);
        // Check if valid date
        if (isNaN(date.getTime())) {
          return timestamp; // Return as is if can't parse
        }
      } catch (e) {
        return timestamp; // Return as is if error
      }
      
      // Format time as 12 hour with AM/PM
      return date.toLocaleTimeString([], { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
      });
    },
    formatDateHeader(timestamp) {
      if (!timestamp) return '';
      
      let date;
      try {
        date = new Date(timestamp);
        if (isNaN(date.getTime())) {
          return timestamp;
        }
      } catch (e) {
        return timestamp;
      }
      
      const today = new Date();
      const yesterday = new Date(today);
      yesterday.setDate(yesterday.getDate() - 1);
      
      // Check if it's today
      if (this.isSameDate(date, today)) {
        return 'Today';
      }
      
      // Check if it's yesterday
      if (this.isSameDate(date, yesterday)) {
        return 'Yesterday';
      }
      
      // For other dates, show in DD/MM/YYYY format
      return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });
    },
    isSameDate(date1, date2) {
      return date1.getDate() === date2.getDate() &&
             date1.getMonth() === date2.getMonth() &&
             date1.getFullYear() === date2.getFullYear();
    },
    getInitial() {
      // Get initial from user or a default
      // This would need to be adapted to your user data structure
      return 'U';
    }
  }
}
</script>

<style scoped>
/* Date Header Styles */
.date-header {
  display: flex;
  justify-content: center;
  margin: 1rem 0;
}

.date-separator {
  position: relative;
  width: 100%;
  text-align: center;
}

.date-separator::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background-color: #e5e7eb;
  z-index: 1;
}

.date-text {
  display: inline-block;
  background-color: #f3f4f6;
  color: #6b7280;
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: 500;
  position: relative;
  z-index: 2;
  border: 1px solid #e5e7eb;
}

.message-container {
  display: flex;
  margin-bottom: 1rem;
  align-items: flex-end;
  gap: 0.5rem;
  position: relative;
}

.message-container.mine {
  justify-content: flex-end;
}

.message-container.others {
  justify-content: flex-start;
}

.avatar {
  width: 28px;
  height: 28px;
  flex-shrink: 0;
}

.avatar-placeholder {
  width: 28px;
}

.avatar-circle {
  width: 100%;
  height: 100%;
  background-color: #e5e7eb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 600;
  color: #6b7280;
}

.message-bubble {
  position: relative;
  max-width: 70%;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.message-content {
  padding: 0.75rem 1rem;
  border-radius: 1.25rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  position: relative;
}

.message-bubble.mine .message-content {
  background-color: #4f46e5; /* Primary color from chat component */
  color: white;
  border-bottom-right-radius: 0.25rem;
}

.message-bubble.others .message-content {
  background-color: #f3f4f6;
  color: #1f2937;
  border-bottom-left-radius: 0.25rem;
}

.message-text {
  margin: 0;
  font-size: 0.95rem;
  line-height: 1.4;
  white-space: pre-wrap;
}

.message-metadata {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  margin-top: 0.25rem;
  gap: 0.25rem;
  font-size: 0.7rem;
}

.message-time {
  opacity: 0.8;
}

.mine .message-time {
  color: rgba(255, 255, 255, 0.8);
}

.others .message-time {
  color: #6b7280;
}

.status-icon {
  opacity: 0.8;
}

/* Single tick for NEW messages (gray) */
.status-icon.new-message {
  opacity: 0.6;
  color: rgba(255, 255, 255, 0.6);
}

/* Double ticks for READ messages (green) */
.status-icon.read-message {
  opacity: 1;
  color: #4ade80; /* Green color like WhatsApp */
}

/* For non-mine messages, keep the default styling */
.others .status-icon.read-message {
  color: #4ade80;
}

/* Transitions for new messages */
.message-container {
  transition: all 0.3s ease;
  animation: message-appear 0.3s forwards;
}

@keyframes message-appear {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Add a subtle hover effect */
.message-content:hover {
  filter: brightness(98%);
}

/* Style for temporary messages that are being sent */
.message-container.sending .message-content {
  opacity: 0.8;
}

.message-bubble.temporary .message-content {
  opacity: 0.7;
}

.status-icon.sending {
  opacity: 0.6;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 0.6;
  }
  50% {
    opacity: 1;
  }
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .message-bubble {
    max-width: 80%;
  }
}
</style>