<template>
  <div class="message-container" :class="{ 'mine': isMine, 'others': !isMine }">
    <div class="avatar" v-if="!isMine">
      <div class="avatar-circle">
        {{ getInitial() }}
      </div>
    </div>
    
    <div class="message-bubble" :class="{ 'mine': isMine, 'others': !isMine }">
      <div class="message-content">
        <p class="message-text">{{ data.message }}</p>
        
        <div class="message-metadata">
          <span class="message-time">{{ formatTime(data.datetime || data.created_at) }}</span>
          
          <svg v-if="isMine" class="status-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
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
    getInitial() {
      // Get initial from user or a default
      // This would need to be adapted to your user data structure
      return 'U';
    }
  }
}
</script>

<style scoped>
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

/* Responsive adjustments */
@media (max-width: 640px) {
  .message-bubble {
    max-width: 80%;
  }
}
</style>