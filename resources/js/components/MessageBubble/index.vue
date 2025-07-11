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
    
    <div class="message-bubble" 
         :class="{ 'mine': isMine, 'others': !isMine, 'temporary': data.isTemporary, 'deleted': isDeleted }"
         @mouseenter="showDeleteOption = true"
         @mouseleave="showDeleteOption = false">
      
      <!-- Delete button (only for own messages and not temporary or already deleted) -->
      <div v-if="isMine && !data.isTemporary && !isDeleted && showDeleteOption" class="delete-button" @click="deleteMessage">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3,6 5,6 21,6"></polyline>
          <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
          <line x1="10" y1="11" x2="10" y2="17"></line>
          <line x1="14" y1="11" x2="14" y2="17"></line>
        </svg>
      </div>

      <div class="message-content">
        <!-- Deleted message indicator -->
        <div v-if="isDeleted" class="deleted-message">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="deleted-icon">
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
          </svg>
          <span class="deleted-text">{{ getDeletedText() }}</span>
        </div>

        <!-- Regular message content (only show if not deleted) -->
        <template v-else>
          <!-- Image display -->
          <div v-if="data.image_url" class="message-image-container">
            <img 
              :src="getImageUrl(data.image_url)" 
              :alt="data.message || 'Image'" 
              class="message-image"
              @click="openImageModal"
              @error="handleImageError"
            >
            <div v-if="data.isTemporary" class="image-uploading-overlay">
              <div class="uploading-spinner"></div>
            </div>
          </div>
          
          <!-- Text message (only show if there's text content) -->
          <p v-if="data.message && data.message.trim()" class="message-text">{{ data.message }}</p>
        </template>
        
        <div class="message-metadata">
          <span class="message-time">{{ formatTime(data.datetime || data.created_at) }}</span>
          
          <!-- Single tick for NEW messages -->
          <svg v-if="isMine && !data.isTemporary && data.status === 'NEW'" class="status-icon new-message" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          
          <!-- Double green ticks for READ messages -->
          <svg v-if="isMine && !data.isTemporary && data.status === 'read'" class="status-icon read-message" xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
  data() {
    return {
      showDeleteOption: false
    }
  },
  computed: {
    isDeleted() {
      return this.data.status === 'DELETED' || this.data.is_deleted === 1 || this.data.is_deleted === true;
    }
  },
  methods: {
    deleteMessage() {
      // Emit an event to the parent component to handle the deletion
      this.$emit('delete-message', this.data);
    },
    getDeletedText() {
      if (this.isMine) {
        return 'You deleted this message';
      } else {
        return 'This message was deleted';
      }
    },
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
    },
    getImageUrl(url) {
      if (!url) return '';
      
      // If it's already a full URL (starts with http), return as is
      if (url.startsWith('http')) {
        return url;
      }
      
      // If it's a relative path, construct the full URL using Linode configuration
      // This handles cases where old images were stored as paths only
      const linodeEndpoint = window.Laravel?.linodeEndpoint || process.env.LINODE_ENDPOINT;
      const linodeBucket = window.Laravel?.linodeBucket || process.env.LINODE_BUCKET;
      
      if (linodeEndpoint && linodeBucket) {
        return `${linodeEndpoint}/${linodeBucket}/${url}`;
      }
      
      // Fallback: return the URL as-is
      return url;
    },
    handleImageError(event) {
      console.warn('Failed to load image:', event.target.src);
      // Set a placeholder image or hide the image
      event.target.style.display = 'none';
      
      // Optionally, you could set a placeholder
      // event.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3QgeD0iMyIgeT0iMyIgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiByeD0iMiIgcnk9IjIiIGZpbGw9IiNmM2Y0ZjYiIHN0cm9rZT0iI2Q1ZDVkNSIvPgo8Y2lyY2xlIGN4PSI5IiBjeT0iOSIgcj0iMiIgZmlsbD0iI2Q1ZDVkNSIvPgo8cGF0aCBkPSJNMjEgMTVsLTMuMDg2LTMuMDg2YTIgMiAwIDAgMC0yLjgyOCAwTDYgMjEiIHN0cm9rZT0iI2Q1ZDVkNSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+';
    },
    openImageModal() {
      // This method would typically open a modal or lightbox
      // For now, we'll just open the image in a new tab
      const imageUrl = this.getImageUrl(this.data.image_url);
      if (imageUrl) {
        window.open(imageUrl, '_blank');
      }
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
  line-height: 1.5;
  word-wrap: break-word;
  white-space: pre-wrap;
}

/* Image message styles */
.message-image-container {
  position: relative;
  margin-bottom: 0.5rem;
  border-radius: 0.75rem;
  overflow: hidden;
  max-width: 250px;
  width: 100%;
}

.message-image {
  width: 100%;
  height: auto;
  max-height: 300px;
  object-fit: cover;
  cursor: pointer;
  transition: opacity 0.3s ease;
  border-radius: 0.75rem;
}

.message-image:hover {
  opacity: 0.9;
}

.image-uploading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.75rem;
}

.uploading-spinner {
  width: 24px;
  height: 24px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Adjust message bubble styling for images */
.message-bubble:has(.message-image-container) {
  padding: 0.25rem;
}

.message-bubble:has(.message-image-container) .message-content {
  padding: 0.25rem;
}

.message-bubble:has(.message-image-container) .message-text {
  padding: 0 0.5rem 0.25rem 0.5rem;
}

.message-bubble:has(.message-image-container) .message-metadata {
  padding: 0 0.5rem 0.25rem 0.5rem;
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

/* Delete button styles */
.delete-button {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #ef4444;
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  opacity: 0;
  transition: opacity 0.2s ease, transform 0.2s ease;
  z-index: 10;
}

.message-bubble:hover .delete-button {
  opacity: 1;
  transform: scale(1.1);
}

.delete-button:hover {
  background-color: #dc2626;
  transform: scale(1.2) !important;
}

.delete-button:active {
  transform: scale(0.95) !important;
}

/* Deleted message styles */
.deleted-message {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  opacity: 0.7;
  font-style: italic;
}

.deleted-icon {
  width: 16px;
  height: 16px;
  opacity: 0.6;
}

.deleted-text {
  font-size: 0.9rem;
}

.message-bubble.deleted .message-content {
  background-color: #f9fafb !important;
  color: #6b7280 !important;
  border: 1px dashed #d1d5db;
}

.message-bubble.mine.deleted .message-content {
  background-color: #f3f4f6 !important;
  color: #6b7280 !important;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .message-bubble {
    max-width: 80%;
  }
  
  .delete-button {
    opacity: 1; /* Always show delete button on mobile */
  }
}
</style>